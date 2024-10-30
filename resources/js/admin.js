import { Modal } from "bootstrap";

window.confirmDelete = function (userId) {
    let userIdToDelete = userId;
    const deleteConfirmationModal = new Modal(
        document.getElementById("deleteConfirmationModal")
    );
    deleteConfirmationModal.show();

    document
        .getElementById("confirmDeleteButton")
        .addEventListener("click", function () {
            if (userIdToDelete) {
                document
                    .getElementById("delete-form-" + userIdToDelete)
                    .submit();
            }
        });
};
