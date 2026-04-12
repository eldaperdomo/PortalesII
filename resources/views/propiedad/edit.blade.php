@extends('welcome')
@section('title', 'Editar Propiedad')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar Propiedad</h4>
    <a href="{{ route('propiedad.show', $propiedad) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('propiedad.update', $propiedad) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $propiedad->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tipo *</label>
                    <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                        @foreach(['casa','apartamento','local_comercial','edificio','otro'] as $tipo)
                            <option value="{{ $tipo }}"
                                {{ old('tipo', $propiedad->tipo) == $tipo ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $tipo)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label">Dirección *</label>
                    <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror"
                           value="{{ old('direccion', $propiedad->direccion) }}" required>
                    @error('direccion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ciudad *</label>
                    <input type="text" name="ciudad" class="form-control @error('ciudad') is-invalid @enderror"
                           value="{{ old('ciudad', $propiedad->ciudad) }}" required>
                    @error('ciudad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Departamento</label>
                    <input type="text" name="departamento" class="form-control"
                           value="{{ old('departamento', $propiedad->departamento) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Código Postal</label>
                    <input type="text" name="codigo_postal" class="form-control"
                           value="{{ old('codigo_postal', $propiedad->codigo_postal) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Área Total (m²)</label>
                    <input type="number" name="area_total" class="form-control" step="0.01" min="0"
                           value="{{ old('area_total', $propiedad->area_total) }}">
                </div>

                <div class="col-md-8">
                    <label class="form-label">Imagen</label>
                    @if($propiedad->imagen)
                        <div class="mb-2">
                            <img src="{{ Storage::url($propiedad->imagen) }}" alt="Imagen actual"
                                 class="img-thumbnail" style="height:80px;">
                            <small class="text-muted ms-2">Imagen actual</small>
                        </div>
                    @endif
                    <input type="file" name="imagen" class="form-control" accept="image/*">
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activa" value="1"
                               id="activa" {{ old('activa', $propiedad->activa) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activa">Propiedad activa</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $propiedad->descripcion) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Actualizar Propiedad
                </button>
                <a href="{{ route('propiedad.show', $propiedad) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection