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
        $fechaVencimiento = $this->proveedor->fecha_vencimiento_contrato 
            ? $this->proveedor->fecha_vencimiento_contrato->format('d/m/Y') 
            : 'No definida';

        return (new MailMessage)
            ->from(config('mail.from.address'), "Tienda D'Jenny")
            ->subject('Alerta: Vencimiento de Contrato')
            ->greeting('Estimado/a administrador,')
            ->line('El contrato del proveedor ' . $this->proveedor->nombre . ' está próximo a vencer.')
            ->line('Fecha de vencimiento: ' . $fechaVencimiento)
            ->action('Ver Proveedor', route('admin.proveedores.edit', $this->proveedor))
            ->line('Por favor, revisa los detalles y toma las medidas necesarias.')
            ->salutation("Atentamente,\nEquipo AlphaSoft");
    }

    public function toArray($notifiable)
    {
        return [];
    }
}