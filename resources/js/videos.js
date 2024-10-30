import { Modal } from "bootstrap";

window.confirmDeleteVideo = function (videoId) {
    let videoIdToDelete = videoId;
    const deleteConfirmationModal = new Modal(
        document.getElementById("deleteConfirmationModalVideos")
    );
    deleteConfirmationModal.show();

    // Añadimos un único listener al botón de confirmación para eliminar el video
    document.getElementById("confirmDeleteVideoButton").addEventListener(
        "click",
        function () {
            if (videoIdToDelete) {
                document
                    .getElementById("delete-video-form-" + videoIdToDelete)
                    .submit();
            }
        },
        { once: true }
    ); // La opción 'once' asegura que el evento se ejecute una sola vez
};

document.addEventListener("DOMContentLoaded", function () {
    const videoInput = document.getElementById("video");
    const videoNameInput = document.getElementById("videoName");
    const nombreVideo = document.getElementById("nombreVideo");
    const videoModal = new Modal(document.getElementById("videoNameModal"));
    const saveVideoButton = document.getElementById("saveVideo");
    const cancelUploadButton = document.getElementById("cancelUpload");
    const formSubirVideo = document.getElementById("formSubirVideo");
    const progressBar = document.getElementById("uploadProgress");

    let xhr = new XMLHttpRequest();
    let uploadInProgress = false;

    videoInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            // Comprobando el tamaño del archivo en bytes (25 MB)
            if (file.size > 26214400) {
                fileErrorMsg.textContent =
                    "El video es demasiado grande. El tamaño máximo permitido es 25 MB.";
                fileErrorMsg.style.display = "block";
                videoInput.value = ""; // Resetear el input del archivo
                return; // Salir de la función si el archivo es demasiado grande
            } else {
                fileErrorMsg.style.display = "none";
            }

            videoNameInput.value = file.name.replace(/\..+$/, ""); // Elimina la extensión del archivo para prellenar el nombre
            videoModal.show();
        }
    });

    saveVideoButton.addEventListener("click", function () {
        nombreVideo.value = videoNameInput.value;

        const formData = new FormData(formSubirVideo);

        progressBar.style.width = "0%";
        progressBar.innerText = "0%";
        progressBar.parentNode.removeAttribute("hidden");
        saveVideoButton.disabled = true;
        uploadInProgress = true;

        xhr.upload.onprogress = function (event) {
            if (event.lengthComputable) {
                const percentComplete = Math.floor(
                    (event.loaded / event.total) * 100
                );
                progressBar.style.width = percentComplete + "%";
                progressBar.innerText = percentComplete + "%";
            }
        };

        xhr.onload = function () {
            uploadInProgress = false;
            saveVideoButton.disabled = false;
            progressBar.parentNode.setAttribute("hidden", "");
            if (xhr.status === 200) {
                videoModal.hide();
                window.location.reload();
            } else {
                alert("Error al subir el video.");
            }
        };

        xhr.onerror = function () {
            if (!uploadInProgress) {
                return; // Ignora errores después de la cancelación
            }
            alert("Error al conectar al servidor.");
            saveVideoButton.disabled = false;
            progressBar.parentNode.setAttribute("hidden", "");
            uploadInProgress = false;
        };

        xhr.open("POST", formSubirVideo.action, true);
        xhr.send(formData);
    });

    cancelUploadButton.addEventListener("click", function () {
        if (uploadInProgress) {
            xhr.abort();
            uploadInProgress = false;
            progressBar.parentNode.setAttribute("hidden", "");
            progressBar.style.width = "0%";
            progressBar.innerText = "0%";
            saveVideoButton.disabled = false;
            videoModal.hide();
        }
    });

    // Manipulación del clic en los elementos de vídeo para mostrar en un modal
    const videoItems = document.querySelectorAll(".video-item");
    videoItems.forEach((item) => {
        item.addEventListener("click", function (event) {
            // Verifica si el clic fue dentro de un botón de editar, guardar cambios o eliminar
            if (
                !event.target.closest(
                    ".edit-button-video, .delete-button-video"
                )
            ) {
                // Si el clic no fue en uno de los botones, ejecuta la lógica para abrir el modal o lo que necesites
                const videoSrc = this.querySelector("source").src;

                const modalVideo = document.getElementById("modalVideo");

                if (modalVideo) {
                    modalVideo.querySelector("source").src = videoSrc;
                    modalVideo.load();
                    modalVideo.play();

                    const videoModal = new Modal(
                        document.getElementById("videoModal")
                    );
                    videoModal.show();
                }
            }
        });
    });

    if (document.getElementById("videoModal")) {
        document
            .getElementById("videoModal")
            .addEventListener("hidden.bs.modal", function () {
                const modalVideo = document.getElementById("modalVideo");
                modalVideo.pause();
                modalVideo.querySelector("source").src = ""; // Clear the source
                modalVideo.load();
            });
    }

    //-----------------------------------------------------------------------------------------------

    const editButtons = document.querySelectorAll(".edit-button-video");
    const editNameElement = document.getElementById("editVideoModal");
    const editNameModal = new Modal(editNameElement); // Instanciar el modal
    const saveButton = document.getElementById("saveVideoNameBtn");
    let currentVideoId = null; // Variable to store the current video's ID

    editButtons.forEach((button) => {
        button.addEventListener("click", function () {
            currentVideoId = this.dataset.videoId; // Store video ID
            const videoNameElement = document.querySelector(
                `p.video-title[data-video-id="${currentVideoId}"]`
            );
            document.getElementById("videoNameInput").value =
                videoNameElement.textContent; // Pre-fill current name
            editNameModal.show();
        });
    });

    saveButton.addEventListener("click", function () {
        const newName = document.getElementById("videoNameInput").value;
        // AJAX request to update the video name on the server
        fetch(`/videos/editar/${currentVideoId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content, // Ensure CSRF token is available
            },
            body: JSON.stringify({ nombre: newName }),
        })
            .then((response) => response.json())
            .then(() => {
                const videoNameElement = document.querySelector(
                    `p.video-title[data-video-id="${currentVideoId}"]`
                );
                videoNameElement.textContent = newName; // Update the title on the page
                editNameModal.hide();
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Error updating the video name.");
            });
    });
});
