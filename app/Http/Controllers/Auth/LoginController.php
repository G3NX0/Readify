<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Jaga-jaga: pastikan akun admin ada sesuai permintaan
        if (strcasecmp($credentials['email'], 'admin@gmail.com') === 0) {
            $admin = User::firstWhere('email', 'admin@gmail.com');
            if (! $admin) {
                $data = [
                    'name' => 'admin',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('admin'),
                ];
                if (Schema::hasColumn('users', 'username')) {
                    $data['username'] = 'admin';
                }
                if (Schema::hasColumn('users', 'role')) {
                    $data['role'] = 'admin';
                }
                $admin = User::create($data);
            } elseif (! Hash::check('admin', (string) $admin->getAuthPassword())) {
                // Sinkronisasi password jika berbeda (opsional agar sesuai instruksi)
                $admin->password = Hash::make('admin');
                if (Schema::hasColumn('users', 'role')) {
                    $admin->role = 'admin';
                }
                $admin->save();
            }
        }

        if (Auth::attempt($credentials, (bool) $request->boolean('remember'))) {
            $request->session()->regenerate();
            $role = (string) (auth()->user()->role ?? 'user');
            return redirect()->intended($role === 'admin' ? route('borrowings.index') : '/');
        }

        return back()->withErrors([
            'email' => 'Kredensial tidak cocok.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
