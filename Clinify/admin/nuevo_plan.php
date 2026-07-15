<?php

require_once 'includes/autenticacion.php';
requerirGestorContenido();

include 'includes/header.php';
include 'includes/sidebar.php';

?>

<main class="contenido-admin">

    <header class="cabecera-admin">
        <span class="texto-secundario">Planes</span>
        <h1>Agregar plan</h1>
        <p>Registra una nueva opción para los usuarios.</p>
    </header>

    <section class="formulario-admin-contenedor">

        <form
            action="actions/guardar_plan.php"
            method="POST"
            class="formulario-admin">

            <?php if (isset($_GET['error'])) { ?>
                <div class="mensaje-admin error">
                    Revisa los datos ingresados.
                </div>
            <?php } ?>

            <div class="campo-admin">
                <label for="nombre">Nombre</label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    minlength="3"
                    maxlength="100"
                    required>
            </div>

            <div class="campo-admin">
                <label for="descripcion">Descripción</label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="4"
                    minlength="10"
                    maxlength="500"
                    required></textarea>
            </div>

            <div class="campo-admin">
                <label for="precio">Precio</label>

                <input
                    type="number"
                    id="precio"
                    name="precio"
                    min="0"
                    step="0.01"
                    required>
            </div>

            <div class="campo-admin">
                <label for="periodo">Periodo</label>

                <input
                    type="text"
                    id="periodo"
                    name="periodo"
                    maxlength="50"
                    placeholder="Mensual"
                    required>
            </div>

            <div class="campo-admin">
                <label for="almacenamiento">Almacenamiento</label>

                <input
                    type="text"
                    id="almacenamiento"
                    name="almacenamiento"
                    maxlength="100"
                    required>
            </div>

            <div class="campo-admin">
                <label for="cantidad_estudios">Cantidad de estudios</label>

                <input
                    type="text"
                    id="cantidad_estudios"
                    name="cantidad_estudios"
                    maxlength="100"
                    required>
            </div>

            <div class="campo-admin">
                <label for="soporte">Soporte</label>

                <input
                    type="text"
                    id="soporte"
                    name="soporte"
                    maxlength="100"
                    required>
            </div>

            <div class="campo-admin">
                <label for="estado">Estado</label>

                <select id="estado" name="estado" required>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>

            <div class="campo-admin">
                <label for="es_mas_vendido">¿Es el más vendido?</label>

                <select id="es_mas_vendido" name="es_mas_vendido" required>
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

            <div class="acciones-formulario">

                <button type="submit" class="btn-admin">
                    Guardar plan
                </button>

                <a href="planes.php" class="btn-secundario-admin">
                    Cancelar
                </a>

            </div>

        </form>

    </section>

</main>

<?php include 'includes/footer.php'; ?>