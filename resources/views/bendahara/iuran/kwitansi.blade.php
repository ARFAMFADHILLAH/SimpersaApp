<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kwitansi Pembayaran Iuran</title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; width: 300px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 2px dashed #333; padding-bottom: 10px; margin-bottom: 10px; }
        .header h2 { margin: 0; font-size: 16px; }
        .header p { margin: 2px 0; font-size: 11px; }
        .body table { width: 100%; }
        .body td { padding: 4px 0; }
        .body .label { font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; border-top: 2px dashed #333; padding-top: 10px; font-size: 10px; }
        .total { font-size: 14px; font-weight: bold; text-align: center; padding: 10px 0; border-top: 1px solid #333; border-bottom: 1px solid #333; margin: 10px 0; }
        .stamp { margin-top: 20px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SIMPERSA</h2>
        <p>Sistem Informasi Persampahan Terintegrasi</p>
        <p>Kwitansi Pembayaran Iuran Sampah</p>
    </div>

    <div class="body">
        <table>
            <tr><td class="label">No. Kwitansi</td><td>: KW-{{ str_pad($iuran->id, 5, '0', STR_PAD_LEFT) }}</td></tr>
            <tr><td class="label">Tanggal Bayar</td><td>: {{ $iuran->tanggal_bayar ? date('d/m/Y', strtotime($iuran->tanggal_bayar)) : '-' }}</td></tr>
            <tr><td class="label">Nama Warga</td><td>: {{ $iuran->warga->user->name ?? '-' }}</td></tr>
            <tr><td class="label">No. Warga</td><td>: {{ $iuran->warga->no_warga ?? '-' }}</td></tr>
            <tr><td class="label">Alamat</td><td>: {{ $iuran->warga->alamat_lengkap ?? '-' }}</td></tr>
            <tr><td class="label">Periode</td><td>: {{ $iuran->bulan_tagihan }}</td></tr>
            <tr><td class="label">Metode Bayar</td><td>: {{ $iuran->metode_pembayaran }}</td></tr>
        </table>

        <div class="total">
            <table>
                <tr><td>Tagihan Pokok</td><td style="text-align:right">Rp {{ number_format($iuran->jumlah_tagihan, 0, ',', '.') }}</td></tr>
                @if($iuran->denda > 0)
                    <tr><td>Denda</td><td style="text-align:right">Rp {{ number_format($iuran->denda, 0, ',', '.') }}</td></tr>
                @endif
                <tr style="font-weight:bold; border-top:1px solid #333;">
                    <td>Total Dibayar</td>
                    <td style="text-align:right">Rp {{ number_format($iuran->jumlah_tagihan + $iuran->denda, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <p style="text-align:center; font-style:italic; margin-top:10px;"># Terbilang: {{ number_format($iuran->jumlah_tagihan + $iuran->denda, 0, ',', '.') }} rupiah #</p>
    </div>

    <div class="stamp">
        <p>Hormat kami,</p>
        <br><br>
        <p>( Bendahara )</p>
    </div>

    <div class="footer">
        <p>Terima kasih telah membayar tepat waktu</p>
        <p>SIMPERSA - {{ date('Y') }}</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
