@extends('layouts.app')

@section('content')
    <div class="container-fluid  max-width-700">
        <div class="row">
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="clienteForm" method="POST" action="{{ route('crearCliente') }}">
                    @csrf
                    <div class="mb-3 custom-form">
                        <label for="nombre" class="form-label fw-bold">
                            <i class="bi bi-person-fill"></i> Nombre
                        </label>
                        <input type="text" class="form-control" id="nombre" name="nombre" maxlength="120" required>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="horario" class="form-label fw-bold">
                            <i class="bi bi-clock-fill"></i> Horario
                        </label>
                        <textarea placeholder="No hay registros..." class="form-control" id="horario" name="horario" maxlength="150"></textarea>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="direccion" class="form-label fw-bold">
                            <i class="bi bi-house-door-fill"></i> Dirección
                        </label>
                        <input type="text" class="form-control" id="direccion" name="direccion" maxlength="255" required>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="telefono" class="form-label fw-bold">
                            <i class="bi bi-telephone-fill"></i> Teléfono
                        </label>
                        <input type="text" class="form-control" id="telefono" name="telefono" maxlength="50">
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="persona_contacto" class="form-label fw-bold">
                            <i class="bi bi-person-fill"></i> Persona de Contacto
                        </label>
                        <input type="text" class="form-control" id="persona_contacto" name="persona_contacto"
                            maxlength="120">
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="telefono_persona_contacto" class="form-label fw-bold">
                            <i class="bi bi-telephone-fill"></i> Teléfono Persona de Contacto
                        </label>
                        <input type="text" class="form-control" id="telefono_persona_contacto"
                            name="telefono_persona_contacto" maxlength="50">
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="gps" class="form-label fw-bold">
                            <i class="bi bi-geo-alt-fill"></i> GPS
                        </label>
                        <input type="text" class="form-control" id="gps" name="gps" maxlength="255">
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="nota_cliente" class="form-label fw-bold">
                            <i class="bi bi-sticky-fill"></i> Nota del Cliente
                        </label>
                        <textarea placeholder="No hay registros..." class="form-control" id="nota_cliente" name="nota_cliente" maxlength="1000"></textarea>
                    </div>

                    {{-- Botón para guardar el nuevo cliente --}}
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-fill"></i> Crear
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
