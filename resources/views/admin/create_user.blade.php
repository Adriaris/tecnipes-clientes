@extends('layouts.app')

@section('content')
    <div class="container max-width-700">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-3 custom-form">
                <label for="username" class="form-label fw-bold"><i class="bi bi-person-fill"></i> Nombre de Usuario</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username') }}"
                    required>
            </div>
            <div class="mb-3 custom-form">
                <label for="email" class="form-label fw-bold"><i class="bi bi-envelope-fill"></i> Correo
                    Electrónico</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                    required>
            </div>
            <div class="mb-3 custom-form">
                <label for="password" class="form-label fw-bold"><i class="bi bi-lock-fill"></i> Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="mb-3 custom-form">
                <label for="password_confirmation" class="form-label fw-bold"><i class="bi bi-lock-fill"></i> Confirmar
                    Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    required>
            </div>
            <div class="mb-3 custom-form">
                <label for="rol" class="form-label fw-bold"><i class="bi bi-list-task"></i> Rol</label>
                <select name="rol" id="rol" class="form-control" required>
                    <option value="admin">Admin</option>
                    <option value="moderador">Moderador</option>
                    <option value="usuario">Usuario</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>


    </div>
@endsection