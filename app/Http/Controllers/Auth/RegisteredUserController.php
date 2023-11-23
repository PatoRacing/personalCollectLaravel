<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EstadoDeUsuario;
use App\Models\Rol;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    
    public function create(): View
    {
        $roles = Rol::all();
        $estadoDeUsuario = EstadoDeUsuario::all();
        $usuarioAutenticado = auth()->id();

        return view('auth.register',[
            'roles' => $roles,
            'estadoDeUsuario' => $estadoDeUsuario,
            'usuarioAutenticado' => $usuarioAutenticado
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:12'],
            'rol_id' => ['required', 'string', 'max:12'],
            'telefono' => ['required', 'string', 'regex:/^[0-9]+$/', 'max:20'],
            'domicilio' => ['required', 'string', 'max:255'],
            'localidad' => ['required', 'string', 'max:255'],
            'codigo_postal' => ['required', 'string', 'max:20'],
            'fecha_de_ingreso' => ['required', 'date'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[A-Z])(?=.*[!@#$%^&*])/', 'confirmed', Rules\Password::defaults()],
        ]);

        if(Auth::check())
        {
            $user = User::create([
                'name' => $request->name,
                'apellido' => $request->apellido,
                'dni' => $request->dni,
                'rol_id' => $request->rol_id,
                'telefono' => $request->telefono,
                'domicilio' => $request->domicilio,
                'localidad' => $request->localidad,
                'codigo_postal' => $request->codigo_postal,
                'fecha_de_ingreso' => $request->fecha_de_ingreso,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'estado_de_usuario_id' => 1,
                'usuario_ultima_modificacion'=> $request->usuario_ultima_modificacion
            ]);
        }

        event(new Registered($user));

        //Auth::login($user);

        return redirect('usuario')->with('message', 'Usuario agregado correctamente');
    }
}
