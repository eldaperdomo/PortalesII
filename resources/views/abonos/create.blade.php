@extends('welcome')
@section('title', 'Nuevo Abono')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-cash-stack me-2"></i>Nuevo Abono</h4>

    <a href="{{ route('pagos.show', $pago) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

{{-- ERRORES --}}
@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif


<div class="row g-4">

    {{-- INFO --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <strong>Información del Pago</strong>
            </div>

            <div class="card-body">

                <p><strong>Contrato:</strong><br>{{ $pago->contrato->codigo }}</p>

                <p><strong>Periodo:</strong><br>{{ $pago->periodo }}</p>

                <p><strong>Total:</strong><br>
                    L {{ number_format($pago->monto_esperado,2) }}
                </p>

                <p><strong>Pagado:</strong><br>
                    L {{ number_format($pago->total_pagado,2) }}
                </p>

                <hr>

                <p>
                    <strong>Saldo:</strong><br>
                    <span class="text-success fs-5">
                        L {{ number_format($pago->monto_esperado - $pago->total_pagado,2) }}
                    </span>
                </p>

            </div>
        </div>
    </div>

    {{-- FORM --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <strong>Registrar Abono</strong>
            </div>

            <div class="card-body">

                <form action="{{ route('abonos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="pago_id" value="{{ $pago->id }}">

                <div class="row g-3">

                    {{-- MONTO --}}
                    <div class="col-md-6">
                        <label class="form-label">Monto *</label>
                        <input type="number" step="0.01" name="monto"
                            class="form-control @error('monto') is-invalid @enderror"
                            value="{{ old('monto') }}">

                        @error('monto')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- MÉTODO --}}
                    <div class="col-md-6">
                        <label class="form-label">Método</label>
                        <select name="metodo" class="form-select" id="metodoPago">
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    {{-- REFERENCIA / COMPROBANTE --}}
                    <div class="col-md-12 d-none" id="campoReferencia">
                        <label class="form-label">Comprobante (imagen o referencia)</label>

                        <input type="file" name="referencia_pago"
                            class="form-control mb-2">

                        

                    </div>

                    {{-- OBSERVACIONES --}}
                    <div class="col-md-12">
                        <label class="form-label">Observaciones</label>
                        <textarea name="observacion"
                            class="form-control"
                            rows="3">{{ old('observacion') }}</textarea>
                    </div>
                    

                </div>
                

                {{-- BOTONES --}}
                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar
                        
                    </button>

                    <a href="{{ route('pagos.show', $pago) }}" class="btn btn-outline-secondary">
                        Cancelar
                    </a>
                </div>

                </form>

            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    const metodo = document.getElementById('metodoPago');
    const referencia = document.getElementById('campoReferencia');

    function toggleReferencia() {
        if (metodo.value === 'transferencia' || metodo.value === 'otro') {
            referencia.classList.remove('d-none');
        } else {
            referencia.classList.add('d-none');
        }
    }

    metodo.addEventListener('change', toggleReferencia);
    toggleReferencia();
</script>
@endpush