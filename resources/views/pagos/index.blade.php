@extends('welcome')
@section('title', 'Pagos')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Pagos</h4>
</div>


{{-- 🔥 FILTRO ACTIVO / INACTIVO --}}
<div class="mb-3">
    <a href="{{ route('pagos.index', ['estado' => 'activos']) }}"
       class="btn btn-sm {{ request('estado', 'activos') == 'activos' ? 'btn-primary' : 'btn-outline-primary' }}">
        Activos
    </a>

    @if(auth()->user()->rol === 'admin')
        <a href="{{ route('pagos.index', ['estado' => 'inactivos']) }}"
           class="btn btn-sm {{ request('estado') == 'inactivos' ? 'btn-danger' : 'btn-outline-danger' }}">
            Inactivos
        </a>
    @endif
</div>

{{-- 🔥 COMBOBOX CONTRATOS --}}
<form method="GET" action="{{ route('pagos.index') }}" class="mb-3">

    <input type="hidden" name="estado" value="{{ request('estado', 'activos') }}">

    <select name="contrato_id" class="form-select" onchange="this.form.submit()">
        <option value="">-- Seleccionar contrato --</option>

        @foreach($contratos as $contrato)
            <option value="{{ $contrato->id }}"
                {{ request('contrato_id') == $contrato->id ? 'selected' : '' }}>
                
                {{ $contrato->codigo }}

                @if(method_exists($contrato, 'trashed') && $contrato->trashed())
                    🔴
                @endif

            </option>
        @endforeach
    </select>

</form>

{{-- 🔥 BOTONES CUANDO HAY CONTRATO --}}
@if(request('contrato_id'))

    <div class="mb-3 d-flex gap-2">

        {{-- VER CONTRATO --}}
        <a href="{{ route('contrato.show', request('contrato_id')) }}"
           class="btn btn-info btn-sm">
            Ver contrato
        </a>

        {{-- NUEVO PAGO (solo activos) --}}
        @if(request('estado') != 'inactivos')
            <a href="{{ route('pagos.create', ['contrato_id' => request('contrato_id')]) }}"
               class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> Nuevo Pago
            </a>
        @endif

    </div>

@endif

{{-- 🔥 TABLA --}}
<div class="card">
    <div class="card-body p-0">

        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Periodo</th>
                    <th>Monto</th>
                    <th>Pagado</th>
                    <th>Estado</th>
                    <th>Contrato</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                {{-- 🔥 SIN CONTRATO --}}
                @if(!request('contrato_id'))
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Seleccione un contrato para ver los pagos
                        </td>
                    </tr>

                {{-- 🔥 SIN PAGOS --}}
                @elseif(count($pagos) === 0)
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No hay pagos para este contrato
                        </td>
                    </tr>

                @else

                    @foreach($pagos as $pago)
                    <tr>

                        <td>{{ $pago->periodo }}</td>

                        <td>L {{ number_format($pago->monto_esperado,2) }}</td>

                        <td>L {{ number_format($pago->total_pagado,2) }}</td>

                        <td>
                            @if($pago->estado == 'pagado')
                                <span class="badge bg-success">Pagado</span>
                            @elseif($pago->estado == 'parcial')
                                <span class="badge bg-warning text-dark">Parcial</span>
                            @else
                                <span class="badge bg-secondary">Pendiente</span>
                            @endif
                        </td>

                        <td>
                            @if($pago->contrato)
                                {{ $pago->contrato->codigo }}
                            @else
                                <span class="text-danger">Eliminado</span>
                            @endif
                        </td>

                        <td class="d-flex gap-1">

                            {{-- VER DETALLE --}}
                            
                            <a href="{{ route('pagos.show', [
                                'pago' => $pago->id,
                                'contrato_id' => request('contrato_id'),
                                'estado' => request('estado')
                            ]) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>


                            {{-- RECIBO --}}
                            <a href="{{ route('recibos.ver', $pago->id) }}"
                               class="btn btn-sm btn-success">
                                📄
                            </a>

                        </td>

                    </tr>
                    @endforeach

                @endif

            </tbody>

        </table>

    </div>
</div>

@endsection