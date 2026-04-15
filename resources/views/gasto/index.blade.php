@extends('welcome')
@section('title', 'Gastos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Gastos</h4>
    <a href="{{ route('gasto.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Registrar Gasto
    </a>
</div>

{{-- Resumen rápido --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 bg-warning bg-opacity-10">
            <div class="card-body py-3">
                <div class="text-muted small">Total Pendiente</div>
                <div class="fs-4 fw-bold text-warning">L {{ number_format($totalPendiente, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
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
                    <th>Concepto</th>
                    <th>Propiedad / Unidad</th>
                    <th>Categoría</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gastos as $gasto)
                    <tr>
                        <td>
                            <strong>{{ $gasto->concepto }}</strong>
                            @if($gasto->proveedor)
                                <br><small class="text-muted">{{ $gasto->proveedor }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $gasto->propiedad->nombre }}<br>
                            <small class="text-muted">{{ $gasto->unidad?->nombre ?? 'General' }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ ucfirst($gasto->categoria) }}</span></td>
                        <td>{{ $gasto->fecha->format('d/m/Y') }}</td>
                        <td>L {{ number_format($gasto->monto, 2) }}</td>
                        <td>
                            <span class="badge badge-{{ $gasto->estado }}">
                                {{ ucfirst($gasto->estado) }}
                            </span>
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