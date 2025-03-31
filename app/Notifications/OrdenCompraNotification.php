<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\OrdenCompra;
use App\Models\Proveedor;

class OrdenCompraNotification extends Notification
{
    use Queueable;

    protected $ordenCompra;
    protected $proveedor;

    public function __construct(OrdenCompra $ordenCompra, Proveedor $proveedor)
    {
        $this->ordenCompra = $ordenCompra;
        $this->proveedor = $proveedor;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $contratoAplica = $this->proveedor->fecha_vencimiento_contrato && $this->proveedor->fecha_vencimiento_contrato->greaterThan(now())
            ? 'Sí aplica'
            : 'No aplica (vencido o no definido)';

        $mail = (new MailMessage)
            ->subject('Nueva Orden de Compra Registrada')
            ->greeting('Hola ' . $this->proveedor->nombre . ',')
            ->line('Hemos registrado una nueva orden de compra para ti.')
            ->line('**Detalles de la Orden:**')
            ->line('Monto: $' . number_format($this->ordenCompra->monto, 2))
            ->line('Estado: ' . ucfirst($this->ordenCompra->estado))
            ->line('Fecha: ' . $this->ordenCompra->fecha->format('d/m/Y H:i'));

        if ($this->ordenCompra->detalles) {
            $mail->line('**Pedidos Nuevos Solicitados:**');
            foreach ($this->ordenCompra->detalles as $detalle) {
                $line = "- {$detalle['producto']}: {$detalle['cantidad']} unidades";
                if (!empty($detalle['descripcion'])) {
                    $line .= " - {$detalle['descripcion']}";
                }
                $mail->line($line);
            }
        } else {
            $mail->line('No se especificaron detalles de pedidos.');
        }

        $mail->line('**Contrato:** ' . $contratoAplica)
            ->action('Contactar al administrador', 'mailto:' . config('mail.from.address'))
            ->salutation('Saludos, Equipo ' . config('app.name'));

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [];
    }
}