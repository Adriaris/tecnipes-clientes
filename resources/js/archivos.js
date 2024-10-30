import { Modal } from "bootstrap";

window.confirmDeleteArchivo = function (archivoId) {
    let archivoIdToDelete = archivoId;
    const deleteConfirmationModal = new Modal(
        document.getElementById("deleteConfirmationModalArchivos")
    );
    deleteConfirmationModal.show();

    document
        .getElementById("confirmDeleteButton")
        .addEventListener("click", function () {
            if (archivoIdToDelete) {
                document
                    .getElementById("delete-form-" + archivoIdToDelete)
                    .submit();
            }
        });
};

//---------------------------------------------

document.addEventListener("DOMContentLoaded", () => {
    const archivoInput = document.getElementById("archivo");
    const submitButton = document.getElementById("submitButton");
    const uploadModal = new Modal(document.getElementById("uploadFileModal"));
    const hiddenArchivoInput = document.getElementById("hiddenArchivoInput");
    const hiddenExtensionArchivo = document.getElementById(
        "hiddenExtensionArchivo"
    );

    archivoInput.addEventListener("change", function (event) {
        if (this.files.length > 0) {
            const file = this.files[0];
            const fileName = file.name;
            const fileExtension = fileName.split(".").pop();

            document.getElementById("modalNombreArchivo").value =
                fileName.replace(/\.[^/.]+$/, ""); // Pre-fill without extension
            hiddenExtensionArchivo.value = fileExtension; // Set file extension
            hiddenArchivoInput.files = this.files; // Copy selected file to hidden input

            uploadModal.show();
        }
    });

    submitButton.addEventListener("click", function () {
        const modalNombreArchivo =
            document.getElementById("modalNombreArchivo");
        const form = document.getElementById("formSubirArchivo");
        document.getElementById("hiddenNombreArchivo").value =
            modalNombreArchivo.value;

        form.submit();
    });

    //---------------------------------------------------------------------------------------------

    // Añade un listener a cada archivo-item
    const archivoItems = document.querySelectorAll(".archivo-item");
    archivoItems.forEach((item) => {
        item.addEventListener("click", function (event) {
            // Chequea si el clic fue en el botón de eliminar o en algún elemento dentro del botón
            if (!event.target.closest(".delete-btn, .edit-button-archivo")) {
                // Si no es el botón de eliminar, encuentra el enlace dentro del elemento archivo-item
                const link = item.querySelector("a");
                if (link) {
                    // Abre el enlace en una nueva pestaña
                    window.open(link.href, "_blank");
                }
            }
        });
    });

    // Previene la propagación del evento clic en los enlaces para evitar la activación doble
    const links = document.querySelectorAll(".archivo-item a");
    links.forEach((link) => {
        link.addEventListener("click", function (event) {
            event.stopPropagation(); // Esto detiene la propagación del evento clic hacia arriba (hacia el archivo-item)
        });
    });

    //---------------------------------------------------------------------------------------

    const editButtons = document.querySelectorAll(".edit-button-archivo");
    const editArchivoModalElement = document.getElementById("editArchivoModal");
    const editArchivoNombremodal = new Modal(editArchivoModalElement); // Instanciar el modal
    const saveButton = document.getElementById("saveArchivoNameBtn");
    let currentArchivoId = null; // Variable para almacenar el ID del archivo actual

    editButtons.forEach((button) => {
        button.addEventListener("click", function () {
            currentArchivoId = this.dataset.archivoId; // Almacenar el ID del archivo
            const archivoNameElement = document.querySelector(
                `.archivo-title > span[data-archivo-id="${currentArchivoId}"]`
            );

            if (archivoNameElement) {
                document.getElementById("archivoNameInput").value =
                    archivoNameElement.textContent; // Prellenar el nombre actual
                editArchivoNombremodal.show();
            } else {
                console.log(archivoNameElement); // Ver el elemento obtenido
                console.log("aaa");
                console.error(
                    "Error: Elemento de título de archivo no encontrado"
                );
                console.log(currentArchivoId); // Ver el ID actual
            }
        });
    });

    saveButton.addEventListener("click", function () {
        const newName = document.getElementById("archivoNameInput").value;
        // Petición AJAX para actualizar el nombre del archivo en el servidor
        fetch(`/archivos/editar/${currentArchivoId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content, // Asegurar que el token CSRF está disponible
            },
            body: JSON.stringify({ nombre: newName }),
        })
            .then((response) => response.json())
            .then(() => {
                const archivoNameElement = document.querySelector(
                    `.archivo-title > span[data-archivo-id="${currentArchivoId}"]`
                );
                if (archivoNameElement) {
                    archivoNameElement.textContent = newName; // Actualizar el título en la página
                    editArchivoNombremodal.hide();
                } else {
                    console.error(
                        "Error: Elemento de título de archivo no encontrado después de guardar"
                    );
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Error al actualizar el nombre del archivo.");
            });
    });
});
