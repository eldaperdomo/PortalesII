@extends('welcome')
@section('title', 'Editar Gasto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar Gasto</h4>
    <a href="{{ route('gasto.show', $gasto) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('gasto.update', $gasto) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Unidad *</label>
                    <select name="unidad_id" class="form-select @error('unidad_id') is-invalid @enderror" required>
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id }}"
                                {{ old('unidad_id', $gasto->unidad_id) == $unidad->id ? 'selected' : '' }}>
                                {{ $unidad->identificador }} — {{ $unidad->propiedad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('unidad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha del Gasto *</label>
                    <input type="date" name="fecha" class="form-control"
                           value="{{ old('fecha', $gasto->fecha->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Monto *</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="monto" step="0.01" min="0" class="form-control"
                               value="{{ old('monto', $gasto->monto) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo *</label>
                    <select name="tipo" class="form-select" required>
                        @foreach(['mantenimiento','reparacion','compra','servicio','otro'] as $tipo)
                            <option value="{{ $tipo }}"
                                {{ old('tipo', $gasto->tipo) == $tipo ? 'selected' : '' }}>
                                {{ ucfirst($tipo) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" maxlength="255" class="form-control"
                           value="{{ old('descripcion', $gasto->descripcion) }}">
                </div>

                <div class="col-md-8">
                    <label class="form-label">URL Comprobante</label>
                    <input type="text" name="comprobante_url" maxlength="255" class="form-control"
                           value="{{ old('comprobante_url', $gasto->comprobante_url) }}">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo" value="1"
                               id="activo" {{ old('activo', $gasto->activo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Activo</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $gasto->observaciones) }}</textarea>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Actualizar Gasto
                </button>
                <a href="{{ route('gasto.show', $gasto) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection