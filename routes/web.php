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

    // ── Propiedades ──────────────────────────────────────────────────────────
    Route::resource('propiedad', PropiedadController::class);

    // ── Unidades ─────────────────────────────────────────────────────────────
    Route::resource('unidad', UnidadController::class);

    // ── Inquilinos ───────────────────────────────────────────────────────────
    Route::resource('inquilino', InquilinoController::class);

    // ── Contratos ────────────────────────────────────────────────────────────
    Route::resource('contrato', ContratoController::class);

    // ── Gastos ───────────────────────────────────────────────────────────────
    Route::resource('gasto', GastoController::class);
