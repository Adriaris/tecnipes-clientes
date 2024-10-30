@extends('layouts.app')

@section('content')
    <div class="container-fluid max-width-700">
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
                <form method="POST" action="{{ route('basculas.store') }}">
                    @csrf
                    <!-- Instrumento -->
                    <div class="mb-3 custom-form">
                        <label for="instrumento" class="form-label fw-bold"><i class="bi bi-tools"></i> Instrumento</label>
                        <select class="form-control" id="instrumento" name="instrumento" required
                            onchange="mostrarCampoOtro(this)" data-otro-input="otroInstrumento">
                            <option value="BasculaElectronica">Báscula electrónica</option>
                            <option value="BasculaHibrida">Báscula Híbrida</option>
                            <option value="BasculaMecanica">Báscula Mecánica</option>
                            <option value="BasculaAnalitica">Báscula Analítica</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <input type="text" class="form-control mt-3" id="otroInstrumento" name="otroInstrumento"
                            style="display:none;" placeholder="Especifique otro instrumento" maxlength="100">
                    </div>
                    <!-- Indicador -->
                    <div class="mb-3 custom-form">
                        <label for="indicador" class="form-label fw-bold"><i class="bi bi-speedometer2"></i>
                            Indicador</label>
                        <select class="form-control" id="indicador" name="indicador" required
                            onchange="mostrarCampoOtro(this)" data-otro-input="otroIndicador">
                            <option value="Electronico">Electrónico</option>
                            <option value="Romana">Romana</option>
                            <option value="Esfera">Esfera</option>
                            <option value="Optico">Óptico</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <input type="text" class="form-control mt-3" id="otroIndicador" name="otroIndicador"
                            style="display:none;" placeholder="Especifique otro indicador" maxlength="30">
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="fabricante" class="form-label fw-bold"><i class="bi bi-building"></i> Fabricante</label>
                        <input type="text" class="form-control" id="fabricante" name="fabricante" maxlength="100"
                            required>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="modelo" class="form-label fw-bold"><i class="bi bi-puzzle-fill"></i> Modelo</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" maxlength="30" required>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="numero_serie" class="form-label fw-bold"><i class="bi bi-upc-scan"></i> Número de
                            Serie</label>
                        <input type="text" class="form-control" id="numero_serie" name="numero_serie" maxlength="100"
                            required>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="codigo" class="form-label fw-bold"><i class="bi bi-key-fill"></i> Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" maxlength="50" required>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="ubicacion" class="form-label fw-bold"><i class="bi bi-geo-alt-fill"></i>
                            Ubicación</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" maxlength="100" required>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="maximo" class="form-label fw-bold"><i class="bi bi-arrows-fullscreen"></i>
                            Máximo (fe)</label>
                        <input type="text" class="form-control" id="maximo" name="maximo" maxlength="30" required>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="unidad_medida_kg_g" class="form-label fw-bold">
                            <i class="bi bi-balance-scale"></i> Unidad de Medida (kg/g)
                        </label>
                        <select class="form-control" id="unidad_medida_kg_g" name="unidad_medida_kg_g" required>
                            <option value="">Seleccione una unidad</option>
                            <option value="kg">Kilogramos (kg)</option>
                            <option value="g">Gramos (g)</option>
                        </select>
                    </div>
                    <!-- Escalón -->
                    <div class="mb-3 custom-form">
                        <label for="escalon" class="form-label fw-bold"><i class="bi bi-ladder"></i> Escalón (e)</label>
                        <input type="text" class="form-control" id="escalon" name="escalon" maxlength="30"
                            required>
                    </div>
                    <!-- División -->
                    <div class="mb-3 custom-form">
                        <label for="division" class="form-label fw-bold"><i class="bi bi-grid-3x3-gap-fill"></i> División
                            (d)</label>
                        <input type="text" class="form-control" id="division" name="division" required
                            maxlength="30">
                    </div>


                    <!-- Acabado -->
                    <div class="mb-3 custom-form">
                        <label for="acabado" class="form-label fw-bold"><i class="bi bi-brush-fill"></i> Acabado</label>
                        <select class="form-control" id="acabado" name="acabado" required
                            onchange="mostrarCampoOtro(this)" data-otro-input="otroAcabado">
                            <option value="Metalica">Metálica</option>
                            <option value="Hormigon">Hormigón</option>
                            <option value="Mixta">Mixta</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <input type="text" class="form-control mt-3" id="otroAcabado" name="otroAcabado"
                            style="display:none;" placeholder="Especifique otro acabado" maxlength="30">
                    </div>
                    <!-- Instalación -->
                    <div class="mb-3 custom-form">
                        <label for="instalacion" class="form-label fw-bold"><i
                                class="bi bi-wrench-adjustable-circle-fill"></i> Instalación</label>
                        <select class="form-control" id="instalacion" name="instalacion" required
                            onchange="mostrarCampoOtro(this)" data-otro-input="otraInstalacion">
                            <option value="Sobresuelo">Sobresuelo</option>
                            <option value="Sobremesa">Sobremesa</option>
                            <option value="Empotrada">Empotrada</option>
                            <option value="Aerea">Aérea</option>
                            <option value="Movil">Móvil</option>
                            <option value="Tolva">Tolva</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <input type="text" class="form-control mt-3" id="otraInstalacion" name="otraInstalacion"
                            style="display:none;" placeholder="Especifique otra instalación" maxlength="30">
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="dimensiones" class="form-label fw-bold"><i class="bi bi-aspect-ratio-fill"></i>
                            Dimensiones</label>
                        <input type="text" class="form-control" id="dimensiones" name="dimensiones" maxlength="30"
                            required>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="numero_apoyos" class="form-label fw-bold"><i class="bi bi-circle-square"></i> Número
                            de Apoyos</label>
                        <input type="number" min="0" class="form-control" id="numero_apoyos"
                            name="numero_apoyos" required>
                    </div>
                    <!-- Tipo de Apoyo -->
                    <div class="mb-3 custom-form">
                        <label for="tipo_apoyo" class="form-label fw-bold"><i class="bi bi-eject-fill"></i> Tipo de
                            Apoyo</label>
                        <select class="form-control" id="tipo_apoyo" name="tipo_apoyo" required
                            onchange="mostrarCampoOtro(this)" data-otro-input="otroTipoApoyo">
                            <option value="Celula de carga">Célula de carga</option>
                            <option value="cuchillas">Cuchillas</option>
                            <option value="flejes">Flejes</option>
                            <option value="Otro">Otro</option>
                        </select>
                        <input type="text" class="form-control mt-3" id="otroTipoApoyo" name="otroTipoApoyo"
                            style="display:none;" placeholder="Especifique otro tipo de apoyo" maxlength="50">
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="modelo_celula" class="form-label fw-bold"><i class="bi bi-cpu-fill"></i> Modelo
                            Célula</label>
                        <input type="text" class="form-control" id="modelo_celula" name="modelo_celula"
                            maxlength="100" required>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="cap_celula" class="form-label fw-bold"><i class="bi bi-hdd-rack-fill"></i> Capacidad
                            Célula</label>
                        <input type="text" class="form-control" id="cap_celula" name="cap_celula" maxlength="30"
                            required>
                    </div>
                    <div class="mb-3 custom-form">
                        <label for="nota_bascula" class="form-label fw-bold"><i class="bi bi-sticky-fill"></i> Nota de la
                            Báscula</label>
                        <textarea class="form-control" id="nota_bascula" name="nota_bascula" maxlength="1000"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success float-right">Crear</button>
                    <div class="mb-3 custom-form">
                        <label class="form-label fw-bold"><i class="bi bi-file-earmark-check-fill"></i> ¿La información
                            está
                            completa?</label>

                        <div class="ps-5">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="datos_completos"
                                    id="datosCompletosSi" value="1">
                                <label class="form-check-label" for="datosCompletosSi">Sí</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="datos_completos"
                                    id="datosCompletosNo" value="0" checked>
                                <label class="form-check-label" for="datosCompletosNo">No</label>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="id_cliente" value="{{ $idCliente }}">


                </form>
            </div>
        </div>
    </div>
    <script>
        function mostrarCampoOtro(selectElement) {
            var inputId = selectElement.getAttribute('data-otro-input');
            var inputOtro = document.getElementById(inputId);
            inputOtro.style.display = selectElement.value === "Otro" ? "block" : "none";
        }
    </script>
@endsection
