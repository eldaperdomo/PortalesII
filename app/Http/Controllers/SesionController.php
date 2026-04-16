<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sesion;

class SesionController extends Controller
{
    public function index(Request $request)
    {
        $query = Sesion::with('usuario');

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->orWhere('user_agent', 'like', "%$search%")
                  ->orWhereHas('usuario', function ($sub) use ($search) {
                      $sub->where('nombre', 'like', "%$search%");
                  });
            });
        }

        $sesiones = $query->latest('inicio_sesion')->paginate(10)->withQueryString();

        return view('sesiones.index', compact('sesiones'));
    }
}
