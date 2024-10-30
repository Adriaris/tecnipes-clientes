import { Modal } from "bootstrap";

document.addEventListener("DOMContentLoaded", function () {
    const modalElement = document.getElementById("confirmRecuperarClienteModal");
    const confirmModal = new Modal(modalElement);
    const recuperarClienteBasculaForm = document.getElementById("recuperarClienteBasculaForm");

    window.confirmRecuperarBascula = function (basculaId, clienteId, clienteEliminado) {
        if (clienteEliminado) {
            // Si el cliente está eliminado, configura y muestra el modal
            recuperarClienteBasculaForm.action = `/recuperar-bascula/${basculaId}?recuperar_cliente=${clienteId}`;
            confirmModal.show();
        } else {
            // Si el cliente no está eliminado, realiza la recuperación inmediata solo de la báscula
            recuperarClienteBasculaForm.action = `/recuperar-bascula/${basculaId}`;
            recuperarClienteBasculaForm.submit();
        }
    };
});

document.addEventListener("DOMContentLoaded", function () {
    const modalElement = document.getElementById("confirmRecuperarClienteConBasculasModal");
    const confirmModal = new Modal(modalElement);
    const recuperarClienteForm = document.getElementById("recuperarClienteForm");

    window.confirmRecuperarCliente = function (clienteId, hasBasculasEliminadas) {
        if (hasBasculasEliminadas) {
            // Si el cliente tiene básculas eliminadas, muestra el modal
            recuperarClienteForm.action = `/recuperar-cliente/${clienteId}`;
            confirmModal.show();
        } else {
            // Si el cliente no tiene básculas eliminadas, realiza la recuperación inmediata
            recuperarClienteForm.action = `/recuperar-cliente/${clienteId}`;
            recuperarClienteForm.submit();
        }
    };
});

window.confirmDeleteCliente = function (clienteId) {
    let clienteIdToDelete = clienteId;
    const deleteConfirmationModal = new Modal(
        document.getElementById("deleteClienteConfirmationModal")
    );
    deleteConfirmationModal.show();

    document
        .getElementById("confirmDeleteClienteButton")
        .addEventListener("click", function () {
            if (clienteIdToDelete) {
                document
                    .getElementById("delete-cliente-form-" + clienteIdToDelete)
                    .submit();
            }
        });
};


window.confirmDeleteBascula = function (basculaId) {
    let basculaIdToDelete = basculaId;
    const deleteConfirmationModal = new Modal(
        document.getElementById("deleteConfirmationModal")
    );
    deleteConfirmationModal.show();
    console.log("boton clicado " + basculaIdToDelete);

    document
        .getElementById("confirmDeleteBasculaButton")
        .addEventListener("click", function () {
            console.log("holaa");
            if (basculaIdToDelete) {
                document
                    .getElementById("delete-bascula-form-" + basculaIdToDelete)
                    .submit();
            }
        });
};