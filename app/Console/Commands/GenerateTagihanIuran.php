<?php

namespace App\Console\Commands;

use App\Models\Iuran;
use App\Models\Pelanggan;
use App\Models\PengaturanIuran;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GenerateTagihanIuran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iuran:generate-tagihan
        {--bulan= : Periode tagihan dalam format YYYY-MM (default: bulan berjalan)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate tagihan iuran bulanan otomatis untuk seluruh pelanggan aktif';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $pengaturan = PengaturanIuran::firstOrCreate(
            ['id' => 1],
            [
                'tarif_dasar_bulanan' => 20000,
                'persentase_denda_per_bulan' => 5,
                'nominal_denda_flat' => 5000,
                'tgl_jatuh_tempo' => 10,
            ]
        );

        $bulan = $this->option('bulan') ?? Carbon::now()->format('Y-m');

        if (!preg_match('/^\d{4}-\d{2}$/', $bulan)) {
            $this->error("Format bulan tidak valid: {$bulan}. Gunakan format YYYY-MM (contoh: 2026-08).");
            return self::FAILURE;
        }

        $pelangganAktif = Pelanggan::with('user')
            ->whereHas('user', function ($q) {
                $q->where('status', 'aktif');
            })
            ->get();

        $countGenerated = 0;
        $countSkipped = 0;

        foreach ($pelangganAktif as $pelanggan) {
            $exists = Iuran::where('pelanggan_id', $pelanggan->id)
                ->where('bulan_tagihan', $bulan)
                ->exists();

            if ($exists) {
                $countSkipped++;
                continue;
            }

            Iuran::create([
                'pelanggan_id' => $pelanggan->id,
                'bulan_tagihan' => $bulan,
                'jumlah_tagihan' => $pengaturan->tarif_dasar_bulanan,
                'denda' => 0,
                'status_pembayaran' => 'Belum Bayar',
            ]);

            $countGenerated++;
        }

        $this->info("Selesai generate {$countGenerated} tagihan baru untuk periode {$bulan} (skipped {$countSkipped} yang sudah ada).");

        return self::SUCCESS;
    }
}
