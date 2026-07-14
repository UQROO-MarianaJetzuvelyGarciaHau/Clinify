<?php

/*
==========================================================
CONEXIÓN CON LA BASE DE DATOS
==========================================================

La conexión se carga antes de mostrar el contenido porque
necesitamos recuperar los planes desde MySQL.
*/
require_once 'config/conexion.php';


/*
==========================================================
CONSULTAR PLANES ACTIVOS
==========================================================

Solo recuperamos los planes con estado "Activo".

Los planes:
- Proximamente
- Desactivado

no aparecerán en esta sección del inicio.
*/
$consultaPlanesInicio = "
    SELECT
        id,
        nombre,
        descripcion,
        precio,
        periodo,
        almacenamiento,
        cantidad_estudios,
        soporte
    FROM planes
    WHERE estado = 'Activo'
    ORDER BY precio ASC
";

$resultadoPlanesInicio = mysqli_query(
    $conexion,
    $consultaPlanesInicio
);


/*
==========================================================
OBTENER EL PLAN MÁS SOLICITADO
==========================================================

La columna contactos.plan_interes contiene el ID del plan
seleccionado por cada persona en el formulario.

Contamos cuántas veces aparece cada plan y recuperamos
el que tenga la cantidad más alta.
*/
$consultaMasVendidoInicio = "
    SELECT
        planes.id,
        COUNT(contactos.id) AS total_interesados
    FROM planes
    INNER JOIN contactos
        ON contactos.plan_interes = planes.id
    WHERE planes.estado = 'Activo'
    GROUP BY planes.id
    HAVING COUNT(contactos.id) > 0
    ORDER BY total_interesados DESC, planes.id ASC
    LIMIT 1
";

$resultadoMasVendidoInicio = mysqli_query(
    $conexion,
    $consultaMasVendidoInicio
);

/*
Por defecto no existe un plan más vendido.

Esto evita que aparezca la etiqueta cuando todavía
no hay contactos registrados.
*/
$planMasVendidoInicioId = null;

if (
    $resultadoMasVendidoInicio &&
    mysqli_num_rows($resultadoMasVendidoInicio) > 0
) {
    $filaMasVendidoInicio = mysqli_fetch_assoc(
        $resultadoMasVendidoInicio
    );

    $planMasVendidoInicioId =
        (int) $filaMasVendidoInicio['id'];
}

?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main>

    <!-- Sección principal -->
    <section class="hero">

        <div class="contenedor hero-contenido">

            <div class="hero-texto">

                <span class="etiqueta">
                    Gestión médica personal
                </span>

                <h1>
                    Organiza tus estudios médicos en un solo lugar
                </h1>

                <p>
                    Clinify te ayuda a mantener tu información médica
                    ordenada, accesible y fácil de consultar cuando más
                    la necesitas.
                </p>

                <div class="hero-botones">

                    <a
                        href="planes.php"
                        class="btn-principal">

                        Ver planes

                    </a>

                    <a
                        href="contacto.php"
                        class="btn-secundario">

                        Solicitar información

                    </a>

                </div>

            </div>


            <div class="hero-imagen">

                <img
                    src="assets/img/logo-clinify.png"
                    alt="Vista del logo de Clinify">

            </div>

        </div>

    </section>


    <!-- Beneficios principales -->
    <section class="seccion">

        <div class="contenedor">

            <div class="titulo-seccion">

                <span>
                    Beneficios
                </span>

                <h2>
                    Una forma más simple de cuidar tu información médica
                </h2>

            </div>


            <div class="grid-3">

                <article class="card">

                    <div class="numero">
                        1
                    </div>

                    <h3>
                        Información organizada
                    </h3>

                    <p>
                        Guarda tus estudios médicos de manera ordenada
                        y fácil de encontrar.
                    </p>

                </article>


                <article class="card">

                    <div class="numero">
                        2
                    </div>

                    <h3>
                        Acceso rápido
                    </h3>

                    <p>
                        Consulta tus documentos importantes desde
                        cualquier dispositivo.
                    </p>

                </article>


                <article class="card">

                    <div class="numero">
                        3
                    </div>

                    <h3>
                        Mayor confianza
                    </h3>

                    <p>
                        Ten a la mano tu historial médico cuando visites
                        a un especialista.
                    </p>

                </article>

            </div>

        </div>

    </section>


    <!-- Sobre Clinify -->
    <section class="seccion-info">

        <div class="contenedor info-contenido">

            <div class="info-imagen">

                <img
                    src="assets/img/image-clinify.png"
                    alt="Aplicación de salud Clinify">

            </div>


            <div class="info-texto">

                <span>
                    ¿Qué es Clinify?
                </span>

                <h2>
                    Una plataforma pensada para pacientes y familias
                </h2>

                <p>
                    Clinify es una propuesta digital para centralizar
                    estudios médicos personales, evitando pérdidas de
                    documentos físicos y facilitando la consulta de
                    información.
                </p>

                <ul>
                    <li>Historial médico más ordenado.</li>

                    <li>
                        Planes adaptados a diferentes necesidades.
                    </li>

                    <li>
                        Interfaz sencilla para usuarios con conocimientos
                        básicos.
                    </li>
                </ul>

            </div>

        </div>

    </section>


    <!-- =====================================================
         PLANES ACTIVOS RECUPERADOS DESDE LA BASE DE DATOS
    ====================================================== -->

    <section class="seccion">

        <div class="contenedor">

            <div class="titulo-seccion">

                <span>
                    Planes
                </span>

                <h2>
                    Elige el plan ideal para ti
                </h2>

            </div>


            <div class="grid-3">

                <?php if (
                    $resultadoPlanesInicio &&
                    mysqli_num_rows($resultadoPlanesInicio) > 0
                ) { ?>

                    <?php while (
                        $plan = mysqli_fetch_assoc(
                            $resultadoPlanesInicio
                        )
                    ) { ?>

                        <?php

                        /*
                        Comprobar si este es el plan con más
                        contactos interesados.
                        */
                        $esMasVendidoInicio =
                            $planMasVendidoInicioId !== null &&
                            (int) $plan['id'] ===
                            $planMasVendidoInicioId;

                        ?>

                        <article class="
                            card-plan
                            <?php
                            echo $esMasVendidoInicio
                                ? 'destacado'
                                : '';
                            ?>
                        ">

                            <!--
                            La etiqueta solo aparece cuando existen
                            contactos y este plan es el más solicitado.
                            -->
                            <?php if ($esMasVendidoInicio) { ?>

                                <span class="badge">
                                    Más vendido
                                </span>

                            <?php } ?>


                            <h3>
                                <?php
                                echo htmlspecialchars(
                                    $plan['nombre']
                                );
                                ?>
                            </h3>


                            <p class="precio">

                                $<?php
                                echo number_format(
                                    (float) $plan['precio'],
                                    2
                                );
                                ?> MXN

                            </p>


                            <p class="text-descripcion">

                                <?php
                                echo htmlspecialchars(
                                    $plan['descripcion']
                                );
                                ?>

                            </p>


                            <!--
                            Este botón no cambia de página.

                            Los atributos data guardan la información
                            del plan para mostrarla en el modal.
                            -->
                            <button
                                type="button"
                                class="btn-principal btn-ver-plan"
                                data-nombre="<?php
                                    echo htmlspecialchars(
                                        $plan['nombre'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                ?>"
                                data-precio="<?php
                                    echo number_format(
                                        (float) $plan['precio'],
                                        2
                                    );
                                ?>"
                                data-periodo="<?php
                                    echo htmlspecialchars(
                                        $plan['periodo'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                ?>"
                                data-descripcion="<?php
                                    echo htmlspecialchars(
                                        $plan['descripcion'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                ?>"
                                data-almacenamiento="<?php
                                    echo htmlspecialchars(
                                        $plan['almacenamiento'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                ?>"
                                data-estudios="<?php
                                    echo htmlspecialchars(
                                        $plan['cantidad_estudios'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                ?>"
                                data-soporte="<?php
                                    echo htmlspecialchars(
                                        $plan['soporte'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                ?>">

                                Ver más

                            </button>

                        </article>

                    <?php } ?>

                <?php } else { ?>

                    <p class="sin-planes-disponibles">
                        Actualmente no existen planes disponibles.
                    </p>

                <?php } ?>

            </div>

        </div>

    </section>


    <!-- =====================================================
         VENTANA EMERGENTE CON INFORMACIÓN DEL PLAN
    ====================================================== -->

    <div
        id="modalPlan"
        class="modal-plan"
        aria-hidden="true">

        <!-- Fondo oscuro; también permite cerrar el modal -->
        <div
            id="fondoModalPlan"
            class="modal-plan-fondo">
        </div>


        <article
            class="modal-plan-contenido"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modalPlanNombre">

            <!-- Botón para cerrar -->
            <button
                type="button"
                id="cerrarModalPlan"
                class="cerrar-modal-plan"
                aria-label="Cerrar ventana">

                ×

            </button>


            <span class="modal-plan-etiqueta">
                Información del plan
            </span>


            <h2 id="modalPlanNombre">
                Nombre del plan
            </h2>


            <p
                id="modalPlanPrecio"
                class="modal-plan-precio">
            </p>


            <p
                id="modalPlanPeriodo"
                class="modal-plan-periodo">
            </p>


            <p
                id="modalPlanDescripcion"
                class="modal-plan-descripcion">
            </p>


            <ul class="modal-plan-lista">

                <li>

                    <strong>
                        Almacenamiento:
                    </strong>

                    <span id="modalPlanAlmacenamiento"></span>

                </li>


                <li>

                    <strong>
                        Estudios:
                    </strong>

                    <span id="modalPlanEstudios"></span>

                </li>


                <li>

                    <strong>
                        Soporte:
                    </strong>

                    <span id="modalPlanSoporte"></span>

                </li>

            </ul>


            <!--
            Mensaje final sencillo.
            -->
            <p class="modal-plan-mensaje">

                Consulta más información en la sección

                <a href="planes.php">
                    Planes
                </a>.

            </p>

        </article>

    </div>


    <!-- Testimonios simulados con JavaScript -->
    <section class="seccion testimonios">

        <div class="contenedor">

            <div class="titulo-seccion">

                <span>
                    Testimonios
                </span>

                <h2>
                    Lo que opinan nuestros usuarios
                </h2>

            </div>

            <div
                class="grid-3"
                id="contenedorTestimonios">

                <!-- JavaScript insertará los testimonios -->

            </div>

        </div>

    </section>


    <!-- Preguntas frecuentes simuladas con JavaScript -->
    <section class="seccion faq">

        <div class="contenedor">

            <div class="titulo-seccion">

                <span>
                    FAQ
                </span>

                <h2>
                    Preguntas frecuentes
                </h2>

            </div>

            <div id="contenedorFaq">

                <!-- JavaScript insertará las preguntas -->

            </div>

        </div>

    </section>

</main>

<script src="assets/js/msjEmergente.js"></script>

<?php include 'includes/footer.php'; ?>