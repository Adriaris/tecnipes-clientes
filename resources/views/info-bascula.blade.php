@extends('layouts.app')

@section('content')
    <section class="max-width-700">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif



        <div class="container-fluid p-2">
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
                    <form id="basculaForm" method="POST" action="{{ route('editarBascula', ['id' => $bascula->id]) }}">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <!-- Instrumento -->
                        @include('layouts.bascula-status', ['bascula' => $bascula])
                        <div class="mb-3 custom-form">

                            <label for="instrumento" class="form-label fw-bold"><i class="bi bi-tools"></i>
                                Instrumento</label>
                            <select class="form-control" id="instrumento" name="instrumento" required disabled
                                onchange="mostrarCampoOtro(this)" data-otro-input="otroInstrumento">
                                <option value="BasculaElectronica"
                                    {{ $bascula->instrumento == 'BasculaElectronica' ? 'selected' : '' }}>Báscula
                                    electrónica
                                </option>
                                <option value="BasculaHibrida"
                                    {{ $bascula->instrumento == 'BasculaHibrida' ? 'selected' : '' }}>Báscula Híbrida
                                </option>
                                <option value="BasculaMecanica"
                                    {{ $bascula->instrumento == 'BasculaMecanica' ? 'selected' : '' }}>Báscula Mecánica
                                </option>
                                <option value="BasculaAnalitica"
                                    {{ $bascula->instrumento == 'BasculaAnalitica' ? 'selected' : '' }}>Báscula Analítica
                                </option>
                                <option value="Otro"
                                    {{ $bascula->instrumento == 'Otro' || (!in_array($bascula->instrumento, ['BasculaElectronica', 'BasculaHibrida', 'BasculaMecanica', 'BasculaAnalitica']) && !empty($bascula->instrumento)) ? 'selected' : '' }}>
                                    Otro</option>
                            </select>
                            <input type="text" class="form-control mt-3" id="otroInstrumento" name="otroInstrumento"
                                style="display: {{ $bascula->instrumento == 'Otro' || (!in_array($bascula->instrumento, ['BasculaElectronica', 'BasculaHibrida', 'BasculaMecanica', 'BasculaAnalitica']) && !empty($bascula->instrumento)) ? 'block' : 'none' }};"
                                value="{{ $bascula->instrumento == 'Otro' || (!in_array($bascula->instrumento, ['BasculaElectronica', 'BasculaHibrida', 'BasculaMecanica', 'BasculaAnalitica']) && !empty($bascula->instrumento)) ? $bascula->instrumento : '' }}"
                                placeholder="Especifique otro instrumento" maxlength="100" readonly>
                        </div>



                        <!-- Indicador -->
                        <div class="mb-3 custom-form">
                            <label for="indicador" class="form-label fw-bold"><i class="bi bi-speedometer2"></i>
                                Indicador</label>
                            <select class="form-control" id="indicador" name="indicador" required disabled
                                onchange="mostrarCampoOtro(this)" data-otro-input="otroIndicador">
                                <option value="Electronico" {{ $bascula->indicador == 'Electronico' ? 'selected' : '' }}>
                                    Electrónico</option>
                                <option value="Romana" {{ $bascula->indicador == 'Romana' ? 'selected' : '' }}>Romana
                                </option>
                                <option value="Esfera" {{ $bascula->indicador == 'Esfera' ? 'selected' : '' }}>Esfera
                                </option>
                                <option value="Optico" {{ $bascula->indicador == 'Optico' ? 'selected' : '' }}>Óptico
                                </option>
                                <option value="Otro"
                                    {{ $bascula->indicador != 'Electronico' && $bascula->indicador != 'Romana' && $bascula->indicador != 'Esfera' && $bascula->indicador != 'Optico' ? 'selected' : '' }}>
                                    Otro</option>
                            </select>
                            <input type="text" class="form-control mt-3" id="otroIndicador" name="otroIndicador"
                                style="display: {{ $bascula->indicador != 'Electronico' && $bascula->indicador != 'Romana' && $bascula->indicador != 'Esfera' && $bascula->indicador != 'Optico' ? 'block' : 'none' }};"
                                value="{{ $bascula->indicador != 'Electronico' && $bascula->indicador != 'Romana' && $bascula->indicador != 'Esfera' && $bascula->indicador != 'Optico' ? $bascula->indicador : '' }}"
                                placeholder="Especifique otro indicador" maxlength="30" readonly>
                        </div>

                        <div class="mb-3 custom-form">
                            <label for="fabricante" class="form-label fw-bold"><i class="bi bi-wrench"></i>
                                Fabricante</label>
                            <input type="text" class="form-control" id="fabricante" name="fabricante"
                                value="{{ $bascula->fabricante }}" maxlength="100" required readonly>
                        </div>
                        <div class="mb-3 custom-form">
                            <label for="modelo" class="form-label fw-bold"><i class="bi bi-puzzle-fill"></i>
                                Modelo</label>
                            <input type="text" class="form-control" id="modelo" name="modelo"
                                value="{{ $bascula->modelo }}" maxlength="30" required readonly>
                        </div>
                        <div class="mb-3 custom-form">
                            <label for="numero_serie" class="form-label fw-bold"><i class="bi bi-upc-scan"></i> Número de
                                Serie</label>
                            <input type="text" class="form-control" id="numero_serie" name="numero_serie"
                                value="{{ $bascula->numero_serie }}" maxlength="100" required readonly>
                        </div>
                        <div class="mb-3 custom-form">
                            <label for="codigo" class="form-label fw-bold"><i class="bi bi-key-fill"></i> Código</label>
                            <input type="text" class="form-control" id="codigo" name="codigo"
                                value="{{ $bascula->codigo }}" maxlength="50" required readonly>
                        </div>
                        <div class="mb-3 custom-form">
                            <label for="ubicacion" class="form-label fw-bold"><i class="bi bi-geo-alt-fill"></i>
                                Ubicación</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion"
                                value="{{ $bascula->ubicacion }}" maxlength="100" required readonly>
                        </div>
                        <div class="mb-3 custom-form">
                            <label for="maximo" class="form-label fw-bold"><i class="bi bi-arrows-fullscreen"></i>
                                Máximo (fe)</label>
                            <input type="text" class="form-control" id="maximo" name="maximo"
                                value="{{ $bascula->maximo }}" maxlength="30" required readonly>
                        </div>
                        <div class="mb-3 custom-form">
                            <label for="unidad_medida_kg_g" class="form-label fw-bold">
                                <i class="bi bi-speedometer"></i></i> Unidad de Medida (kg/g)
                            </label>
                            <select class="form-control" id="unidad_medida_kg_g" name="unidad_medida_kg_g" required
                                disabled>
                                <option value="">Seleccione una unidad</option>
                                <option value="kg" {{ $bascula->unidad_medida_kg_g == 'kg' ? 'selected' : '' }}>
                                    Kilogramos
                                    (kg)</option>
                                <option value="g" {{ $bascula->unidad_medida_kg_g == 'g' ? 'selected' : '' }}>Gramos
                                    (g)
                                </option>
                            </select>
                        </div>

                        <!-- Escalón -->
                        <div class="mb-3 custom-form">
                            <label for="escalon" class="form-label fw-bold"><i class="bi bi-ladder"></i> Escalón
                                (e)</label>
                            <input type="text" class="form-control" id="escalon" name="escalon"
                                value="{{ $bascula->escalon }}" maxlength="30" required readonly>
                        </div>
                        <!-- División -->
                        <div class="mb-3 custom-form">
                            <label for="division" class="form-label fw-bold"><i class="bi bi-grid-3x3-gap-fill"></i>
                                División (d)</label>
                            <input type="text" class="form-control" id="division" name="division"
                                value="{{ $bascula->division }}" required maxlength="30" readonly>
                        </div>


                        <!-- Acabado -->
                        <div class="mb-3 custom-form">
                            <label for="acabado" class="form-label fw-bold"><i class="bi bi-brush-fill"></i>
                                Acabado</label>
                            <select class="form-control" id="acabado" name="acabado" required disabled
                                onchange="mostrarCampoOtro(this)" data-otro-input="otroAcabado">
                                <option value="Metalica" {{ $bascula->acabado == 'Metalica' ? 'selected' : '' }}>Metálica
                                </option>
                                <option value="Hormigon" {{ $bascula->acabado == 'Hormigon' ? 'selected' : '' }}>Hormigón
                                </option>
                                <option value="Mixta" {{ $bascula->acabado == 'Mixta' ? 'selected' : '' }}>Mixta</option>
                                <option value="Otro"
                                    {{ $bascula->acabado == 'Otro' || (!in_array($bascula->acabado, ['Metalica', 'Hormigon', 'Mixta']) && !empty($bascula->acabado)) ? 'selected' : '' }}>
                                    Otro</option>
                            </select>
                            <input type="text" class="form-control mt-3" id="otroAcabado" name="otroAcabado"
                                style="display: {{ $bascula->acabado == 'Otro' || (!in_array($bascula->acabado, ['Metalica', 'Hormigon', 'Mixta']) && !empty($bascula->acabado)) ? 'block' : 'none' }};"
                                value="{{ $bascula->acabado == 'Otro' || (!in_array($bascula->acabado, ['Metalica', 'Hormigon', 'Mixta']) && !empty($bascula->acabado)) ? $bascula->acabado : '' }}"
                                placeholder="Especifique otro acabado" maxlength="30" readonly>
                        </div>

                        <!-- Instalación -->
                        <div class="mb-3 custom-form">
                            <label for="instalacion" class="form-label fw-bold"><i
                                    class="bi bi-wrench-adjustable-circle-fill"></i> Instalación</label>
                            <select class="form-control" id="instalacion" name="instalacion" required disabled
                                onchange="mostrarCampoOtro(this)" data-otro-input="otraInstalacion">
                                <option value="Sobresuelo" {{ $bascula->instalacion == 'Sobresuelo' ? 'selected' : '' }}>
                                    Sobresuelo</option>
                                <option value="Sobremesa" {{ $bascula->instalacion == 'Sobremesa' ? 'selected' : '' }}>
                                    Sobremesa</option>
                                <option value="Empotrada" {{ $bascula->instalacion == 'Empotrada' ? 'selected' : '' }}>
                                    Empotrada</option>
                                <option value="Aerea" {{ $bascula->instalacion == 'Aerea' ? 'selected' : '' }}>Aérea
                                </option>
                                <option value="Movil" {{ $bascula->instalacion == 'Movil' ? 'selected' : '' }}>Móvil
                                </option>
                                <option value="Tolva" {{ $bascula->instalacion == 'Tolva' ? 'selected' : '' }}>Tolva
                                </option>
                                <option value="Otro"
                                    {{ $bascula->instalacion != 'Sobresuelo' && $bascula->instalacion != 'Sobremesa' && $bascula->instalacion != 'Empotrada' && $bascula->instalacion != 'Aerea' && $bascula->instalacion != 'Movil' && $bascula->instalacion != 'Tolva' ? 'selected' : '' }}>
                                    Otro</option>
                            </select>
                            <input type="text" class="form-control mt-3" id="otraInstalacion" name="otraInstalacion"
                                style="display: {{ $bascula->instalacion != 'Sobresuelo' && $bascula->instalacion != 'Sobremesa' && $bascula->instalacion != 'Empotrada' && $bascula->instalacion != 'Aerea' && $bascula->instalacion != 'Movil' && $bascula->instalacion != 'Tolva' ? 'block' : 'none' }};"
                                value="{{ $bascula->instalacion != 'Sobresuelo' && $bascula->instalacion != 'Sobremesa' && $bascula->instalacion != 'Empotrada' && $bascula->instalacion != 'Aerea' && $bascula->instalacion != 'Movil' && $bascula->instalacion != 'Tolva' ? $bascula->instalacion : '' }}"
                                placeholder="Especifique otra instalación" maxlength="30" readonly>
                        </div>

                        <div class="mb-3 custom-form">
                            <label for="dimensiones" class="form-label fw-bold"><i class="bi bi-aspect-ratio-fill"></i>
                                Dimensiones</label>
                            <input type="text" class="form-control" id="dimensiones" name="dimensiones"
                                value="{{ $bascula->dimensiones }}" maxlength="30" required readonly>
                        </div>
                        <div class="mb-3 custom-form">
                            <label for="numero_apoyos" class="form-label fw-bold"><i class="bi bi-circle-square"></i>
                                Número
                                de Apoyos</label>
                            <input type="number" min="0" class="form-control" id="numero_apoyos"
                                name="numero_apoyos" value="{{ $bascula->numero_apoyos }}" required readonly>
                        </div>
                        <!-- Tipo de Apoyo -->
                        <div class="mb-3 custom-form">
                            <label for="tipo_apoyo" class="form-label fw-bold"><i class="bi bi-eject-fill"></i> Tipo de
                                Apoyo</label>
                            <select class="form-control" id="tipo_apoyo" name="tipo_apoyo" required disabled
                                onchange="mostrarCampoOtro(this)" data-otro-input="otroTipoApoyo">
                                <option value="Celula de carga"
                                    {{ $bascula->tipo_apoyo == 'Celula de carga' ? 'selected' : '' }}>Célula de carga
                                </option>
                                <option value="cuchillas" {{ $bascula->tipo_apoyo == 'cuchillas' ? 'selected' : '' }}>
                                    Cuchillas</option>
                                <option value="flejes" {{ $bascula->tipo_apoyo == 'flejes' ? 'selected' : '' }}>Flejes
                                </option>
                                <option value="Otro"
                                    {{ $bascula->tipo_apoyo != 'Celula de carga' && $bascula->tipo_apoyo != 'cuchillas' && $bascula->tipo_apoyo != 'flejes' ? 'selected' : '' }}>
                                    Otro</option>
                            </select>
                            <input type="text" class="form-control mt-3" id="otroTipoApoyo" name="otroTipoApoyo"
                                style="display: {{ $bascula->tipo_apoyo != 'Celula de carga' && $bascula->tipo_apoyo != 'cuchillas' && $bascula->tipo_apoyo != 'flejes' ? 'block' : 'none' }};"
                                value="{{ $bascula->tipo_apoyo != 'Celula de carga' && $bascula->tipo_apoyo != 'cuchillas' && $bascula->tipo_apoyo != 'flejes' ? $bascula->tipo_apoyo : '' }}"
                                placeholder="Especifique otro tipo de apoyo" maxlength="50" readonly>
                        </div>

                        <div class="mb-3 custom-form">
                            <label for="modelo_celula" class="form-label fw-bold"><i class="bi bi-cpu-fill"></i> Modelo
                                Célula</label>
                            <input type="text" class="form-control" id="modelo_celula" name="modelo_celula"
                                value="{{ $bascula->modelo_celula }}" maxlength="100" required readonly>
                        </div>
                        <div class="mb-3 custom-form">
                            <label for="cap_celula" class="form-label fw-bold"><i class="bi bi-hdd-rack-fill"></i>
                                Capacidad
                                Célula</label>
                            <input type="text" class="form-control" id="cap_celula" name="cap_celula"
                                value="{{ $bascula->cap_celula }}" maxlength="30" required readonly>
                        </div>
                        <div class="mb-3 custom-form">
                            <label for="nota_bascula" class="form-label fw-bold"><i class="bi bi-sticky-fill"></i> Nota
                                de la
                                Báscula</label>
                            <textarea class="form-control" id="nota_bascula" name="nota_bascula" maxlength="3000" readonly>{{ $bascula->nota_bascula }}</textarea>
                        </div>

                        <div id="warningDiv" style="display: none; color: red; margin-top: 10px;">
                            Por favor, complete todos los campos requeridos.
                        </div>





                        <div>
                            <div class="float-left">
                                <!-- Botón para activar la edición o cancelarla -->
                                <button type="button" id="editarCancelarBtn" class="btn btn-primary">
                                    <i class="bi bi-pencil-fill"></i> Editar
                                </button>
                                <!-- Botón para guardar los cambios -->
                                <button type="button" id="guardarBtn" class="btn btn-success" disabled>
                                    <i class="bi bi-check-fill"></i> Guardar
                                </button>
                            </div>


                        </div>


                    </form>

                    <div class="dropdown float-right">
                        <button class="btn btn-secondary" type="button" id="dropdownMenuButton"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Acciones <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @can('accessModeratorAndAdmin')
                                <li>
                                    <form id="papelera-bascula-form-{{ $bascula->id }}"
                                        action="{{ route('papelera.bascula.mover', $bascula->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('POST') <!-- Cambiado a POST para la acción de papelera -->
                                    </form>
                                    <a class="dropdown-item" href="#"
                                        onclick="event.preventDefault(); confirmPapeleraBascula({{ $bascula->id }});">
                                        <div class="icon-container text-danger">
                                            <i class="bi bi-trash-fill"></i>
                                        </div> Enviar a Papelera
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item btn-trasladar" href="#" data-bs-toggle="modal"
                                        data-bs-target="#cambiarClienteModal" onclick="setBasculaId({{ $bascula->id }});">
                                        <div class="icon-container text-primary">
                                            <i class="bi bi-arrow-left-right"></i>
                                        </div>Cambiar Cliente
                                    </a>
                                </li>
                            @endcan
                            @if ($bascula->operativa)
                                <li>
                                    <form action="{{ route('darDeBajaBascula', ['id' => $bascula->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item ">
                                            <div class="icon-container text-danger">
                                                <i class="bi bi-arrow-down-circle-fill"></i>
                                            </div> Dar de Baja
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <form action="{{ route('darDeAltaBascula', ['id' => $bascula->id]) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item ">
                                            <div class="icon-container text-success">
                                                <i class="bi bi-arrow-up-circle-fill"></i>
                                            </div> Dar de Alta
                                        </button>
                                    </form>
                                </li>
                            @endif
                            <li>
                                <form id="datos-completos-form-{{ $bascula->id }}"
                                    action="{{ route('actualizar-datos-completos', $bascula->id) }}" method="POST">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="dropdown-item ">
                                        @if ($bascula->datos_completos)
                                            <div class="icon-container text-danger">
                                                <i class="bi bi-file-earmark-x-fill"></i>
                                            </div> Datos Incompletos
                                        @else
                                            <div class="icon-container text-success">
                                                <i class="bi bi-file-earmark-check-fill"></i>
                                            </div> Datos Completos
                                        @endif
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


    </section>

    @include('layouts.cliente-bascula', ['cliente' => $bascula->cliente])

    @include('layouts.gallery', [
        'imagenes' => $bascula->imagenes,
        'tipo' => 'basculas', // Este valor se usa para identificar el tipo de entidad
        'idRelacion' => $bascula->id,
    ])

    @include('layouts.videos', [
        'videos' => $bascula->videos,
        'tipo' => 'basculas',
        'idRelacion' => $bascula->id,
    ])

    @include('layouts.archivos', [
        'archivos' => $bascula->archivos,
        'tipo' => 'basculas', // Este valor se usa para identificar el tipo de entidad
        'idRelacion' => $bascula->id,
    ])


    <!-- MODAL PAPELERA-->
    <div class="modal fade" id="papeleraBasculaConfirmationModal" tabindex="-1"
        aria-labelledby="papeleraBasculaConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="papeleraBasculaConfirmationModalLabel">Confirmar envío a papelera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres enviar esta báscula a la papelera?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmPapeleraBasculaButton">Papelera</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de mover báscula -->
    <div class="modal fade" id="cambiarClienteModal" tabindex="-1" aria-labelledby="cambiarClienteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cambiarClienteModalLabel">Mover báscula a otro cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="buscarCliente" placeholder="Buscar Cliente...">
                    </div>
                    <div class="row" id="clientes-container">
                        <!-- Clientes se cargarán aquí -->
                    </div>
                    <div id="pagination-links" class="mt-3">
                        <!-- Links de paginación se cargarán aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="moverBasculaBtn" disabled>Mover Báscula</button>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/basculas.js')


    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.style.height = 'auto';
                textarea.style.height = (textarea.scrollHeight) + 'px';
                textarea.addEventListener('input', autoResize);
            });
        });

        function autoResize(event) {
            const textarea = event.target;
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }


        let enEdicion = false; // Estado de edición

        document.getElementById('editarCancelarBtn').addEventListener('click', function() {
            if (!enEdicion) {
                // Activar modo edición
                entrarModoEdicion();
            } else {
                var warningDiv = document.getElementById('warningDiv');
                warningDiv.style.display = 'none';
                // Cancelar modo edición
                cancelarEdicion();
            }
        });

        document.getElementById('guardarBtn').addEventListener('click', function() {
            if (enEdicion) {
                var form = document.getElementById('basculaForm');
                var requiredFields = form.querySelectorAll('[required]');
                var allFilled = true;

                // Verificar si todos los campos requeridos están llenos
                requiredFields.forEach(function(field) {
                    if (!field.value.trim()) {
                        allFilled = false;
                    }
                });

                // Si todos los campos requeridos están llenos, se envía el formulario
                if (allFilled) {
                    form.submit();
                } else {
                    // Mostrar el div de advertencia si faltan campos por llenar
                    var warningDiv = document.getElementById('warningDiv');
                    warningDiv.style.display = 'block';
                }
            }

        });


        function entrarModoEdicion() {
            document.querySelectorAll('.form-control').forEach(function(element) {
                element.removeAttribute('readonly');
                element.removeAttribute('disabled');
            });
            document.getElementById('guardarBtn').disabled = false;
            document.getElementById('editarCancelarBtn').classList.remove('btn-primary');
            document.getElementById('editarCancelarBtn').classList.add('btn-secondary');
            document.getElementById('editarCancelarBtn').innerHTML = '<i class="bi bi-x-lg"></i> Cancelar';
            enEdicion = true;
        }

        function cancelarEdicion() {
            document.querySelectorAll('.form-control').forEach(function(element) {
                if (element.tagName === 'SELECT' || element.type === 'checkbox' || element.type === 'radio') {
                    element.setAttribute('disabled', true);
                } else {
                    element.setAttribute('readonly', 'readonly');
                }
            });
            document.getElementById('guardarBtn').disabled = true;
            document.getElementById('editarCancelarBtn').classList.remove('btn-secondary');
            document.getElementById('editarCancelarBtn').classList.add('btn-primary');
            document.getElementById('editarCancelarBtn').innerHTML = '<i class="bi bi-pencil-fill"></i> Editar';
            enEdicion = false;
        }

        function mostrarCampoOtro(selectElement) {
            var inputId = selectElement.getAttribute('data-otro-input');
            var inputOtro = document.getElementById(inputId);
            inputOtro.style.display = selectElement.value === "Otro" ? "block" : "none";
        }
    </script>



@endsection
