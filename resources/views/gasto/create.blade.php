@extends('welcome')
@section('title', 'Registrar Gasto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Registrar Gasto</h4>
    <a href="{{ route('gasto.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('gasto.store') }}" method="POST">
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Unidad *</label>
                    <select name="unidad_id" class="form-select @error('unidad_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar unidad --</option>
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id }}"
                                {{ old('unidad_id', request('unidad_id')) == $unidad->id ? 'selected' : '' }}>
                                {{ $unidad->identificador }} — {{ $unidad->propiedad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('unidad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha del Gasto *</label>
                    <input type="date" name="fecha"
                           class="form-control @error('fecha') is-invalid @enderror"
                           value="{{ old('fecha', date('Y-m-d')) }}" required>
                    @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Monto *</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="monto" step="0.01" min="0"
                               class="form-control @error('monto') is-invalid @enderror"
                               value="{{ old('monto') }}" required>
                        @error('monto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo *</label>
                    <select name="categoria" class="form-select @error('categoria') is-invalid @enderror" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach(['mantenimiento','reparacion','compra','servicio','otro'] as $categoria)
                            <option value="{{ $categoria }}" {{ old('categoria') == $categoria ? 'selected' : '' }}>
                                {{ ucfirst($categoria) }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" maxlength="255" class="form-control"
                           value="{{ old('descripcion') }}">
                </div>

                <div class="col-md-8">
                    <label class="form-label">URL Comprobante</label>
                    <input type="text" name="comprobante" maxlength="255" class="form-control"
                           value="{{ old('comprobante') }}" placeholder="https://...">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo" value="1"
                               id="activo" {{ old('activo', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Activo</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Guardar Gasto
                </button>
                <a href="{{ route('gasto.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection