@extends('layouts.app3')
@section('title', 'Confirmar Pago Nequi')
@section('content')
<div class="container">
    <h1>Confirmar Pago Nequi</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Orden #{{ $orden->id }}</h5>
            <p><strong>Total:</strong> ${{ number_format($orden->total, 2) }}</p>
            <p><strong>Número Nequi:</strong> {{ $nequiNumber }}</p>
            <p>Por favor, realiza el pago al número Nequi indicado y proporciona el ID de la transacción.</p>
            <form action="{{ route('cajero.payment.confirm', $orden->id) }}" method="POST" id="payment-form">
                @csrf
                <div class="mb-3">
                    <label for="transaction_id" class="form-label">ID de Transacción Nequi</label>
                    <input type="text" name="transaction_id" id="transaction_id" class="form-control" required maxlength="255">
                    @error('transaction_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Confirmar Pago</button>
                <a href="{{ route('cajero.transactions') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('payment-form').addEventListener('submit', (e) => {
            console.log('Payment confirmation form submitted');
            Swal.fire({
                title: '¿Confirmar pago Nequi?',
                text: 'Asegúrate de que el ID de transacción es correcto.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, confirmar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) {
                    e.preventDefault();
                    console.log('Payment confirmation cancelled');
                }
            });
        });
    });
</script>
@endpush
@endsection