<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
        ]);

        Setting::updateOrCreate(['key' => 'app_name'], ['value' => $request->app_name]);
        
        // Clear cache if you use it for settings
        
        return back()->with('success', 'Pengaturan brand berhasil diperbarui.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    public function backup()
    {
        $filename = "backup-" . date('Y-m-d-H-i-s') . ".sql";
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        
        $path = storage_path('app/' . $filename);
        
        // Command for MySQL dump
        $command = sprintf(
            'mysqldump -u %s %s %s > %s',
            $dbUser,
            ($dbPass ? '-p' . $dbPass : ''),
            $dbName,
            escapeshellarg($path)
        );

        exec($command);

        if (file_exists($path)) {
            return response()->download($path)->deleteFileAfterSend(true);
        }

        return back()->withErrors(['backup' => 'Gagal melakukan backup. Pastikan mysqldump terinstal di server.']);
    }
}
