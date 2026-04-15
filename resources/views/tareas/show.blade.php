@extends('welcome')
@section('title', 'Detalle Tarea')

@section('content')

<div class="d-flex justify-content-between mb-4">
<h4>{{ $tarea->titulo }}</h4>

<div class="d-flex gap-2">
<a href="{{ route('tareas.edit',$tarea) }}" class="btn btn-warning">Editar</a>
<a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary">Volver</a>
</div>
</div>

<div class="card">
<div class="card-body">

<table class="table table-borderless">

<tr>
<th>Unidad:</th>
<td>{{ $tarea->unidad->nombre ?? 'Unidad #'.$tarea->unidad_id }}</td>
</tr>

<tr>
<th>Solicitud:</th>
<td>{{ $tarea->solicitudInquilino->asunto ?? 'N/A' }}</td>
</tr>

<tr>
<th>Prioridad:</th>
<td>{{ ucfirst($tarea->prioridad) }}</td>
</tr>

<tr>
<th>Estado:</th>
<td>{{ ucfirst(str_replace('_',' ',$tarea->estado)) }}</td>
</tr>

<tr>
<th>Fecha límite:</th>
<td>{{ $tarea->fecha_limite }}</td>
</tr>

<tr>
<th>Descripción:</th>
<td>{{ $tarea->descripcion }}</td>
</tr>

</table>

</div>
</div>

@endsection