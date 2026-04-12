@extends('welcome')
@section('title', 'Inquilino')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-people me-2"></i>Inquilinos</h4>
    <a href="{{ route('inquilino.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Inquilino
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Contacto</th>
                    <th>Contratos</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($Inquilinos as $inquilino)
                    <tr>
                        <td>
                            <strong>{{ $inquilino->nombre_completo }}</strong>
                            @if($inquilino->ocupacion)
                                <br><small class="text-muted">{{ $inquilino->ocupacion }}</small>
                            @endif
                        </td>
                        <td>{{ $inquilino->dni }}</td>
                        <td>
                            {{ $inquilino->telefono ?? '—' }}<br>
                            <small class="text-muted">{{ $inquilino->email ?? '' }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $inquilino->contratos_count }}</span>
                        </td>
                        <td>
                            @if($inquilino->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('inquilino.show', $inquilino) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('inquilino.edit', $inquilino) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('inquilino.destroy', $inquilino) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este inquilino?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No hay inquilinos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $Inquilinos->links() }}</div>
@endsection