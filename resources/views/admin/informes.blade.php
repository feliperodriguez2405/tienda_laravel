@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
<div class="container py-4">
    <header class="row mb-4 align-items-center animate__animated animate__fadeIn">
        <div class="col-12">
            <h1 class="fw-bold mb-0">Reportes de Ventas e Inventario</h1>
            <p>Información detallada sobre las ventas e inventario de la tienda.</p>
        </div>
    </header>

    <section class="accordion" id="reportesAccordion" aria-label="Panel de Reportes">
        <!-- Ventas Section -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="ventas-heading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ventas-collapse" aria-expanded="false" aria-controls="ventas-collapse">
                    Reportes de Ventas
                </button>
            </h2>
            <div id="ventas-collapse" class="accordion-collapse collapse" aria-labelledby="ventas-heading" data-bs-parent="#reportesAccordion">
                <div class="accordion-body">
                    <div class="row g-4">
                        <!-- Ventas por Día -->
                        <div class="col-md-6">
                            <div class="card shadow-sm report-card animate__animated animate__fadeInUp" role="region" aria-labelledby="ventas-dia-title">
                                <div class="card-header bg-success text-white d-flex align-items-center">
                                    <i class="bi bi-cash-stack me-2" aria-hidden="true"></i>
                                    <h5 class="mb-0" id="ventas-dia-title">Ventas por Día (Últimos 7 días)</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="ventasChart" height="150" aria-label="Gráfico de ventas por día"></canvas>
                                    @if($ventas->isEmpty())
                                        <p class="text-center mt-3" role="alert">No hay ventas registradas en los últimos 7 días.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Productos Más Vendidos -->
                        <div class="col-md-6">
                            <div class="card shadow-sm report-card animate__animated animate__fadeInUp" data-delay="100" role="region" aria-labelledby="productos-vendidos-title">
                                <div class="card-header bg-primary text-white d-flex align-items-center">
                                    <i class="bi bi-star-fill me-2" aria-hidden="true"></i>
                                    <h5 class="mb-0" id="productos-vendidos-title">Productos Más Vendidos (Top 5)</h5>
                                </div>
                                <div class="card-body">
                                    @if($productosMasVendidos->isEmpty())
                                        <p role="alert">No hay ventas registradas.</p>
                                    @else
                                        <ul class="list-group list-group-flush">
                                            @foreach($productosMasVendidos as $producto)
                                                <li class="list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeIn" data-delay="{{ $loop->index * 100 }}">
                                                    <span>{{ $producto->nombre }}</span>
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventario Section -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="inventario-heading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#inventario-collapse" aria-expanded="false" aria-controls="inventario-collapse">
                    Reportes de Inventario
                </button>
            </h2>
            <div id="inventario-collapse" class="accordion-collapse collapse" aria-labelledby="inventario-heading" data-bs-parent="#reportesAccordion">
                <div class="accordion-body">
                    <div class="row g-4">
                        <!-- Inventario - Bajo Stock -->
                        <div class="col-md-6">
                            <div class="card shadow-sm report-card animate__animated animate__fadeInUp" data-delay="200" role="region" aria-labelledby="bajo-stock-title">
                                <div class="card-header bg-warning text-white d-flex align-items-center">
                                    <i class="bi bi-box-seam me-2" aria-hidden="true"></i>
                                    <h5 class="mb-0" id="bajo-stock-title">Productos con Bajo Stock</h5>
                                </div>
                                <div class="card-body">
                                    @if($bajoStock->isEmpty())
                                        <p role="alert">Todos los productos tienen stock suficiente.</p>
                                    @else
                                        <ul class="list-group list-group-flush">
                                            @foreach($bajoStock as $producto)
                                                <li class="list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeIn" data-delay="{{ $loop->index * 100 }}">
                                                    <span>{{ $producto->nombre }}</span>
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
                            <div class="card shadow-sm report-card animate__animated animate__fadeInUp" data-delay="300" role="region" aria-labelledby="valor-inventario-title">
                                <div class="card-header bg-info text-white d-flex align-items-center">
                                    <i class="bi bi-wallet2 me-2" aria-hidden="true"></i>
                                    <h5 class="mb-0" id="valor-inventario-title">Valor Total del Inventario</h5>
                                </div>
                                <div class="card-body text-center">
                                    <h3 class="text-info fw-bold animate__animated animate__pulse animate__infinite">{{ number_format($valorInventario, 0, ',', '.') }} COP</h3>
                                    <p>Valor estimado basado en stock y precios actuales.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Caja Section -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="caja-heading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#caja-collapse" aria-expanded="false" aria-controls="caja-collapse">
                    Reportes de Caja
                </button>
            </h2>
            <div id="caja-collapse" class="accordion-collapse collapse" aria-labelledby="caja-heading" data-bs-parent="#reportesAccordion">
                <div class="accordion-body">
                    <div class="row g-4">
                        <!-- Ganancia Total -->
                        <div class="col-md-6">
                            <div class="card shadow-sm report-card animate__animated animate__fadeInUp" data-delay="400" role="region" aria-labelledby="ganancia-total-title">
                                <div class="card-header bg-dark text-white d-flex align-items-center">
                                    <i class="bi bi-graph-up me-2" aria-hidden="true"></i>
                                    <h5 class="mb-0" id="ganancia-total-title">Ganancia Total</h5>
                                </div>
                                <div class="card-body text-center">
                                    <h3 class="fw-bold animate__animated animate__pulse animate__infinite">{{ number_format($gananciaTotal, 0, ',', '.') }} COP</h3>
                                    <p>Ganancia total de ventas entregadas (Precio de Venta - Precio de Compra).</p>
                                </div>
                            </div>
                        </div>

                        <!-- Cierre de Caja -->
                        <div class="col-md-6">
                            <div class="card shadow-sm report-card animate__animated animate__fadeInUp" data-delay="500" role="region" aria-labelledby="cierre-caja-title">
                                <div class="card-header bg-purple text-white d-flex align-items-center">
                                    <i class="bi bi-calculator me-2" aria-hidden="true"></i>
                                    <h5 class="mb-0" id="cierre-caja-title">Cierre de Caja (Hoy)</h5>
                                </div>
                                <div class="card-body">
                                    @if($cierreCaja['transacciones'] == 0)
                                        <p role="alert">No hay transacciones registradas para hoy.</p>
                                    @else
                                        <div class="mb-4">
                                            <h6 class="fw-bold">Resumen</h6>
                                            <p>Total Ventas: <span class="fw-bold text-success">{{ number_format($cierreCaja['total_ventas'], 0, ',', '.') }} COP</span></p>
                                            <p>Transacciones: <span class="fw-bold">{{ $cierreCaja['transacciones'] }}</span></p>
                                        </div>

                                        <h6 class="fw-bold mb-3">Métodos de Pago</h6>
                                        <canvas id="metodosPagoChart" height="100" aria-label="Gráfico de métodos de pago"></canvas>

                                        <h6 class="fw-bold mt-4 mb-3">Transacciones por Cajero</h6>
                                        <ul class="list-group list-group-flush">
                                            @foreach($cierreCaja['por_cajero'] as $cajero)
                                                <li class="list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeIn" data-delay="{{ $loop->index * 100 }}">
                                                    <span>{{ $cajero['cajero'] }}</span>
                                                    <span class="badge bg-info rounded-pill">{{ $cajero['count'] }} transacciones</span>
                                                </li>
                                            @endforeach
                                        </ul>

                                        <h6 class="fw-bold mt-4 mb-3">Productos Más Vendidos (Hoy)</h6>
                                        @if($cierreCaja['productos_top']->isEmpty())
                                            <p role="alert">No hay ventas registradas hoy.</p>
                                        @else
                                            <ul class="list-group list-group-flush">
                                                @foreach($cierreCaja['productos_top'] as $producto)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center animate__animated animate__fadeIn" data-delay="{{ $loop->index * 100 }}">
                                                        <span>{{ $producto->nombre }}</span>
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
            </div>
        </div>
    </section>
</div>

<!-- Incluir Chart.js, Animate.css y Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
    document.addEventListener('DOMContentLoaded', () => {
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
    });
</script>
@endsection