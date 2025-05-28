<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Proveedor;
use App\Notifications\ContratoVencimientoNotification;
use Illuminate\Support\Facades\Notification;

class CheckContratoVencimientos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $proveedores = Proveedor::whereNotNull('fecha_vencimiento_contrato')
            ->where('fecha_vencimiento_contrato', '<=', now()->addDays(7))
            ->where('recibir_notificaciones', true)
            ->get();
    
        $correo_notificaciones = config('mail.notificaciones_correo', 'alphasoft.cmjff@gmail.com');
    
        foreach ($proveedores as $proveedor) {
            try {
                Notification::route('mail', $correo_notificaciones)
                    ->notify(new ContratoVencimientoNotification($proveedor));
            } catch (\Exception $e) {
                \Log::error('Error enviando notificación: ' . $e->getMessage());
                }
            
            }
        }
    }