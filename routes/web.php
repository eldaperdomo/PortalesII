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
    Route::resource('propiedad', PropiedadesController::class);

    // ── Propiedades ──────────────────────────────────────────────────────────
    Route::resource('propiedad', PropiedadesController::class);

    // ── Unidades ─────────────────────────────────────────────────────────────
    Route::resource('unidad', UnidadesController::class);

    // ── Inquilinos ───────────────────────────────────────────────────────────
    Route::resource('inquilino', InquilinosController::class);

    // ── Contratos ────────────────────────────────────────────────────────────
    Route::resource('contrato', ContratosController::class);

    // ── Gastos ───────────────────────────────────────────────────────────────
    Route::resource('gasto', GastosController::class);

});
