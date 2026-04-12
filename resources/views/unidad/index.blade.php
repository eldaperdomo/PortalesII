@extends('welcome')
@section('title', 'Unidad')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-door-open me-2"></i>Unidades</h4>
    <a href="{{ route('unidad.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nueva Unidad
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Unidad</th>
                    <th>Propiedad</th>
                    <th>Tipo</th>
                    <th>Renta Mensual</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($unidades as $unidad)
                    <tr>
                        <td>
                            <strong>{{ $unidad->nombre }}</strong>
                            @if($unidad->piso) <br><small class="text-muted">Piso {{ $unidad->piso }}</small> @endif
                        </td>
                        <td>{{ $unidad->propiedad->nombre }}</td>
                        <td>{{ ucfirst($unidad->tipo) }}</td>
                        <td>L {{ number_format($unidad->precio_renta, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $unidad->estado }}">
                                {{ ucfirst(str_replace('_', ' ', $unidad->estado)) }}
                            </span>
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