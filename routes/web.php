<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropiedadesController;
use App\Http\Controllers\UnidadesController;
use App\Http\Controllers\InquilinosController;
use App\Http\Controllers\ContratosController;
use App\Http\Controllers\GastosController;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Rutas del Integrante 2 — Núcleo del alquiler
| Agregar dentro del grupo auth de tu web.php principal
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // ── Propiedades ──────────────────────────────────────────────────────────
    Route::resource('propiedades', PropiedadesController::class);

    // ── Unidades ─────────────────────────────────────────────────────────────
    Route::resource('unidades', UnidadesController::class);

    // ── Inquilinos ───────────────────────────────────────────────────────────
    Route::resource('inquilinos', InquilinosController::class);

    // ── Contratos ────────────────────────────────────────────────────────────
    Route::resource('contratos', ContratosController::class);

    // ── Gastos ───────────────────────────────────────────────────────────────
    Route::resource('gastos', GastosController::class);

});
