@extends('welcome')
@section('title', 'Unidad '.$unidad->nombre)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>
        <i class="bi bi-door-open me-2"></i>
        {{ $unidad->nombre }}
    </h4>

    <div class="d-flex gap-2">
        <a href="{{ route('unidad.edit', $unidad) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>

        <a href="{{ route('unidad.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- INFO GENERAL --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>Información General</strong>
            </div>

            <div class="card-body">

                <table class="table table-sm table-borderless">

                    <tr>
                        <th>Propiedad:</th>
                        <td>{{ $unidad->propiedad->nombre ?? 'N/A' }}</td>
                    </tr>

                    <tr>
                        <th>Número:</th>
                        <td>{{ $unidad->numero ?? 'N/A' }}</td>
                    </tr>

                    <tr>
                        <th>Tipo:</th>
                        <td>{{ ucfirst($unidad->tipo) }}</td>
                    </tr>

                    <tr>
                        <th>Piso:</th>
                        <td>{{ $unidad->piso ?? 'N/A' }}</td>
                    </tr>

                    <tr>
                        <th>Área:</th>
                        <td>{{ $unidad->area ? $unidad->area.' m²' : 'N/A' }}</td>
                    </tr>

                </table>

            </div>
        </div>
    </div>

    {{-- DETALLES --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <strong>Detalles</strong>
            </div>

            <div class="card-body">

                <table class="table table-sm table-borderless">

                    <tr>
                        <th>Habitaciones:</th>
                        <td>{{ $unidad->habitaciones }}</td>
                    </tr>

                    <tr>
                        <th>Baños:</th>
                        <td>{{ $unidad->banos }}</td>
                    </tr>

                    <tr>
                        <th>Parqueo:</th>
                        <td>
                            {{ $unidad->tiene_parqueo ? 'Sí' : 'No' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Precio:</th>
                        <td>
                            <strong>L {{ number_format($unidad->precio_renta,2) }}</strong>
                        </td>
                    </tr>

                    <tr>
                        <th>Estado:</th>
                        <td>
                            <span class="badge bg-{{ 
                                $unidad->estado == 'disponible' ? 'success' : 
                                ($unidad->estado == 'ocupada' ? 'danger' : 'warning') }}">
                                {{ ucfirst(str_replace('_',' ',$unidad->estado)) }}
                            </span>
                        </td>
                    </tr>

                </table>

            </div>
        </div>
    </div>

    {{-- DESCRIPCIÓN --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Descripción</strong>
            </div>

            <div class="card-body">
                {{ $unidad->descripcion ?? 'Sin descripción' }}
            </div>
        </div>
    </div>

</div>

@endsection