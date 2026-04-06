<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\InquilinoController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\GastoController;

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
    Route::resource('propiedades', PropiedadController::class);

    // ── Unidades ─────────────────────────────────────────────────────────────
    Route::resource('unidades', UnidadController::class);

    // ── Inquilinos ───────────────────────────────────────────────────────────
    Route::resource('inquilinos', InquilinoController::class);

    // ── Contratos ────────────────────────────────────────────────────────────
    Route::resource('contratos', ContratoController::class);

    // ── Gastos ───────────────────────────────────────────────────────────────
    Route::resource('gastos', GastoController::class);

});
