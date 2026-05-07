<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $config = Configuracion::first();
        $usuarios = User::with('role')->get();

        return view('admin.usuarios', compact('config', 'usuarios'));
    }

    public function create()
    {
        $config = Configuracion::first();
        return view('admin.usuarios_crear', compact('config'));
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado con éxito');
    }
    public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return response()->json(['success' => true]);
}
    public function edit($id)
{
    $user = User::with('role')->findOrFail($id);
    return response()->json($user);
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
    $user->name = $request->name;
    $user->email = $request->email;
    if ($request->filled('password')) {
        $user->password = bcrypt($request->password);
    }
    $user->role_id = $request->role_id;
    $user->save();

    return response()->json(['success' => true]);
}

}


