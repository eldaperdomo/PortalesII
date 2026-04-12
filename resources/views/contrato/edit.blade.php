@extends('layouts.app')
@section('title', 'Editar Contrato')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar Contrato</h4>
    <a href="{{ route('contratos.show', $contrato) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('contratos.update', $contrato) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Unidad *</label>
                    <select name="unidad_id" class="form-select @error('unidad_id') is-invalid @enderror" required>
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id }}"
                                {{ old('unidad_id', $contrato->unidad_id) == $unidad->id ? 'selected' : '' }}>
                                {{ $unidad->identificador }} — {{ $unidad->propiedad->nombre }}
                                ({{ ucfirst($unidad->estado) }})
                            </option>
                        @endforeach
                    </select>
                    @error('unidad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Inquilino *</label>
                    <select name="inquilino_id" class="form-select @error('inquilino_id') is-invalid @enderror" required>
                        @foreach($inquilinos as $inquilino)
                            <option value="{{ $inquilino->id }}"
                                {{ old('inquilino_id', $contrato->inquilino_id) == $inquilino->id ? 'selected' : '' }}>
                                {{ $inquilino->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('inquilino_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio"
                           class="form-control @error('fecha_inicio') is-invalid @enderror"
                           value="{{ old('fecha_inicio', $contrato->fecha_inicio->format('Y-m-d')) }}" required>
                    @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Fin *</label>
                    <input type="date" name="fecha_fin"
                           class="form-control @error('fecha_fin') is-invalid @enderror"
                           value="{{ old('fecha_fin', $contrato->fecha_fin->format('Y-m-d')) }}" required>
                    @error('fecha_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Día de Pago *</label>
                    <input type="number" name="dia_pago" min="1" max="31" class="form-control"
                           value="{{ old('dia_pago', $contrato->dia_pago) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Monto de Renta *</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="monto_renta" step="0.01" min="0" class="form-control"
                               value="{{ old('monto_renta', $contrato->monto_renta) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado *</label>
                    <select name="estado" class="form-select" required>
                        <option value="activo"    {{ old('estado', $contrato->estado) == 'activo'    ? 'selected' : '' }}>Activo</option>
                        <option value="terminado" {{ old('estado', $contrato->estado) == 'terminado' ? 'selected' : '' }}>Terminado</option>
                        <option value="cancelado" {{ old('estado', $contrato->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo" value="1"
                               id="activo" {{ old('activo', $contrato->activo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Contrato activo</label>
                    </div>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Actualizar Contrato
                </button>
                <a href="{{ route('contratos.show', $contrato) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection