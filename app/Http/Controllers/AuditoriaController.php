<?php
namespace App\Http\Controllers;
use App\Models\AuditoriaLog;
use Illuminate\Http\Request;
class AuditoriaController extends Controller
{

     // 📄 Listar logs de auditoría
    public function index(Request $request)
    {
        $query = \App\Models\AuditoriaLog::with('usuario');

        // 🔍 BUSCADOR GENERAL
        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('tabla', 'like', "%$search%")
                ->orWhere('accion', 'like', "%$search%")
                ->orWhere('registro_id', 'like', "%$search%")
                ->orWhere('ip', 'like', "%$search%")

                // 🔥 buscar por nombre de usuario
                ->orWhereHas('usuario', function ($sub) use ($search) {
                    $sub->where('nombre', 'like', "%$search%");
                });

            });
        }

        $logs = $query->latest('fecha')->paginate(10)->withQueryString();

        return view('auditoria.index', compact('logs'));
    }

}