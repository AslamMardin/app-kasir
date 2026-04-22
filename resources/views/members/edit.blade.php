@extends('layouts.app')
@section('title', 'Edit Member')
@section('page_title', 'Edit Member')
@section('content')
<div style="max-width: 500px;">
    <a href="/members" style="color: var(--text-muted); text-decoration: none; font-size: 0.875rem; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;"><i data-lucide="arrow-left" style="width:16px;"></i> Kembali</a>
    <div class="glass-card">
        <form method="POST" action="/members/{{ $member->id }}">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">No. HP</label><input type="text" name="phone" class="form-input" value="{{ $member->phone }}" required></div>
            <div class="form-group"><label class="form-label">Nama</label><input type="text" name="name" class="form-input" value="{{ $member->name }}" required></div>
            @if($errors->any())<div style="color: var(--danger); font-size: 0.875rem; margin-bottom: 1rem;">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
            <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
