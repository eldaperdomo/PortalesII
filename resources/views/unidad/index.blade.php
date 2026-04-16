@extends('welcome')
@section('title', 'Unidades')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-door-open me-2"></i>Unidades</h4>
    <a href="{{ route('unidad.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nueva Unidad
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-center">

            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                    placeholder="Buscar por identificador..."
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">-- Todos --</option>
                    <option value="disponible" {{ request('estado')=='disponible'?'selected':'' }}>Disponible</option>
                    <option value="ocupado" {{ request('estado')=='ocupado'?'selected':'' }}>Ocupado</option>
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>

                <a href="{{ route('unidad.index') }}" class="btn btn-outline-secondary">
                    Limpiar
                </a>
            </div>

        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Identificador</th>
                    <th>Propiedad</th>
                    <th>Renta Mensual</th>
                    <th>Estado</th>
                    <th>Activo</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($unidades as $unidad)
                    <tr>
                        <td><strong>{{ $unidad->identificador }}</strong></td>
                        <td>{{ $unidad->propiedad->nombre }}</td>
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
                        <td class="text-end">
                            <a href="{{ route('unidad.show', $unidad) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('unidad.edit', $unidad) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('unidad.destroy', $unidad) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar esta unidad?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No hay unidades registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $unidades->links() }}</div>
@endsection