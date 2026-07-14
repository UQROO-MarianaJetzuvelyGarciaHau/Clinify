<?php

/* Cargar la sesión y las funciones de permisos */
require_once 'includes/autenticacion.php';

/* Solo el Administrador puede acceder a esta página */
requerirAdministrador();

/* Componentes visuales del panel */
include 'includes/header.php';
include 'includes/sidebar.php';

/* Conexión con MySQL */
include '../config/conexion.php';

/*
Obtener los perfiles junto con el nombre de su rol.
No recuperamos la contraseña porque no debe mostrarse.
*/
$consulta = "SELECT
                usuarios.id,
                usuarios.nombre,
                usuarios.correo,
                usuarios.estado,
                usuarios.fecha_registro,
                roles.nombre AS rol
             FROM usuarios
             INNER JOIN roles
                ON usuarios.id_rol = roles.id
             ORDER BY usuarios.id DESC";

$resultado = mysqli_query($conexion, $consulta);

?>

<main class="contenido-admin">

    <header class="cabecera-admin cabecera-flex">

        <div>
            <span class="texto-secundario">Administración</span>

            <h1>Perfiles de usuario</h1>

            <p>
                Crea y administra las cuentas con acceso al panel.
            </p>
        </div>

        <a href="nuevo_usuario.php" class="btn-admin">
            Crear perfil
        </a>

    </header>

    <!-- Mensajes después de realizar alguna acción -->
    <?php if (isset($_GET['mensaje'])) { ?>

        <?php if ($_GET['mensaje'] === 'creado') { ?>
            <div class="mensaje-admin exito">
                El perfil fue creado correctamente.
            </div>
        <?php } ?>

        <?php if ($_GET['mensaje'] === 'actualizado') { ?>
            <div class="mensaje-admin exito">
                El perfil fue actualizado correctamente.
            </div>
        <?php } ?>

        <?php if ($_GET['mensaje'] === 'estado') { ?>
            <div class="mensaje-admin exito">
                El estado del perfil fue modificado.
            </div>
        <?php } ?>

        <?php if ($_GET['mensaje'] === 'eliminado') { ?>
            <div class="mensaje-admin exito">
                El perfil fue eliminado correctamente.
            </div>
        <?php } ?>

        <?php if ($_GET['mensaje'] === 'protegido') { ?>
            <div class="mensaje-admin error">
                No puedes desactivar o eliminar tu propia cuenta.
            </div>
        <?php } ?>

        <?php if ($_GET['mensaje'] === 'ultimo_admin') { ?>
            <div class="mensaje-admin error">
                No se puede eliminar o desactivar al último administrador activo.
            </div>
        <?php } ?>

        <?php if ($_GET['mensaje'] === 'error') { ?>
            <div class="mensaje-admin error">
                No se pudo completar la operación.
            </div>
        <?php } ?>

    <?php } ?>

    <section class="tabla-contenedor">

        <table class="tabla-admin">

            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php if ($resultado && mysqli_num_rows($resultado) > 0) { ?>

                    <?php while ($usuario = mysqli_fetch_assoc($resultado)) { ?>

                        <tr>
                            <td>
                                <?php echo htmlspecialchars($usuario['nombre']); ?>

                                <?php if ((int) $usuario['id'] === (int) $_SESSION['usuario_id']) { ?>
                                    <small class="usuario-actual">Tu cuenta</small>
                                <?php } ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($usuario['correo']); ?>
                            </td>

                            <td>
                                <span class="etiqueta-rol">
                                    <?php echo htmlspecialchars($usuario['rol']); ?>
                                </span>
                            </td>

                            <td>
                                <?php if ((int) $usuario['estado'] === 1) { ?>
                                    <span class="estado-admin activo">
                                        Activo
                                    </span>
                                <?php } else { ?>
                                    <span class="estado-admin inactivo">
                                        Inactivo
                                    </span>
                                <?php } ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    'd/m/Y',
                                    strtotime($usuario['fecha_registro'])
                                );
                                ?>
                            </td>

                            <td>
                                <div class="acciones-tabla">

                                    <a
                                        href="editar_usuario.php?id=<?php echo $usuario['id']; ?>"
                                        class="btn-tabla editar">
                                        Editar
                                    </a>

                                    <!-- El cambio de estado se envía mediante POST -->
                                    <form
                                        action="actions/cambiar_estado_usuario.php"
                                        method="POST">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo $usuario['id']; ?>">

                                        <button
                                            type="submit"
                                            class="btn-tabla estado">
                                            <?php
                                            echo (int) $usuario['estado'] === 1
                                                ? 'Desactivar'
                                                : 'Activar';
                                            ?>
                                        </button>

                                    </form>

                                    <!-- Eliminación mediante POST -->
                                    <form
                                        action="actions/eliminar_usuario.php"
                                        method="POST"
                                        onsubmit="return confirm('¿Deseas eliminar este perfil?');">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo $usuario['id']; ?>">

                                        <button
                                            type="submit"
                                            class="btn-tabla eliminar">
                                            Eliminar
                                        </button>

                                    </form>

                                </div>
                            </td>
                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="6">
                            No existen perfiles registrados.
                        </td>
                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </section>

</main>

<?php include 'includes/footer.php'; ?>