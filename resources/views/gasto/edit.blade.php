@extends('welcome')
@section('title', 'Editar Gasto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar Gasto</h4>
    <a href="{{ route('gasto.show', $gasto) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Volver
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('gasto.update', $gasto) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Propiedad *</label>
                    <select name="propiedad_id" id="propiedad_id" class="form-select @error('propiedad_id') is-invalid @enderror" required>
                        @foreach($propiedades as $propiedad)
                            <option value="{{ $propiedad->id }}"
                                {{ old('propiedad_id', $gasto->propiedad_id) == $propiedad->id ? 'selected' : '' }}>
                                {{ $propiedad->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('propiedad_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Unidad (opcional)</label>
                    <select name="unidad_id" id="unidad_id" class="form-select">
                        <option value="">-- General --</option>
                        @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id }}"
                                    data-propiedad="{{ $unidad->propiedad_id }}"
                                {{ old('unidad_id', $gasto->unidad_id) == $unidad->id ? 'selected' : '' }}>
                                {{ $unidad->nombre }} ({{ $unidad->propiedad->nombre }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">Concepto *</label>
                    <input type="text" name="concepto" class="form-control @error('concepto') is-invalid @enderror"
                           value="{{ old('concepto', $gasto->concepto) }}" required>
                    @error('concepto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Categoría *</label>
                    <select name="categoria" class="form-select" required>
                        @foreach(['mantenimiento','reparacion','impuesto','seguro','servicios','administracion','limpieza','otro'] as $cat)
                            <option value="{{ $cat }}"
                                {{ old('categoria', $gasto->categoria) == $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Monto *</label>
                    <div class="input-group">
                        <span class="input-group-text">L</span>
                        <input type="number" name="monto" class="form-control" step="0.01" min="0"
                               value="{{ old('monto', $gasto->monto) }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Fecha *</label>
                    <input type="date" name="fecha" class="form-control"
                           value="{{ old('fecha', $gasto->fecha->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Estado *</label>
                    <select name="estado" class="form-select" required>
                        @foreach(['pendiente','pagado','cancelado'] as $e)
                            <option value="{{ $e }}"
                                {{ old('estado', $gasto->estado) == $e ? 'selected' : '' }}>
                                {{ ucfirst($e) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Proveedor</label>
                    <input type="text" name="proveedor" class="form-control"
                           value="{{ old('proveedor', $gasto->proveedor) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">N° Comprobante</label>
                    <input type="text" name="comprobante" class="form-control"
                           value="{{ old('comprobante', $gasto->comprobante) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nuevo Archivo Adjunto</label>
                    @if($gasto->archivo_adjunto)
                        <div class="mb-1">
                            <a href="{{ Storage::url($gasto->archivo_adjunto) }}" target="_blank"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-paperclip me-1"></i>Ver adjunto actual
                            </a>
                        </div>
                    @endif
                    <input type="file" name="archivo_adjunto" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $gasto->descripcion) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i>Actualizar Gasto
                </button>
                <a href="{{ route('gasto.show', $gasto) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('propiedad_id').addEventListener('change', function () {
    const propiedadId = this.value;
    const unidadSelect = document.getElementById('unidad_id');
    unidadSelect.querySelectorAll('option[data-propiedad]').forEach(opt => {
        opt.style.display = (!propiedadId || opt.dataset.propiedad === propiedadId) ? '' : 'none';
    });
    unidadSelect.value = '';
});
</script>
@endpush