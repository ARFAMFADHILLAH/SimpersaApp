<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kwitansi Pembayaran</title>
    <style>
        body { font-family: 'Courier New', monospace; padding: 40px; color: #222; }
        .kwitansi { max-width: 400px; margin: 0 auto; border: 2px dashed #333; padding: 30px; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 5px; }
        .sub { text-align: center; font-size: 11px; color: #666; margin-bottom: 20px; }
        table { width: 100%; font-size: 12px; }
        td { padding: 4px 0; }
        .label { color: #888; }
        .garis { border-top: 1px dashed #333; margin: 10px 0; }
        .total { font-size: 16px; font-weight: bold; text-align: center; margin: 15px 0; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 20px; }
        .print-btn { text-align: center; margin-top: 20px; }
        .print-btn button { padding: 8px 20px; font-size: 12px; cursor: pointer; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    <div class="kwitansi">
        <h1>SIMPERSA</h1>
        <div class="sub">Sistem Informasi Pengelolaan Sampah Terintegrasi</div>
        <div class="sub">BUKTI PEMBAYARAN IURAN</div>

        <div class="garis"></div>
        <table>
            <tr><td class="label">No Kwitansi</td><td>: KWI-{{ $iuran->id }}/{{ date('Ymd') }}</td></tr>
            <tr><td class="label">No Pelanggan</td><td>: {{ $pelanggan->no_pelanggan }}</td></tr>
            <tr><td class="label">Nama</td><td>: {{ $pelanggan->user->name }}</td></tr>
            <tr><td class="label">Periode</td><td>: {{ $iuran->bulan_tagihan }}</td></tr>
            <tr><td class="label">Tanggal Bayar</td><td>: {{ \Carbon\Carbon::parse($iuran->tanggal_bayar)->format('d/m/Y') }}</td></tr>
            <tr><td class="label">Metode</td><td>: {{ $iuran->metode_pembayaran }}</td></tr>
        </table>
        <div class="garis"></div>
        <div class="total">Rp {{ number_format($iuran->jumlah_tagihan + ($iuran->denda ?? 0), 0, ',', '.') }}</div>
        <div class="garis"></div>
        <table>
            <tr><td>Tagihan</td><td class="label">: Rp {{ number_format($iuran->jumlah_tagihan, 0, ',', '.') }}</td></tr>
            <tr><td>Denda</td><td class="label">: Rp {{ number_format($iuran->denda ?? 0, 0, ',', '.') }}</td></tr>
            <tr><td>Status</td><td class="label">: LUNAS</td></tr>
        </table>
        <div class="footer">Terima kasih telah melakukan pembayaran tepat waktu.<br>SIMPERSA &mdash; Desa/Kelurahan Anda</div>
    </div>
    <div class="print-btn">
        <button onclick="window.print()">Cetak / Simpan PDF</button>
    </div>
</body>
</html>
