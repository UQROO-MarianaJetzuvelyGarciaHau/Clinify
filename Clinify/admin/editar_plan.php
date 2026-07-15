<?php

require_once 'includes/autenticacion.php';
requerirGestorContenido();

include 'includes/header.php';
include 'includes/sidebar.php';
include '../config/conexion.php';

$id = (int) ($_GET['id'] ?? 0);

$consulta = "SELECT * FROM planes WHERE id = ? LIMIT 1";
$stmt = mysqli_prepare($conexion, $consulta);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$plan = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

if (!$plan) {
    header("Location: planes.php?mensaje=error");
    exit();
}

?>

<main class="contenido-admin">

    <header class="cabecera-admin">
        <span class="texto-secundario">Planes</span>
        <h1>Editar plan</h1>
    </header>

    <section class="formulario-admin-contenedor">

        <form
            action="actions/actualizar_plan.php"
            method="POST"
            class="formulario-admin">

            <input type="hidden" name="id" value="<?php echo $plan['id']; ?>">

            <div class="campo-admin">
                <label for="nombre">Nombre</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?php echo htmlspecialchars($plan['nombre']); ?>"
                    required>
            </div>

            <div class="campo-admin">
                <label for="descripcion">Descripción</label>
                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="4"
                    required><?php echo htmlspecialchars($plan['descripcion']); ?></textarea>
            </div>

            <div class="campo-admin">
                <label for="precio">Precio</label>
                <input
                    type="number"
                    id="precio"
                    name="precio"
                    min="0"
                    step="0.01"
                    value="<?php echo $plan['precio']; ?>"
                    required>
            </div>

            <div class="campo-admin">
                <label for="periodo">Periodo</label>
                <input
                    type="text"
                    id="periodo"
                    name="periodo"
                    value="<?php echo htmlspecialchars($plan['periodo']); ?>"
                    required>
            </div>

            <div class="campo-admin">
                <label for="almacenamiento">Almacenamiento</label>
                <input
                    type="text"
                    id="almacenamiento"
                    name="almacenamiento"
                    value="<?php echo htmlspecialchars($plan['almacenamiento']); ?>"
                    required>
            </div>

            <div class="campo-admin">
                <label for="cantidad_estudios">Cantidad de estudios</label>
                <input
                    type="text"
                    id="cantidad_estudios"
                    name="cantidad_estudios"
                    value="<?php echo htmlspecialchars($plan['cantidad_estudios']); ?>"
                    required>
            </div>

            <div class="campo-admin">
                <label for="soporte">Soporte</label>
                <input
                    type="text"
                    id="soporte"
                    name="soporte"
                    value="<?php echo htmlspecialchars($plan['soporte']); ?>"
                    required>
            </div>

            <div class="campo-admin">
                <label for="estado">Estado</label>

                <select id="estado" name="estado">
                    <option value="1" <?php echo $plan['estado'] == 1 ? 'selected' : ''; ?>>
                        Activo
                    </option>

                    <option value="0" <?php echo $plan['estado'] == 0 ? 'selected' : ''; ?>>
                        Inactivo
                    </option>
                </select>
            </div>

            <div class="campo-admin">
                <label for="es_mas_vendido">Más vendido</label>

                <select id="es_mas_vendido" name="es_mas_vendido">
                    <option value="0" <?php echo $plan['es_mas_vendido'] == 0 ? 'selected' : ''; ?>>
                        No
                    </option>

                    <option value="1" <?php echo $plan['es_mas_vendido'] == 1 ? 'selected' : ''; ?>>
                        Sí
                    </option>
                </select>
            </div>

            <div class="acciones-formulario">
                <button type="submit" class="btn-admin">
                    Guardar cambios
                </button>

                <a href="planes.php" class="btn-secundario-admin">
                    Cancelar
                </a>
            </div>

        </form>

    </section>

</main>

<?php include 'includes/footer.php'; ?>