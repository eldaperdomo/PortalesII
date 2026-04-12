@extends('layouts.app')
@section('title', $propiedad->nombre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-house-door me-2"></i>{{ $propiedad->nombre }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('propiedades.edit', $propiedad) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="{{ route('propiedades.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-3">Información General</h6>
                <table class="table table-sm table-borderless">
                    <tr><th>Tipo</th><td>{{ ucfirst($propiedad->tipo) }}</td></tr>
                    <tr><th>Dirección</th><td>{{ $propiedad->direccion ?? '—' }}</td></tr>
                    <tr><th>Activo</th>
                        <td>
                            @if($propiedad->activo)
                                <span class="badge bg-success">Sí</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Registrado</th><td>{{ $propiedad->creado_en?->format('d/m/Y') ?? '—' }}</td></tr>
                </table>
                @if($propiedad->descripcion)
                    <p class="text-muted small border-top pt-2">{{ $propiedad->descripcion }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-door-open me-2"></i>Unidades</strong>
                <a href="{{ route('unidades.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus me-1"></i>Agregar
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Identificador</th>
                            <th>Renta</th>
                            <th>Estado</th>
                            <th>Activo</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($propiedad->unidades as $unidad)
                            <tr>
                                <td>{{ $unidad->identificador }}</td>
                                <td>L {{ number_format($unidad->monto_renta, 2) }}</td>
                                <td>
                                    <span class="badge
                                        @if($unidad->estado == 'disponible') bg-success
                                        @elseif($unidad->estado == 'ocupada') bg-danger
                                        @else bg-warning text-dark @endif">
                                        {{ ucfirst($unidad->estado) }}
                                    </span>
                                </td>
                                <td>
                                    @if($unidad->activo)
                                        <span class="badge bg-success">Sí</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('unidades.show', $unidad) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Sin unidades registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection