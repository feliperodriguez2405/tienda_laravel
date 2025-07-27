<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\OrdenCompra;
use App\Models\Proveedor;
use Carbon\Carbon;

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
        $contratoAplica = ($this->proveedor->fecha_vencimiento_contrato && Carbon::parse($this->proveedor->fecha_vencimiento_contrato)->greaterThan(now()))
            ? 'Sí aplica'
            : 'No aplica (vencido o no definido)';

        $mail = (new MailMessage)
            ->from(config('mail.from.address'), "Tienda D'Jenny")
            ->subject('Nueva Orden de Compra #' . $this->ordenCompra->id . ' - Pendiente de Confirmación')
            ->greeting('Estimado/a ' . ($this->proveedor->nombre ?? 'Proveedor') . ',')
            ->line(new \Illuminate\Support\HtmlString('<img src="https://img.freepik.com/vector-gratis/carro-tienda-edificio-tienda-dibujos-animados_138676-2085.jpg?t=st=1743616027~exp=1743619627~hmac=ffae08aea9e36ba0c9518e47c19ab0a81f8b7c777a5df13b0745b6b85ee6d6a2&w=740" alt="Logo Tienda D\'Jenny" style="max-width: 200px; margin-bottom: 20px;">'))
            ->line('Hemos registrado una nueva orden de compra a su nombre. Por favor, al momento de entregar los productos, indique los precios correspondientes y el monto total."Facturación"')
            ->line('**Detalles de la Orden #' . $this->ordenCompra->id . ':**')
            ->line('Fecha: ' . ($this->ordenCompra->fecha ? $this->ordenCompra->fecha->format('d/m/Y H:i') : 'No especificada'))
            ->line('Estado: ' . ucfirst($this->ordenCompra->estado ?? 'procesando'));

        // Manejo de los detalles de productos como array
        if (is_array($this->ordenCompra->detalles) && !empty($this->ordenCompra->detalles)) {
            $mail->line('**Productos Solicitados:**');
            foreach ($this->ordenCompra->detalles as $detalle) {
                $productoLine = "- " . ($detalle['producto'] ?? 'Producto no especificado')
                    . ": " . ($detalle['cantidad'] ?? 0) . " unidades";

                // Agregar más detalles si están disponibles
                if (!empty($detalle['descripcion'])) {
                    $productoLine .= " - " . $detalle['descripcion'];
                }
                if (isset($detalle['precio']) && is_numeric($detalle['precio'])) {
                    $productoLine .= " (Precio unitario: $" . number_format($detalle['precio'], 2) . ")";
                }
                // Calcular subtotal si precio y cantidad están disponibles
                if (isset($detalle['precio']) && is_numeric($detalle['precio']) && isset($detalle['cantidad']) && is_numeric($detalle['cantidad'])) {
                    $subtotal = $detalle['precio'] * $detalle['cantidad'];
                    $productoLine .= " - Subtotal: $" . number_format($subtotal, 2);
                }

                $mail->line($productoLine);
            }

            // Agregar total si existe
            if (isset($this->ordenCompra->monto) && $this->ordenCompra->monto > 0) {
                $mail->line('**Total estimado: $' . number_format($this->ordenCompra->monto, 2) . '**');
            }
        } else {
            $mail->line('No se especificaron detalles de productos en esta orden.');
        }

        $mail->line('**Contrato:** ' . $contratoAplica)
            ->line('Por favor, coordine con nosotros para confirmar los precios y el monto total al entregar la orden.')
            ->action('Contactar al Administrador', 'mailto:' . config('mail.from.address'))
            ->salutation("Atentamente,\nEquipo AlphaSoft");

        return $mail;
    }
}