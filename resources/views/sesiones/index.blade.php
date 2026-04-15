
@extends('welcome')
@section('title', 'Sesiones')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-person-lines-fill me-2"></i>Sesiones</h4>
</div>

{{-- 🔍 BUSCADOR --}}
<div class="card mb-3">
    <div class="card-body">

        <form method="GET" class="row g-2">

            <div class="col-md-8">
                <input type="text" name="search" class="form-control"
                    placeholder="Buscar por usuario o navegador..."
                    value="{{ request('search') }}">
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button class="btn btn-primary">Buscar</button>
                <a href="{{ route('sesiones.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            </div>

        </form>

    </div>
</div>

{{-- 📋 TABLA --}}
<div class="card">
    <div class="card-body p-0">

        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Usuario</th>
                    <th>Inicio</th>
                    <th>Cierre</th>
                    <th>Estado</th>
                    <th>Navegador</th>
                </tr>
            </thead>

            <tbody>
                @forelse($sesiones as $sesion)
                <tr>

                    <td>{{ $sesion->usuario?->nombre }}</td>

                    <td>{{ $sesion->inicio_sesion }}</td>

                    <td>
                        {{ $sesion->cierre_sesion ?? '---' }}
                    </td>

                    <td>
                        @if($sesion->cierre_sesion)
                            <span class="badge bg-danger">Cerrada</span>
                        @else
                            <span class="badge bg-success">Activa</span>
                        @endif
                    </td>

                    <td>{{ $sesion->user_agent }}</td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        No hay sesiones
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

<div class="mt-3">
    {{ $sesiones->links() }}
</div>

@endsection

