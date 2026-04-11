<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function loginshow()
    {
        if (Auth::check()) {
            return redirect()->route('workspace.index');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|max:255|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($data)) {
            return back()->with('error', 'Email or password wrong.');
        }

        $request->session()->regenerate();
        return redirect()->route('workspace.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
