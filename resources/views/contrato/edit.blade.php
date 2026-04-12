@extends('welcome')
@section('title', 'Editar Contrato')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar Contrato — {{ $contrato->codigo }}</h4>
    <a href="{{ route('contrato.show', $contrato) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('contrato.update', $contrato) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">

                <div class="col-12"><h6 class="text-muted border-bottom pb-2">Partes del contrato</h6></div>

                <div class="col-md-6">
                    <label class="form-label">Unidad *</label>
                    <select name="unidad_id" class="form-select @error('unidad_id') is-invalid @enderror" required>
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id }}"
                                {{ old('unidad_id', $contrato->unidad_id) == $unidad->id ? 'selected' : '' }}>
                                {{ $unidad->nombre }} — {{ $unidad->propiedad->nombre }}
                                ({{ ucfirst(str_replace('_',' ',$unidad->estado)) }})
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
                                {{ $inquilino->nombre_completo }} — {{ $inquilino->dni }}
                            </option>
                        @endforeach
                    </select>
                    @error('inquilino_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12"><h6 class="text-muted border-bottom pb-2 mt-2">Términos económicos</h6></div>

                <div class="col-md-3">
                    <label class="form-label">Monto Mensual *</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="monto_mensual" class="form-control" step="0.01" min="0"
                               value="{{ old('monto_mensual', $contrato->monto_mensual) }}" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Depósito</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="deposito" class="form-control" step="0.01" min="0"
                               value="{{ old('deposito', $contrato->deposito) }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Día de Pago *</label>
                    <input type="number" name="dia_pago" class="form-control" min="1" max="31"
                           value="{{ old('dia_pago', $contrato->dia_pago) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Incremento Anual (%)</label>
                    <input type="number" name="incremento_anual" class="form-control" step="0.01" min="0" max="100"
                           value="{{ old('incremento_anual', $contrato->incremento_anual) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Periodicidad *</label>
                    <select name="periodicidad" class="form-select" required>
                        @foreach(['mensual','bimestral','trimestral','semestral','anual'] as $p)
                            <option value="{{ $p }}"
                                {{ old('periodicidad', $contrato->periodicidad) == $p ? 'selected' : '' }}>
                                {{ ucfirst($p) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado *</label>
                    <select name="estado" class="form-select" required>
                        @foreach(['activo','pendiente','vencido','cancelado'] as $e)
                            <option value="{{ $e }}"
                                {{ old('estado', $contrato->estado) == $e ? 'selected' : '' }}>
                                {{ ucfirst($e) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12"><h6 class="text-muted border-bottom pb-2 mt-2">Vigencia</h6></div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Inicio *</label>
                    <input type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror"
                           value="{{ old('fecha_inicio', $contrato->fecha_inicio->format('Y-m-d')) }}" required>
                    @error('fecha_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Fin *</label>
                    <input type="date" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror"
                           value="{{ old('fecha_fin', $contrato->fecha_fin->format('Y-m-d')) }}" required>
                    @error('fecha_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="renovacion_automatica" value="1"
                               id="renovacion"
                               {{ old('renovacion_automatica', $contrato->renovacion_automatica) ? 'checked' : '' }}>
                        <label class="form-check-label" for="renovacion">Renovación automática</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Cláusulas Adicionales</label>
                    <textarea name="clausulas_adicionales" class="form-control" rows="3">{{ old('clausulas_adicionales', $contrato->clausulas_adicionales) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="2">{{ old('observaciones', $contrato->observaciones) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Actualizar Contrato
                </button>
                <a href="{{ route('contrato.show', $contrato) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection