@extends('layouts.app')

@section('content')
    <div class="container-fluid max-width-700">

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
        <form id="clienteForm" method="POST" action="{{ route('editarCliente', ['id' => $cliente->id]) }}">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-3 custom-form">
                <label for="nombre" class="form-label fw-bold">
                    <i class="bi bi-person-fill"></i> Nombre del Cliente
                </label>
                <input type="text" placeholder="No hay registros..." class="form-control editable" id="nombre"
                    name="nombre" value="{{ $cliente->nombre }}" maxlength="120" readonly>
            </div>
            <div class="mb-3 custom-form">
                <label for="horario" class="form-label fw-bold">
                    <i class="bi bi-clock-fill"></i> Horario
                </label>
                <textarea placeholder="No hay registros..." class="form-control editable" id="horario" name="horario" maxlength="150"
                    readonly>{{ $cliente->horario }}</textarea>
            </div>
            <div class="mb-3 custom-form">
                <label for="direccion" class="form-label fw-bold">
                    <i class="bi bi-house-door-fill"></i> Dirección
                </label>
                <div class="input-group">
                    <input type="text" class="form-control editable" id="direccion" name="direccion"
                        value="{{ $cliente->direccion }}" maxlength="255" readonly>
                    <button class="btn btn-outline-secondary btn-copy" type="button" id="copyButton"
                        title="Copiar dirección">
                        <i class="bi bi-clipboard"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-maps" type="button" id="mapsButton" title="Ver en Mapas">
                        <i class="bi bi-geo-alt-fill"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3 custom-form">
                <label for="gps" class="form-label fw-bold">
                    <i class="bi bi-geo-alt-fill"></i> GPS
                </label>
                <div class="input-group">
                    <input type="text" class="form-control editable" id="gps" name="gps"
                        value="{{ $cliente->gps }}" maxlength="255" readonly>
                    <button class="btn btn-outline-secondary btn-copy" type="button" id="copyGpsButton" title="Copiar GPS">
                        <i class="bi bi-clipboard"></i>
                    </button>
                    <button class="btn btn-outline-secondary btn-maps" type="button" id="mapsGpsButton"
                        title="Ver en Mapas">
                        <i class="bi bi-geo-alt-fill"></i>
                    </button>
                </div>
            </div>


            <div class="mb-3 custom-form">
                <label for="telefono" class="form-label fw-bold mb-0">
                    <i class="bi bi-telephone-fill"></i> Teléfono
                </label>
                <span class="cursiva-text">Para añadir más de un número, sepáralos por una coma ","</span>
                <div class="input-group">
                    <input type="text" placeholder="No hay registros..." class="form-control editable" id="telefono"
                        name="telefono" value="{{ $cliente->telefono }}" maxlength="50" readonly>
                    <button class="btn btn-outline-secondary btn-call" type="button" id="callButton" title="Llamar">
                        <i class="bi bi-telephone-fill"></i>
                    </button>
                </div>
            </div>
            <div class="mb-3 custom-form">
                <label for="persona_contacto" class="form-label fw-bold">
                    <i class="bi bi-person-fill"></i> Persona de Contacto
                </label>
                <input type="text" placeholder="No hay registros..." class="form-control editable"
                    id="persona_contacto" name="persona_contacto" value="{{ $cliente->persona_contacto }}"
                    maxlength="120" readonly>
            </div>
            <div class="mb-3 custom-form">
                <label for="telefono_persona_contacto" class="form-label fw-bold mb-0">
                    <i class="bi bi-telephone-fill"></i> Teléfono persona de Contacto
                </label>
                <span class="cursiva-text">Para añadir más de un número, sepáralos por una coma ","</span>
                <div class="input-group">
                    <input type="text" placeholder="No hay registros..." class="form-control editable"
                        id="telefono_persona_contacto" name="telefono_persona_contacto"
                        value="{{ $cliente->telefono_persona_contacto }}" maxlength="50" readonly>
                    <button class="btn btn-outline-secondary btn-call" type="button" id="callContactButton"
                        title="Llamar">
                        <i class="bi bi-telephone-fill"></i>
                    </button>
                </div>
            </div>
            <div class="mb-3 custom-form">
                <label for="nota_cliente" class="form-label fw-bold">
                    <i class="bi bi-sticky-fill"></i> Nota del Cliente
                </label>
                <textarea placeholder="No hay registros..." class="form-control editable" id="nota_cliente" name="nota_cliente"
                    maxlength="3000" readonly>{{ $cliente->nota_cliente }}</textarea>
            </div>
            <div class="d-flex justify-content-between">
                {{-- Botón para activar la edición o cancelarla --}}
                <div>
                    <button type="button" id="editarCancelarBtn" class="btn btn-primary">
                        <i class="bi bi-pencil-fill"></i> Editar
                    </button>
                    {{-- Botón para guardar los cambios --}}
                    <button type="button" id="guardarBtn" class="btn btn-success" disabled>
                        <i class="bi bi-check-fill"></i> Guardar
                    </button>

                </div>


                <div>
                    @can('accessModeratorAndAdmin')
                        <button type="button" class="btn btn-danger" onclick="confirmPapeleraCliente({{ $cliente->id }})">
                            <i class="bi bi-trash-fill"></i> Papelera
                        </button>
                    @endcan
                </div>
            </div>

        </form>

        <!-- Formulario de envío a papelera oculto -->
        <form id="papelera-cliente-form-{{ $cliente->id }}"
            action="{{ route('papelera.cliente.mover', $cliente->id) }}" method="POST" style="display: none;">
            @csrf
        </form>


    </div>


    @include('layouts.basculas-cliente', ['basculas' => $cliente->basculas])

    @include('layouts.gallery', [
        'imagenes' => $cliente->imagenes,
        'tipo' => 'clientes', // Este valor se usa para identificar el tipo de entidad
        'idRelacion' => $cliente->id,
    ])

    @include('layouts.videos', [
        'videos' => $cliente->videos,
        'tipo' => 'clientes',
        'idRelacion' => $cliente->id,
    ])


    @include('layouts.archivos', [
        'archivos' => $cliente->archivos,
        'tipo' => 'clientes', // Este valor se usa para identificar el tipo de entidad
        'idRelacion' => $cliente->id,
    ])






    @vite('resources/js/clientes.js')


    <!-- MODAL PAPELERA-->
    <div class="modal fade" id="papeleraClienteConfirmationModal" tabindex="-1"
        aria-labelledby="papeleraClienteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="papeleraClienteConfirmationModalLabel">Confirmar envío a papelera</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres enviar este cliente a la papelera?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmPapeleraClienteButton">Papelera</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal LLAMAR-->
    <div class="modal fade" id="callModal" tabindex="-1" aria-labelledby="callModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="callModalLabel">Seleccionar Número para Llamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se agregarán los botones dinámicamente -->
                    <div id="modalBodyContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

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

        document.getElementById('copyButton').addEventListener('click', function() {
            const direccion = document.getElementById('direccion');
            direccion.select(); // Selecciona el texto del input
            document.execCommand('copy'); // Copia el texto seleccionado


            const icon = this.querySelector('i'); // Encuentra el ícono dentro del botón
            icon.style.color = '#0c5492'; // Cambia el color del ícono a azul

            // Establece un temporizador para revertir el color después de 1 segundo
            setTimeout(function() {
                icon.style.color = ''; // Revierte al color original eliminando el estilo inline
            }, 300); // 500 milisegundos = 0.5 segundo
        });

        document.getElementById('mapsButton').addEventListener('click', function() {
            const direccion = document.getElementById('direccion').value;
            // Detectar dispositivos iOS para usar Apple Maps, de lo contrario usar Google Maps
            const iOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
            const baseURL = iOS ? 'http://maps.apple.com/?q=' : 'https://www.google.com/maps/search/?api=1&query=';
            window.open(baseURL + encodeURIComponent(direccion), '_blank');
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Copiar GPS
            document.getElementById('copyGpsButton').addEventListener('click', function() {
                const gps = document.getElementById('gps');
                gps.select();
                document.execCommand('copy');

                const icon = this.querySelector('i');
                icon.style.color = '#0c5492';

                setTimeout(function() {
                    icon.style.color = '';
                }, 300);
            });

            // Ver GPS en mapas
            document.getElementById('mapsGpsButton').addEventListener('click', function() {
                const gps = document.getElementById('gps').value;
                const iOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
                const baseURL = iOS ? 'http://maps.apple.com/?q=' :
                    'https://www.google.com/maps/search/?api=1&query=';
                window.open(baseURL + encodeURIComponent(gps), '_blank');
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Selecciona todos los inputs y textareas de la página
            const formElements = document.querySelectorAll('input, textarea');

            formElements.forEach(element => {
                element.addEventListener('focus', function() {
                    // Si el elemento está en readonly, desenfócalo inmediatamente
                    if (element.hasAttribute('readonly')) {
                        element.blur();
                    }
                });
            });
        });



        let enEdicion = false; // Estado de edición

        document.getElementById('editarCancelarBtn').addEventListener('click', function() {
            if (!enEdicion) {
                // Activar modo edición
                entrarModoEdicion();
            } else {
                // Cancelar modo edición
                cancelarEdicion();
            }
        });

        document.getElementById('guardarBtn').addEventListener('click', function() {
            if (enEdicion) {
                // Aquí el código para guardar los cambios
                document.getElementById('clienteForm')
                    .submit(); // Asegúrate de que el ID del formulario sea correcto
                cancelarEdicion(); // Opcional, para salir del modo de edición después de guardar
            }
        });

        function entrarModoEdicion() {
            document.querySelectorAll('.form-control.editable').forEach(function(element) {
                element.removeAttribute('readonly'); // Hacer campos editables
            });
            document.getElementById('guardarBtn').disabled = false; // Activar botón de guardar
            document.getElementById('editarCancelarBtn').classList.remove('btn-primary');
            document.getElementById('editarCancelarBtn').classList.add('btn-secondary');
            document.getElementById('editarCancelarBtn').innerHTML =
                '<i class="bi bi-x-lg"></i> Cancelar'; // Cambiar a cancelar
            enEdicion = true;
        }

        function cancelarEdicion() {
            document.querySelectorAll('.form-control.editable').forEach(function(element) {
                element.setAttribute('readonly', 'readonly'); // Restaurar estado no editable
            });
            document.getElementById('guardarBtn').disabled = true; // Desactivar botón de guardar
            document.getElementById('editarCancelarBtn').classList.remove('btn-secondary');
            document.getElementById('editarCancelarBtn').classList.add('btn-primary');
            document.getElementById('editarCancelarBtn').innerHTML =
                '<i class="bi bi-pencil-fill"></i> Editar'; // Cambiar a editar
            enEdicion = false;
        }
    </script>
    <script>
        // Obtén el elemento del textarea por su ID
        var textarea = document.getElementById("nota_cliente");

        // Ajusta automáticamente la altura del textarea en función de su contenido
        textarea.style.height = "auto";
        textarea.style.height = (textarea.scrollHeight) + "px";

        // Obtén el elemento del textarea por su ID
        var textareaHorario = document.getElementById("horario");

        // Ajusta automáticamente la altura del textarea en función de su contenido
        textareaHorario.style.height = "auto";
        textareaHorario.style.height = (textareaHorario.scrollHeight) + "px";
    </script>
@endsection
