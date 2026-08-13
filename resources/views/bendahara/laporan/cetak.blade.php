<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan - {{ $bulan }}</title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 6px 8px; border: 1px solid #333; text-align: left; }
        th { background: #f0f0f0; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .total-row td { border-top: 2px solid #333; font-weight: bold; }
        .footer { text-align: center; margin-top: 30px; border-top: 1px solid #333; padding-top: 10px; font-size: 10px; }
        .signature { margin-top: 40px; }
        .signature td { border: none; padding: 20px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SIMPERSA</h1>
        <p>Sistem Informasi Persampahan Terintegrasi</p>
        <p><strong>Laporan Laba/Rugi</strong></p>
        <p>Periode: {{ $bulan }}</p>
    </div>

    <table>
        <tr><th colspan="2" style="text-align:center;">LAPORAN LABA / RUGI</th></tr>
        <tr>
            <td class="bold">PENDAPATAN</td>
            <td class="text-right">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr>
            <td class="bold">BEBAN USAHA</td>
            <td></td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;Beban Gaji Petugas</td>
            <td class="text-right">Rp {{ number_format($pengeluaranGaji, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>&nbsp;&nbsp;Beban Operasional (BBM, Servis, dll)</td>
            <td class="text-right">Rp {{ number_format($pengeluaranOperasional, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="bold">Total Beban Usaha</td>
            <td class="text-right bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr class="total-row">
            <td class="bold">LABA / (RUGI) BERSIH</td>
            <td class="text-right bold {{ $labaRugi >= 0 ? '' : 'color:red;' }}">
                Rp {{ number_format(abs($labaRugi), 0, ',', '.') }}
                ({{ $labaRugi >= 0 ? 'Surplus' : 'Defisit' }})
            </td>
        </tr>
    </table>

    <table>
        <tr><th colspan="2" style="text-align:center;">ARUS KAS SEDERHANA</th></tr>
        <tr>
            <td class="bold">Kas Masuk (Penerimaan Penjualan Sampah)</td>
            <td class="text-right">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="bold">Kas Keluar (Pembayaran)</td>
            <td class="text-right">(Rp {{ number_format($totalPengeluaran, 0, ',', '.') }})</td>
        </tr>
        <tr class="total-row">
            <td class="bold">Perubahan Kas Bersih</td>
            <td class="text-right bold">Rp {{ number_format($pemasukanBulanIni - $totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="signature">
        <table>
            <tr>
                <td style="width:50%; text-align:center;">
                    <p>Mengetahui,</p>
                    <p>Manager/Pimpinan</p>
                    <br><br><br>
                    <p>( _____________________ )</p>
                </td>
                <td style="width:50%; text-align:center;">
                    <p>Hormat kami,</p>
                    <p>Bendahara</p>
                    <br><br><br>
                    <p>( _____________________ )</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini diterbitkan oleh SIMPERSA - {{ date('Y') }}</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <script>window.print();</script>
</body>
</html>
