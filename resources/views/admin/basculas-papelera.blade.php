@extends('layouts.app')

@section('content')



    <form action="{{ route('buscarBasculasEliminadas') }}" method="GET">
        @csrf
        <div class="d-flex ps-2 pe-2">
            <!-- Buscador ocupando el espacio restante -->
            <div class="flex-grow-1">
                <div class="input-group">
                    <input type="text" class="form-control" name="busqueda"
                        placeholder="Buscar por cliente, número de serie, código o modelo">
                    <button class="btn custom-btn" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
            @can('accessAdmin')
                <div style="width: 130px;" class="ps-2">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#confirmVaciarBasculasModal">
                        Vaciar TODO
                    </button>
                </div>
            @endcan

        </div>
    </form>

    <!-- Lista de básculas eliminadas (inicialmente oculto) -->
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
        <div id="basculasEliminadas" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-3 mb-3">

            @if ($basculasEliminadas->count() > 0)
                @foreach ($basculasEliminadas as $bascula)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title fw-bold blue-text mb-2">
                                        <i class="bi bi-speedometer" style="color: #f46610; font-size: 22px;"></i>
                                        {{ $bascula->modelo }}
                                    </h5>
                                    @include('layouts.bascula-status', ['bascula' => $bascula])
                                </div>
                                <p class="card-text fw-bold">{{ $bascula->cliente->nombre }}</p>
                                <p class="card-text"><strong>Código:</strong> {{ $bascula->codigo }}</p>
                                <p class="card-text"><strong>Nº de Serie:</strong> {{ $bascula->numero_serie }}</p>
                                <p class="card-text mb-2"><strong>Capacidad:</strong> {{ $bascula->maximo }}
                                    {{ $bascula->unidad_medida_kg_g }}</p>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-primary"
                                            onclick="confirmRecuperarBascula({{ $bascula->id }}, {{ $bascula->cliente->id }}, {{ $bascula->cliente->trashed() ? 'true' : 'false' }})">
                                            <i class="bi bi-arrow-clockwise"></i> Recuperar Báscula
                                        </button>
                                    </div>
                                    @can('accessAdmin')
                                        <div>
                                            <button type="button" class="btn btn-danger"
                                                onclick="event.preventDefault(); confirmDeleteBascula({{ $bascula->id }});">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>

                                            <!-- Formulario de eliminación oculto -->
                                            <form id="delete-bascula-form-{{ $bascula->id }}"
                                                action="{{ route('admin.basculas.destroy', $bascula->id) }}" method="POST"
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
                <p>No hay básculas en la papelera.</p>
            @endif
        </div>

        {{ $basculasEliminadas->links() }}

    </div>



    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmRecuperarClienteModal" tabindex="-1"
        aria-labelledby="confirmRecuperarClienteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmRecuperarClienteModalLabel">Confirmar recuperación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @isset($bascula)
                        El cliente <b>{{ $bascula->cliente->nombre }}</b> asociado con esta báscula <strong style="color: red">
                            está en la papelera.</strong> ¿Desea recuperar ambos?
                    @else
                        <p>No hay información disponible para mostrar en este modal.</p>
                    @endisset
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="recuperarClienteBasculaForm" method="POST" action="">
                        @csrf
                        <button type="submit" class="btn btn-success"><i class="bi bi-arrow-clockwise"></i> Recuperar
                            Ambos</button>
                    </form>
                </div>
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
                    ¿Estás seguro de que quieres eliminar <b class="ultra-red-text"> PERMANENTEMENTE </b> esta báscula?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBasculaButton">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación para Vaciar la Papelera de Básculas -->
    <div class="modal fade" id="confirmVaciarBasculasModal" tabindex="-1" aria-labelledby="confirmVaciarBasculasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmVaciarBasculasModalLabel">Confirmar Eliminación Total</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas <b class="ultra-red-text"> ELIMINAR PERMANENTEMENTE todas las básculas de
                        la papelera? </b> Esta
                    acción no se
                    puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('vaciar-papelera-basculas') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Vaciar Papelera de Básculas</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @vite('resources/js/papelera.js')
@endsection
