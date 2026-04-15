@extends('welcome')
@section('title', 'Nuevo Usuario')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="bi bi-person-plus me-2"></i>Nuevo Usuario</h4>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="card">
    <div class="card-body">

        <form action="{{ route('usuarios.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">

            {{-- NOMBRE --}}
            <div class="col-md-6">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre"
                    class="form-control @error('nombre') is-invalid @enderror"
                    value="{{ old('nombre') }}" required>

                @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- USERNAME --}}
            <div class="col-md-6">
                <label class="form-label">Username *</label>
                <input type="text" name="username"
                    class="form-control @error('username') is-invalid @enderror"
                    value="{{ old('username') }}" required>

                @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- EMAIL --}}
            <div class="col-md-6">
                <label class="form-label">Email *</label>
                <input type="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>

                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="col-md-6">
                <label class="form-label">Password *</label>
                <input type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" required>

                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- ROL --}}
            <div class="col-md-6">
                <label class="form-label">Rol *</label>
                <select name="rol" class="form-select">
                    <option value="admin">Admin</option>
                    <option value="empleado">Empleado</option>
                </select>
            </div>

            {{-- FOTO --}}
            <div class="col-md-6">
                <label class="form-label">Foto de perfil</label>
                <input type="file" name="foto" class="form-control">
            </div>

        </div>

        {{-- BOTONES --}}
        <div class="mt-4 d-flex gap-2">
            <button class="btn btn-primary">
                <i class="bi bi-save"></i> Guardar
            </button>

            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">
                Cancelar
            </a>
        </div>

        </form>

    </div>
</div>

@endsection