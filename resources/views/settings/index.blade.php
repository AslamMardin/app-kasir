@extends('layouts.app')
@section('title', 'Pengaturan')
@section('page_title', 'Pengaturan Sistem')
@section('page_subtitle', 'Kelola identitas aplikasi dan keamanan akun')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    {{-- Brand & Theme --}}
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="glass-card">
            <h3 style="margin-bottom: 1.5rem;"><i data-lucide="layout" style="width: 20px; display: inline; vertical-align: middle;"></i> Identitas Toko</h3>
            <form method="POST" action="/settings/update">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Toko / Brand</label>
                    <input type="text" name="app_name" class="form-input" value="{{ $appName }}" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Nama Brand</button>
            </form>
        </div>

        <div class="glass-card">
            <h3 style="margin-bottom: 1.5rem;"><i data-lucide="palette" style="width: 20px; display: inline; vertical-align: middle;"></i> Tampilan (Tema)</h3>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: rgba(255,255,255,0.05); border-radius: 12px; border: 1px solid var(--border);">
                <div>
                    <p style="font-weight: 600;">Mode Terang / Gelap</p>
                    <p style="font-size: 0.75rem; color: var(--text-muted);">Ubah nuansa warna aplikasi</p>
                </div>
                <button id="themeToggle" class="btn btn-primary" style="padding: 0.5rem 1rem;">
                    <i data-lucide="sun" id="themeIcon"></i> <span id="themeText">Terang</span>
                </button>
            </div>
        </div>

        <div class="glass-card">
            <h3 style="margin-bottom: 1.5rem;"><i data-lucide="database" style="width: 20px; display: inline; vertical-align: middle;"></i> Backup Data</h3>
            <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.5rem;">Unduh salinan seluruh database Anda dalam format .sql.</p>
            <a href="/settings/backup" class="btn btn-primary" style="width: 100%; background: #3b82f6;">
                <i data-lucide="download" style="width: 18px;"></i> Download Backup Database
            </a>
            @error('backup') <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.5rem;">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Security --}}
    <div class="glass-card">
        <h3 style="margin-bottom: 1.5rem;"><i data-lucide="lock" style="width: 20px; display: inline; vertical-align: middle;"></i> Keamanan Akun</h3>
        <p style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 1.5rem;">Ganti password Anda secara berkala untuk menjaga keamanan akun.</p>
        
        @if(session('success'))
            <div style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem;">{{ session('success') }}</div>
        @endif

        <form method="POST" action="/settings/password">
            @csrf
            <div class="form-group">
                <label class="form-label">Password Saat Ini</label>
                <input type="password" name="current_password" class="form-input" required>
                @error('current_password') <span style="color: var(--danger); font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="new_password" class="form-input" required>
                @error('new_password') <span style="color: var(--danger); font-size: 0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" class="form-input" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Ganti Password</button>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    // Theme Toggle Logic
    const themeBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');

    function updateThemeUI(theme) {
        if (theme === 'light') {
            themeIcon.setAttribute('data-lucide', 'moon');
            themeText.textContent = 'Gelap';
        } else {
            themeIcon.setAttribute('data-lucide', 'sun');
            themeText.textContent = 'Terang';
        }
        lucide.createIcons();
    }

    themeBtn.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeUI(newTheme);
    });

    // Initial UI state
    updateThemeUI(localStorage.getItem('theme') || 'dark');
</script>
@endpush
