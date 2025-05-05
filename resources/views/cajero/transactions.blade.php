@extends('layouts.app3')
@section('title', 'Transacciones')
@section('content')
<div class="container">
    <h1>Transacciones</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Detalles</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ordenes as $orden)
                <tr>
                    <td>{{ $orden->id }}</td>
                    <td>${{ $orden->total }}</td>
                    <td>{{ $orden->created_at }}</td>
                    <td>
                        @foreach ($orden->detalles as $detalle)
                            {{ $detalle->producto->nombre }} (x{{ $detalle->cantidad }})<br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection