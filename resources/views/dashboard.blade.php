@extends('welcome')
@section('title', 'Dashboard')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-speedometer2 me-2"></i>Dashboard</h4>
</div>

<div class="row g-3">

    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6 class="text-muted">Propiedades</h6>
            <h3>{{ $totalPropiedades ?? 0 }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6 class="text-muted">Unidades</h6>
            <h3>{{ $totalUnidades ?? 0 }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6 class="text-muted">Inquilinos</h6>
            <h3>{{ $totalInquilinos ?? 0 }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6 class="text-muted">Pagos Pendientes</h6>
            <h3 class="text-danger">{{ $pagosPendientes ?? 0 }}</h3>
        </div>
    </div>

</div>

<div class="row g-3 mt-2">

    <div class="col-md-4">
        <div class="card p-3">
            <h6>Solicitudes abiertas</h6>
            <h3 class="text-warning">{{ $solicitudesAbiertas ?? 0 }}</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h6>Tareas pendientes</h6>
            <h3 class="text-primary">{{ $tareasPendientes ?? 0 }}</h3>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h6>Tareas completadas</h6>
            <h3 class="text-success">{{ $tareasCompletadas ?? 0 }}</h3>
        </div>
    </div>

</div>

<div class="card mt-4">
    <div class="card-header">
        <strong>Últimas solicitudes</strong>
    </div>

    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Asunto</th>
                    <th>Unidad</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimasSolicitudes ?? [] as $s)
                <tr>
                    <td>{{ $s->asunto }}</td>
                    <td>{{ $s->unidad->nombre ?? 'Unidad #'.$s->unidad_id }}</td>
                    <td>
                        <span class="badge bg-info">
                            {{ ucfirst(str_replace('_',' ',$s->estado)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No hay datos
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection