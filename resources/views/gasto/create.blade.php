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
        <form action="{{ route('gasto.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Propiedad *</label>
                    <select name="propiedad_id" id="propiedad_id"
                            class="form-select @error('propiedad_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar propiedad --</option>
                        @foreach($propiedad as $propiedad)
                            <option value="{{ $propiedad->id }}"
                                {{ old('propiedad_id', request('propiedad_id')) == $propiedad->id ? 'selected' : '' }}>
                                {{ $propiedad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('propiedad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Unidad (opcional)</label>
                    <select name="unidad_id" id="unidad_id" class="form-select">
                        <option value="">-- General / toda la propiedad --</option>
                        @foreach($unidad as $unidad)
                            <option value="{{ $unidad->id }}"
                                    data-propiedad="{{ $unidad->propiedad_id }}"
                                {{ old('unidad_id') == $unidad->id ? 'selected' : '' }}>
                                {{ $unidad->nombre }} ({{ $unidad->propiedad->nombre }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Concepto *</label>
                    <input type="text" name="concepto" class="form-control @error('concepto') is-invalid @enderror"
                           value="{{ old('concepto') }}" required>
                    @error('concepto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Categoría *</label>
                    <select name="categoria" class="form-select @error('categoria') is-invalid @enderror" required>
                        @foreach(['mantenimiento','reparacion','impuesto','seguro','servicios','administracion','limpieza','otro'] as $cat)
                            <option value="{{ $cat }}" {{ old('categoria') == $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Monto *</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="monto" class="form-control @error('monto') is-invalid @enderror"
                               step="0.01" min="0" value="{{ old('monto') }}" required>
                        @error('monto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha *</label>
                    <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror"
                           value="{{ old('fecha', date('Y-m-d')) }}" required>
                    @error('fecha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado *</label>
                    <select name="estado" class="form-select" required>
                        @foreach(['pendiente','pagado','cancelado'] as $e)
                            <option value="{{ $e }}" {{ old('estado', 'pendiente') == $e ? 'selected' : '' }}>
                                {{ ucfirst($e) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Proveedor</label>
                    <input type="text" name="proveedor" class="form-control" value="{{ old('proveedor') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">N° Comprobante / Factura</label>
                    <input type="text" name="comprobante" class="form-control" value="{{ old('comprobante') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Archivo Adjunto</label>
                    <input type="file" name="archivo_adjunto" class="form-control"
                           accept=".pdf,.jpg,.jpeg,.png">
                    <small class="text-muted">PDF o imagen, máx. 4MB</small>
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion') }}</textarea>
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

@push('scripts')
<script>
// Filtrar unidades según la propiedad seleccionada
document.getElementById('propiedad_id').addEventListener('change', function () {
    const propiedadId = this.value;
    const unidadSelect = document.getElementById('unidad_id');
    const options = unidadSelect.querySelectorAll('option[data-propiedad]');

    options.forEach(opt => {
        opt.style.display = (!propiedadId || opt.dataset.propiedad === propiedadId) ? '' : 'none';
    });

    unidadSelect.value = '';
});
</script>
@endpush