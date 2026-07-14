/* =========================
   MENÚ RESPONSIVO
========================= */
/** Lo que hacemos es esperar un click en el menu hamburguesa para aparecer o desaparecer las opciones del menú */

// Buscar el botón del menú hamburguesa y el elemento del menú e almacenar en variables

const btnMenu = document.getElementById("btnMenu");
const menu = document.getElementById("menu");

// Si existen ambos elementos en la página, agregar el evento click para escuchar la 
if (btnMenu && menu) {
    btnMenu.addEventListener("click", () => {
        // Alternar la clase "activo" para mostrar/ocultar el menú.
        menu.classList.toggle("activo"); //toggle (alternar) agrega o quita la clase
    });
}

/* =========================
   SIMULACIÓN DE API: TESTIMONIOS
========================= */


const testimonios = [
    {
        nombre: "Carlos Mendoza",
        texto: "Clinify me ayudó a tener mis estudios médicos más organizados y fáciles de consultar."
    },
    {
        nombre: "Ana López",
        texto: "Me parece una plataforma útil para familias que necesitan guardar información médica."
    },
    {
        nombre: "María Hernández",
        texto: "La idea de tener todo en un solo lugar me da más tranquilidad."
    }
];

// Obtener el contenedor donde se mostrarán los testimonios para manipular y asignarlo a una variable.
const contenedorTestimonios = document.getElementById("contenedorTestimonios");

// Si el contenedor existe, insertar los testimonios en HTML.
if (contenedorTestimonios) {
    // Recorrer el arreglo de testimonios y agregar cada tarjeta.
    testimonios.forEach(testimonio => {
        contenedorTestimonios.innerHTML += `
            <article class="card">
                <h3>${testimonio.nombre}</h3>
                <p>"${testimonio.texto}"</p>
            </article>
        `;
    });
}

/* =========================
   SIMULACIÓN DE API: FAQ
========================= */

const preguntasFrecuentes = [
    {
        pregunta: "¿Clinify almacena estudios médicos reales en este sitio?",
        respuesta: "No. Este sitio es informativo y promocional para presentar la plataforma."
    },
    {
        pregunta: "¿Puedo solicitar más información?",
        respuesta: "Sí. Puedes usar el formulario de contacto para dejar tus datos."
    },
    {
        pregunta: "¿El sitio es responsivo?",
        respuesta: "Sí. Está diseñado para visualizarse en computadoras, tablets y celulares."
    }
];

const contenedorFaq = document.getElementById("contenedorFaq");

// Si el contenedor FAQ existe, agregar cada pregunta y respuesta.
if (contenedorFaq) {
    preguntasFrecuentes.forEach(item => {
        contenedorFaq.innerHTML += `
            <article class="faq-item">
                <h3>${item.pregunta}</h3>
                <p>${item.respuesta}</p>
            </article>
        `; // InnerHTML - agrega contenido al final del contenido
    });
}

/* =========================================
   VALIDACIÓN DEL FORMULARIO DE CONTACTO
========================================= */

const formularioContacto = document.querySelector(
    ".formulario-contacto"
);

if (formularioContacto) {
    formularioContacto.addEventListener(
        "submit",
        function (evento) {
            /*
            Aunque HTML valida el teléfono, hacemos una
            segunda comprobación sencilla con JavaScript.
            */
            const telefono = document
                .getElementById("telefono")
                .value
                .trim();

            if (!/^\d{10}$/.test(telefono)) {
                alert(
                    "El teléfono debe contener exactamente 10 dígitos."
                );

                evento.preventDefault();
            }
        }
    );
}


/* =========================================
   NOTIFICACIÓN TOAST
========================================= */

const toast = document.getElementById("toast");

if (toast) {
    /*
    Esperar tres segundos antes de comenzar
    a ocultar la notificación.
    */
    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateY(-1rem)";
        toast.style.transition = "0.5s ease";

        /*
        Eliminar el elemento después de completar
        la transición visual.
        */
        setTimeout(() => {
            toast.remove();

            /*
            Quitar ?mensaje=exito o ?mensaje=error
            para que el toast no vuelva al recargar.
            */
            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );
        }, 500);

    }, 3000);
}