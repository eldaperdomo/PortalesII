@extends('welcome')
@section('title', $inquilino->nombre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-person me-2"></i>{{ $inquilino->nombre }}</h4>
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
            <div class="card-body text-center">
                @if($inquilino->foto_url)
                    <img src="{{ $inquilino->foto_url }}" alt="{{ $inquilino->nombre }}"
                         class="rounded-circle mb-3" style="width:90px;height:90px;object-fit:cover;">
                @else
                    <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:90px;height:90px;">
                        <i class="bi bi-person-fill text-white fs-2"></i>
                    </div>
                @endif
                <h5 class="mb-1">{{ $inquilino->nombre }}</h5>
                <p class="text-muted small mb-0">{{ $inquilino->correo ?? 'Sin correo' }}</p>
                <p class="text-muted small">{{ $inquilino->telefono ?? 'Sin teléfono' }}</p>
                <span class="badge {{ $inquilino->activo ? 'bg-success' : 'bg-secondary' }}">
                    {{ $inquilino->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            <div class="card-footer text-muted small text-center">
                Registrado: {{ $inquilino->creado_en?->format('d/m/Y') ?? '—' }}
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-file-text me-2"></i>Contratos</strong>
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
                            <th>Unidad</th>
                            <th>Propiedad</th>
                            <th>Renta</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquilino->contratos as $contrato)
                            <tr>
                                <td>{{ $contrato->unidad->identificador }}</td>
                                <td>{{ $contrato->unidad->propiedad->nombre }}</td>
                                <td>L {{ number_format($contrato->monto_renta, 2) }}</td>
                                <td>{{ $contrato->fecha_inicio->format('d/m/Y') }}</td>
                                <td>{{ $contrato->fecha_fin->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge
                                        @if($contrato->estado == 'activo') bg-success
                                        @elseif($contrato->estado == 'terminado') bg-secondary
                                        @else bg-danger @endif">
                                        {{ ucfirst($contrato->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('contrato.show', $contrato) }}" class="btn btn-sm btn-outline-info">
                                    <a href="{{ route('contrato.show', $contrato) }}"
                                       class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-3">Sin contratos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection