@extends('layouts.app')
@section('title', $unidad->identificador)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-door-open me-2"></i>{{ $unidad->identificador }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('unidades.edit', $unidad) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="{{ route('unidades.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-3">Detalle</h6>
                <table class="table table-sm table-borderless">
                    <tr><th>Propiedad</th>
                        <td><a href="{{ route('propiedades.show', $unidad->propiedad) }}">{{ $unidad->propiedad->nombre }}</a></td>
                    </tr>
                    <tr><th>Estado</th>
                        <td>
                            <span class="badge
                                @if($unidad->estado == 'disponible') bg-success
                                @elseif($unidad->estado == 'ocupada') bg-danger
                                @else bg-warning text-dark @endif">
                                {{ ucfirst($unidad->estado) }}
                            </span>
                        </td>
                    </tr>
                    <tr><th>Renta</th><td><strong>L {{ number_format($unidad->monto_renta, 2) }}</strong></td></tr>
                    <tr><th>Activo</th>
                        <td>{{ $unidad->activo ? 'Sí' : 'No' }}</td>
                    </tr>
                    <tr><th>Registrado</th><td>{{ $unidad->creado_en?->format('d/m/Y') ?? '—' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Contratos --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-file-text me-2"></i>Contratos</strong>
                @if($unidad->estado == 'disponible')
                    <a href="{{ route('contratos.create') }}?unidad_id={{ $unidad->id }}"
                       class="btn btn-sm btn-primary">
                        <i class="bi bi-plus me-1"></i>Nuevo
                    </a>
                @endif
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Inquilino</th><th>Inicio</th><th>Fin</th><th>Renta</th><th>Estado</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($unidad->contratos as $contrato)
                            <tr>
                                <td>{{ $contrato->inquilino->nombre }}</td>
                                <td>{{ $contrato->fecha_inicio->format('d/m/Y') }}</td>
                                <td>{{ $contrato->fecha_fin->format('d/m/Y') }}</td>
                                <td>L {{ number_format($contrato->monto_renta, 2) }}</td>
                                <td>
                                    <span class="badge
                                        @if($contrato->estado == 'activo') bg-success
                                        @elseif($contrato->estado == 'terminado') bg-secondary
                                        @else bg-danger @endif">
                                        {{ ucfirst($contrato->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('contratos.show', $contrato) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">Sin contratos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Gastos --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-cash-stack me-2"></i>Gastos</strong>
                <a href="{{ route('gastos.create') }}?unidad_id={{ $unidad->id }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus me-1"></i>Registrar
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Descripción</th><th>Tipo</th><th>Fecha</th><th>Monto</th></tr>
                    </thead>
                    <tbody>
                        @forelse($unidad->gastos as $gasto)
                            <tr>
                                <td>{{ $gasto->descripcion ?? '—' }}</td>
                                <td>{{ ucfirst($gasto->tipo) }}</td>
                                <td>{{ $gasto->fecha_gasto->format('d/m/Y') }}</td>
                                <td>L {{ number_format($gasto->monto, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">Sin gastos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection