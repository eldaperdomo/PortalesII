@extends('welcome')
@section('title', $inquilino->nombre_completo)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-person me-2"></i>{{ $inquilino->nombre_completo }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('inquilino.edit', $inquilino) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="{{ route('inquilino.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-3">Información Personal</h6>
                <table class="table table-sm table-borderless">
                    <tr><th>DNI</th><td>{{ $inquilino->dni }}</td></tr>
                    <tr><th>Nacimiento</th><td>{{ $inquilino->fecha_nacimiento?->format('d/m/Y') ?? 'N/D' }}</td></tr>
                    <tr><th>Estado Civil</th><td>{{ ucfirst($inquilino->estado_civil ?? 'N/D') }}</td></tr>
                    <tr><th>Email</th><td>{{ $inquilino->email ?? '—' }}</td></tr>
                    <tr><th>Teléfono</th><td>{{ $inquilino->telefono ?? '—' }}</td></tr>
                    <tr><th>Emergencia</th><td>{{ $inquilino->contacto_emergencia ?? '—' }} {{ $inquilino->telefono_emergencia ? '/ '.$inquilino->telefono_emergencia : '' }}</td></tr>
                    <tr><th>Ocupación</th><td>{{ $inquilino->ocupacion ?? '—' }}</td></tr>
                    <tr><th>Empresa</th><td>{{ $inquilino->empresa ?? '—' }}</td></tr>
                    <tr><th>Ingreso</th><td>{{ $inquilino->ingreso_mensual ? 'L '.number_format($inquilino->ingreso_mensual,2) : '—' }}</td></tr>
                    <tr><th>Estado</th>
                        <td>
                            @if($inquilino->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                    </tr>
                </table>
                @if($inquilino->observaciones)
                    <p class="text-muted small border-top pt-2">{{ $inquilino->observaciones }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-file-text me-2"></i>Historial de Contratos</strong>
                <a href="{{ route('contrato.create') }}?inquilino_id={{ $inquilino->id }}"
                   class="btn btn-sm btn-primary">
                    <i class="bi bi-plus me-1"></i>Nuevo Contrato
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Unidad</th>
                            <th>Propiedad</th>
                            <th>Monto</th>
                            <th>Período</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquilino->contratos as $contrato)
                            <tr>
                                <td>{{ $contrato->codigo }}</td>
                                <td>{{ $contrato->unidad->nombre }}</td>
                                <td>{{ $contrato->unidad->propiedad->nombre }}</td>
                                <td>L {{ number_format($contrato->monto_mensual, 2) }}</td>
                                <td>
                                    <small>
                                        {{ $contrato->fecha_inicio->format('d/m/Y') }} —
                                        {{ $contrato->fecha_fin->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $contrato->estado }}">
                                        {{ ucfirst($contrato->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('contrato.show', $contrato) }}"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">Sin contratos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection