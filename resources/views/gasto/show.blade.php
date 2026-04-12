@extends('welcome')
@section('title', 'Gasto — '.$gasto->concepto)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-receipt me-2"></i>{{ $gasto->concepto }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('gasto.edit', $gasto) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="{{ route('gasto.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4 justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="35%">Propiedad</th>
                        <td>
                            <a href="{{ route('propiedad.show', $gasto->propiedad) }}">
                                {{ $gasto->propiedad->nombre }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Unidad</th>
                        <td>
                            @if($gasto->unidad)
                                <a href="{{ route('unidad.show', $gasto->unidad) }}">{{ $gasto->unidad->nombre }}</a>
                            @else
                                <span class="text-muted">General (toda la propiedad)</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Categoría</th><td><span class="badge bg-light text-dark border">{{ ucfirst($gasto->categoria) }}</span></td></tr>
                    <tr><th>Monto</th><td><strong class="fs-5">L {{ number_format($gasto->monto, 2) }}</strong></td></tr>
                    <tr><th>Fecha</th><td>{{ $gasto->fecha->format('d/m/Y') }}</td></tr>
                    <tr><th>Estado</th>
                        <td><span class="badge badge-{{ $gasto->estado }}">{{ ucfirst($gasto->estado) }}</span></td>
                    </tr>
                    <tr><th>Proveedor</th><td>{{ $gasto->proveedor ?? '—' }}</td></tr>
                    <tr><th>Comprobante</th><td>{{ $gasto->comprobante ?? '—' }}</td></tr>
                    @if($gasto->archivo_adjunto)
                    <tr>
                        <th>Adjunto</th>
                        <td>
                            <a href="{{ Storage::url($gasto->archivo_adjunto) }}" target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-paperclip me-1"></i>Ver archivo
                            </a>
                        </td>
                    </tr>
                    @endif
                    @if($gasto->descripcion)
                    <tr>
                        <th>Descripción</th>
                        <td>{{ $gasto->descripcion }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection