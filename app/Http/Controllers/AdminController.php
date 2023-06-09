<?php

namespace App\Http\Controllers;
use App\Models\Administrador;
use App\Models\Usuario;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function index()
    {
        $administradores = Administrador::all();
        return view ('admin.paineladmin',compact ('administradores'));
    }

    public function showloginform ()
    {
        return view ('admin.index');
    }

    public function store(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(!Auth::attempt($credentials)){
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return redirect()->route('admin.index');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function edit(Request $request, Administrador $admin){
        $request->validate([
            'name' => 'required',
        ]);

        $admin->name = $request->name;

        $admin->save();

        return redirect()->route('admin.index');
    }

    public function lista()
    {
        $clientes = Usuario::all();
        return view ('admin.listaclientes',compact ('clientes'));
    }

    public function destroy($id) {
        Usuario::where('id',$id)->delete();
        return redirect()->route('admin.listaclientes')->with('mensagem','Usuario apagado com sucesso.');
    }

}
