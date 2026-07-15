

/* Buscar todos los botones Ver más */
const botonesVerPlan = document.querySelectorAll(
    ".btn-ver-plan"
);

/* Recuperar el modal y sus controles */
const modalPlan = document.getElementById("modalPlan");

const cerrarModalPlan = document.getElementById(
    "cerrarModalPlan"
);

const fondoModalPlan = document.getElementById(
    "fondoModalPlan"
);


/* Recuperar los espacios donde se mostrarán los datos */
const modalPlanNombre = document.getElementById(
    "modalPlanNombre"
);

const modalPlanPrecio = document.getElementById(
    "modalPlanPrecio"
);

const modalPlanPeriodo = document.getElementById(
    "modalPlanPeriodo"
);

const modalPlanDescripcion = document.getElementById(
    "modalPlanDescripcion"
);

const modalPlanAlmacenamiento = document.getElementById(
    "modalPlanAlmacenamiento"
);

const modalPlanEstudios = document.getElementById(
    "modalPlanEstudios"
);

const modalPlanSoporte = document.getElementById(
    "modalPlanSoporte"
);


/*
Agregar el evento click a cada botón.

El botón seleccionado contiene los datos de su propio plan.
*/
botonesVerPlan.forEach((boton) => {

    boton.addEventListener("click", () => {

        modalPlanNombre.textContent =
            boton.dataset.nombre;

        modalPlanPrecio.textContent =
            `$${boton.dataset.precio} MXN`;

        modalPlanPeriodo.textContent =
            boton.dataset.periodo;

        modalPlanDescripcion.textContent =
            boton.dataset.descripcion;

        modalPlanAlmacenamiento.textContent =
            boton.dataset.almacenamiento;

        modalPlanEstudios.textContent =
            boton.dataset.estudios;

        modalPlanSoporte.textContent =
            boton.dataset.soporte;


        /* Mostrar el modal */
        modalPlan.classList.add("activo");

        modalPlan.setAttribute(
            "aria-hidden",
            "false"
        );

        document.body.classList.add(
            "modal-abierto"
        );

    });

});


/* Función reutilizable para cerrar */
function ocultarModalPlan() {

    modalPlan.classList.remove("activo");

    modalPlan.setAttribute(
        "aria-hidden",
        "true"
    );

    document.body.classList.remove(
        "modal-abierto"
    );

}


/* Cerrar mediante la X */
cerrarModalPlan.addEventListener(
    "click",
    ocultarModalPlan
);


/* Cerrar al presionar el fondo oscuro */
fondoModalPlan.addEventListener(
    "click",
    ocultarModalPlan
);


/* Cerrar mediante la tecla Escape */
document.addEventListener("keydown", (evento) => {

    if (
        evento.key === "Escape" &&
        modalPlan.classList.contains("activo")
    ) {
        ocultarModalPlan();
    }

});

