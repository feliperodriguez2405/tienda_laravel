@extends('layouts.app3')
@section('title', 'Procesar Pago Nequi')
@section('content')
<div class="container">
    <h1>Procesar Pago con Nequi</h1>
    <p>Orden ID: {{ $orden->id }}</p>
    <p>Total: ${{ $orden->total }}</p>
    <p>Por favor, realiza la transferencia a la cuenta Nequi: <strong>{{ $nequiNumber }}</strong></p>
    <p>Una vez realizado el pago, confirma con el administrador para actualizar el estado.</p>
    <a href="{{ route('cajero.transactions') }}" class="btn btn-primary">Volver a Transacciones</a>
</div>
@endsection