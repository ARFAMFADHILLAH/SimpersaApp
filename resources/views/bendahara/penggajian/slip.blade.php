<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $gaji->petugas->name ?? 'Petugas' }}</title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; width: 350px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 18px; }
        .header p { margin: 2px 0; font-size: 11px; }
        .info { margin-bottom: 15px; }
        .info td { padding: 3px 0; }
        .detail { width: 100%; border-collapse: collapse; }
        .detail td, .detail th { padding: 6px 4px; border-bottom: 1px solid #ccc; }
        .detail .label { font-weight: bold; }
        .total { font-weight: bold; font-size: 14px; text-align: center; padding: 10px; border-top: 2px solid #333; border-bottom: 2px solid #333; margin: 10px 0; }
        .footer { text-align: center; margin-top: 20px; border-top: 1px solid #333; padding-top: 10px; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SIMPERSA</h2>
        <p>Slip Gaji Petugas</p>
    </div>

    <table class="info">
        <tr><td>Nama Petugas</td><td>: {{ $gaji->petugas->name ?? '-' }}</td></tr>
        <tr><td>Email</td><td>: {{ $gaji->petugas->email ?? '-' }}</td></tr>
        <tr><td>Periode Gaji</td><td>: {{ $gaji->bulan_gaji }}</td></tr>
        <tr><td>Status</td><td>: {{ $gaji->status_pembayaran }}</td></tr>
    </table>

    <table class="detail">
        <tr><th colspan="2" style="text-align:center; background:#f0f0f0;">Rincian Gaji</th></tr>
        <tr><td>Gaji Pokok</td><td style="text-align:right">Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td></tr>
        <tr><td>Insentif & Bonus</td><td style="text-align:right">Rp {{ number_format($gaji->insentif_lembur, 0, ',', '.') }}</td></tr>
        <tr><td>Potongan</td><td style="text-align:right; color:red;">(Rp {{ number_format($gaji->potongan, 0, ',', '.') }})</td></tr>
    </table>

    <div class="total">
        TOTAL GAJI BERSIH: Rp {{ number_format($gaji->total_gaji_bersih, 0, ',', '.') }}
    </div>

    <div style="margin-top:20px;">
        <p>Dibayarkan pada: {{ $gaji->updated_at->format('d/m/Y') }}</p>
        <br><br>
        <p>Hormat kami,</p>
        <br><br>
        <p>( Bendahara )</p>
    </div>

    <div class="footer">
        <p>Slip gaji ini sah dan diterbitkan oleh SIMPERSA</p>
    </div>

    <script>window.print();</script>
</body>
</html>
