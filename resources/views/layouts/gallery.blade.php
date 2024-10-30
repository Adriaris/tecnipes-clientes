    <section class="max-width-700">


        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h2-title">Imágenes</h2>
            @can('accessModeratorAndAdmin')
                <button id="activate-delete" class="btn btn-outline-danger">Activar eliminación</button>
            @endcan
        </div>




        <div class="pswp-gallery galeria-container" id="gallery">
            @foreach ($imagenes as $imagen)
                <a href="{{ asset('storage/' . $imagen->url_imagen) }}" class="galeria-item"
                    data-pswp-width="{{ $imagen->width }}" data-pswp-height="{{ $imagen->height }}" target="_blank">
                    <img src="{{ asset('storage/' . $imagen->thumbnail_url) }}" alt="{{ $imagen->title }}">
                    @can('accessModeratorAndAdmin')
                        <button class="btn btn-danger btn-sm delete-button" style="display:none;"
                            onclick="confirmDeleteImagen({{ $imagen->id }})">
                            <i class="bi bi-trash"></i> Eliminar
                        </button>
                        <form id="delete-imagen-form-{{ $imagen->id }}"
                            action="{{ route('imagenes.eliminar', $imagen->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endcan
                </a>
            @endforeach


            <div class="galeria-upload">
                <form action="{{ route('imagenes.agregar') }}" method="post" enctype="multipart/form-data"
                    id="formSubirImagen">
                    @csrf
                    <input type="hidden" name="tipo" value="{{ $tipo }}">
                    <input type="hidden" name="idRelacion" value="{{ $idRelacion }}">
                    <label for="imagen">
                        <i class="bi bi-plus"></i>
                        <input type="file" name="imagen" id="imagen" accept="image/*" required>
                    </label>
                </form>

            </div>
        </div>
    </section>

    @vite('resources/js/imagenes.js')


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteButtons = document.querySelectorAll('.delete-button');

            // Agregar el evento de clic a cada botón de eliminar
            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.stopPropagation(); // Detiene la propagación del evento
                    event
                        .preventDefault(); // Previene el comportamiento por defecto (siguiendo el enlace)
                    var imagenId = this.getAttribute(
                        'data-imagen-id'
                    ); // Asegúrate de agregar un atributo 'data-imagen-id' a cada botón en el HTML

                    confirmDeleteImagen(imagenId);
                });
            });

            var links = document.querySelectorAll('.pswp-gallery .galeria-item');
            links.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    if (link.classList.contains('activated')) {
                        event
                            .preventDefault(); // Previene que se abra el enlace si está en modo de eliminación
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                // Manejo de la selección de imágenes para subir
                var inputImagen = document.getElementById('imagen');
                inputImagen.addEventListener('change', function() {
                    if (inputImagen.files.length > 0) {
                        var formulario = document.getElementById('formSubirImagen');
                        formulario.submit();
                        conse.log("imagen enviada")
                    } else {
                        conse.log("imagen NO enviada")
                    }
                });

            });

            document.getElementById('activate-delete').addEventListener('click', function() {
                var galeriaItems = document.querySelectorAll('.galeria-item');
                galeriaItems.forEach(function(item) {
                    item.classList.toggle('activated'); // Alternar la clase 'activated'
                    var deleteButton = item.querySelector('.delete-button');
                    deleteButton.style.display = (deleteButton.style.display === 'none' ||
                            deleteButton.style
                            .display === '') ? 'block' :
                        'none'; // Alternar la visibilidad del botón de eliminar
                });
                // Alternar las clases para el estilo del botón
                this.classList.toggle('btn-danger');
                this.classList.toggle('btn-outline-danger');

                // Cambiar el texto del botón
                if (this.classList.contains('btn-danger')) {
                    this.textContent = 'Desactivar eliminación';
                } else {
                    this.textContent = 'Activar eliminación';
                }
            });
        });

        function confirmDeleteImagen(imagenId) {
            var form = document.getElementById('delete-imagen-form-' + imagenId);
            form.submit(); // Envía el formulario directamente
        }
    </script>
