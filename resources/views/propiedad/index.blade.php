@extends('welcome')
@section('title', 'Propiedad')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-house-door me-2"></i>Propiedades</h4>
    <a href="{{ route('propiedad.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nueva Propiedad
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Ciudad</th>
                    <th>Unidades</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($propiedad as $propiedad)
                    <tr>
                        <td>
                            <strong>{{ $propiedad->nombre }}</strong><br>
                            <small class="text-muted">{{ $propiedad->direccion }}</small>
                        </td>
                        <td><span class="text-capitalize">{{ $propiedad->tipo }}</span></td>
                        <td>{{ $propiedad->ciudad }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ $propiedad->unidad_count }} total</span>
                            <span class="badge badge-disponible">{{ $propiedad->unidad_disponibles }} libres</span>
                            <span class="badge badge-ocupada">{{ $propiedad->unidad_ocupadas }} ocupadas</span>
                        </td>
                        <td>
                            @if($propiedad->activa)
                                <span class="badge bg-success">Activa</span>
                            @else
                                <span class="badge bg-secondary">Inactiva</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('propiedad.show', $propiedad) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('propiedad.edit', $propiedad) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('propiedad.destroy', $propiedad) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar esta propiedad?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No hay propiedades registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $propiedad->links() }}</div>
@endsection