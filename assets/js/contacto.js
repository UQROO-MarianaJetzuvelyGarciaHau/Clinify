/* =========================
   VALIDACIÓN CONTACTO
========================= */

const formularioContacto = document.querySelector(".formulario-contacto");

if (formularioContacto) {
    formularioContacto.addEventListener("submit", function (e) {
        const telefono = document.getElementById("telefono").value.trim();

        if (!/^\d{10}$/.test(telefono)) {
            alert("El teléfono debe contener exactamente 10 dígitos.");
            e.preventDefault();
        }
    });
}

/* =========================
   TOAST DE CONTACTO
========================= */

const toast = document.getElementById("toast");

if (toast) {
    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transition = "0.5s";

        setTimeout(() => {
            toast.remove();

            // Limpia la URL para que no vuelva a salir al recargar
            window.history.replaceState({}, document.title, window.location.pathname);
        }, 500);

    }, 3000);
}