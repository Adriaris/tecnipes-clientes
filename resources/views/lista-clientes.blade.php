@extends('layouts.app')

@section('content')
    <form action="{{ route('buscarClientes') }}" method="GET">
        @csrf
        <div class="d-flex ps-2 pe-2">
            <!-- Buscador ocupando el espacio restante -->
            <div class="flex-grow-1 pe-2">
                <div class="input-group">
                    <input type="text" class="form-control" name="busqueda" placeholder="Buscar por nombre de cliente">
                    <button class="btn custom-btn" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
            <!-- Botón Crear Cliente con ancho fijo -->
            <div style="width: 130px;">
                <a href="{{ route('crear-cliente') }}"
                    class="btn btn-success w-100 {{ Route::currentRouteName() == 'crear-cliente' ? 'active' : '' }}">
                    Crear Cliente
                </a>
            </div>
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
            @foreach ($clientes as $cliente)
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold blue-text mb-2">
                                <i class="bi bi-person-fill" style="color: #f46610; font-size: 22px;"></i>
                                {{ $cliente->nombre }}
                            </h5>
                            <p class="card-text"><strong>Dirección:</strong> {{ $cliente->direccion }}</p>
                            <p class="card-text mb-2"><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
                            <div class="mt-auto">
                                <a href="{{ route('info-cliente', ['id' => $cliente->id]) }}" class="btn btn-primary">Info
                                    Cliente</a>
                                <a href="{{ route('basculas-cliente', ['id' => $cliente->id]) }}"
                                    class="btn btn-success">Ver Básculas</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>



        {{-- Muestra los enlaces de paginación --}}
        {{ $clientes->links() }}
    </div>
@endsection
