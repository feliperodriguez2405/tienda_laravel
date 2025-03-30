@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-12">
            <h2 class="text-primary fw-bold">Reportes de Ventas e Inventario</h2>
            <p class="text-muted">Información detallada sobre las ventas e inventario de la tienda.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Ventas por Día -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <i class="bi bi-cash-stack me-2"></i>
                    <h5 class="mb-0">Ventas por Día (Últimos 7 días)</h5>
                </div>
                <div class="card-body">
                    <canvas id="ventasChart" height="150"></canvas>
                    @if($ventas->isEmpty())
                        <p class="text-muted text-center mt-3">No hay ventas registradas en los últimos 7 días.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-star-fill me-2"></i>
                    <h5 class="mb-0">Productos Más Vendidos (Top 5)</h5>
                </div>
                <div class="card-body">
                    @if($productosMasVendidos->isEmpty())
                        <p class="text-muted">No hay ventas registradas.</p>
                    @else
                        <ul class="list-group">
                            @foreach($productosMasVendidos as $producto)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $producto->nombre }}
                                    <div>
                                        <span class="badge bg-success me-2">{{ $producto->cantidad_vendida }} vendidos</span>
                                        <span class="badge bg-primary">${{ number_format($producto->total, 2) }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Inventario - Bajo Stock -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white d-flex align-items-center">
                    <i class="bi bi-box-seam me-2"></i>
                    <h5 class="mb-0">Productos con Bajo Stock</h5>
                </div>
                <div class="card-body">
                    @if($bajoStock->isEmpty())
                        <p class="text-muted">Todos los productos tienen stock suficiente.</p>
                    @else
                        <ul class="list-group">
                            @foreach($bajoStock as $producto)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $producto->nombre }}
                                    <span class="badge bg-danger rounded-pill">{{ $producto->stock }} unidades</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <!-- Valor Total del Inventario -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex align-items-center">
                    <i class="bi bi-wallet2 me-2"></i>
                    <h5 class="mb-0">Valor Total del Inventario</h5>
                </div>
                <div class="card-body text-center">
                    <h3 class="text-info fw-bold">${{ number_format($valorInventario, 2) }}</h3>
                    <p class="text-muted">Valor estimado basado en stock y precios actuales.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ventasCtx = document.getElementById('ventasChart').getContext('2d');
    new Chart(ventasCtx, {
        type: 'line',
        data: {
            labels: [@foreach($ventas as $venta)"{{ $venta->fecha }}",@endforeach],
            datasets: [{
                label: 'Total Vendido ($)',
                data: [@foreach($ventas as $venta){{ $venta->total_vendido }},@endforeach],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Monto ($)' } },
                x: { title: { display: true, text: 'Fecha' } }
            }
        }
    });
</script>

<style>
    .card { border-radius: 12px; transition: transform 0.3s ease; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); }
    .list-group-item { border: none; padding: 0.75rem 1rem; }
    .badge { font-size: 0.9rem; padding: 0.4rem 0.75rem; }
    h5 { font-size: 1.25rem; }
    .text-muted { font-size: 0.9rem; }
</style>
@endsection