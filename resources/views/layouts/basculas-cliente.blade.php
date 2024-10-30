<section class="max-width-700">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h2-title mb-0">Básculas</h2>
        <a href="{{ route('crear-bascula', ['idCliente' => $cliente->id]) }}" class="btn btn-success">
            Crear Báscula
        </a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead id="basculas-table-head">
                <tr>
                    <th class="fw-bold blue-text">Capacidad</th>
                    <th class="fw-bold blue-text">Modelo</th>
                    <th class="fw-bold blue-text">Nº serie</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="basculas-table-body">
                @php
                    $basculasOrdenadas = $basculas
                        ->map(function ($bascula) {
                            if (is_numeric($bascula->maximo)) {
                                $maximoConvertido =
                                    $bascula->unidad_medida_kg_g === 'kg' ? $bascula->maximo * 1000 : $bascula->maximo;
                            } else {
                                $maximoConvertido = PHP_INT_MAX;
                            }
                            $bascula->maximo_convertido = $maximoConvertido;
                            return $bascula;
                        })
                        ->sortByDesc('maximo_convertido')
                        ->values();

                    // Quitar el campo temporal antes de mostrar
                    $basculasOrdenadas->each(function ($bascula) {
                        unset($bascula->maximo_convertido);
                    });
                @endphp
                @foreach ($basculasOrdenadas as $bascula)
                    <tr>
                        <td>{{ $bascula->maximo }} {{ $bascula->unidad_medida_kg_g }}</td>
                        <td>{{ $bascula->modelo }}</td>
                        <td>{{ $bascula->numero_serie }}</td>
                        <td>
                            <a href="{{ route('info-bascula', ['id' => $bascula->id]) }}"
                                class="btn btn-primary">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<script>
    function formatTableCells() {
        const tds = document.querySelectorAll('#basculas-table-body td');

        tds.forEach(td => {
            if (!td.querySelector('a')) { // Ignorar celdas que contienen enlaces
                const originalText = td.getAttribute('data-original-text') || td.innerText;
                td.setAttribute('data-original-text', originalText);

                if (window.innerWidth <= 450) {
                    if (originalText.length > 9) {
                        let formattedText = '';
                        for (let i = 0; i < originalText.length; i += 9) {
                            if (i + 9 < originalText.length) {
                                formattedText += originalText.slice(i, i + 9) + '-\n';
                            } else {
                                formattedText += originalText.slice(i);
                            }
                        }
                        td.innerText = formattedText;
                    } else {
                        td.innerText = originalText;
                    }
                } else {
                    td.innerText = originalText;
                }
            }
        });
    }

    window.addEventListener('resize', formatTableCells);
    document.addEventListener('DOMContentLoaded', formatTableCells);
</script>
