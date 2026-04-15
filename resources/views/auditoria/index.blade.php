@extends('welcome')
@section('title', 'Auditoría')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-clock-history me-2"></i>Auditoría</h4>
</div>

{{-- 🔍 BUSCADOR --}}
<div class="card mb-3">
    <div class="card-body">

        <form method="GET" class="row g-2">

            <div class="col-md-8">
                <input type="text" name="search" class="form-control"
                    placeholder="Buscar por usuario, acción, tabla..."
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>

                <a href="{{ route('auditoria.index') }}" class="btn btn-outline-secondary">
                    Limpiar
                </a>
            </div>

        </form>

    </div>
</div>

{{-- 📋 TABLA --}}
<div class="card">
    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Tabla</th>
                    <th>ID</th>
                    <th>IP</th>
                    <th>Fecha</th>
                </tr>
            </thead>

            <tbody>
                @forelse($logs as $log)
                <tr>

                    {{-- USUARIO --}}
                    <td>
                        {{ $log->usuario?->nombre ?? 'Sistema' }}
                    </td>

                    {{-- ACCIÓN --}}
                    <td>
                        <span class="badge 
                            @if($log->accion == 'CREATE') bg-success
                            @elseif($log->accion == 'UPDATE') bg-warning text-dark
                            @elseif($log->accion == 'DELETE') bg-danger
                            @else bg-secondary
                            @endif">
                            {{ $log->accion }}
                        </span>
                    </td>

                    {{-- TABLA --}}
                    <td>{{ $log->tabla }}</td>

                    {{-- ID --}}
                    <td>{{ $log->registro_id }}</td>

                    {{-- IP --}}
                    <td>{{ $log->ip }}</td>

                    {{-- FECHA --}}
                   <td>{{ \Carbon\Carbon::parse($log->fecha)->format('d/m/Y h:i A') }}</td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        No hay registros
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

{{-- 🔥 PAGINACIÓN PRO --}}
<div class="card mt-3">
    <div class="card-body d-flex justify-content-between align-items-center">

        <div class="text-muted small">
            Mostrando 
            {{ $logs->firstItem() ?? 0 }} 
            a 
            {{ $logs->lastItem() ?? 0 }} 
            de 
            {{ $logs->total() }} registros
        </div>

        <div class="d-flex justify-content-end">
            {{ $logs->links('pagination::simple-bootstrap-5') }}
        </div>

    </div>
</div>

@endsection
