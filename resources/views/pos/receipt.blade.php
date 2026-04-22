<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $transaction->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; width: 300px; margin: 0 auto; padding: 10px; font-size: 12px; color: #000; background: #fff; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        .row { display: flex; justify-content: space-between; }
        .item-row { margin-bottom: 2px; }
        h2 { font-size: 16px; margin-bottom: 2px; }
        @media print { body { width: 80mm; } .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="center">
        <h2>TOKO CAMPALAGIAN</h2>
        <p>Jl. Poros Campalagian</p>
        <p>Telp: 0422-XXXXXXX</p>
    </div>
    <div class="line"></div>
    <div class="row"><span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span><span>{{ $transaction->invoice_number }}</span></div>
    <div class="row"><span>Kasir: {{ $transaction->user->name ?? '-' }}</span></div>
    @if($transaction->member)<div class="row"><span>Member: {{ $transaction->member->name }}</span></div>@endif
    <div class="line"></div>

    @foreach($transaction->items as $item)
    <div class="item-row">
        <div>{{ $item->product_name }}</div>
        <div class="row">
            <span>{{ $item->quantity }} x Rp {{ number_format($item->product_price, 0, ',', '.') }}</span>
            <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <div class="line"></div>
    <div class="row bold"><span>TOTAL</span><span>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span></div>
    <div class="row"><span>Bayar ({{ strtoupper($transaction->payment_method) }})</span><span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span></div>
    <div class="row"><span>Kembali</span><span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span></div>
    <div class="line"></div>

    <div class="center" style="margin-top: 10px;">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Barang yang sudah dibeli</p>
        <p>tidak dapat dikembalikan</p>
    </div>

    <div class="no-print center" style="margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; font-size: 14px; cursor: pointer;">🖨️ Cetak Struk</button>
        <button onclick="window.close()" style="padding: 10px 30px; font-size: 14px; cursor: pointer; margin-left: 10px;">✕ Tutup</button>
    </div>
</body>
</html>
