<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function edit($id)
    {
        $user = User::findOrFail($id);
        session(['titulo' => 'Editar Usuario']);
        return view('admin.edit_user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'rol' => 'required|string|max:255',
        ]);

        $user->username = $request->input('username');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->rol = $request->input('rol');
        $user->save();

        session(['titulo' => 'Administración']);
        return redirect()->route('admin.home')->with('success', 'Usuario actualizado con éxito.');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.home')->with('error-eliminar-usuario', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        session(['titulo' => 'Administración']);
        return redirect()->route('admin.home')->with('success', 'Usuario eliminado con éxito.');
    }

    public function create()
    {
        session(['titulo' => 'Crear Usuario...']);
        return view('admin.create_user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|string|max:255',
        ]);

        $user = new User();
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->rol = $request->input('rol');
        $user->save();

        session(['titulo' => 'Administración']);
        return redirect()->route('admin.home')->with('success', 'Usuario creado con éxito.');
    }


    public function profile()
{
    $user = Auth::user();
    session(['titulo' => 'Perfil de Usuario']);
    return view('profile', compact('user'));
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    $user = Auth::user();
    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Contraseña actualizada con éxito.');
}

}
