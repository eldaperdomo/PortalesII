@extends('welcome')
@section('title', 'Nueva Solicitud')

@section('content')

<div class="d-flex justify-content-between mb-4">
    <h4>Nueva Solicitud</h4>
    <a href="{{ route('solicitudes.index') }}" class="btn btn-outline-secondary">Volver</a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
<div class="card-body">

<form action="{{ route('solicitudes.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="row g-3">

{{-- 🔥 UNIDAD --}}
<div class="col-md-6">
<label>Unidad *</label>
<select name="unidad_id" id="unidadSelect" class="form-select">

<option value="">-- Seleccione --</option>

@foreach($unidades as $u)

@php
    $contrato = $u->contratos->where('estado','activo')->first();
    $inquilino = $contrato?->inquilino;
@endphp

<option value="{{ $u->id }}"
    data-inquilino="{{ $inquilino?->nombre ?? '' }}"
    data-inquilino-id="{{ $inquilino?->id ?? '' }}">
    
    {{ $u->nombre ?? 'Unidad #'.$u->id }}

</option>

@endforeach

</select>
</div>

{{-- 🔥 INQUILINO AUTO --}}
<div class="col-md-6">
<label>Inquilino</label>

<input type="text" id="inquilinoNombre" class="form-control" readonly>

<input type="hidden" name="inquilino_id" id="inquilinoId">
</div>

{{-- TIPO --}}
<div class="col-md-6">
<label>Tipo</label>
<select name="tipo" class="form-select">
<option value="reparacion">Reparación</option>
<option value="mantenimiento">Mantenimiento</option>
<option value="queja">Queja</option>
<option value="sugerencia">Sugerencia</option>
<option value="incidente">Incidente</option>
<option value="otro">Otro</option>
</select>
</div>

{{-- PRIORIDAD --}}
<div class="col-md-6">
<label>Prioridad</label>
<select name="prioridad" class="form-select">
<option value="media">Media</option>
<option value="alta">Alta</option>
<option value="urgente">Urgente</option>
<option value="baja">Baja</option>
</select>
</div>

{{-- ASUNTO --}}
<div class="col-md-12">
<label>Asunto</label>
<input type="text" name="asunto" class="form-control" required>
</div>

{{-- DESCRIPCIÓN --}}
<div class="col-md-12">
<label>Descripción</label>
<textarea name="descripcion" class="form-control" required></textarea>
</div>

{{-- EVIDENCIA --}}
<div class="col-md-6">
<label>Evidencia</label>
<input type="file" name="evidencia" class="form-control">
</div>

</div>

<div class="mt-3">
<button class="btn btn-primary">
    <i class="bi bi-save"></i> Guardar
</button>
</div>

</form>

</div>
</div>

@endsection

{{-- 🔥 SCRIPT --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const unidadSelect = document.getElementById('unidadSelect');
    const nombre = document.getElementById('inquilinoNombre');
    const id = document.getElementById('inquilinoId');

    unidadSelect.addEventListener('change', function () {

        const selected = this.options[this.selectedIndex];

        const nombreInq = selected.dataset.inquilino;
        const idInq = selected.dataset.inquilinoId;

        nombre.value = nombreInq || '';
        id.value = idInq || '';

    });

});
</script>
@endpush