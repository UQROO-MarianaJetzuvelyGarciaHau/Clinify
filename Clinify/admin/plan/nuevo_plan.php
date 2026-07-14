<?php

/* Comprobar sesión y permisos */
require_once '../includes/autenticacion.php';
requerirGestorContenido();

/* Cargar la estructura del panel */
include 'header_plan.php';
include '../includes/sidebar.php';

require_once '../../config/conexion.php';

?>

<main class="contenido-admin">

    <!-- Encabezado -->
    <header class="encabezado-formulario-plan">

        <span class="texto-secundario">
            Planes
        </span>

        <h1>Agregar plan</h1>

        <p>
            Completa la información para registrar
            un nuevo plan en Clinify.
        </p>

    </header>


    <!-- Formulario -->
    <section class="contenedor-formulario-plan">

        <form
            action="../actions/planes/guardar_plan.php"
            method="POST"
            class="formulario-plan">

            <?php if (isset($_GET['error'])) { ?>

                <div class="mensaje-plan mensaje-error">
                    Revisa los datos ingresados.
                </div>

            <?php } ?>


            <!-- Nombre -->
            <div class="campo-plan">

                <label for="nombre">
                    Nombre del plan
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    minlength="3"
                    maxlength="100"
                    placeholder="Ejemplo: Básico"
                    required>

            </div>


            <!-- Descripción -->
            <div class="campo-plan">

                <label for="descripcion">
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="4"
                    minlength="10"
                    maxlength="500"
                    placeholder="Describe brevemente el plan"
                    required></textarea>

            </div>


            <!-- Precio y periodo -->
            <div class="fila-campos-plan">

                <div class="campo-plan">

                    <label for="precio">
                        Precio
                    </label>

                    <input
                        type="number"
                        id="precio"
                        name="precio"
                        min="0"
                        step="0.01"
                        placeholder="99.00"
                        required>

                </div>

                <div class="campo-plan">

                    <label for="periodo">
                        Periodo
                    </label>

                    <input
                        type="text"
                        id="periodo"
                        name="periodo"
                        maxlength="50"
                        placeholder="Mensual"
                        required>

                </div>

            </div>


            <!-- Almacenamiento y estudios -->
            <div class="fila-campos-plan">

                <div class="campo-plan">

                    <label for="almacenamiento">
                        Almacenamiento
                    </label>

                    <input
                        type="text"
                        id="almacenamiento"
                        name="almacenamiento"
                        maxlength="100"
                        placeholder="10 GB"
                        required>

                </div>

                <div class="campo-plan">

                    <label for="cantidad_estudios">
                        Cantidad de estudios
                    </label>

                    <input
                        type="text"
                        id="cantidad_estudios"
                        name="cantidad_estudios"
                        maxlength="100"
                        placeholder="Hasta 20 estudios"
                        required>

                </div>

            </div>


            <!-- Soporte y estado -->
            <div class="fila-campos-plan">

                <div class="campo-plan">

                    <label for="soporte">
                        Soporte
                    </label>

                    <input
                        type="text"
                        id="soporte"
                        name="soporte"
                        maxlength="100"
                        placeholder="Soporte por correo"
                        required>

                </div>

                <div class="campo-plan">

                    <label for="estado">
                        Estado
                    </label>

                    <select
                        id="estado"
                        name="estado"
                        required>

                        <option value="Activo">
                            Activo
                        </option>

                        <option value="Proximamente">
                            Próximamente
                        </option>

                        <option value="Desactivado">
                            Desactivado
                        </option>

                    </select>

                </div>

            </div>


            <!-- Explicación de los estados -->
            <div class="ayuda-estado">

                <p>
                    <strong>Activo:</strong>
                    se muestra normalmente en el sitio.
                </p>

                <p>
                    <strong>Próximamente:</strong>
                    se muestra opaco y sin contratación.
                </p>

                <p>
                    <strong>Desactivado:</strong>
                    no aparece en el sitio público.
                </p>

            </div>


            <!-- Botones -->
            <div class="acciones-formulario-plan">

                <button
                    type="submit"
                    class="btn-guardar-plan">

                    Guardar plan

                </button>

                <a
                    href="planes.php"
                    class="btn-cancelar-plan">

                    Cancelar

                </a>

            </div>

        </form>

    </section>

</main>

<?php include '../includes/footer.php'; ?>