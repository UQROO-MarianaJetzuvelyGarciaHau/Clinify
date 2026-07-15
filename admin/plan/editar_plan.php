<?php

/* Comprobar sesión y permisos */
require_once '../includes/autenticacion.php';
requerirGestorContenido();

/* Conexión con MySQL */
require_once '../../config/conexion.php';

/* Recuperar el ID enviado en la URL */
$id = (int) ($_GET['id'] ?? 0);

/* Validar que el ID sea correcto */
if ($id <= 0) {
    header("Location: planes.php?mensaje=error");
    exit();
}

/* Buscar el plan que se desea editar */
$consulta = "
    SELECT
        id,
        nombre,
        descripcion,
        precio,
        periodo,
        almacenamiento,
        cantidad_estudios,
        soporte,
        estado
    FROM planes
    WHERE id = ?
    LIMIT 1
";

$stmt = mysqli_prepare($conexion, $consulta);

if (!$stmt) {
    header("Location: planes.php?mensaje=error");
    exit();
}

/* Asociar el ID al signo ? de la consulta */
mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

/* Ejecutar la consulta */
mysqli_stmt_execute($stmt);

/* Recuperar el plan */
$resultado = mysqli_stmt_get_result($stmt);
$plan = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

/* Regresar si el plan no existe */
if (!$plan) {
    header("Location: planes.php?mensaje=error");
    exit();
}

/* Cargar componentes después de las redirecciones */
include 'header_plan.php';
include '../includes/sidebar.php';


require_once '../../config/conexion.php';

?>

<main class="contenido-admin">

    <header class="encabezado-formulario-plan">

        <span class="texto-secundario">
            Planes
        </span>

        <h1>Editar plan</h1>

        <p>
            Modifica la información o el estado del plan.
        </p>

    </header>


    <section class="contenedor-formulario-plan">

        <form
            action="../actions/planes/actualizar_plan.php"
            method="POST"
            class="formulario-plan">

            <!-- ID del plan -->
            <input
                type="hidden"
                name="id"
                value="<?php echo $plan['id']; ?>">

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
                    value="<?php echo htmlspecialchars($plan['nombre']); ?>"
                    minlength="3"
                    maxlength="100"
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
                    required><?php echo htmlspecialchars($plan['descripcion']); ?></textarea>

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
                        value="<?php echo htmlspecialchars($plan['precio']); ?>"
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
                        value="<?php echo htmlspecialchars($plan['periodo']); ?>"
                        maxlength="50"
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
                        value="<?php echo htmlspecialchars($plan['almacenamiento']); ?>"
                        maxlength="100"
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
                        value="<?php echo htmlspecialchars($plan['cantidad_estudios']); ?>"
                        maxlength="100"
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
                        value="<?php echo htmlspecialchars($plan['soporte']); ?>"
                        maxlength="100"
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

                        <option
                            value="Activo"
                            <?php
                            echo $plan['estado'] === 'Activo'
                                ? 'selected'
                                : '';
                            ?>>

                            Activo

                        </option>

                        <option
                            value="Proximamente"
                            <?php
                            echo $plan['estado'] === 'Proximamente'
                                ? 'selected'
                                : '';
                            ?>>

                            Próximamente

                        </option>

                        <option
                            value="Desactivado"
                            <?php
                            echo $plan['estado'] === 'Desactivado'
                                ? 'selected'
                                : '';
                            ?>>

                            Desactivado

                        </option>

                    </select>

                </div>

            </div>


            <!-- Botones -->
            <div class="acciones-formulario-plan">

                <button
                    type="submit"
                    class="btn-guardar-plan">

                    Guardar cambios

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