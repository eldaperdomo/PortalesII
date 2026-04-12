@extends('welcome')
@section('title', 'Nuevo Contrato')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-file-plus me-2"></i>Nuevo Contrato</h4>
    <a href="{{ route('contrato.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('contrato.store') }}" method="POST">
            @csrf
            <div class="row g-3">

                <div class="col-12"><h6 class="text-muted border-bottom pb-2">Partes del contrato</h6></div>

                <div class="col-md-6">
                    <label class="form-label">Unidad *</label>
                    <select name="unidad_id" class="form-select @error('unidad_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar unidad disponible --</option>
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id }}"
                                {{ old('unidad_id', request('unidad_id')) == $unidad->id ? 'selected' : '' }}>
                                {{ $unidad->nombre }} — {{ $unidad->propiedad->nombre }}
                                (L {{ number_format($unidad->precio_renta, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('unidad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Inquilino *</label>
                    <select name="inquilino_id" class="form-select @error('inquilino_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar inquilino --</option>
                        @foreach($inquilinos as $inquilino)
                            <option value="{{ $inquilino->id }}"
                                {{ old('inquilino_id', request('inquilino_id')) == $inquilino->id ? 'selected' : '' }}>
                                {{ $inquilino->nombre_completo }} — {{ $inquilino->dni }}
                            </option>
                        @endforeach
                    </select>
                    @error('inquilino_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12"><h6 class="text-muted border-bottom pb-2 mt-2">Términos económicos</h6></div>

                <div class="col-md-4">
                    <label class="form-label">Monto Mensual *</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="monto_mensual" class="form-control @error('monto_mensual') is-invalid @enderror"
                               step="0.01" min="0" value="{{ old('monto_mensual') }}" required>
                        @error('monto_mensual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Depósito</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="deposito" class="form-control" step="0.01" min="0"
                               value="{{ old('deposito', 0) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Día de Pago *</label>
                    <input type="number" name="dia_pago" class="form-control @error('dia_pago') is-invalid @enderror"
                           min="1" max="31" value="{{ old('dia_pago', 1) }}" required>
                    @error('dia_pago') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Incremento Anual (%)</label>
                    <input type="number" name="incremento_anual" class="form-control"
                           step="0.01" min="0" max="100" value="{{ old('incremento_anual', 0) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Periodicidad *</label>
                    <select name="periodicidad" class="form-select" required>
                        @foreach(['mensual','bimestral','trimestral','semestral','anual'] as $p)
                            <option value="{{ $p }}" {{ old('periodicidad', 'mensual') == $p ? 'selected' : '' }}>
                                {{ ucfirst($p) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado *</label>
                    <select name="estado" class="form-select" required>
                        @foreach(['activo','pendiente','vencido','cancelado'] as $e)
                            <option value="{{ $e }}" {{ old('estado', 'activo') == $e ? 'selected' : '' }}>
                                {{ ucfirst($e) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12"><h6 class="text-muted border-bottom pb-2 mt-2">Vigencia</h6></div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror"
                           value="{{ old('fecha_inicio') }}" required>
                    @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Fin *</label>
                    <input type="date" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror"
                           value="{{ old('fecha_fin') }}" required>
                    @error('fecha_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="renovacion_automatica" value="1"
                               id="renovacion" {{ old('renovacion_automatica') ? 'checked' : '' }}>
                        <label class="form-check-label" for="renovacion">Renovación automática</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Cláusulas Adicionales</label>
                    <textarea name="clausulas_adicionales" class="form-control" rows="3">{{ old('clausulas_adicionales') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Crear Contrato
                </button>
                <a href="{{ route('contrato.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection