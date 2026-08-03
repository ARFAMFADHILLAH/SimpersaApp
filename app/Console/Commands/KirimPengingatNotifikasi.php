<?php

namespace App\Console\Commands;

use App\Models\Iuran;
use App\Models\JadwalNotifikasi;
use App\Models\Notification;
use App\Models\Pelanggan;
use App\Models\TemplateNotifikasi;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class KirimPengingatNotifikasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifikasi:kirim-pengingat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim notifikasi in-app sesuai jadwal_notifikasi aktif (pengingat iuran, tunggakan, dll)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();
        $totalTerkirim = 0;

        // Ambil semua jadwal aktif beserta template aktifnya
        $jadwalList = JadwalNotifikasi::where('is_aktif', true)
            ->with('template')
            ->get()
            ->filter(fn ($j) => $j->template && $j->template->is_aktif);

        foreach ($jadwalList as $jadwal) {
            if (!$this->jadwalAktifHariIni($jadwal, $now)) {
                continue;
            }

            $template = $jadwal->template;

            // Klasifikasi pengiriman berdasarkan kode template
            if ($template->kode_template === 'TPL_TAGIHAN_WA' || str_contains(strtolower($template->kode_template), 'tagihan') || str_contains(strtolower($template->kode_template), 'tunggakan')) {
                $totalTerkirim += $this->kirimPengingatIuran($template, $now);
            }
        }

        $this->info("Notifikasi pengingat dikirim ke {$totalTerkirim} penerima.");

        return self::SUCCESS;
    }

    /**
     * Cek apakah jadwal pemicu cocok dengan hari ini.
     */
    private function jadwalAktifHariIni(JadwalNotifikasi $jadwal, Carbon $now): bool
    {
        $waktuKirim = Carbon::parse($jadwal->waktu_kirim ?? '08:00:00');

        // Waktu belum tiba
        if ($now->format('H:i') < $waktuKirim->format('H:i')) {
            return false;
        }

        switch ($jadwal->pemicu) {
            case 'harian':
                return true;

            case 'mingguan':
                // hari_ke: 1=Senin ... 7=Minggu
                $hariKe = (int) ($jadwal->hari_ke ?? 0);
                return $hariKe === $now->dayOfWeekIso;

            case 'bulanan':
                // hari_ke: tanggal dalam bulan (contoh: 25)
                $hariKe = (int) ($jadwal->hari_ke ?? 0);
                return $hariKe === $now->day;

            case 'event':
                // Pemicu event tidak dieksekusi otomatis (dipicu dari controller)
                return false;

            default:
                return false;
        }
    }

    /**
     * Kirim pengingat iuran ke seluruh pelanggan yang masih menunggak.
     */
    private function kirimPengingatIuran(TemplateNotifikasi $template, Carbon $now): int
    {
        $terkirim = 0;

        $pelangganMenunggak = Iuran::with('pelanggan.user')
            ->where('status_pembayaran', 'Belum Bayar')
            ->get()
            ->groupBy('pelanggan_id');

        foreach ($pelangganMenunggak as $pelangganId => $tagihanList) {
            $pelanggan = $tagihanList->first()->pelanggan;

            if (!$pelanggan || !$pelanggan->user) {
                continue;
            }

            $user = $pelanggan->user;
            $totalTunggakan = $tagihanList->sum('jumlah_tagihan');
            $jumlahBulan = $tagihanList->count();

            // Render template dengan placeholder
            $pesan = $this->renderTemplate($template->isi_pesan, [
                '{nama}' => $user->name,
                '{nomor}' => $pelanggan->no_pelanggan ?? '-',
                '{bulan}' => $tagihanList->last()->bulan_tagihan,
                '{nominal}' => 'Rp ' . number_format($totalTunggakan, 0, ',', '.'),
                '{jumlah_bulan}' => $jumlahBulan,
            ]);

            // Cegah duplikasi: notifikasi yang sama untuk user dan jenis belum terkirim hari ini
            $sudahAda = Notification::where('user_id', $user->id)
                ->where('tipe', 'pengingat_iuran')
                ->whereDate('created_at', $now->toDateString())
                ->exists();

            if ($sudahAda) {
                continue;
            }

            Notification::create([
                'user_id' => $user->id,
                'judul' => 'Pengingat Iuran Sampah',
                'pesan' => $pesan,
                'tipe' => 'pengingat_iuran',
                'tautan' => route('pelanggan.iuran.index'),
                'is_read' => false,
            ]);

            $terkirim++;
        }

        return $terkirim;
    }

    /**
     * Ganti placeholder pada isi pesan template.
     */
    private function renderTemplate(string $isi, array $data): string
    {
        return str_replace(array_keys($data), array_values($data), $isi);
    }
}
