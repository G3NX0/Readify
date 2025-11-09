<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $hasUsername = Schema::hasColumn('users', 'username');
        $hasRole = Schema::hasColumn('users', 'role');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];

        if ($hasUsername) {
            $rules['username'] = ['nullable', 'string', 'max:255', 'unique:users,username'];
        } else {
            $rules['username'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // cast 'hashed' di model akan menghash
        ];

        if ($hasUsername) {
            $data['username'] = $validated['username'] ?? null;
        }
        if ($hasRole) {
            $data['role'] = 'user';
        }

        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect('/')->with('status', 'Registrasi berhasil.');
    }
}
