@extends('welcome')
@section('title', 'Usuario '.$usuario->nombre)

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>
        <i class="bi bi-person me-2"></i>
        {{ $usuario->nombre }}
    </h4>

    <div class="d-flex gap-2">
        <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>

        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- FOTO --}}
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">

                @if($usuario->foto_perfil_url)
                    <img src="{{ asset('storage/'.$usuario->foto_perfil_url) }}"
                         class="img-fluid rounded-circle mb-3"
                         style="max-width: 180px;">
                @else
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width:180px; height:180px;">
                        <i class="bi bi-person fs-1 text-muted"></i>
                    </div>
                @endif

                <h5 class="mb-1">{{ $usuario->nombre }}</h5>
                <small class="text-muted">{{ $usuario->username }}</small>

            </div>
        </div>
    </div>

    {{-- DATOS --}}
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <strong>Información del Usuario</strong>
            </div>

            <div class="card-body">

                <table class="table table-borderless table-sm">

                    <tr>
                        <th>Email:</th>
                        <td>{{ $usuario->email }}</td>
                    </tr>

                    <tr>
                        <th>Rol:</th>
                        <td>
                            <span class="badge bg-{{ $usuario->rol == 'admin' ? 'dark' : 'info' }}">
                                {{ ucfirst($usuario->rol) }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Estado:</th>
                        <td>
                            @if(!$usuario->activo)
                                <span class="badge bg-secondary">Inactivo</span>
                            @else
                                <span class="badge bg-success">Activo</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Fecha de registro:</th>
                        <td>
                            {{ $usuario->creado_en ? $usuario->creado_en->format('d/m/Y') : 'N/A' }}
                        </td>
                    </tr>

                </table>

            </div>
        </div>

    </div>

</div>

@endsection

