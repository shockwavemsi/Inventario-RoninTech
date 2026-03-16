<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            if ($user->role->name === 'admin') {
                return redirect('/admin');
            }

            return redirect('/usuario');
        }

        return back()->with('error', 'Credenciales incorrectas');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}


