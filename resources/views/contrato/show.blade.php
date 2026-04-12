@extends('welcome')
@section('title', 'Contrato '.$contrato->codigo)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-file-text me-2"></i>Contrato {{ $contrato->codigo }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('contrato.edit', $contrato) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="{{ route('contrato.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Resumen del contrato --}}
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header"><strong>Detalles del Contrato</strong></div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><th>Código</th><td>{{ $contrato->codigo }}</td></tr>
                    <tr><th>Estado</th>
                        <td><span class="badge badge-{{ $contrato->estado }}">{{ ucfirst($contrato->estado) }}</span></td>
                    </tr>
                    <tr><th>Monto Mensual</th><td><strong>L {{ number_format($contrato->monto_mensual, 2) }}</strong></td></tr>
                    <tr><th>Depósito</th><td>L {{ number_format($contrato->deposito, 2) }}</td></tr>
                    <tr><th>Día de Pago</th><td>Día {{ $contrato->dia_pago }} de cada mes</td></tr>
                    <tr><th>Periodicidad</th><td>{{ ucfirst($contrato->periodicidad) }}</td></tr>
                    <tr><th>Incremento Anual</th><td>{{ $contrato->incremento_anual }}%</td></tr>
                    <tr><th>Inicio</th><td>{{ $contrato->fecha_inicio->format('d/m/Y') }}</td></tr>
                    <tr><th>Fin</th><td>{{ $contrato->fecha_fin->format('d/m/Y') }}</td></tr>
                    <tr><th>Duración</th><td>{{ $contrato->duracion_meses }} meses</td></tr>
                    <tr><th>Monto Total</th><td>L {{ number_format($contrato->monto_total, 2) }}</td></tr>
                    <tr><th>Renovación Auto.</th>
                        <td>{{ $contrato->renovacion_automatica ? 'Sí' : 'No' }}</td>
                    </tr>
                </table>
                @if($contrato->estado === 'activo')
                    @if($contrato->dias_para_vencer > 0)
                        <div class="alert alert-{{ $contrato->dias_para_vencer <= 30 ? 'warning' : 'info' }} py-2">
                            <i class="bi bi-clock me-1"></i>
                            Vence en <strong>{{ $contrato->dias_para_vencer }}</strong> días
                        </div>
                    @else
                        <div class="alert alert-danger py-2">
                            <i class="bi bi-exclamation-triangle me-1"></i>Contrato vencido
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-7">
        {{-- Inquilino --}}
        <div class="card mb-4">
            <div class="card-header"><strong><i class="bi bi-person me-2"></i>Inquilino</strong></div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $contrato->inquilino->nombre_completo }}</strong></p>
                <p class="mb-1 text-muted">DNI: {{ $contrato->inquilino->dni }}</p>
                <p class="mb-1 text-muted">Tel: {{ $contrato->inquilino->telefono ?? '—' }}</p>
                <p class="mb-0 text-muted">{{ $contrato->inquilino->email ?? '' }}</p>
                <a href="{{ route('inquilino.show', $contrato->inquilino) }}" class="btn btn-sm btn-outline-info mt-2">
                    Ver perfil completo
                </a>
            </div>
        </div>

        {{-- Unidad --}}
        <div class="card mb-4">
            <div class="card-header"><strong><i class="bi bi-door-open me-2"></i>Unidad Arrendada</strong></div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $contrato->unidad->nombre }}</strong></p>
                <p class="mb-1 text-muted">Propiedad: {{ $contrato->unidad->propiedad->nombre }}</p>
                <p class="mb-0 text-muted">{{ $contrato->unidad->propiedad->direccion_completa }}</p>
                <a href="{{ route('unidad.show', $contrato->unidad) }}" class="btn btn-sm btn-outline-info mt-2">
                    Ver unidad
                </a>
            </div>
        </div>

        {{-- Cláusulas y Observaciones --}}
        @if($contrato->clausulas_adicionales || $contrato->observaciones)
        <div class="card">
            <div class="card-header"><strong>Notas y Cláusulas</strong></div>
            <div class="card-body">
                @if($contrato->clausulas_adicionales)
                    <h6 class="text-muted">Cláusulas adicionales</h6>
                    <p>{{ $contrato->clausulas_adicionales }}</p>
                @endif
                @if($contrato->observaciones)
                    <h6 class="text-muted">Observaciones</h6>
                    <p class="mb-0">{{ $contrato->observaciones }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection