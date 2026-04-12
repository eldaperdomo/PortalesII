@extends('layouts.app')
@section('title', 'Editar Inquilino')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar — {{ $inquilino->nombre }}</h4>
    <a href="{{ route('inquilinos.show', $inquilino) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inquilinos.update', $inquilino) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre completo *</label>
                    <input type="text" name="nombre" maxlength="150"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $inquilino->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" maxlength="20" class="form-control"
                           value="{{ old('telefono', $inquilino->telefono) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" maxlength="100"
                           class="form-control @error('correo') is-invalid @enderror"
                           value="{{ old('correo', $inquilino->correo) }}">
                    @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">URL Foto</label>
                    <input type="text" name="foto_url" maxlength="255" class="form-control"
                           value="{{ old('foto_url', $inquilino->foto_url) }}">
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo" value="1"
                               id="activo" {{ old('activo', $inquilino->activo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Inquilino activo</label>
                    </div>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Actualizar
                </button>
                <a href="{{ route('inquilinos.show', $inquilino) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection