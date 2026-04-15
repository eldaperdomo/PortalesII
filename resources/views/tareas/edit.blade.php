@extends('welcome')
@section('title', 'Editar Tarea')

@section('content')

<div class="d-flex justify-content-between mb-4">
<h4>Editar Tarea</h4>
<a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary">Volver</a>
</div>

<div class="card">
<div class="card-body">

<form method="POST" action="{{ route('tareas.update',$tarea) }}">
@csrf @method('PUT')

<div class="row g-3">

<div class="col-md-6">
<label>Unidad</label>
<select name="unidad_id" class="form-select">
@foreach($unidades as $u)
<option value="{{ $u->id }}" {{ $tarea->unidad_id==$u->id?'selected':'' }}>
{{ $u->nombre ?? 'Unidad #'.$u->id }}
</option>
@endforeach
</select>
</div>

<div class="col-md-6">
<label>Estado</label>
<select name="estado" class="form-select">
<option value="pendiente">Pendiente</option>
<option value="en_proceso">En proceso</option>
<option value="completada">Completada</option>
<option value="cancelada">Cancelada</option>
</select>
</div>

<div class="col-md-6">
<label>Título</label>
<input type="text" name="titulo" class="form-control"
value="{{ $tarea->titulo }}">
</div>

<div class="col-md-6">
<label>Prioridad</label>
<select name="prioridad" class="form-select">
<option value="media">Media</option>
<option value="alta">Alta</option>
<option value="urgente">Urgente</option>
</select>
</div>

<div class="col-md-12">
<label>Descripción</label>
<textarea name="descripcion" class="form-control">{{ $tarea->descripcion }}</textarea>
</div>

</div>

<div class="mt-3">
<button class="btn btn-warning">Actualizar</button>
</div>

</form>

</div>
</div>

@endsection