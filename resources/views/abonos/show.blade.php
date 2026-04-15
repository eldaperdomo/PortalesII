@extends('welcome')
@section('title', 'Detalle Abono')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-cash me-2"></i>Detalle del Abono</h4>

    <a href="{{ route('pagos.show', $abono->pago_id) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row g-4">

    {{-- INFO --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>Información</strong>
            </div>

            <div class="card-body">

                <table class="table table-borderless">

                    <tr>
                        <th>Monto:</th>
                        <td>L {{ number_format($abono->monto,2) }}</td>
                    </tr>

                    <tr>
                        <th>Método:</th>
                        <td>{{ ucfirst($abono->metodo) }}</td>
                    </tr>

                    <tr>
                        <th>Fecha:</th>
                        <td>{{ \Carbon\Carbon::parse($abono->fecha_abono)->format('d/m/Y h:i A') }}</td>
                    </tr>

                    <tr>
                        <th>Observación:</th>
                        <td>{{ $abono->observacion ?? 'N/A' }}</td>
                    </tr>

                </table>

            </div>
        </div>
    </div>

    {{-- COMPROBANTE --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>Comprobante</strong>
            </div>

            <div class="card-body text-center">

                @if($abono->referencia_pago)
                    <img src="{{ asset('storage/'.$abono->referencia_pago) }}"
                         class="img-fluid rounded border"
                         style="max-height:300px;">
                @else
                    <p class="text-muted">No hay comprobante</p>
                @endif

            </div>
        </div>
    </div>

</div>

@endsection