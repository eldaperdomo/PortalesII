@extends('welcome')
@section('title', 'Pagos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-cash-stack me-2"></i>Pagos</h4>

    <a href="{{ route('pagos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Pago
    </a>
</div>

{{-- 🔍 FILTROS --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2">

            <input type="hidden" name="incluir_inactivos" value="{{ request('incluir_inactivos') }}">

            <div class="col-md-3">
                <input type="month" name="periodo" class="form-control"
                       value="{{ request('periodo') }}">
            </div>

            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">-- Estado --</option>
                    <option value="pendiente" {{ request('estado')=='pendiente'?'selected':'' }}>Pendiente</option>
                    <option value="parcial" {{ request('estado')=='parcial'?'selected':'' }}>Parcial</option>
                    <option value="pagado" {{ request('estado')=='pagado'?'selected':'' }}>Pagado</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="text" name="contrato"
                       class="form-control"
                       placeholder="Código contrato"
                       value="{{ request('contrato') }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>

                <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">
                    Limpiar
                </a>
            </div>

        </form>
    </div>
</div>

{{-- 🔥 ACTIVOS / TODOS --}}
<div class="mb-3 d-flex gap-2">

    <a href="{{ route('pagos.index', array_merge(request()->query(), ['incluir_inactivos' => null])) }}"
       class="btn btn-sm {{ !request()->has('incluir_inactivos') ? 'btn-primary' : 'btn-outline-primary' }}">
        Activos
    </a>

    @if(auth()->user()->esAdmin())
    <a href="{{ route('pagos.index', array_merge(request()->query(), ['incluir_inactivos' => 'true'])) }}"
       class="btn btn-sm {{ request('incluir_inactivos') == 'true' ? 'btn-dark' : 'btn-outline-dark' }}">
        Todos
    </a>
    @endif

</div>

{{-- 📋 TABLA --}}
<div class="card">
    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Contrato</th>
                    <th>Periodo</th>
                    <th>Monto</th>
                    <th>Pagado</th>
                    <th>Saldo</th>
                    <th>Estado</th>
                    <th>Estado Sistema</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($pagos as $pago)
                <tr>

                    <td>
                        <strong>{{ $pago->contrato->codigo }}</strong><br>
                        <small class="text-muted">
                            {{ ucfirst($pago->contrato->periodicidad) }}
                        </small>
                    </td>

                    <td>{{ $pago->periodo }}</td>

                    <td>L {{ number_format($pago->monto_esperado,2) }}</td>
                    <td>L {{ number_format($pago->total_pagado,2) }}</td>

                    <td>
                        <strong>L {{ number_format($pago->saldo,2) }}</strong>
                    </td>

                    <td>
                        <span class="badge bg-{{ 
                            $pago->estado == 'pagado' ? 'success' : 
                            ($pago->estado == 'parcial' ? 'warning' : 'danger') }}">
                            {{ ucfirst($pago->estado) }}
                        </span>
                    </td>

                    {{-- ACTIVO / INACTIVO --}}
                    <td>
                        @if($pago->activo)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>

                    <td class="text-end">

                        <a href="{{ route('pagos.show', $pago) }}"
                           class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ route('pagos.edit', $pago) }}"
                           class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </a>

                        {{-- ADMIN --}}
                        @if(auth()->user()->esAdmin())

                            @if($pago->activo)
                            <form action="{{ route('pagos.destroy', $pago) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                            @endif

                            @if(!$pago->activo)
                            <form action="{{ route('pagos.activar', $pago) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <button class="btn btn-sm btn-success">
                                    <i class="bi bi-check"></i>
                                </button>
                            </form>
                            @endif

                        @endif

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        No hay pagos registrados
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

<div class="mt-3">
    {{ $pagos->links() }}
</div>

@endsection