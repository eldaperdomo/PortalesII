@extends('welcome')
@section('title', 'Tareas de Mantenimiento')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-tools me-2"></i>Tareas</h4>

    <a href="{{ route('tareas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nueva Tarea
    </a>
</div>

{{-- 🔍 FILTROS --}}
<div class="card mb-3">
<div class="card-body">

<form method="GET" class="row g-2">

<input type="hidden" name="incluir_inactivos" value="{{ request('incluir_inactivos') }}">

<div class="col-md-4">
<input type="text" name="search" class="form-control"
placeholder="Buscar tarea..." value="{{ request('search') }}">
</div>

<div class="col-md-2">
<select name="estado" class="form-select">
<option value="">Estado</option>
<option value="pendiente">Pendiente</option>
<option value="en_proceso">En proceso</option>
<option value="completada">Completada</option>
<option value="cancelada">Cancelada</option>
</select>
</div>

<div class="col-md-2">
<select name="prioridad" class="form-select">
<option value="">Prioridad</option>
<option value="baja">Baja</option>
<option value="media">Media</option>
<option value="alta">Alta</option>
<option value="urgente">Urgente</option>
</select>
</div>

<div class="col-md-4 d-flex gap-2">
<button class="btn btn-primary"><i class="bi bi-search"></i></button>
<a href="{{ route('tareas.index') }}" class="btn btn-outline-secondary">Limpiar</a>
</div>

</form>

</div>
</div>

{{-- 🔥 ACTIVOS / TODOS --}}
<div class="mb-3 d-flex gap-2">

<a href="{{ route('tareas.index', array_merge(request()->query(), ['incluir_inactivos'=>null])) }}"
class="btn btn-sm {{ !request()->has('incluir_inactivos') ? 'btn-primary' : 'btn-outline-primary' }}">
Activos
</a>

@if(auth()->user()->esAdmin())
<a href="{{ route('tareas.index', array_merge(request()->query(), ['incluir_inactivos'=>'true'])) }}"
class="btn btn-sm {{ request('incluir_inactivos')=='true' ? 'btn-dark' : 'btn-outline-dark' }}">
Todos
</a>
@endif

</div>

{{-- 📋 TABLA --}}
<div class="card">
<div class="card-body p-0">

<table class="table table-hover align-middle mb-0">
<thead class="table-light">
<tr>
<th>Tarea</th>
<th>Unidad</th>
<th>Prioridad</th>
<th>Estado</th>
<th>Fecha límite</th>
<th>Activo</th>
<th class="text-end">Acciones</th>
</tr>
</thead>

<tbody>
@forelse($tareas as $t)
<tr>

<td>
<strong>{{ $t->titulo }}</strong><br>
<small class="text-muted">{{ $t->descripcion }}</small>
</td>

<td>{{ $t->unidad->nombre ?? 'Unidad #'.$t->unidad_id }}</td>

<td>
<span class="badge bg-{{ 
$t->prioridad == 'urgente' ? 'danger' :
($t->prioridad == 'alta' ? 'warning' :
($t->prioridad == 'media' ? 'info' : 'secondary')) }}">
{{ ucfirst($t->prioridad) }}
</span>
</td>

<td>
<span class="badge bg-{{ 
$t->estado == 'completada' ? 'success' :
($t->estado == 'pendiente' ? 'secondary' :
($t->estado == 'en_proceso' ? 'primary' : 'dark')) }}">
{{ ucfirst(str_replace('_',' ',$t->estado)) }}
</span>
</td>

<td>
{{ $t->fecha_limite ? \Carbon\Carbon::parse($t->fecha_limite)->format('d/m/Y') : 'N/A' }}
</td>

<td>
@if($t->activo)
<span class="badge bg-success">Activo</span>
@else
<span class="badge bg-secondary">Inactivo</span>
@endif
</td>

<td class="text-end">

<a href="{{ route('tareas.show',$t) }}" class="btn btn-sm btn-outline-info">
<i class="bi bi-eye"></i>
</a>

<a href="{{ route('tareas.edit',$t) }}" class="btn btn-sm btn-outline-warning">
<i class="bi bi-pencil"></i>
</a>

@if(auth()->user()->esAdmin())

@if($t->activo)
<form action="{{ route('tareas.destroy',$t) }}" method="POST" class="d-inline">
@csrf @method('DELETE')
<button class="btn btn-sm btn-outline-danger">
<i class="bi bi-x"></i>
</button>
</form>
@endif

@if(!$t->activo)
<form action="{{ route('tareas.activar',$t) }}" method="POST" class="d-inline">
@csrf @method('PUT')
<button class="btn btn-sm btn-success">
<i class="bi bi-check"></i>
</button>
</form>
@endif

@endif

</td>

</tr>
@empty
<tr>
<td colspan="7" class="text-center py-4 text-muted">No hay tareas</td>
</tr>
@endforelse
</tbody>

</table>

</div>
</div>

<div class="mt-3">
{{ $tareas->links() }}
</div>

@endsection