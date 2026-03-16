<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Ciclista;

class CiclistaController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $ciclista = Ciclista::where('email', $request->email)->first();

        if ($ciclista && Hash::check($request->password, $ciclista->password)) {
            Auth::login($ciclista);

            return redirect('/bienvenida');
        }

        return back()->with('error', 'Correo o contraseña incorrectos');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function showBienvenida()
    {
        return view('bienvenida');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:80',
            'apellidos' => 'required|string|max:80',
            'email' => 'required|email|unique:ciclista,email',
            'password' => 'required|string|min:4|confirmed',
            'fecha_nacimiento' => 'required|date',
            'peso_base' => 'required|numeric',
            'altura_base' => 'required|integer',
        ]);

        Ciclista::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'peso_base' => $request->peso_base,
            'altura_base' => $request->altura_base,
        ]);

        return redirect('/login')->with('success', 'Cuenta creada correctamente. ¡Puedes iniciar sesión!');
    }

    public function mostrarDatosCiclista()
    {
        return view('menu.ciclista');
    }
    public function index()
    {
        $ciclista = Auth::user();

        $historico = \App\Models\HistoricoCiclista::where('id_ciclista', $ciclista->id)
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json([
            'ciclista' => $ciclista,
            'historico' => $historico
        ]);
    }

}