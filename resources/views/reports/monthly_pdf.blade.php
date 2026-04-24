<!DOCTYPE html>
<html>
<head>
    <title>Laporan Bulanan - {{ $month }}/{{ $year }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .summary { margin-bottom: 20px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PENJUALAN BULANAN</h2>
        <p>Periode: {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</p>
    </div>

    <div class="summary">
        <table style="width: 300px;">
            <tr>
                <td>Total Transaksi</td>
                <td>: {{ $totalTransactions }}</td>
            </tr>
            <tr>
                <td>Total Penjualan</td>
                <td>: Rp {{ number_format($totalSales, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jumlah Transaksi</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $row)
            <tr>
                <td>{{ date('d/m/Y', strtotime($row->date)) }}</td>
                <td>{{ $row->total_transactions }}</td>
                <td>Rp {{ number_format($row->total_sales, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background: #f9f9f9;">
                <td>TOTAL</td>
                <td>{{ $totalTransactions }}</td>
                <td>Rp {{ number_format($totalSales, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 50px; text-align: right;">
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
