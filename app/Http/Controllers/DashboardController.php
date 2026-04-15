<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Propiedad;
use App\Models\Unidad;
use App\Models\Inquilino;
use App\Models\Pago;
use App\Models\SolicitudInquilino;
use App\Models\TareaMantenimiento;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalPropiedades' => Propiedad::count(),
            'totalUnidades' => Unidad::count(),
            'totalInquilinos' => Inquilino::count(),

            'pagosPendientes' => Pago::where('estado', 'pendiente')->count(),

            'solicitudesAbiertas' => SolicitudInquilino::where('estado', 'abierta')->count(),

            'tareasPendientes' => TareaMantenimiento::where('estado', 'pendiente')->count(),
            'tareasCompletadas' => TareaMantenimiento::where('estado', 'completada')->count(),

            'ultimasSolicitudes' => SolicitudInquilino::with('unidad')
                ->latest('id')
                ->take(5)
                ->get()
        ]);
    }
}