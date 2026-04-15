@extends('welcome')
@section('title', $propiedad->nombre)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">
        <i class="bi bi-house-door me-2"></i>{{ $propiedad->nombre }}
    </h4>

    <div class="d-flex gap-2">
        <a href="{{ route('propiedad.edit', $propiedad) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>

        <a href="{{ route('propiedad.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- INFO --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">

                @if($propiedad->imagen)
                    <img src="{{ Storage::url($propiedad->imagen) }}"
                         class="img-fluid rounded mb-3">
                @endif

                <h6 class="text-muted mb-3">Información General</h6>

                <table class="table table-sm table-borderless">
                    <tr><th>Tipo</th><td>{{ ucfirst($propiedad->tipo) }}</td></tr>
                    <tr><th>Dirección</th><td>{{ $propiedad->direccion ?? '—' }}</td></tr>
                    <tr><th>Activo</th>
                    <tr><th>Tipo</th><td>{{ ucfirst(str_replace('_',' ',$propiedad->tipo)) }}</td></tr>
                    <tr><th>Dirección</th><td>{{ $propiedad->direccion_completa }}</td></tr>
                    <tr><th>Área</th><td>{{ $propiedad->area_total ?? 'N/D' }} m²</td></tr>
                    <tr>
                        <th>Estado</th>
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
<<<<<<< HEAD
                    <p class="text-muted small border-top pt-2">{{ $propiedad->descripcion }}</p>
=======
                    <p class="text-muted small">{{ $propiedad->descripcion }}</p>
>>>>>>> 46a26b139ac95d3675b48ca2d0d1fe625c558f87
                @endif

            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-door-open me-2"></i>Unidades</strong>
                <a href="{{ route('unidad.create') }}" class="btn btn-sm btn-primary">
    {{-- UNIDADES --}}
    <div class="col-md-8">

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-door-open me-2"></i>Unidades</strong>

                <a href="{{ route('unidad.create') }}?propiedad_id={{ $propiedad->id }}"
                   class="btn btn-sm btn-primary">
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
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Renta</th>
                            <th>Estado</th>
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
                        @forelse($propiedad->unidades ?? [] as $unidad)
                            <tr>
                                <td>{{ $unidad->nombre }}</td>
                                <td>{{ ucfirst($unidad->tipo) }}</td>
                                <td>L {{ number_format($unidad->precio_renta, 2) }}</td>

                                <td>
                                    <span class="badge 
                                        @if($unidad->estado == 'disponible') bg-success
                                        @elseif($unidad->estado == 'ocupada') bg-danger
                                        @else bg-warning
                                        @endif">
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
                                    <a href="{{ route('unidad.show', $unidad) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">
                                    Sin unidades registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- GASTOS --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong><i class="bi bi-cash-stack me-2"></i>Últimos Gastos</strong>

                <a href="{{ route('gasto.create') }}?propiedad_id={{ $propiedad->id }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus me-1"></i>Registrar
                </a>
            </div>

            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Concepto</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($propiedad->gastos ?? [] as $gasto)
                            <tr>
                                <td>{{ $gasto->concepto }}</td>

                                <td>
                                    {{ $gasto->fecha ? \Carbon\Carbon::parse($gasto->fecha)->format('d/m/Y') : '—' }}
                                </td>

                                <td>L {{ number_format($gasto->monto, 2) }}</td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($gasto->estado) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    Sin gastos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection

