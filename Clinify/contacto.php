<?php

/* Cargar los componentes principales del sitio */
include 'includes/header.php';
include 'includes/navbar.php';

/* Conectar con la base de datos */
include 'config/conexion.php';

/*
Recuperar el plan enviado desde la página de planes.

Ejemplo:
contacto.php?plan=2
*/
$planSeleccionado = (int) ($_GET['plan'] ?? 0);

/*
Obtener únicamente los planes que pueden aparecer
en el formulario público.

Los planes Desactivados no se muestran.
*/
$consultaPlanes = "
    SELECT id, nombre, estado
    FROM planes
    WHERE estado IN ('Activo', 'Proximamente')
    ORDER BY nombre ASC
";

$resultadoPlanes = mysqli_query(
    $conexion,
    $consultaPlanes
);

?>

<main>

    <!-- =============================
         ENCABEZADO
    ============================== -->

    <section class="hero hero-simple">

        <div class="contenedor">

            <span class="etiqueta">
                Contacto
            </span>

            <h1>
                Queremos escucharte
            </h1>

            <p>
                Si tienes dudas acerca de Clinify o deseas recibir
                más información sobre nuestros planes, puedes
                comunicarte mediante el siguiente formulario.
            </p>

        </div>

    </section>


    <!-- =============================
         SECCIÓN DE CONTACTO
    ============================== -->

    <section class="seccion contacto">

        <div class="contenedor">

            <!-- Título centrado -->
            <div class="contacto-titulo">

                <span class="etiqueta">
                    Hablemos
                </span>

                <h2>
                    Estamos para resolver tus dudas
                </h2>

                <p>
                    Selecciona el plan de tu interés y nuestro equipo
                    responderá tu mensaje lo antes posible.
                </p>

            </div>


            <!-- Contenido en flex -->
            <div class="contacto-flex">

                <!-- Tarjetas informativas -->
                <div class="contacto-info">

                    <article class="dato-contacto">

                        <h3>
                            Correo electrónico
                        </h3>

                        <p>
                            contacto@clinify.com
                        </p>

                    </article>


                    <article class="dato-contacto">

                        <h3>
                            Teléfono
                        </h3>

                        <p>
                            +52 (983) 123 4567
                        </p>

                    </article>


                    <article class="dato-contacto">

                        <h3>
                            Horario de atención
                        </h3>

                        <p>
                            Lunes a viernes
                            <br>
                            9:00 AM - 5:00 PM
                        </p>

                    </article>

                </div>


                <!-- Formulario -->
                <form
                    action="actions/guardar_contacto.php"
                    method="POST"
                    class="formulario-contacto">

                    <!-- Nombre -->
                    <div class="campo">

                        <label for="nombre">
                            Nombre completo
                        </label>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            minlength="3"
                            maxlength="80"
                            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                            title="Escribe únicamente letras y espacios."
                            placeholder="Ejemplo: Mariana García"
                            autocomplete="name"
                            required>

                    </div>


                    <!-- Correo -->
                    <div class="campo">

                        <label for="correo">
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            id="correo"
                            name="correo"
                            maxlength="120"
                            placeholder="ejemplo@correo.com"
                            autocomplete="email"
                            title="Ingresa un correo electrónico válido."
                            required>

                    </div>


                    <!-- Teléfono -->
                    <div class="campo">

                        <label for="telefono">
                            Teléfono
                        </label>

                        <input
                            type="tel"
                            id="telefono"
                            name="telefono"
                            minlength="10"
                            maxlength="10"
                            pattern="[0-9]{10}"
                            placeholder="9831234567"
                            title="Ingresa exactamente 10 números."
                            autocomplete="tel"
                            required>

                    </div>


                    <!-- Plan de interés -->
                    <div class="campo">

                        <label for="plan_interes">
                            Plan de interés
                        </label>

                        <select
                            id="plan_interes"
                            name="plan_interes"
                            required>

                            <option value="">
                                Selecciona un plan
                            </option>

                            <?php if ($resultadoPlanes) { ?>

                                <?php while (
                                    $plan = mysqli_fetch_assoc($resultadoPlanes)
                                ) { ?>

                                    <option
                                        value="<?php echo $plan['id']; ?>"
                                        <?php
                                        echo (int) $plan['id'] === $planSeleccionado
                                            ? 'selected'
                                            : '';
                                        ?>>

                                        <?php
                                        echo htmlspecialchars($plan['nombre']);

                                        if ($plan['estado'] === 'Proximamente') {
                                            echo " — Próximamente";
                                        }
                                        ?>

                                    </option>

                                <?php } ?>

                            <?php } ?>

                        </select>

                    </div>


                    <!-- Mensaje -->
                    <div class="campo">

                        <label for="mensaje">
                            Mensaje
                        </label>

                        <textarea
                            id="mensaje"
                            name="mensaje"
                            rows="6"
                            minlength="15"
                            maxlength="500"
                            placeholder="Escribe aquí tu mensaje..."
                            required></textarea>

                        <small>
                            Mínimo 15 y máximo 500 caracteres.
                        </small>

                    </div>


                    <!-- Botón -->
                    <button
                        type="submit"
                        class="btn-principal">

                        Enviar mensaje

                    </button>

                </form>

            </div>

        </div>

    </section>

</main>


<!-- =============================
     NOTIFICACIÓN TOAST
============================== -->

<?php if (isset($_GET['mensaje'])) { ?>

    <?php
    /*
    Solo permitimos dos valores para evitar imprimir
    contenido no esperado desde la URL.
    */
    $tipoMensaje = $_GET['mensaje'];

    if (
        $tipoMensaje === 'exito' ||
        $tipoMensaje === 'error'
    ) {
    ?>

        <div
            id="toast"
            class="toast <?php echo $tipoMensaje; ?>">

            <?php if ($tipoMensaje === 'exito') { ?>

                ✔ Mensaje enviado correctamente.

            <?php } else { ?>

                ✖ Revisa los datos e intenta nuevamente.

            <?php } ?>

        </div>

    <?php } ?>

<?php } ?>


<?php include 'includes/footer.php'; ?>