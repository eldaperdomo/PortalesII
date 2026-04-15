@extends('welcome')
@section('title', 'Editar Unidad')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar Unidad — {{ $unidad->nombre }}</h4>
    <a href="{{ route('unidad.index', $unidad) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('unidad.update', $unidad) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Propiedad *</label>
                    <select name="propiedad_id" class="form-select @error('propiedad_id') is-invalid @enderror" required>
                        @foreach($propiedades as $propiedad)
                            <option value="{{ $propiedad->id }}"
                                {{ old('propiedad_id', $unidad->propiedad_id) == $propiedad->id ? 'selected' : '' }}>
                                {{ $propiedad->nombre }} — {{ $propiedad->ciudad }}
                            </option>
                        @endforeach
                    </select>
                    @error('propiedad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nombre / Identificador *</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $unidad->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Número</label>
                    <input type="text" name="numero" class="form-control"
                           value="{{ old('numero', $unidad->numero) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tipo *</label>
                    <select name="tipo" class="form-select" required>
                        @foreach(['apartamento','casa','habitacion','local','oficina','bodega','otro'] as $tipo)
                            <option value="{{ $tipo }}"
                                {{ old('tipo', $unidad->tipo) == $tipo ? 'selected' : '' }}>
                                {{ ucfirst($tipo) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Piso</label>
                    <input type="number" name="piso" class="form-control"
                           value="{{ old('piso', $unidad->piso) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Área (m²)</label>
                    <input type="number" name="area" class="form-control" step="0.01" min="0"
                           value="{{ old('area', $unidad->area) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Habitaciones *</label>
                    <input type="number" name="habitaciones" class="form-control" min="0"
                           value="{{ old('habitaciones', $unidad->habitaciones) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Baños *</label>
                    <input type="number" name="banos" class="form-control" min="0"
                           value="{{ old('banos', $unidad->banos) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Precio de Renta *</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="precio_renta" class="form-control" step="0.01" min="0"
                               value="{{ old('precio_renta', $unidad->precio_renta) }}" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado *</label>
                    <select name="estado" class="form-select" required>
                        @foreach(['disponible','ocupada','en_mantenimiento','inactiva'] as $estado)
                            <option value="{{ $estado }}"
                                {{ old('estado', $unidad->estado) == $estado ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $estado)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tiene_parqueo" value="1"
                               id="parqueo" {{ old('tiene_parqueo', $unidad->tiene_parqueo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="parqueo">Incluye parqueo</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $unidad->descripcion) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Actualizar Unidad
                </button>
                <a href="{{ route('unidad.index', $unidad) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection