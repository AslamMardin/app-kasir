@extends('layouts.app')
@section('title', 'Edit Produk')
@section('page_title', 'Edit Produk')

@section('content')
<div style="max-width: 600px;">
    <a href="/products" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;"><i data-lucide="arrow-left" style="width:16px;"></i> Kembali</a>
    <div class="glass-card">
        <form method="POST" action="/products/{{ $product->id }}">
            @csrf @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group"><label class="form-label">Barcode</label><input type="text" name="barcode" class="form-input" value="{{ $product->barcode }}" required></div>
                <div class="form-group"><label class="form-label">Nama Produk</label><input type="text" name="name" class="form-input" value="{{ $product->name }}" required></div>
                <div class="form-group"><label class="form-label">Kategori</label><select name="category_id" class="form-input" required>@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach</select></div>
                <div class="form-group"><label class="form-label">Unit</label><select name="unit" class="form-input"><option value="pcs" {{ $product->unit == 'pcs' ? 'selected' : '' }}>pcs</option><option value="kg" {{ $product->unit == 'kg' ? 'selected' : '' }}>kg</option><option value="liter" {{ $product->unit == 'liter' ? 'selected' : '' }}>liter</option></select></div>
                <div class="form-group"><label class="form-label">Harga Beli (Rp)</label><input type="number" name="purchase_price" class="form-input" value="{{ $product->purchase_price }}" required></div>
                <div class="form-group"><label class="form-label">Harga Jual (Rp)</label><input type="number" name="selling_price" class="form-input" value="{{ $product->selling_price }}" required></div>
                <div class="form-group"><label class="form-label">Stok</label><input type="number" name="stock" class="form-input" value="{{ $product->stock }}" required></div>
                <div class="form-group"><label class="form-label">Stok Minimum</label><input type="number" name="min_stock" class="form-input" value="{{ $product->min_stock }}" required></div>
            </div>
            @if($errors->any())<div style="color: var(--danger); font-size: 0.875rem; margin-bottom: 1rem;">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem;">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
