<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContratoVencimientoNotification extends Notification
{
    use Queueable;

    protected $proveedor;

    public function __construct($proveedor)
    {
        $this->proveedor = $proveedor;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Alerta: Vencimiento de Contrato')
                    ->line('El contrato del proveedor ' . $this->proveedor->nombre . ' está próximo a vencer.')
                    ->line('Fecha de vencimiento: ' . $this->proveedor->fecha_vencimiento_contrato->format('d/m/Y'))
                    ->action('Ver Proveedor', route('admin.proveedores.edit', $this->proveedor))
                    ->line('Por favor, revisa los detalles y toma las medidas necesarias.');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}