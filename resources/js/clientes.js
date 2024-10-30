import { Modal } from "bootstrap";



window.confirmPapeleraCliente = function (clienteId) {
    let clienteIdToPapelera = clienteId;
    const papeleraConfirmationModal = new Modal(
        document.getElementById("papeleraClienteConfirmationModal")
    );
    papeleraConfirmationModal.show();

    document
        .getElementById("confirmPapeleraClienteButton")
        .addEventListener("click", function () {
            if (clienteIdToPapelera) {
                document
                    .getElementById("papelera-cliente-form-" + clienteIdToPapelera)
                    .submit();
            }
        });
};


function cleanPhoneNumber(phoneNumber) {
    return phoneNumber.replace(/\D/g, ''); // Elimina todo lo que no sea dígito
}

function extractPhoneNumbers(input) {
    return input.split(',').map(number => cleanPhoneNumber(number.trim())).filter(number => number !== '');
}

function addCountryPrefix(phoneNumbers) {
    return phoneNumbers.map(number => {
        if (number.startsWith('34')) {
            return `+${number}`;
        } else if (!number.startsWith('+34')) {
            return `+34${number}`;
        }
        return number;
    });
}

function showModalWithNumbers(numbers) {
    const modalBodyContent = document.getElementById('modalBodyContent');
    modalBodyContent.innerHTML = '';

    numbers.forEach((number) => {
        const button = document.createElement('button');
        button.className = 'border-blue color-blue m-1';
        button.innerHTML = `<i class="bi bi-telephone-fill color-orange "></i> <b>${number}</b>`;
        button.onclick = function() {
            window.location.href = 'tel:' + number;
        };
        modalBodyContent.appendChild(button);
    });

    const callModalElement = document.getElementById('callModal');
    const callModal = new Modal(callModalElement);
    callModal.show();
}

document.getElementById('callButton').addEventListener('click', function() {
    let telefono = document.getElementById('telefono').value;
    let phoneNumbers = extractPhoneNumbers(telefono);
    phoneNumbers = addCountryPrefix(phoneNumbers);

    if (phoneNumbers.length === 1) {
        window.location.href = 'tel:' + phoneNumbers[0];
    } else if (phoneNumbers.length > 1) {
        showModalWithNumbers(phoneNumbers);
    } else {
        alert('El número de teléfono está vacío.');
    }
});

document.getElementById('callContactButton').addEventListener('click', function() {
    let telefonoContacto = document.getElementById('telefono_persona_contacto').value;
    let phoneNumbers = extractPhoneNumbers(telefonoContacto);
    phoneNumbers = addCountryPrefix(phoneNumbers);

    if (phoneNumbers.length === 1) {
        window.location.href = 'tel:' + phoneNumbers[0];
    } else if (phoneNumbers.length > 1) {
        showModalWithNumbers(phoneNumbers);
    } else {
        alert('El número de teléfono de contacto está vacío.');
    }
});