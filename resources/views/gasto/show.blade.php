@extends('welcome')
@section('title', 'Detalle Gasto')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0"><i class="bi bi-receipt me-2"></i>Detalle del Gasto</h4>
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
                        <th width="35%">Unidad</th>
                        <td>
                            <a href="{{ route('unidad.show', $gasto->unidad) }}">
                                {{ $gasto->unidad->identificador }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Propiedad</th>
                        <td>
                            <a href="{{ route('propiedad.show', $gasto->unidad->propiedad) }}">
                                {{ $gasto->unidad->propiedad->nombre }}
                            </a>
                        </td>
                    </tr>
                    <tr><th>Tipo</th>
                        <td><span class="badge bg-light text-dark border">{{ ucfirst($gasto->tipo) }}</span></td>
                    </tr>
                    <tr><th>Monto</th>
                        <td><strong class="fs-5">L {{ number_format($gasto->monto, 2) }}</strong></td>
                    </tr>
                    <tr><th>Fecha</th>
                        <td>{{ $gasto->fecha->format('d/m/Y') }}</td>
                    </tr>
                    <tr><th>Descripción</th>
                        <td>{{ $gasto->descripcion ?? '—' }}</td>
                    </tr>
                    @if($gasto->comprobante_url)
                    <tr>
                        <th>Comprobante</th>
                        <td>
                            <a href="{{ $gasto->comprobante_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-link-45deg me-1"></i>Ver comprobante
                            </a>
                        </td>
                    </tr>
                    @endif
                    <tr><th>Activo</th>
                        <td>
                            <span class="badge {{ $gasto->activo ? 'bg-success' : 'bg-secondary' }}">
                                {{ $gasto->activo ? 'Sí' : 'No' }}
                            </span>
                        </td>
                    </tr>
                    <tr><th>Registrado</th>
                        <td>{{ $gasto->creado_en?->format('d/m/Y H:i') ?? '—' }}</td>
                    </tr>
                    @if($gasto->observaciones)
                    <tr>
                        <th>Observaciones</th>
                        <td>{{ $gasto->observaciones }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection