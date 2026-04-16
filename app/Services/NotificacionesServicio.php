<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\Inquilino;
use Illuminate\Support\Facades\Mail;

class NotificacionesServicio
{
    /**
     * 🔥 NOTIFICAR ABONO (CON PDF)
     */
   public function notificarAbono($abono, $recibo = null)
{
    $inquilino = $abono->pago->contrato->inquilino;

    if (!$inquilino) return;

    // 🔥 DEFINIR MENSAJE PRIMERO
    $mensaje = "Se registró tu abono de L. " . number_format($abono->monto, 2);

    // 🔥 CREAR SOLO UNA VEZ
    $notificacion = Notificacion::create([
        'inquilino_id' => $inquilino->id,
        'tipo' => 'abono',
        'titulo' => 'Abono registrado',
        'mensaje' => $mensaje,
        'estado' => 'pendiente',
        'destino_correo' => $inquilino->email,
    ]);

    // 🔥 SI NO HAY EMAIL, SOLO GUARDA Y YA
    if (!$inquilino->email) return;

    try {
       Mail::raw($mensaje, function ($mail) use ($inquilino, $recibo) {
            $mail->to($inquilino->email)
                ->subject('Recibo de abono');

            if ($recibo && $recibo->pdf_url) {
                $mail->attach(public_path($recibo->pdf_url));
            }
        });

        $notificacion->update([
            'estado' => 'enviada',
            'fecha_enviada' => now()
        ]);

    } catch (\Exception $e) {
        $notificacion->update([
            'estado' => 'fallida'
        ]);
    }
}
    /**
     * 🔥 NOTIFICAR PAGO COMPLETO
     */
    public function notificarPagoCompleto($pago, $recibo = null)
    {
        $inquilino = $pago->contrato->inquilino;

        if (!$inquilino || !$inquilino->email) return;

        $mensaje = "Tu pago del periodo {$pago->periodo} ha sido completado.";

        $notificacion = Notificacion::create([
            'inquilino_id' => $inquilino->id,
            'tipo' => 'pago_completo',
            'titulo' => 'Pago completado',
            'mensaje' => $mensaje,
            'estado' => 'pendiente',
            'destino_correo' => $inquilino->email,
        ]);

        try {
           Mail::raw($mensaje, function ($mail) use ($inquilino, $recibo) {
                $mail->to($inquilino->email)
                    ->subject('Pago completado');

                if ($recibo && $recibo->pdf_url) {
                    $mail->attach(public_path($recibo->pdf_url));
                }
            });

            $notificacion->update([
                'estado' => 'enviada',
                'fecha_enviada' => now()
            ]);

        } catch (\Exception $e) {
            $notificacion->update([
                'estado' => 'fallida'
            ]);
        }
    }

    /**
     * 🔥 NOTIFICAR SOLICITUD
     */
    public function notificarSolicitud($solicitud)
    {
        $inquilino = $solicitud->inquilino;

        // 🔥 INQUILINO
        if ($inquilino && $inquilino->email) {

            Notificacion::create([
                'inquilino_id' => $inquilino->id,
                'tipo' => 'solicitud',
                'titulo' => 'Solicitud enviada',
                'mensaje' => "Tu solicitud fue registrada correctamente",
                'estado' => 'pendiente',
                'destino_correo' => $inquilino->email,
            ]);

            Mail::raw('Tu solicitud fue registrada correctamente', function ($mail) use ($inquilino) {
                $mail->to($inquilino->email)
                    ->subject('Solicitud registrada');
            });
        }

        // 🔥 ADMIN
        $admins = \App\Models\Usuario::where('rol', 'admin')->where('activo', true)->get();

        foreach ($admins as $admin) {

            Notificacion::create([
                'usuario_id' => $admin->id,
                'tipo' => 'solicitud',
                'titulo' => 'Nueva solicitud',
                'mensaje' => "Se registró una nueva solicitud",
                'estado' => 'pendiente',
                'destino_correo' => $admin->email,
            ]);

            if ($admin->email) {
                Mail::raw('Nueva solicitud registrada', function ($mail) use ($admin) {
                    $mail->to($admin->email)
                        ->subject('Nueva solicitud');
                });
            }
        }
    }
}