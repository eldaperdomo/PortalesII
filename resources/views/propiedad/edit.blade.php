@extends('welcome')
@section('title', 'Editar Propiedad')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar — {{ $propiedad->nombre }}</h4>
    <a href="{{ route('propiedad.show', $propiedad) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('propiedad.update', $propiedad) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" maxlength="150"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $propiedad->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tipo *</label>
                    <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                        <option value="casa"     {{ old('tipo', $propiedad->tipo) == 'casa'     ? 'selected' : '' }}>Casa</option>
                        <option value="edificio" {{ old('tipo', $propiedad->tipo) == 'edificio' ? 'selected' : '' }}>Edificio</option>
                    </select>
                    @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" maxlength="255" class="form-control"
                           value="{{ old('direccion', $propiedad->direccion) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $propiedad->descripcion) }}</textarea>
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo" value="1"
                               id="activo" {{ old('activo', $propiedad->activo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Propiedad activa</label>
                    </div>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Actualizar
                </button>
                <a href="{{ route('propiedad.show', $propiedad) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection