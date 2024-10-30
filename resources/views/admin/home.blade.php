@extends('layouts.app')

@section('content')
    <div class="container my-container max-width-700">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h2-title">Administrar usuarios</h2>
            <div class="actions mt-3">
                <a href="{{ route('admin.users.create') }}" class="btn btn-success"><i class="bi bi-person-plus-fill"></i>
                    Crear
                    Usuario</a>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error-eliminar-usuario'))
            <div class="alert alert-danger">
                {{ session('error-eliminar-usuario') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table user-table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th style="width: 20%;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->rol }}</td>
                            <td class="action-buttons d-flex justify-content-end">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary me-2"><i
                                        class="bi bi-pencil-fill"></i></a>
                                <button class="btn btn-danger" onclick="confirmDelete({{ $user->id }})"> <i
                                        class="bi bi-trash"></i></button>
                                <form id="delete-form-{{ $user->id }}"
                                    action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>





    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres eliminar este usuario?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/admin.js')
@endsection
