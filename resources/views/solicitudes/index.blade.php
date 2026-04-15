@extends('welcome')
@section('title', 'Solicitudes')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-tools me-2"></i>Solicitudes</h4>

    <a href="{{ route('solicitudes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nueva Solicitud
    </a>
</div>

{{-- 🔍 FILTROS --}}
<div class="card mb-3">
<div class="card-body">

<form method="GET" class="row g-2">

<input type="hidden" name="incluir_inactivos" value="{{ request('incluir_inactivos') }}">

<div class="col-md-5">
<input type="text" name="search" class="form-control"
placeholder="Buscar..." value="{{ request('search') }}">
</div>


<div class="col-md-3">
<select name="estado" class="form-select">
    <option value="">-- Todos los estados --</option>
    <option value="abierta" {{ request('estado')=='abierta'?'selected':'' }}>Abierta</option>
    <option value="en_revision" {{ request('estado')=='en_revision'?'selected':'' }}>En revisión</option>
    <option value="en_proceso" {{ request('estado')=='en_proceso'?'selected':'' }}>En proceso</option>
    <option value="resuelta" {{ request('estado')=='resuelta'?'selected':'' }}>Resuelta</option>
    <option value="cerrada" {{ request('estado')=='cerrada'?'selected':'' }}>Cerrada</option>
</select>
</div>

<div class="col-md-4 d-flex gap-2">
<button class="btn btn-primary">
<i class="bi bi-search"></i>
</button>

<a href="{{ route('solicitudes.index') }}" class="btn btn-outline-secondary">
Limpiar
</a>
</div>

</form>

</div>
</div>

{{-- 🔥 ACTIVOS / TODOS --}}
<div class="mb-3 d-flex gap-2">

    <a href="{{ route('solicitudes.index', array_merge(request()->query(), ['incluir_inactivos' => null])) }}"
       class="btn btn-sm {{ !request()->has('incluir_inactivos') ? 'btn-primary' : 'btn-outline-primary' }}">
        Activos
    </a>

    @if(auth()->user()->esAdmin())
    <a href="{{ route('solicitudes.index', array_merge(request()->query(), ['incluir_inactivos' => 'true'])) }}"
       class="btn btn-sm {{ request('incluir_inactivos') == 'true' ? 'btn-dark' : 'btn-outline-dark' }}">
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
<th>Asunto</th>
<th>Unidad</th>
<th>Inquilino</th>
<th>Tipo</th>
<th>Estado</th>
<th>Activo</th>
<th class="text-end">Acciones</th>
</tr>
</thead>

<tbody>
@forelse($solicitudes as $s)
<tr>

<td>{{ $s->asunto }}</td>

<td>{{ $s->unidad->nombre ?? 'Unidad #'.$s->unidad_id }}</td>

<td>{{ $s->inquilino->nombre ?? 'N/A' }}</td>
<td>{{ $s->tipo }}</td>
<td>
<span class="badge bg-{{ 
$s->estado == 'resuelta' ? 'success' : 
($s->estado == 'abierta' ? 'primary' : 'warning') }}">
{{ ucfirst(str_replace('_',' ',$s->estado)) }}
</span>
</td>

<td>
@if($s->activo)
<span class="badge bg-success">Activo</span>
@else
<span class="badge bg-secondary">Inactivo</span>
@endif
</td>

<td class="text-end">

<a href="{{ route('solicitudes.show',$s) }}" class="btn btn-sm btn-outline-info">
<i class="bi bi-eye"></i>
</a>

<a href="{{ route('solicitudes.edit',$s) }}" class="btn btn-sm btn-outline-warning">
<i class="bi bi-pencil"></i>
</a>

@if($s->activo)
<form action="{{ route('solicitudes.destroy',$s) }}" method="POST" class="d-inline">
@csrf @method('DELETE')
<button class="btn btn-sm btn-outline-danger">
<i class="bi bi-x"></i>
</button>
</form>
@else
<form action="{{ route('solicitudes.activar',$s) }}" method="POST" class="d-inline">
@csrf @method('PUT')
<button class="btn btn-sm btn-success">
<i class="bi bi-check"></i>
</button>
</form>
@endif

</td>

</tr>
@empty
<tr>
<td colspan="6" class="text-center py-4 text-muted">
No hay solicitudes
</td>
</tr>
@endforelse
</tbody>

</table>

</div>
</div>

<div class="mt-3">
{{ $solicitudes->links() }}
</div>

@endsection