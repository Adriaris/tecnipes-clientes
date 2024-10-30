import { Modal } from "bootstrap";


window.confirmPapeleraBascula = function (basculaId) {
    let basculaIdToPapelera = basculaId;
    const papeleraConfirmationModal = new Modal(
        document.getElementById("papeleraBasculaConfirmationModal")
    );
    papeleraConfirmationModal.show();

    document
        .getElementById("confirmPapeleraBasculaButton")
        .addEventListener("click", function () {
            if (basculaIdToPapelera) {
                document
                    .getElementById("papelera-bascula-form-" + basculaIdToPapelera)
                    .submit();
            }
        });
};

document.addEventListener("DOMContentLoaded", function () {
    const buscarClienteInput = document.getElementById("buscarCliente");
    const clientesContainer = document.getElementById("clientes-container");
    const paginationLinks = document.getElementById("pagination-links");
    const moverBasculaBtn = document.getElementById("moverBasculaBtn");
    let selectedClienteId = null;
    let basculaIdToMove = null;

    // Asignar el id de la báscula al botón mover
    window.setBasculaId = function (basculaId) {
        basculaIdToMove = basculaId;
        moverBasculaBtn.setAttribute("data-bascula-id", basculaId);
        fetchClientes(); // Cargar todos los clientes al abrir el modal
    };

    // Buscar clientes al escribir en el input
    buscarClienteInput.addEventListener("input", function () {
        fetchClientes(this.value);
    });

    // Fetch clients from the server
    function fetchClientes(query = "", page = 1) {
        fetch(`/buscar-clientes-modal?query=${query}&page=${page}`)
            .then((response) => response.json())
            .then((data) => {
                clientesContainer.innerHTML = data.clientes;
                paginationLinks.innerHTML = data.pagination;
                agregarEventosClientes();
                agregarEventosPaginacion();
            })
            .catch((error) => console.error("Error:", error));
    }

    // Seleccionar un cliente
    function agregarEventosClientes() {
        const seleccionarBtns =
            clientesContainer.querySelectorAll(".btn-seleccionar");
        seleccionarBtns.forEach((btn) => {
            btn.addEventListener("click", function () {
                const clienteId = this.getAttribute("data-cliente-id");
                const card = this.closest(".card");
                if (selectedClienteId === clienteId) {
                    selectedClienteId = null;
                    moverBasculaBtn.disabled = true;
                    resetSeleccionarBtns();
                    card.classList.remove("selected-card");
                } else {
                    selectedClienteId = clienteId;
                    moverBasculaBtn.disabled = false;
                    resetSeleccionarBtns();
                    this.textContent = "Cancelar";
                    this.classList.add("btn-secondary");
                    this.classList.remove("btn-primary");
                    card.classList.add("selected-card");
                }
            });
        });
    }

    // Resetear botones seleccionar
    function resetSeleccionarBtns() {
        const seleccionarBtns =
            clientesContainer.querySelectorAll(".btn-seleccionar");
        seleccionarBtns.forEach((btn) => {
            btn.textContent = "Seleccionar";
            btn.classList.add("btn-primary");
            btn.classList.remove("btn-secondary");
            const card = btn.closest(".card");
            card.classList.remove("selected-card");
            if (
                selectedClienteId &&
                btn.getAttribute("data-cliente-id") !== selectedClienteId
            ) {
                btn.disabled = true;
                card.classList.add("disabled-card");
            } else {
                btn.disabled = false;
                card.classList.remove("disabled-card");
            }
        });
    }

    // Mover báscula al nuevo cliente
    moverBasculaBtn.addEventListener("click", function () {
        if (selectedClienteId && basculaIdToMove) {
            fetch("/mover-bascula", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    bascula_id: basculaIdToMove,
                    cliente_id: selectedClienteId,
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch((error) => console.error("Error:", error));
        }
    });

    // Agregar eventos a los enlaces de paginación
    function agregarEventosPaginacion() {
        const paginationLinks = document.querySelectorAll(".pagination a");
        paginationLinks.forEach((link) => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const url = new URL(this.href);
                const page = url.searchParams.get("page");
                const query = buscarClienteInput.value;
                fetchClientes(query, page);
            });
        });
    }
});
