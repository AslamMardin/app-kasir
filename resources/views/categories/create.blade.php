@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('page_title', 'Tambah Kategori')
@section('content')
<div style="max-width: 500px;">
    <a href="/categories" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;"><i data-lucide="arrow-left" style="width:16px;"></i> Kembali</a>
    <div class="glass-card">
        <form method="POST" action="/categories">
            @csrf
            <div class="form-group"><label class="form-label">Nama Kategori</label><input type="text" name="name" class="form-input" required autofocus></div>
            @if($errors->any())<div style="color: var(--danger); font-size: 0.875rem; margin-bottom: 1rem;">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
            <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan</button>
        </form>
    </div>
</div>
@endsection
