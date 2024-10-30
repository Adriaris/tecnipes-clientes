import "./bootstrap";
import "../css/styles.css";
import "../css/navbar.css";
import "../css/pagination.css";
import "../css/galeria.css";
import "../css/archivos.css";
import "../css/administracion.css";
import "../css/app.css";
import "../css/basculas.css";
import "../css/clientes.css";
import "../css/video.css";
import "../css/modal.css";

import PhotoSwipeLightbox from "photoswipe/lightbox";
import "photoswipe/style.css";

document.addEventListener("DOMContentLoaded", function () {
    const lightbox = new PhotoSwipeLightbox({
        gallery: "#gallery",
        children: "a",
        pswpModule: () => import("photoswipe"),
    });
    lightbox.init();

    // Manejo de la selección de imágenes para subir
    var inputImagen = document.getElementById("imagen");
    if (inputImagen) {
        inputImagen.addEventListener("change", function () {
            if (inputImagen.files.length > 0) {
                var formulario = document.getElementById("formSubirImagen");
                formulario.submit();
            }
        });
    }
});
