@extends('welcome')
@section('title', 'Editar Usuario')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-pencil me-2"></i>Editar Usuario</h4>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">

        <form action="{{ route('usuarios.update', $usuario) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">

            {{-- NOMBRE --}}
            <div class="col-md-6">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre"
                    class="form-control"
                    value="{{ old('nombre', $usuario->nombre) }}" required>
            </div>

            {{-- USERNAME --}}
            <div class="col-md-6">
                <label class="form-label">Username *</label>
                <input type="text" name="username"
                    class="form-control"
                    value="{{ old('username', $usuario->username) }}" required>
            </div>

            {{-- EMAIL --}}
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input type="email" name="email"
                    class="form-control"
                    value="{{ old('email', $usuario->email) }}" required>
            </div>

            {{-- ROL --}}
            <div class="col-md-6">
                <label class="form-label">Rol *</label>
                <select name="rol" class="form-select">
                    <option value="admin" {{ $usuario->rol=='admin'?'selected':'' }}>Admin</option>
                    <option value="empleado" {{ $usuario->rol=='empleado'?'selected':'' }}>Empleado</option>
                </select>
            </div>

            {{-- FOTO --}}
            <div class="col-md-6">
                <label class="form-label">Nueva Foto</label>
                <input type="file" name="foto" class="form-control">
            </div>

            {{-- PREVIEW --}}
            <div class="col-md-6">
                @if($usuario->foto_perfil_url)
                    <label class="form-label d-block">Foto actual</label>
                    <img src="{{ asset('storage/'.$usuario->foto_perfil_url) }}"
                         width="100" class="rounded border">
                @endif
            </div>

        </div>

        {{-- BOTONES --}}
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-warning">
                <i class="bi bi-save"></i> Actualizar
            </button>

            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                Cancelar
            </a>
        </div>

        </form>

    </div>
</div>

@endsection