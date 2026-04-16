@extends('welcome')
@section('title', 'Contratos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-file-text me-2"></i>Contratos</h4>
    <a href="{{ route('contrato.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Contrato
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-center">

            <div class="col-md-5">
                <input type="text" name="search" class="form-control"
                    placeholder="Buscar por código o inquilino..."
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">-- Todos --</option>
                    <option value="activo" {{ request('estado')=='activo'?'selected':'' }}>Activo</option>
                    <option value="vencido" {{ request('estado')=='vencido'?'selected':'' }}>Vencido</option>
                    <option value="cancelado" {{ request('estado')=='cancelado'?'selected':'' }}>Cancelado</option>
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>

                <a href="{{ route('contrato.index') }}" class="btn btn-outline-secondary">
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
                    <th>Inquilino</th>
                    <th>Unidad / Propiedad</th>
                    <th>Renta</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($Contrato as $contrato)
                    <tr>
                        <td><strong>{{ $contrato->inquilino->nombre }}</strong></td>
                        <td>
                            {{ $contrato->unidad->identificador }}<br>
                            <small class="text-muted">{{ $contrato->unidad->propiedad->nombre }}</small>
                        </td>
                        <td>L {{ number_format($contrato->monto_renta, 2) }}</td>
                        <td>{{ $contrato->fecha_inicio->format('d/m/Y') }}</td>
                        <td>
                            {{ $contrato->fecha_fin->format('d/m/Y') }}
                            @if($contrato->estado == 'activo' && $contrato->dias_para_vencer <= 30 && $contrato->dias_para_vencer > 0)
                                <br><small class="text-warning"><i class="bi bi-exclamation-triangle"></i> {{ $contrato->dias_para_vencer }} días</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge
                                @if($contrato->estado == 'activo') bg-success
                                @elseif($contrato->estado == 'terminado') bg-secondary
                                @else bg-danger @endif">
                                {{ ucfirst($contrato->estado) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('contrato.show', $contrato) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('contrato.edit', $contrato) }}" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('contrato.destroy', $contrato) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este contrato?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No hay contratos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $Contrato->links() }}</div>
@endsection