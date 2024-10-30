<section class="max-width-700">
    <h2 class="mb-4 h2-title">Información del Cliente</h2>


    <h5 class="card-title fw-bold blue-text mb-2">
        <i class="bi bi-person-fill" style="color: #f46610; font-size: 22px;"></i>
        {{ $cliente->nombre }}
    </h5>
    <p class="card-text full-width"><strong>Dirección:</strong> {{ $cliente->direccion }}</p>
    <div class="contact-info">
        <p class="card-text phone"><strong>Teléfono:</strong> {{ $cliente->telefono }}</p>
        <div class="view-client">
            <a href="{{ route('info-cliente', ['id' => $cliente->id]) }}" class="btn btn-primary">Ver
                Cliente</a>
        </div>
    </div>

</section>
