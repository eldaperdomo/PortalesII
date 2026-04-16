@extends('welcome')
@section('title', 'Nuevo Pago')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-cash-stack me-2"></i>Nuevo Pago</h4>

    <a href="{{ route('pagos.index', [
        'contrato_id' => request('contrato_id'),
        'estado' => request('estado', 'activos')
    ]) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

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

        <form action="{{ route('pagos.store') }}" method="POST">
        @csrf

        <div class="row g-3">

            {{-- CONTRATO --}}
            <div class="col-md-6">
                <label class="form-label">Contrato *</label>

                <select name="contrato_id"
                        class="form-select @error('contrato_id') is-invalid @enderror"
                        id="contratoSelect"
                        {{ request('contrato_id') ? 'disabled' : '' }}>

                    <option value="">-- Seleccione --</option>

                    @foreach($contratos as $c)
                        <option value="{{ $c->id }}"
                                data-inicio="{{ $c->fecha_inicio }}"
                                data-periodicidad="{{ $c->periodicidad }}"
                                data-dia="{{ $c->dia_pago }}"
                                data-ultimo="{{ $c->ultimo_periodo }}"
                                {{ request('contrato_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->codigo }} - {{ ucfirst($c->periodicidad) }}
                        </option>
                    @endforeach

                </select>

                {{-- 🔥 IMPORTANTE --}}
                @if(request('contrato_id'))
                    <input type="hidden" name="contrato_id" value="{{ request('contrato_id') }}">
                @endif

                @error('contrato_id') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror
            </div>

            {{-- PERIODO --}}
            <div class="col-md-6">
                <label class="form-label">Periodo *</label>

                <input type="month" name="periodo"
                       id="periodoInput"
                       class="form-control @error('periodo') is-invalid @enderror"
                       value="{{ old('periodo') }}">

                @error('periodo') 
                    <div class="invalid-feedback">{{ $message }}</div> 
                @enderror

                <small id="infoPeriodo" class="text-muted d-block mt-1"></small>
            </div>

        </div>

        {{-- BOTONES --}}
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar
            </button>

            <a href="{{ route('pagos.index', [
                'contrato_id' => request('contrato_id'),
                'estado' => request('estado', 'activos')
            ]) }}" class="btn btn-outline-secondary">
                Cancelar
            </a>
        </div>

        </form>

    </div>
</div>

@endsection


{{-- 🔥 SCRIPT INTELIGENTE --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const contratoSelect = document.getElementById('contratoSelect');
    const periodoInput = document.getElementById('periodoInput');
    const info = document.getElementById('infoPeriodo');

    function factor(periodicidad) {
        switch (periodicidad) {
            case 'mensual': return 1;
            case 'bimestral': return 2;
            case 'trimestral': return 3;
            case 'semestral': return 6;
            case 'anual': return 12;
            default: return 1;
        }
    }

    function sumarMeses(periodo, cantidad) {
        let [year, month] = periodo.split('-').map(Number);

        month += cantidad;

        while (month > 12) {
            month -= 12;
            year++;
        }

        return year + '-' + String(month).padStart(2, '0');
    }

    contratoSelect.addEventListener('change', function () {

        const selected = this.options[this.selectedIndex];

        if (!selected.value) {
            info.innerHTML = '';
            periodoInput.value = '';
            return;
        }

        const inicioStr = selected.dataset.inicio;
        const periodicidad = selected.dataset.periodicidad;
        const diaPago = parseInt(selected.dataset.dia);
        const ultimo = selected.dataset.ultimo;

        const f = factor(periodicidad);

        let sugerido;

        // 🔥 SI YA HAY PAGOS
        if (ultimo) {
            sugerido = sumarMeses(ultimo, f);
        } else {
            let fechaInicio = new Date(inicioStr);

            let year = fechaInicio.getFullYear();
            let month = fechaInicio.getMonth() + 1;

            if (fechaInicio.getDate() > diaPago) {
                month++;
            }

            if (month > 12) {
                month = 1;
                year++;
            }

            sugerido = year + '-' + String(month).padStart(2, '0');
        }

        periodoInput.value = sugerido;

        info.innerHTML = `
            <strong>Periodo sugerido:</strong> ${sugerido}
        `;
    });

    // 🔥 AUTO EJECUTAR SI YA VIENE CONTRATO
    if (contratoSelect.value) {
        contratoSelect.dispatchEvent(new Event('change'));
    }

});
</script>
@endpush