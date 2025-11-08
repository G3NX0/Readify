<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function showLogin(Request $request): View
    {
        $intended = $request->query('intended');
        return view('admin.login', compact('intended'));
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string']]);
        $password = (string) config('admin.password', 'secret');
        if (hash_equals($password, (string) $request->string('password'))) {
            $request->session()->regenerate();
            $request->session()->put('is_admin', true);
            $target = $request->string('intended') ?: route('borrowings.index');
            return redirect()->to((string) $target);
        }
        return back()->withErrors(['password' => 'Invalid admin password'])->withInput();
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('is_admin');
        return redirect('/');
    }
}

