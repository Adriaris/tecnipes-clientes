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

        <form id="editUserForm" action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            <div class="mb-3 custom-form">
                <label for="username" class="form-label fw-bold"><i class="bi bi-person-fill"></i> Nombre de Usuario</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}"
                    readonly required>
            </div>
            <div class="mb-3 custom-form">
                <label for="email" class="form-label fw-bold"><i class="bi bi-envelope-fill"></i> Correo
                    Electrónico</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}"
                    readonly required>
            </div>
            <div class="mb-3 custom-form">
                <label for="password" class="form-label fw-bold"><i class="bi bi-lock-fill"></i> Contraseña</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder="Deja en blanco para no cambiar" readonly>
            </div>
            <div class="mb-3 custom-form">
                <label for="password_confirmation" class="form-label fw-bold"><i class="bi bi-lock-fill"></i> Confirmar
                    Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    placeholder="Deja en blanco para no cambiar" readonly>
            </div>
            <div class="mb-3 custom-form">
                <label for="rol" class="form-label fw-bold"><i class="bi bi-list-task"></i> Rol</label>
                <select name="rol" id="rol" class="form-control" readonly disabled required>
                    <option value="admin" {{ $user->rol == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="moderador" {{ $user->rol == 'moderador' ? 'selected' : '' }}>Moderador</option>
                    <option value="usuario" {{ $user->rol == 'usuario' ? 'selected' : '' }}>Usuario</option>
                </select>
            </div>
            <button type="button" id="editButton" class="btn btn-primary">Editar</button>
            <button type="button" id="cancelButton" class="btn btn-danger d-none">Cancelar</button>
            <button type="submit" id="updateButton" class="btn btn-success" disabled>Actualizar</button>
        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButton = document.getElementById('editButton');
            const cancelButton = document.getElementById('cancelButton');
            const updateButton = document.getElementById('updateButton');
            const formElements = document.getElementById('editUserForm').elements;

            function toggleEditMode(editing) {
                for (let i = 0; i < formElements.length; i++) {
                    if (formElements[i].tagName === 'INPUT' || formElements[i].tagName === 'SELECT') {
                        formElements[i].readOnly = !editing;
                        formElements[i].disabled = !editing;
                    }
                }
                updateButton.disabled = !editing; // Enable or disable the update button
                if (editing) {
                    editButton.classList.add('d-none');
                    cancelButton.classList.remove('d-none');
                } else {
                    editButton.classList.remove('d-none');
                    cancelButton.classList.add('d-none');
                }
            }

            editButton.addEventListener('click', function() {
                toggleEditMode(true);
            });

            cancelButton.addEventListener('click', function() {
                toggleEditMode(false);
            });
        });
    </script>
@endsection
