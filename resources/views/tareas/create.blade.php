@extends('welcome')
@section('title', 'Nueva Tarea')

@section('content')

<div class="d-flex justify-content-between mb-4">
<h4>Nueva Tarea</h4>
<a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary">Volver</a>
</div>

@if($errors->any())
<div class="alert alert-danger">
<ul>
@foreach($errors->all() as $e)
<li>{{ $e }}</li>
@endforeach
</ul>
</div>
@endif

<div class="card">
<div class="card-body">

<form method="POST" action="{{ route('tareas.store') }}">
@csrf

<div class="row g-3">

<div class="col-md-6">
<label>Unidad</label>
<select name="unidad_id" class="form-select">
@foreach($unidades as $u)
<option value="{{ $u->id }}">{{ $u->nombre ?? 'Unidad #'.$u->id }}</option>
@endforeach
</select>
</div>

<div class="col-md-6">
<label>Solicitud (opcional)</label>
<select name="solicitud_inquilino_id" class="form-select">
<option value="">-- Ninguna --</option>
@foreach($solicitudes as $s)
<option value="{{ $s->id }}">{{ $s->asunto }}</option>
@endforeach
</select>
</div>

<div class="col-md-6">
<label>Título</label>
<input type="text" name="titulo" class="form-control">
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
<textarea name="descripcion" class="form-control"></textarea>
</div>

<div class="col-md-6">
<label>Fecha límite</label>
<input type="date" name="fecha_limite" class="form-control">
</div>

</div>

<div class="mt-3">
<button class="btn btn-primary">Guardar</button>
</div>

</form>

</div>
</div>

@endsection