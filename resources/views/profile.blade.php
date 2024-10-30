@extends('layouts.app')

@section('content')
    <div class="container max-width-700">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="profileForm" action="{{ route('profile.updatePassword') }}" method="POST">
            @csrf
            <div class="mb-3 custom-form">
                <label for="username" class="form-label fw-bold"><i class="bi bi-person-fill"></i> Nombre de Usuario</label>
                <input type="text" id="username" class="form-control" value="{{ $user->username }}" readonly>
            </div>
            <div class="mb-3 custom-form">
                <label for="email" class="form-label fw-bold"><i class="bi bi-envelope-fill"></i> Correo
                    Electrónico</label>
                <input type="email" id="email" class="form-control" value="{{ $user->email }}" readonly>
            </div>
            <div class="mb-3 custom-form">
                <label for="current_password" class="form-label fw-bold"><i class="bi bi-lock-fill"></i> Contraseña
                    Actual</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>
            </div>
            <div class="mb-3 custom-form">
                <label for="new_password" class="form-label fw-bold"><i class="bi bi-lock-fill"></i> Nueva
                    Contraseña</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
            </div>
            <div class="mb-3 custom-form">
                <label for="new_password_confirmation" class="form-label fw-bold"><i class="bi bi-lock-fill"></i> Confirmar
                    Nueva Contraseña</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control"
                    required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
        </form>


    </div>

    <div class="container max-width-700">
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger w-100">Cerrar Sesión</button>
        </form>
    </div>
@endsection
