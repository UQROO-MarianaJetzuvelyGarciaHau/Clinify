<?php

require_once 'includes/autenticacion.php';
requerirAdministrador();

include 'includes/header.php';
include 'includes/sidebar.php';
include '../config/conexion.php';

/* Obtener el ID recibido en la URL */
$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: usuarios.php?mensaje=error");
    exit();
}

/* Consultar el perfil que se desea editar */
$consulta = "SELECT id, nombre, correo, id_rol, estado
             FROM usuarios
             WHERE id = ?
             LIMIT 1";

$stmt = mysqli_prepare($conexion, $consulta);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

if (!$usuario) {
    header("Location: usuarios.php?mensaje=error");
    exit();
}

/* Recuperar los roles para el select */
$roles = mysqli_query(
    $conexion,
    "SELECT id, nombre FROM roles ORDER BY id ASC"
);

?>

<main class="contenido-admin">

    <header class="cabecera-admin">
        <span class="texto-secundario">Perfiles</span>
        <h1>Editar perfil</h1>
        <p>Modifica los datos y permisos de la cuenta.</p>
    </header>

    <section class="formulario-admin-contenedor">

        <form
            action="actions/actualizar_usuario.php"
            method="POST"
            class="formulario-admin">

            <input
                type="hidden"
                name="id"
                value="<?php echo $usuario['id']; ?>">

            <?php if (isset($_GET['error'])) { ?>

                <div class="mensaje-admin error">
                    Revisa la información ingresada.
                </div>

            <?php } ?>

            <div class="campo-admin">

                <label for="nombre">Nombre completo</label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?php echo htmlspecialchars($usuario['nombre']); ?>"
                    minlength="3"
                    maxlength="100"
                    required>

            </div>

            <div class="campo-admin">

                <label for="correo">Correo electrónico</label>

                <input
                    type="email"
                    id="correo"
                    name="correo"
                    value="<?php echo htmlspecialchars($usuario['correo']); ?>"
                    maxlength="120"
                    required>

            </div>

            <div class="campo-admin">

                <label for="id_rol">Rol</label>

                <select id="id_rol" name="id_rol" required>

                    <?php while ($rol = mysqli_fetch_assoc($roles)) { ?>

                        <option
                            value="<?php echo $rol['id']; ?>"
                            <?php
                            echo (int) $usuario['id_rol'] === (int) $rol['id']
                                ? 'selected'
                                : '';
                            ?>>

                            <?php echo htmlspecialchars($rol['nombre']); ?>

                        </option>

                    <?php } ?>

                </select>

            </div>

            <div class="campo-admin">

                <label for="password">
                    Nueva contraseña
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    maxlength="100">

                <small>
                    Déjala vacía para conservar la contraseña actual.
                </small>

            </div>

            <div class="campo-admin">

                <label for="confirmar_password">
                    Confirmar nueva contraseña
                </label>

                <input
                    type="password"
                    id="confirmar_password"
                    name="confirmar_password"
                    minlength="8"
                    maxlength="100">

            </div>

            <div class="acciones-formulario">

                <button type="submit" class="btn-admin">
                    Guardar cambios
                </button>

                <a href="usuarios.php" class="btn-secundario-admin">
                    Cancelar
                </a>

            </div>

        </form>

    </section>

</main>

<?php include 'includes/footer.php'; ?>