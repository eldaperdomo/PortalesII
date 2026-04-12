@extends('layouts.app')
@section('title', 'Nueva Unidad')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Nueva Unidad</h4>
    <a href="{{ route('unidades.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('unidades.store') }}" method="POST">
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Propiedad *</label>
                    <select name="propiedad_id" class="form-select @error('propiedad_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar propiedad --</option>
                        @foreach($propiedades as $propiedad)
                            <option value="{{ $propiedad->id }}"
                                {{ old('propiedad_id', request('propiedad_id')) == $propiedad->id ? 'selected' : '' }}>
                                {{ $propiedad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('propiedad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Identificador *</label>
                    <input type="text" name="identificador" maxlength="50"
                           class="form-control @error('identificador') is-invalid @enderror"
                           value="{{ old('identificador') }}" placeholder="Ej: Apto 101, Local A" required>
                    @error('identificador') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado *</label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                        <option value="disponible"    {{ old('estado', 'disponible') == 'disponible'    ? 'selected' : '' }}>Disponible</option>
                        <option value="ocupada"       {{ old('estado') == 'ocupada'       ? 'selected' : '' }}>Ocupada</option>
                        <option value="mantenimiento" {{ old('estado') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                    </select>
                    @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Monto de Renta *</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="monto_renta" step="0.01" min="0"
                               class="form-control @error('monto_renta') is-invalid @enderror"
                               value="{{ old('monto_renta') }}" required>
                        @error('monto_renta') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo" value="1"
                               id="activo" {{ old('activo', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Unidad activa</label>
                    </div>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Guardar Unidad
                </button>
                <a href="{{ route('unidades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection