@extends('welcome')
@section('title', 'Nueva Unidad')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Nueva Unidad</h4>
    <a href="{{ route('unidad.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('unidad.store') }}" method="POST">
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Propiedad *</label>
                    <select name="propiedad_id" class="form-select @error('propiedad_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar propiedad --</option>
                        @foreach($propiedades as $propiedad)
                            <option value="{{ $propiedad->id }}"
                                {{ old('propiedad_id', request('propiedad_id')) == $propiedad->id ? 'selected' : '' }}>
                                {{ $propiedad->nombre }} — {{ $propiedad->ciudad }}
                            </option>
                        @endforeach
                    </select>
                    @error('propiedad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nombre / Identificador *</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}" placeholder="Ej: Apto 101, Local A" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Número</label>
                    <input type="text" name="numero" class="form-control" value="{{ old('numero') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tipo *</label>
                    <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                        @foreach(['apartamento','casa','habitacion','local','oficina','bodega','otro'] as $tipo)
                            <option value="{{ $tipo }}" {{ old('tipo') == $tipo ? 'selected' : '' }}>
                                {{ ucfirst($tipo) }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Piso</label>
                    <input type="number" name="piso" class="form-control" value="{{ old('piso') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Área (m²)</label>
                    <input type="number" name="area" class="form-control" step="0.01" min="0"
                           value="{{ old('area') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Habitaciones *</label>
                    <input type="number" name="habitaciones" class="form-control" min="0"
                           value="{{ old('habitaciones', 1) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Baños *</label>
                    <input type="number" name="banos" class="form-control" min="0"
                           value="{{ old('banos', 1) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Precio de Renta *</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="precio_renta" class="form-control @error('precio_renta') is-invalid @enderror"
                               step="0.01" min="0" value="{{ old('precio_renta') }}" required>
                        @error('precio_renta') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado *</label>
                    <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                        @foreach(['disponible','ocupada','en_mantenimiento','inactiva'] as $estado)
                            <option value="{{ $estado }}" {{ old('estado', 'disponible') == $estado ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $estado)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tiene_parqueo" value="1"
                               id="parqueo" {{ old('tiene_parqueo') ? 'checked' : '' }}>
                        <label class="form-check-label" for="parqueo">Incluye parqueo</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Guardar Unidad
                </button>
                <a href="{{ route('unidad.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection