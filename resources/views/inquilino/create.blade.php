@extends('welcome')
@section('title', 'Nuevo Inquilino')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-person-plus me-2"></i>Nuevo Inquilino</h4>
    <a href="{{ route('inquilino.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inquilino.store') }}" method="POST">
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre completo *</label>
                    <input type="text" name="nombre" maxlength="150"
                           class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre') }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" maxlength="20" class="form-control"
                           value="{{ old('telefono') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Correo electrónico</label>
                    <input type="email" name="correo" maxlength="100"
                           class="form-control @error('correo') is-invalid @enderror"
                           value="{{ old('correo') }}">
                    @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">URL Foto</label>
                    <input type="text" name="foto_url" maxlength="255" class="form-control"
                           value="{{ old('foto_url') }}" placeholder="https://...">
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="activo" value="1"
                               id="activo" {{ old('activo', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Inquilino activo</label>
                    </div>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i>Guardar Inquilino
                </button>
                <a href="{{ route('inquilino.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection