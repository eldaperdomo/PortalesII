<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sesion;

class AuthController extends Controller
{
    // 📄 Mostrar vista login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 🔐 LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        $campo = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credenciales = [
            $campo => $request->login,
            'password' => $request->password,
            'activo' => true
        ];

        if (Auth::attempt($credenciales)) {

            $request->session()->regenerate();

            $usuario = Auth::user();

            // 🔥 GUARDAR SESIÓN
            Sesion::create([
                'usuario_id' => $usuario->id,
                'inicio_sesion' => now(),
                'user_agent' => $request->header('User-Agent')
            ]);

            // 🔥 VALIDACIÓN EXTRA
            if (!$usuario->activo) {
                Auth::logout();
                return back()->withErrors(['login' => 'Usuario inactivo']);
            }

            // 🔥 REDIRECCIÓN
            if ($usuario->esAdmin()) {
                return redirect()->route('dashboard.admin');
            }

            return redirect()->route('dashboard.empleado');
        }

        return back()->withErrors([
            'login' => 'Credenciales incorrectas'
        ])->withInput();
    }

    // 🚪 LOGOUT
    public function logout(Request $request)
    {
        $usuarioId = Auth::id(); // 🔥 guardar antes

        // 🔥 ACTUALIZAR SESIÓN
        $sesion = Sesion::where('usuario_id', $usuarioId)
            ->whereNull('cierre_sesion')
            ->latest()
            ->first();

        if ($sesion) {
            $sesion->update([
                'cierre_sesion' => now()
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
