<section class="max-width-700">
    <div class="files-container">
        <h2 class="h2-title">Archivos</h2>
        @foreach ($archivos as $archivo)
            <div class="d-flex justify-content-between align-middle archivo-item">
                <div class="text-file-width">
                    @php
                        $iconClass = 'bi bi-file-earmark'; // Default icon
                        $colorClass = 'icon-file-default'; // Default color
                        switch ($archivo->tipo_amigable) {
                            case 'Excel':
                                $iconClass = 'bi bi-file-earmark-spreadsheet';
                                $colorClass = 'icon-file-excel';
                                break;
                            case 'PDF':
                                $iconClass = 'bi bi-file-earmark-pdf';
                                $colorClass = 'icon-file-pdf';
                                break;
                            case 'Word':
                                $iconClass = 'bi bi-file-earmark-word';
                                $colorClass = 'icon-file-word';
                                break;
                            case 'TXT':
                                $iconClass = 'bi bi-file-earmark-text';
                                $colorClass = 'icon-file-txt';
                                break;
                            default:
                                $iconClass = 'bi bi-file-earmark';
                                $colorClass = 'icon-file-default';
                        }
                    @endphp
                    <span class="icon-file {{ $colorClass }}">
                        <i class="{{ $iconClass }}"></i>
                    </span>
                    <a href="{{ asset('storage/' . $archivo->url_archivo) }}" target="_blank" class="archivo-title">
                        <span data-archivo-id="{{ $archivo->id }}">{{ $archivo->nombre_original }}</span>

                    </a>
                </div>
                <div>

                    <button class="btn btn-primary btn-sm edit-button-archivo" data-archivo-id="{{ $archivo->id }}">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                    @can('accessModeratorAndAdmin')
                        <button class="btn btn-danger btn-sm me-2 delete-btn"
                            onclick="confirmDeleteArchivo({{ $archivo->id }})">
                            <i class="bi bi-trash"></i>
                        </button>
                        <form id="delete-form-{{ $archivo->id }}" action="{{ route('archivos.eliminar', $archivo->id) }}"
                            method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endcan
                </div>
            </div>
        @endforeach

        <div class="files-upload mt-3">
            <label for="archivo" class="archivo-label">
                <i class="bi bi-plus"></i> Seleccionar archivo
                <input type="file" id="archivo" class="d-none" required>
            </label>

            <!-- Modal para cambiar nombre y subir archivo -->
            <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="uploadFileModalLabel">Cambiar nombre y subir archivo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" id="modalNombreArchivo" class="form-control"
                                placeholder="Nombre del archivo">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" id="submitButton">Subir</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario oculto para la subida de archivos -->
            <form action="{{ route('archivos.agregar') }}" method="post" enctype="multipart/form-data"
                id="formSubirArchivo" class="d-none">
                @csrf
                <input type="hidden" name="tipo" value="{{ $tipo }}">
                <input type="hidden" name="idRelacion" value="{{ $idRelacion }}">
                <input type="hidden" name="nombre_archivo" id="hiddenNombreArchivo">
                <input type="hidden" name="extension_archivo" id="hiddenExtensionArchivo">
                <input type="file" name="archivo" id="hiddenArchivoInput" class="d-none">
            </form>
        </div>



    </div>
</section>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteConfirmationModalArchivos" tabindex="-1"
    aria-labelledby="deleteConfirmationModalArchivosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalArchivosLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que quieres eliminar este archivo?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar el nombre del archivo -->
<div class="modal fade" id="editArchivoModal" tabindex="-1" aria-labelledby="editArchivoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editArchivoModalLabel">Editar nombre del archivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="archivoNameInput"
                    placeholder="Nuevo nombre del archivo">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="saveArchivoNameBtn">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>


@vite('resources/js/archivos.js')
