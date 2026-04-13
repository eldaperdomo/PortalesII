@extends('welcome')
@section('title', 'Gastos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Gastos</h4>
    <a href="{{ route('gasto.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Registrar Gasto
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body py-3">
                <div class="text-muted small">Gastos Este Mes</div>
                <div class="fs-4 fw-bold text-info">L {{ number_format($totalMes, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Unidad / Propiedad</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Activo</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gastos as $gasto)
                    <tr>
                        <td>
                            {{ $gasto->unidad->identificador }}<br>
                            <small class="text-muted">{{ $gasto->unidad->propiedad->nombre }}</small>
                        </td>
                        <td>{{ $gasto->descripcion ?? '—' }}</td>
                        <td><span class="badge bg-light text-dark border">{{ ucfirst($gasto->tipo) }}</span></td>
                        <td>{{ $gasto->fecha->format('d/m/Y') }}</td>
                        <td>L {{ number_format($gasto->monto, 2) }}</td>
                        <td>
                            @if($gasto->activo)
                                <span class="badge bg-success">Sí</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('gasto.show', $gasto) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('gasto.edit', $gasto) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('gasto.destroy', $gasto) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este gasto?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No hay gastos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $gastos->links() }}</div>
@endsection