@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="container py-4">
    <div class="row mb-4 align-items-center animate__animated animate__fadeIn">
        <div class="col-md-12">
            <h1 class="fw-bold mb-0">Reportes de Ventas e Inventario</h1>
            <p>Información detallada sobre las ventas e inventario de la tienda.</p>
        </div>
    </div>

    <div class="row g-4" id="reportes-container">
        <!-- Ventas por Día -->
        <div class="col-md-6">
            <div class="card shadow-sm report-card animate__animated animate__fadeInUp">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <i class="bi bi-cash-stack me-2"></i>
                    <h5 class="mb-0">Ventas por Día (Últimos 7 días)</h5>
                </div>
                <div class="card-body">
                    <canvas id="ventasChart" height="150"></canvas>
                    @if($ventas->isEmpty())
                        <p class=" text-center mt-3">No hay ventas registradas en los últimos 7 días.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="col-md-6">
            <div class="card shadow-sm report-card animate__animated animate__fadeInUp" data-delay="100">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-star-fill me-2"></i>
                    <h5 class="mb-0">Productos Más Vendidos (Top 5)</h5>
                </div>
                <div class="card-body">
                    @if($productosMasVendidos->isEmpty())
                        <p>No hay ventas registradas.</p>
                    @else
                        <ul class="list-group">
                            @foreach($productosMasVendidos as $producto)
                                <li class="list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeIn" data-delay="{{ $loop->index * 100 }}">
                                    {{ $producto->nombre }}
                                    <div>
                                        <span class="badge bg-success me-2">{{ $producto->cantidad_vendida }} vendidos</span>
                                        <span class="badge bg-primary">{{ number_format($producto->total, 0, ',', '.') }} COP</span>
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
            <div class="card shadow-sm report-card animate__animated animate__fadeInUp" data-delay="200">
                <div class="card-header bg-warning text-white d-flex align-items-center">
                    <i class="bi bi-box-seam me-2"></i>
                    <h5 class="mb-0">Productos con Bajo Stock</h5>
                </div>
                <div class="card-body">
                    @if($bajoStock->isEmpty())
                        <p>Todos los productos tienen stock suficiente.</p>
                    @else
                        <ul class="list-group">
                            @foreach($bajoStock as $producto)
                                <li class="list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeIn" data-delay="{{ $loop->index * 100 }}">
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
            <div class="card shadow-sm report-card animate__animated animate__fadeInUp" data-delay="300">
                <div class="card-header bg-info text-white d-flex align-items-center">
                    <i class="bi bi-wallet2 me-2"></i>
                    <h5 class="mb-0">Valor Total del Inventario</h5>
                </div>
                <div class="card-body text-center">
                    <h3 class="text-info fw-bold animate__animated animate__pulse animate__infinite">{{ number_format($valorInventario, 0, ',', '.') }} COP</h3>
                    <p>Valor estimado basado en stock y precios actuales.</p>
                </div>
            </div>
        </div>

        <!-- Ganancia Total -->
        <div class="col-md-6">
            <div class="card shadow-sm report-card animate__animated animate__fadeInUp" data-delay="400">
                <div class="card-header bg-dark text-white d-flex align-items-center">
                    <i class="bi bi-graph-up me-2"></i>
                    <h5 class="mb-0">Ganancia Total</h5>
                </div>
                <div class="card-body text-center">
                    <h3 class="fw-bold animate__animated animate__pulse animate__infinite">{{ number_format($gananciaTotal, 0, ',', '.') }} COP</h3>
                    <p>Ganancia total de ventas entregadas (Precio de Venta - Precio de Compra).</p>
                </div>
            </div>
        </div>

        <!-- Cierre de Caja -->
        <div class="col-md-6">
            <div class="card shadow-sm report-card animate__animated animate__fadeInUp" data-delay="500">
                <div class="card-header bg-purple text-white d-flex align-items-center">
                    <i class="bi bi-calculator me-2"></i>
                    <h5 class="mb-0">Cierre de Caja (Hoy)</h5>
                </div>
                <div class="card-body">
                    @if($cierreCaja['transacciones'] == 0)
                        <p>No hay transacciones registradas para hoy.</p>
                    @else
                        <div class="mb-4">
                            <h6 class="fw-bold">Resumen</h6>
                            <p>Total Ventas: <span class="fw-bold text-success">{{ number_format($cierreCaja['total_ventas'], 0, ',', '.') }} COP</span></p>
                            <p>Transacciones: <span class="fw-bold">{{ $cierreCaja['transacciones'] }}</span></p>
                        </div>

                        <!-- Gráfico de Métodos de Pago -->
                        <h6 class="fw-bold mb-3">Métodos de Pago</h6>
                        <canvas id="metodosPagoChart" height="100"></canvas>

                        <!-- Transacciones por Cajero -->
                        <h6 class="fw-bold mt-4 mb-3">Transacciones por Cajero</h6>
                        <ul class="list-group">
                            @foreach($cierreCaja['por_cajero'] as $cajero)
                                <li class="list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeIn" data-delay="{{ $loop->index * 100 }}">
                                    {{ $cajero['cajero'] }}
                                    <span class="badge bg-info rounded-pill">{{ $cajero['count'] }} transacciones</span>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Productos Más Vendidos Hoy -->
                        <h6 class="fw-bold mt-4 mb-3">Productos Más Vendidos (Hoy)</h6>
                        @if($cierreCaja['productos_top']->isEmpty())
                            <p>No hay ventas registradas hoy.</p>
                        @else
                            <ul class="list-group">
                                @foreach($cierreCaja['productos_top'] as $producto)
                                    <li class="list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeIn" data-delay="{{ $loop->index * 100 }}">
                                        {{ $producto->nombre }}
                                        <div>
                                            <span class="badge bg-success me-2">{{ $producto->cantidad_vendida }} vendidos</span>
                                            <span class="badge bg-primary">{{ number_format($producto->total, 0, ',', '.') }} COP</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir Chart.js y Animate.css -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
    // Gráfico de Ventas
    const ventasCtx = document.getElementById('ventasChart').getContext('2d');
    const ventasChart = new Chart(ventasCtx, {
        type: 'line',
        data: {
            labels: [@foreach($ventas as $venta)"{{ $venta->fecha }}",@endforeach],
            datasets: [{
                label: 'Total Vendido (COP)',
                data: [@foreach($ventas as $venta){{ $venta->total_vendido }},@endforeach],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { 
                    beginAtZero: true, 
                    title: { display: true, text: 'Monto (COP)' },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 });
                        }
                    }
                },
                x: { 
                    title: { display: true, text: 'Fecha' },
                    grid: { display: false }
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toLocaleString('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 });
                        }
                    }
                }
            }
        }
    });

    // Gráfico de Métodos de Pago
    const metodosPagoCtx = document.getElementById('metodosPagoChart').getContext('2d');
    const metodosPagoChart = new Chart(metodosPagoCtx, {
        type: 'pie',
        data: {
            labels: [@foreach($cierreCaja['metodos_pago'] as $metodo)"{{ $metodo['nombre'] }}",@endforeach],
            datasets: [{
                data: [@foreach($cierreCaja['metodos_pago'] as $metodo){{ $metodo['count'] }},@endforeach],
                backgroundColor: ['#6f42c1', '#28a745', '#dc3545', '#ffc107'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 14 },
                        padding: 20
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} transacciones (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Animaciones al hacer scroll
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const delay = entry.target.getAttribute('data-delay') || 0;
                    setTimeout(() => {
                        entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                    }, delay);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.report-card').forEach(card => {
            observer.observe(card);
        });
    });

    // Efecto hover en las tarjetas
    document.querySelectorAll('.report-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px)';
            card.style.boxShadow = '0 12px 24px rgba(0, 0, 0, 0.15)';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
        });
    });
</script>
@endsection