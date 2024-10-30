@extends('layouts.app')

@section('content')
    <form action="{{ route('buscarBasculas') }}" method="GET">
        @csrf
        <div class="d-flex ps-2 pe-2">
            <!-- Buscador ocupando el espacio restante -->
            <div class="flex-grow-1">
                <div class="input-group">
                    <input type="text" class="form-control" name="busqueda"
                        placeholder="Buscar cliente, modelo, código o nº serie">
                    <button class="btn custom-btn" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            @if ($showCreateButton ?? false)
                <!-- Botón Crear Nueva Báscula con ancho fijo -->
                <div style="width: 130px;" class="ps-2">
                    <a href="{{ route('crear-bascula', ['idCliente' => $id]) }}" class="btn btn-success w-100">
                        Crear Báscula
                    </a>
                </div>
            @endif
        </div>
    </form>





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
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4  g-3 mb-3">
            @foreach ($basculas as $bascula)
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
                            <div class="mt-auto">
                                <a href="{{ route('info-bascula', ['id' => $bascula->id]) }}" class="btn btn-primary">Info
                                    Báscula</a>
                                <a href="{{ route('info-cliente', ['id' => $bascula->id_cliente]) }}"
                                    class="btn btn-success">Info Cliente</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>



        {{-- Muestra los enlaces de paginación --}}
        {{ $basculas->links() }}
    </div>
@endsection
