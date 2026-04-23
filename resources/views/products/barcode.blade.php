<!DOCTYPE html>
<html>
<head>
    <title>Cetak Barcode - {{ $product->name }}</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 20px; }
        .label-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; justify-items: center; }
        .barcode-card { border: 1px solid #eee; padding: 10px; width: 200px; text-align: center; }
        .product-name { font-size: 12px; font-weight: bold; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .barcode-svg svg { width: 100%; height: 50px; }
        .barcode-text { font-family: monospace; font-size: 12px; margin-top: 5px; }
        .price { font-size: 14px; font-weight: bold; margin-top: 5px; }
        @media print { .no-print { display: none; } body { padding: 0; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 30px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">🖨️ Cetak Barcode</button>
        <p style="color: #666; font-size: 14px;">Tips: Atur "Scale" ke 100% dan "Margins" ke None di pengaturan print browser.</p>
    </div>

    <div class="label-container">
        @for($i = 0; $i < 12; $i++)
        <div class="barcode-card">
            <div class="product-name">{{ $product->name }}</div>
            <div class="barcode-svg">
                {!! $barcode !!}
            </div>
            <div class="barcode-text">{{ $product->barcode }}</div>
            <div class="price">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</div>
        </div>
        @endfor
    </div>
</body>
</html>
