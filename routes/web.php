<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ContratoController;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\InquilinoController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\SesionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\AbonoPagoController;
use App\Http\Controllers\SolicitudInquilinoController;
use App\Http\Controllers\TareaMantenimientoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReciboController;
use Illuminate\Support\Facades\Mail;

Route::get('/test-correo', function () {

    Mail::raw('Correo de prueba desde Laravel', function ($message) {
        $message->to('TU_CORREO@gmail.com')
                ->subject('Prueba Laravel');
    });

    return "Correo enviado";
});
/*
|--------------------------------------------------------------------------
| REDIRECCIÓN INICIAL
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| LOGIN (PÚBLICO)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard/admin', [DashboardController::class, 'index'])
        ->middleware('rol:admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/empleado', [DashboardController::class, 'index'])
        ->middleware('rol:admin,empleado')
        ->name('dashboard.empleado');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['rol:admin'])->group(function () {

        Route::resource('usuarios', UsuarioController::class);

        Route::put('/usuarios/{id}/activar', [UsuarioController::class, 'activar'])
            ->name('usuarios.activar');

        Route::get('/auditoria', [AuditoriaController::class, 'index'])
            ->name('auditoria.index');

        Route::get('/sesiones', [SesionController::class, 'index'])
            ->name('sesiones.index');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN + EMPLEADO
    |--------------------------------------------------------------------------
    */
    Route::middleware(['rol:admin,empleado'])->group(function () {

        Route::resource('propiedad', PropiedadController::class);
        Route::resource('unidad', UnidadController::class);
        Route::resource('inquilino', InquilinoController::class);
        Route::resource('contrato', ContratoController::class);
        Route::resource('gasto', GastoController::class);
        Route::resource('pagos', PagoController::class);
        Route::resource('solicitudes', SolicitudInquilinoController::class);
        Route::resource('tareas', TareaMantenimientoController::class);

      
        Route::put('/solicitudes/{solicitude}/activar', [SolicitudInquilinoController::class, 'activar'])
            ->name('solicitudes.activar');

  
        Route::put('/pagos/{pago}/activar', [PagoController::class, 'activar'])
            ->name('pagos.activar');

        Route::put('/tareas/{tarea}/activar', [TareaMantenimientoController::class, 'activar'])
            ->name('tareas.activar');
            
    });

    /*
    |--------------------------------------------------------------------------
    | ABONOS
    |--------------------------------------------------------------------------
    */
    Route::get('/abonos/create/{pago}', [AbonoPagoController::class, 'create'])->name('abonos.create');
    Route::post('/abonos', [AbonoPagoController::class, 'store'])->name('abonos.store');
    Route::get('/abonos/{abono}', [AbonoPagoController::class, 'show'])->name('abonos.show');

    Route::get('/recibos/ver/{pago}', [ReciboController::class, 'verReciboPago'])
    ->name('recibos.ver');
    Route::get('/recibos/ver-abono/{abono}', [ReciboController::class, 'verReciboAbono'])
    ->name('recibos.ver.abono');

});