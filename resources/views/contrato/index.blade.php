@extends('welcome')
@section('title', 'Contratos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-file-text me-2"></i>Contratos</h4>
    <a href="{{ route('contrato.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Nuevo Contrato
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Inquilino</th>
                    <th>Unidad / Propiedad</th>
                    <th>Monto Mensual</th>
                    <th>Vigencia</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($Contrato as $contrato)
                    <tr>
                        <td><strong>{{ $contrato->codigo }}</strong></td>
                        <td>{{ $contrato->inquilino->nombre_completo }}</td>
                        <td>
                            {{ $contrato->unidad->nombre }}<br>
                            <small class="text-muted">{{ $contrato->unidad->propiedad->nombre }}</small>
                        </td>
                        <td>L {{ number_format($contrato->monto_mensual, 2) }}</td>
                        <td>
                            <small>
                                {{ $contrato->fecha_inicio->format('d/m/Y') }}<br>
                                {{ $contrato->fecha_fin->format('d/m/Y') }}
                            </small>
                        </td>
                        <td>
                            <span class="badge badge-{{ $contrato->estado }}">
                                {{ ucfirst($contrato->estado) }}
                            </span>
                            @if($contrato->estado === 'activo' && $contrato->dias_para_vencer <= 30 && $contrato->dias_para_vencer > 0)
                                <br><small class="text-warning">Vence en {{ $contrato->dias_para_vencer }} días</small>
                            @endif
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