@extends('layouts.app')
@section('title', 'Detalle Contrato')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-file-text me-2"></i>Detalle del Contrato</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('contratos.edit', $contrato) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="{{ route('contratos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header"><strong>Condiciones del Contrato</strong></div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><th>Estado</th>
                        <td>
                            <span class="badge
                                @if($contrato->estado == 'activo') bg-success
                                @elseif($contrato->estado == 'terminado') bg-secondary
                                @else bg-danger @endif">
                                {{ ucfirst($contrato->estado) }}
                            </span>
                        </td>
                    </tr>
                    <tr><th>Monto de Renta</th><td><strong>L {{ number_format($contrato->monto_renta, 2) }}</strong></td></tr>
                    <tr><th>Día de Pago</th><td>Día {{ $contrato->dia_pago }} de cada mes</td></tr>
                    <tr><th>Fecha Inicio</th><td>{{ $contrato->fecha_inicio->format('d/m/Y') }}</td></tr>
                    <tr><th>Fecha Fin</th><td>{{ $contrato->fecha_fin->format('d/m/Y') }}</td></tr>
                    <tr><th>Duración</th><td>{{ $contrato->duracion_meses }} meses</td></tr>
                    <tr><th>Activo</th><td>{{ $contrato->activo ? 'Sí' : 'No' }}</td></tr>
                    <tr><th>Registrado</th><td>{{ $contrato->creado_en?->format('d/m/Y') ?? '—' }}</td></tr>
                </table>

                @if($contrato->estado == 'activo')
                    @if($contrato->dias_para_vencer > 0 && $contrato->dias_para_vencer <= 30)
                        <div class="alert alert-warning py-2">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Vence en <strong>{{ $contrato->dias_para_vencer }}</strong> días
                        </div>
                    @elseif($contrato->dias_para_vencer <= 0)
                        <div class="alert alert-danger py-2">
                            <i class="bi bi-exclamation-triangle me-1"></i>Contrato vencido
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header"><strong><i class="bi bi-person me-2"></i>Inquilino</strong></div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $contrato->inquilino->nombre }}</strong></p>
                <p class="mb-1 text-muted">{{ $contrato->inquilino->telefono ?? '—' }}</p>
                <p class="mb-2 text-muted">{{ $contrato->inquilino->correo ?? '—' }}</p>
                <a href="{{ route('inquilinos.show', $contrato->inquilino) }}" class="btn btn-sm btn-outline-info">
                    Ver perfil
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><strong><i class="bi bi-door-open me-2"></i>Unidad</strong></div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $contrato->unidad->identificador }}</strong></p>
                <p class="mb-1 text-muted">Propiedad: {{ $contrato->unidad->propiedad->nombre }}</p>
                <p class="mb-2 text-muted">{{ $contrato->unidad->propiedad->direccion ?? '' }}</p>
                <a href="{{ route('unidades.show', $contrato->unidad) }}" class="btn btn-sm btn-outline-info">
                    Ver unidad
                </a>
            </div>
        </div>
    </div>
</div>
@endsection