@extends('welcome')
@section('title', 'Editar Pago')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-pencil me-2"></i>Editar Pago</h4>

    <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

{{-- 🔥 MENSAJE SI YA ESTÁ PAGADO --}}
@if($pago->estado === 'pagado')
<div class="alert alert-success">
    Este pago ya está completado y no puede modificarse.
</div>
@endif

{{-- 🔥 ERRORES --}}
@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">

        <form action="{{ route('pagos.update', $pago) }}" method="POST">
        @csrf @method('PUT')

        <div class="row g-3">

            {{-- CONTRATO --}}
            <div class="col-md-6">
                <label class="form-label">Contrato</label>
                <input class="form-control" value="{{ $pago->contrato->codigo }}" readonly>
            </div>

            {{-- PERIODICIDAD --}}
            <div class="col-md-6">
                <label class="form-label">Periodicidad</label>
                <input class="form-control" value="{{ ucfirst($pago->contrato->periodicidad) }}" readonly>
            </div>

            {{-- PERIODO --}}
            <div class="col-md-6">
                <label class="form-label">Periodo *</label>

                <input type="month" name="periodo"
                       class="form-control @error('periodo') is-invalid @enderror"
                       value="{{ old('periodo', $pago->periodo) }}"
                       {{ $pago->estado === 'pagado' ? 'disabled' : '' }}>

                @error('periodo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- MONTO --}}
            <div class="col-md-6">
                <label class="form-label">Monto</label>
                <input class="form-control" value="L {{ number_format($pago->monto_esperado, 2) }}" readonly>
            </div>

        </div>

        {{-- BOTONES --}}
        <div class="mt-4 d-flex gap-2">

            <button class="btn btn-warning"
                {{ $pago->estado === 'pagado' ? 'disabled' : '' }}>
                <i class="bi bi-save"></i> Actualizar
            </button>

            <a href="{{ route('pagos.index') }}" class="btn btn-outline-secondary">
                Cancelar
            </a>

        </div>

        </form>

    </div>
</div>

@endsection