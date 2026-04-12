@extends('welcome')
@section('title', 'Editar Inquilino')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar — {{ $inquilino->nombre_completo }}</h4>
    <a href="{{ route('inquilino.show', $inquilino) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inquilino.update', $inquilino) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">

                <div class="col-12"><h6 class="text-muted border-bottom pb-2">Datos personales</h6></div>

                <div class="col-md-4">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                           value="{{ old('nombre', $inquilino->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Apellido *</label>
                    <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror"
                           value="{{ old('apellido', $inquilino->apellido) }}" required>
                    @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">DNI / Identidad *</label>
                    <input type="text" name="dni" class="form-control @error('dni') is-invalid @enderror"
                           value="{{ old('dni', $inquilino->dni) }}" required>
                    @error('dni') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control"
                           value="{{ old('fecha_nacimiento', $inquilino->fecha_nacimiento?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado Civil</label>
                    <select name="estado_civil" class="form-select">
                        <option value="">-- Seleccionar --</option>
                        @foreach(['soltero','casado','divorciado','viudo','otro'] as $ec)
                            <option value="{{ $ec }}"
                                {{ old('estado_civil', $inquilino->estado_civil) == $ec ? 'selected' : '' }}>
                                {{ ucfirst($ec) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12"><h6 class="text-muted border-bottom pb-2 mt-2">Contacto</h6></div>

                <div class="col-md-4">
                    <label class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $inquilino->email) }}">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control"
                           value="{{ old('telefono', $inquilino->telefono) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Teléfono de Emergencia</label>
                    <input type="text" name="telefono_emergencia" class="form-control"
                           value="{{ old('telefono_emergencia', $inquilino->telefono_emergencia) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contacto de Emergencia</label>
                    <input type="text" name="contacto_emergencia" class="form-control"
                           value="{{ old('contacto_emergencia', $inquilino->contacto_emergencia) }}">
                </div>

                <div class="col-12"><h6 class="text-muted border-bottom pb-2 mt-2">Información Laboral</h6></div>

                <div class="col-md-4">
                    <label class="form-label">Ocupación</label>
                    <input type="text" name="ocupacion" class="form-control"
                           value="{{ old('ocupacion', $inquilino->ocupacion) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Empresa</label>
                    <input type="text" name="empresa" class="form-control"
                           value="{{ old('empresa', $inquilino->empresa) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Ingreso Mensual</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="ingreso_mensual" class="form-control" step="0.01" min="0"
                               value="{{ old('ingreso_mensual', $inquilino->ingreso_mensual) }}">
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $inquilino->observaciones) }}</textarea>
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
                    <i class="bi bi-save me-1"></i>Actualizar Inquilino
                </button>
                <a href="{{ route('inquilino.show', $inquilino) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection