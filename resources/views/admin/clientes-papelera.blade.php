@extends('layouts.app')

@section('content')

    <form action="{{ route('buscarClientesEliminados') }}" method="GET">
        @csrf
        <div class="d-flex ps-2 pe-2">
            <!-- Buscador ocupando el espacio restante -->
            <div class="flex-grow-1">
                <div class="input-group">
                    <input type="text" class="form-control" name="busqueda" placeholder="Buscar por nombre o teléfono">
                    <button class="btn custom-btn" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
            @can('accessAdmin')
                <div style="width: 130px;" class="ps-2">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#confirmVaciarClientesModal">
                        Vaciar TODO
                    </button>
                </div>
            @endcan

        </div>
    </form>

    <!-- Lista de clientes eliminados -->
    <div class="container-fluid ps-2 pe-2 pt-3">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div id="clientesEliminados" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3 mb-3">


            @if ($clientesEliminados->count() > 0)
                @foreach ($clientesEliminados as $cliente)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold blue-text mb-2">
                                    <i class="bi bi-person-fill" style="color: #f46610; font-size: 22px;"></i>
                                    {{ $cliente->nombre }}
                                </h5>
                                <p class="card-text"><strong>Dirección:</strong> {{ $cliente->direccion }}</p>
                                <p class="card-text mb-2"><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-primary"
                                            onclick="confirmRecuperarCliente({{ $cliente->id }}, {{ $cliente->basculas()->onlyTrashed()->count() > 0 ? 'true' : 'false' }})">
                                            <i class="bi bi-arrow-clockwise"></i> Recuperar Cliente
                                        </button>
                                    </div>

                                    <!-- Botón para eliminar el cliente -->
                                    @can('accessAdmin')
                                        <div>
                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmDeleteCliente({{ $cliente->id }})">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                            <!-- Formulario de eliminación oculto -->
                                            <form id="delete-cliente-form-{{ $cliente->id }}"
                                                action="{{ route('admin.clientes.destroy', $cliente->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    @endcan

                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>No hay clientes en la papelera.</p>
            @endif
        </div>

        {{ $clientesEliminados->links() }}
    </div>






    <!-- Modal de Confirmación de Recuperación de Cliente con Básculas -->
    <div class="modal fade" id="confirmRecuperarClienteConBasculasModal" tabindex="-1"
        aria-labelledby="confirmRecuperarClienteConBasculasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmRecuperarClienteConBasculasModalLabel">Recuperar Cliente y
                        Básculas
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Este cliente <b> tiene básculas en la papelera</b>. ¿Desea recuperar solo el cliente o también todas
                    sus
                    básculas?
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <!-- Botón de Cancelar alineado a la izquierda -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                    <!-- Botones de acción alineados a la derecha -->
                    <div>
                        <form id="recuperarClienteForm" method="POST" action="">
                            @csrf
                            <button type="submit" name="recuperar" value="cliente_y_basculas" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise"></i> Cliente y Básculas
                            </button>
                            <button type="submit" name="recuperar" value="cliente" class="btn btn-success">
                                <i class="bi bi-arrow-clockwise"></i> Solo Cliente
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="deleteClienteConfirmationModal" tabindex="-1"
        aria-labelledby="deleteClienteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteClienteConfirmationModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres eliminar <b class="ultra-red-text"> PERMANENTEMENTE </b> este cliente? <b>
                        También se eliminarán todos sus básculas asociadas.</b>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteClienteButton">Eliminar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal de Confirmación para Vaciar la Papelera de Clientes -->
    <div class="modal fade" id="confirmVaciarClientesModal" tabindex="-1"
        aria-labelledby="confirmVaciarClientesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmVaciarClientesModalLabel">Confirmar Eliminación Total</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    ¿Estás seguro de que deseas <b class="ultra-red-text"> ELIMINAR PERMANENTEMENTE todos los clientes de
                        la papelera? </b> <br> <b>Esta
                        acción eliminará también todas las básculas asociadas a ellos y no se puede deshacer.</b>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('vaciar-papelera-clientes') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Vaciar Papelera de Clientes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>






    @vite('resources/js/papelera.js')


@endsection
