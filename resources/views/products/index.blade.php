@extends('layouts.app')
@section('title', 'Produk')
@section('page_title', 'Manajemen Produk')
@section('page_subtitle', 'Kelola data produk toko')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 0.5rem;">
        <input type="text" name="search" class="form-input" style="width: 300px;" placeholder="Cari produk..." value="{{ request('search') }}">
        <button class="btn btn-primary">Cari</button>
    </form>
    <a href="/products/create" class="btn btn-primary"><i data-lucide="plus" style="width:16px;"></i> Tambah Produk</a>
</div>

@if(session('success'))
    <div style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem;">{{ session('success') }}</div>
@endif

<div class="glass-card" style="padding: 0; overflow: hidden;">
    <table class="data-table">
        <thead><tr><th>Barcode</th><th>Nama Produk</th><th>Kategori</th><th>Harga Beli</th><th>Harga Jual</th><th>Stok</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td style="font-family: monospace;">{{ $product->barcode }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                <td>
                    <span style="color: {{ $product->stock <= $product->min_stock ? 'var(--danger)' : 'var(--accent)' }}; font-weight: 600;">{{ $product->stock }}</span>
                    {{ $product->unit }}
                </td>
                <td style="display: flex; gap: 0.5rem; align-items: center;">
                    <a href="/products/{{ $product->id }}/barcode" title="Cetak Barcode" style="color: var(--text-muted); transition: color 0.2s;" target="_blank">
                        <i data-lucide="barcode" style="width: 20px;"></i>
                    </a>
                    <a href="/products/{{ $product->id }}/edit" title="Edit Produk" style="color: var(--accent); transition: color 0.2s;">
                        <i data-lucide="edit-3" style="width: 20px;"></i>
                    </a>
                    <form action="/products/{{ $product->id }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" title="Hapus Produk" style="color: var(--danger); background:none; border:none; cursor:pointer; padding: 0; display: flex; align-items: center;">
                            <i data-lucide="trash-2" style="width: 20px;"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center; color: var(--text-muted);">Belum ada produk</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div style="margin-top: 1rem;">{{ $products->links() }}</div>
@endsection
