<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function index()
    {
        return view("auth.login");
    }
    public function login(Request $request)
    {
        $credential = [
            "username" => $request->get('username'),
            "password" => $request->get('password')
        ];
        if (Auth::attempt($credential)) {
            return redirect('/dashboard');
        } else {
            return redirect()->back()->with("error-message", "Invalid Credential");
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
