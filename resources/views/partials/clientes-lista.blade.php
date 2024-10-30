@foreach ($clientes as $cliente)
    <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
        <div class="card h-100">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title fw-bold blue-text mb-2">
                    <i class="bi bi-person-fill" style="color: #f46610; font-size: 22px;"></i>
                    {{ $cliente->nombre }}
                </h5>
                <p class="card-text"><strong>Dirección:</strong> {{ $cliente->direccion }}</p>
                <button class="btn btn-primary mt-auto btn-seleccionar"
                    data-cliente-id="{{ $cliente->id }}">Seleccionar</button>
            </div>
        </div>
    </div>
@endforeach
