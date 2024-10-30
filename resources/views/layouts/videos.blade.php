<section class="max-width-700">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h2-title">Videos</h2>
    </div>

    <div class="video-container" id="videoGallery">
        @foreach ($videos as $video)
            <div class="d-flex justify-content-between video-item">
                <div class="video-preview">
                    <video preload="none" loading="lazy">
                        <source src="{{ asset('storage/' . $video->url_video) }}" type="video/mp4">
                        Su navegador no soporta la etiqueta de vídeo.
                    </video>
                </div>
                <p class="video-title" data-video-id="{{ $video->id }}"> <i class="bi bi-play-fill"></i>
                    {{ $video->nombre }}</p>
                <div>
                    <button class="btn btn-primary btn-sm edit-button-video" data-video-id="{{ $video->id }}">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    @can('accessModeratorAndAdmin')
                        <button class="btn btn-danger btn-sm me-2 delete-button-video"
                            onclick="confirmDeleteVideo({{ $video->id }})" data-video-id="{{ $video->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="delete-video-form-{{ $video->id }}" action="{{ route('videos.eliminar', $video->id) }}"
                            method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endcan
                </div>
            </div>
        @endforeach




        <div>
            <form action="{{ route('videos.agregar') }}" method="post" enctype="multipart/form-data"
                id="formSubirVideo">
                @csrf
                <input type="hidden" name="tipo" value="{{ $tipo }}">
                <input type="hidden" name="idRelacion" value="{{ $idRelacion }}">
                <label for="video" class="video-label">
                    <i class="bi bi-plus"></i> Seleccionar video
                    <input type="file" name="video" id="video" accept="video/*" required style="display:none;">

                </label>

                <input type="text" name="nombre" id="nombreVideo" style="display:none;">
            </form>

        </div>
        <p id="fileErrorMsg" style="color: red; text-align:center; display: none;"></p>
    </div>
</section>


<!-- MODAL PREVISUALIZACIÓN VIDEO -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Reproducción de Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <video id="modalVideo" controls autoplay>
                    <source src="" type="video/mp4">
                    Tu navegador no soporta la etiqueta de vídeo.
                </video>
            </div>
        </div>
    </div>
</div>

<!-- MODAL SUBIDA VIDEO -->
<div class="modal fade" id="videoNameModal" tabindex="-1" aria-labelledby="videoNameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoNameModalLabel">Cambiar nombre y subir vídeo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <input type="text" id="videoName" class="form-control" placeholder="Nombre del vídeo">
                </div>

                <!-- Barra de progreso, inicialmente oculta -->
                <div class="progress" style="height: 20px;" hidden>
                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                        aria-valuemax="100" id="uploadProgress">0%</div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelUpload"
                    data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveVideo">Subir</button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL CONFIRMACIÓN ELIMINACIÓN -->
<div class="modal fade" id="deleteConfirmationModalVideos" tabindex="-1"
    aria-labelledby="deleteConfirmationModalVideosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalVideosLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que quieres eliminar este video?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteVideoButton">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar el nombre del video -->
<div class="modal fade" id="editVideoModal" tabindex="-1" aria-labelledby="editVideoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVideoModalLabel">Editar Nombre del Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="videoNameInput" placeholder="Nuevo nombre del video">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveVideoNameBtn">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>





@vite('resources/js/videos.js')
