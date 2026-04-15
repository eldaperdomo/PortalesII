@extends('welcome')
@section('title','Detalle Solicitud')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-tools me-2"></i>{{ $solicitude->asunto }}</h4>

    <div class="d-flex gap-2">
        <a href="{{ route('solicitudes.edit',$solicitude) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>

        <a href="{{ route('solicitudes.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- 🔥 EVIDENCIA --}}
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">

                @if($solicitude->evidencia_url)
                    <img src="{{ asset('storage/'.$solicitude->evidencia_url) }}"
                         class="img-fluid rounded mb-3"
                         style="max-height: 200px;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                         style="height:200px;">
                        <i class="bi bi-image fs-1 text-muted"></i>
                    </div>
                @endif

                <h6 class="mt-2">Evidencia</h6>

            </div>
        </div>
    </div>

    {{-- 🔥 INFO --}}
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <strong>Información</strong>
            </div>

            <div class="card-body">

                <table class="table table-borderless table-sm">

                    <tr>
                        <th>Unidad:</th>
                        <td>{{ $solicitude->unidad->nombre ?? 'Unidad #'.$solicitude->unidad_id }}</td>
                    </tr>

                    <tr>
                        <th>Inquilino:</th>
                        <td>{{ $solicitude->inquilino->nombre ?? 'N/A' }}</td>
                    </tr>

                    <tr>
                        <th>Tipo:</th>
                        <td>{{ ucfirst($solicitude->tipo) }}</td>
                    </tr>

                    <tr>
                        <th>Prioridad:</th>
                        <td>
                            <span class="badge bg-warning">
                                {{ ucfirst($solicitude->prioridad) }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Estado:</th>
                        <td>
                            <span class="badge bg-success">
                                {{ ucfirst(str_replace('_',' ',$solicitude->estado)) }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Descripción:</th>
                        <td>{{ $solicitude->descripcion }}</td>
                    </tr>

                    @if($solicitude->respuesta)
                    <tr>
                        <th>Respuesta:</th>
                        <td>{{ $solicitude->respuesta }}</td>
                    </tr>
                    @endif

                </table>

            </div>
        </div>

    </div>

</div>

@endsection