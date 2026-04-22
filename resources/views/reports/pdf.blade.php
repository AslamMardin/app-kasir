<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan - {{ $date->format('d/m/Y') }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; color: #000; }
        .header p { margin: 5px 0 0; color: #666; }
        .summary { margin-bottom: 20px; }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { padding: 5px; border: 1px solid #ddd; }
        .summary-label { background: #f9f9f9; font-weight: bold; width: 30%; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th { background: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; }
        .data-table td { border: 1px solid #ddd; padding: 8px; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>TOKO CAMPALAGIAN</h1>
        <p>Laporan Penjualan Harian</p>
        <p>Tanggal: {{ $date->format('d F Y') }}</p>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td class="summary-label">Total Transaksi</td>
                <td>{{ $totalTransactions }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Omzet (Tunai)</td>
                <td>Rp {{ number_format($totalSales, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Invoice</th>
                <th>Waktu</th>
                <th>Kasir</th>
                <th>Metode</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $t)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="font-family: monospace;">{{ $t->invoice_number }}</td>
                <td>{{ $t->created_at->format('H:i') }}</td>
                <td>{{ $t->user->name }}</td>
                <td style="text-transform: uppercase;">{{ $t->payment_method }}</td>
                <td class="text-right">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">TOTAL KESELURUHAN</th>
                <th class="text-right">Rp {{ number_format($totalSales, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
