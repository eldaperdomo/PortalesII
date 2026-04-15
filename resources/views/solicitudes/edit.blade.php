@extends('welcome')
@section('title', 'Editar Solicitud')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-pencil me-2"></i>Editar Solicitud</h4>

    <a href="{{ route('solicitudes.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row g-4">

    {{-- 🔥 LADO IZQUIERDO (EVIDENCIA) --}}
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

                <label class="form-label mt-2">Nueva evidencia</label>
                <input type="file" name="evidencia" form="formEdit" class="form-control">

            </div>
        </div>
    </div>

    {{-- 🔥 LADO DERECHO --}}
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <strong>Información de la Solicitud</strong>
            </div>

            <div class="card-body">

                <form id="formEdit"
                      action="{{ route('solicitudes.update',$solicitude) }}"
                      method="POST"
                      enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <label>Unidad</label>
                        <input class="form-control"
                               value="{{ $solicitude->unidad->nombre ?? 'Unidad #'.$solicitude->unidad_id }}"
                               readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Inquilino</label>
                        <input class="form-control"
                               value="{{ $solicitude->inquilino->nombre ?? 'N/A' }}"
                               readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="reparacion" {{ $solicitude->tipo=='reparacion'?'selected':'' }}>Reparación</option>
                            <option value="mantenimiento" {{ $solicitude->tipo=='mantenimiento'?'selected':'' }}>Mantenimiento</option>
                            <option value="queja" {{ $solicitude->tipo=='queja'?'selected':'' }}>Queja</option>
                            <option value="sugerencia" {{ $solicitude->tipo=='sugerencia'?'selected':'' }}>Sugerencia</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>Prioridad</label>
                        <select name="prioridad" class="form-select">
                            <option value="baja" {{ $solicitude->prioridad=='baja'?'selected':'' }}>Baja</option>
                            <option value="media" {{ $solicitude->prioridad=='media'?'selected':'' }}>Media</option>
                            <option value="alta" {{ $solicitude->prioridad=='alta'?'selected':'' }}>Alta</option>
                            <option value="urgente" {{ $solicitude->prioridad=='urgente'?'selected':'' }}>Urgente</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label>Asunto</label>
                        <input type="text" name="asunto"
                               class="form-control"
                               value="{{ $solicitude->asunto }}">
                    </div>

                    <div class="col-md-12">
                        <label>Descripción</label>
                        <textarea name="descripcion"
                                  class="form-control">{{ $solicitude->descripcion }}</textarea>
                    </div>

                    <div class="col-md-6">
                    <label>Estado</label>
                    <select name="estado" class="form-select">

                        <option value="abierta"
                            {{ $solicitude->estado=='abierta'?'selected':'' }}>
                            Abierta
                        </option>

                        <option value="en_revision"
                            {{ $solicitude->estado=='en_revision'?'selected':'' }}>
                            En revisión
                        </option>

                        <option value="en_proceso"
                            {{ $solicitude->estado=='en_proceso'?'selected':'' }}>
                            En proceso
                        </option>

                        <option value="resuelta"
                            {{ $solicitude->estado=='resuelta'?'selected':'' }}>
                            Resuelta
                        </option>

                        <option value="cerrada"
                            {{ $solicitude->estado=='cerrada'?'selected':'' }}>
                            Cerrada
                        </option>

                    </select>
                </div>

                    <div class="col-md-6">
                        <label>Respuesta</label>
                        <input type="text" name="respuesta"
                               class="form-control"
                               value="{{ $solicitude->respuesta }}">
                    </div>

                </div>

                <div class="mt-4">
                    <button class="btn btn-warning">
                        <i class="bi bi-save"></i> Actualizar
                    </button>
                </div>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection