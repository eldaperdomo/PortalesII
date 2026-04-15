<?php
namespace App\Http\Controllers;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditoriaServicio;

class UsuarioController extends Controller
{

    // 👉 LISTAR
    public function index(Request $request)
    {
        $query = Usuario::query();

        // 🔥 Solo activos por defecto
        if ($request->incluir_inactivos !== 'true') {
            $query->where('activo', true);
        }

        // Filtro por rol
        if ($request->rol) {
            $query->where('rol', $request->rol);
        }

        // Búsqueda
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->search}%")
                  ->orWhere('username', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $usuarios = $query->latest()->paginate(10)->withQueryString();

        return view('usuarios.index', compact('usuarios'));
    }

    // 👉 FORM CREAR
    public function create()
    {
        return view('usuarios.create');
    }

    // 👉 GUARDAR
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:150',
            'username' => 'required|max:50|unique:usuarios,username',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'rol' => 'required|in:admin,empleado',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $rutaImagen = null;

        if ($request->hasFile('foto')) {
            $rutaImagen = $request->file('foto')->store('usuarios', 'public');
        }

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'foto_perfil_url' => $rutaImagen,
            'activo' => true
        ]);

        AuditoriaServicio::registrar([
            'tabla' => 'usuarios',
            'accion' => 'CREATE',
            'registro_id' => $usuario->id,
            'datos_nuevos' => $usuario->toArray()
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }

    // 👉 VER
    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.show', compact('usuario'));
    }

    // 👉 FORM EDITAR
    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    // 👉 ACTUALIZAR
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|max:150',
            'username' => "required|max:50|unique:usuarios,username,$id",
            'email' => "required|email|unique:usuarios,email,$id",
            'rol' => 'required|in:admin,empleado',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $antes = $usuario->toArray();

        // Imagen
        if ($request->hasFile('foto')) {

            if ($usuario->foto_perfil_url && \Storage::disk('public')->exists($usuario->foto_perfil_url)) {
                \Storage::disk('public')->delete($usuario->foto_perfil_url);
            }

            $rutaImagen = $request->file('foto')->store('usuarios', 'public');
            $usuario->foto_perfil_url = $rutaImagen;
        }

        $usuario->update([
            'nombre' => $request->nombre,
            'username' => $request->username,
            'email' => $request->email,
            'rol' => $request->rol,
        ]);

        AuditoriaServicio::registrar([
            'tabla' => 'usuarios',
            'accion' => 'UPDATE',
            'registro_id' => $usuario->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $usuario->toArray()
        ]);

        return back()->with('success', 'Usuario actualizado correctamente');
    }

    // 👉 DESACTIVAR (DELETE lógico)
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        if (Auth::id() == $usuario->id) {
            return back()->withErrors('No puedes desactivar tu propio usuario');
        }

        if ($usuario->rol === 'admin') {
            $admins = Usuario::where('rol', 'admin')->where('activo', true)->count();

            if ($admins <= 1) {
                return back()->withErrors('No puedes desactivar el último admin activo');
            }
        }

        $antes = $usuario->toArray();

        $usuario->update(['activo' => false]);

        AuditoriaServicio::registrar([
            'tabla' => 'usuarios',
            'accion' => 'DELETE', // 🔥 AQUÍ
            'registro_id' => $usuario->id,
            'datos_anteriores' => $antes
        ]);

        return back()->with('success', 'Usuario desactivado correctamente');
    }

    // 👉 ACTIVAR (UPDATE)
    public function activar($id)
    {
        $usuario = Usuario::findOrFail($id);

        if ($usuario->activo) {
            return back()->withErrors('El usuario ya está activo');
        }

        $antes = $usuario->toArray();

        $usuario->update(['activo' => true]);

        AuditoriaServicio::registrar([
            'tabla' => 'usuarios',
            'accion' => 'UPDATE', // 🔥 AQUÍ
            'registro_id' => $usuario->id,
            'datos_anteriores' => $antes,
            'datos_nuevos' => $usuario->toArray()
        ]);

        return back()->with('success', 'Usuario activado correctamente');
    }
}

