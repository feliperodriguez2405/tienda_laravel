<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - Pedido #{{ $orden->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20mm; }
        .invoice { width: 100%; max-width: 210mm; margin: 0 auto; border: 1px solid #ccc; padding: 20mm; }
        .header { text-align: center; margin-bottom: 20mm; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; }
        .details { margin-bottom: 10mm; }
        .details p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10mm; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; text-align: right; }
        .footer { text-align: center; font-size: 12px; color: #555; }
        @media print {
            body { padding: 0; }
            .invoice { border: none; box-shadow: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice">
        <div class="header">
            <h1>Factura de Venta</h1>
            <p>Pedido #{{ $orden->id }}</p>
            <p>Fecha: {{ $orden->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="details">
            <p><strong>Cliente:</strong> {{ $orden->user->name ?? 'Usuario no disponible' }}</p>
            <p><strong>Dirección:</strong> {{ $orden->direccion ?? 'No especificada' }}</p>
            <p><strong>Estado:</strong> {{ ucfirst($orden->estado) }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario (COP)</th>
                    <th>Total (COP)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orden->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre ?? 'Producto no disponible' }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>${{ number_format($detalle->producto->precio ?? 0, 2) }}</td>
                        <td>${{ number_format(($detalle->producto->precio ?? 0) * $detalle->cantidad, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="total">Total</td>
                    <td class="total">${{ number_format($orden->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="footer">
            <p>Gracias por su compra. Para más información, contacte al soporte.</p>
            <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p> <!-- 29/06/2025 22:20 -->
        </div>
    </div>
</body>
</html>