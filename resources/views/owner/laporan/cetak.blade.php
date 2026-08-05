<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan_Rekapitulasi_{{ $mulai }}_s.d_{{ $selesai }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; color: #333; line-height: 1.4; padding: 20px; }
        .Kop-surat { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .Kop-surat h2 { margin: 0; uppercase; }
        .Kop-surat p { margin: 5px 0 0 0; font-size: 12px; color: #666; }
        .info-periode { margin-bottom: 20px; font-size: 14px; }
        table { w-full; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; width: 100%; }
        table th, table td { border: 1px solid #666; padding: 8px; text-align: left; }
        table th { bg-gray-100; font-weight: bold; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .badge-laba { padding: 5px; font-weight: bold; font-size: 14px; border: 1px solid #333; display: inline-block; margin-top: 10px; }

        /* Tombol Aksi */
        .no-print-zone { background: #f3f4f6; padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: right; }
        .btn-print { background: #4f46e5; color: #fff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: bold; }

        @media print {
            .no-print-zone { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <!-- Area Tombol Aksi Kendali Peramban -->
    <div class="no-print-zone">
        <button onclick="window.print()" class="btn-print">Cetak Dokumen / Simpan PDF</button>
    </div>

    <!-- Kop Instansi Resmi -->
    <div class="Kop-surat">
        <h2>SISTEM INFORMASI MANAJEMEN PERSAMPAHAN (SIMPERSA)</h2>
        <p>Laporan Pertanggungjawaban Keuangan & Volume Operasional Harian</p>
    </div>

    <div class="info-periode">
        <strong>Periode Laporan:</strong> {{ date('d-m-Y', strtotime($mulai)) }} s.d {{ date('d-m-Y', strtotime($selesai)) }}
    </div>

    <!-- 1. RINGKASAN NERACA -->
    <h3>I. Ringkasan Keuangan Bersih (Laba/Rugi)</h3>
    <table>
        <thead>
            <tr>
                <th>Komponen Anggaran</th>
                <th class="text-right">Jumlah Total (Rupiah)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Masukan Pendapatan Iuran Warga</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pengeluaran Gaji Petugas & Staf</td>
                <td class="text-right">(Rp {{ number_format($totalGaji, 0, ',', '.') }})</td>
            </tr>
            <tr>
                <td>Total Pengeluaran Alokasi Operasional & BBM Armada</td>
                <td class="text-right">(Rp {{ number_format($totalOperasional, 0, ',', '.') }})</td>
            </tr>
        </tbody>
    </table>
    <div class="badge-laba">
        STATUS NERACA BERSIH: Rp {{ number_format($labaBersih, 0, ',', '.') }}
    </div>

    <hr style="margin:30px 0; border: 0; border-top: 1px dashed #999;">

    <!-- 2. DETAIL LOGISTIK OPERASIONAL -->
    <h3>II. Rincian Penarikan Sampah di Lapangan</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Warga</th>
                <th>Volume (m³)</th>
                <th>Berat (Kg)</th>
                <th>Petugas Lapangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengangkutan as $angkut)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($angkut->tanggal_angkut)) }}</td>
                    <td>{{ $angkut->warga->user->name }}</td>
                    <td>{{ $angkut->volume_m3 }} m³</td>
                    <td>{{ $angkut->berat_kg }} Kg</td>
                    <td>{{ $angkut->petugas->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;">Tidak ada log pengangkutan sampah pada periode ini.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="2">Total Akumulasi Terkelola</td>
                <td>{{ $pengangkutan->sum('volume_m3') }} m³</td>
                <td colspan="2">{{ $pengangkutan->sum('berat_kg') }} Kg</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
