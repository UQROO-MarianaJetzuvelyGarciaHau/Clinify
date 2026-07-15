<?php

/*
|--------------------------------------------------------------------------
| SEGURIDAD DEL MÓDULO
|--------------------------------------------------------------------------
| Cargamos la sesión y las funciones relacionadas con los permisos.
*/

require_once '../includes/autenticacion.php';

/*
El apartado Usuarios es exclusivo del Administrador.

Aunque un Asistente escriba directamente la dirección
en el navegador, no podrá entrar.
*/
requerirAdministrador();


/*
|--------------------------------------------------------------------------
| CONEXIÓN CON LA BASE DE DATOS
|--------------------------------------------------------------------------
| Desde admin/usuario subimos dos niveles para llegar a:
| config/conexion.php
*/

require_once '../../config/conexion.php';


/*
|--------------------------------------------------------------------------
| CONSULTAR USUARIOS
|--------------------------------------------------------------------------
| Recuperamos los usuarios junto con el nombre de su rol.
|
| No recuperamos la contraseña porque nunca debe mostrarse
| dentro del panel administrativo.
*/

$consultaUsuarios = "
    SELECT
        usuarios.id,
        usuarios.nombre,
        usuarios.correo,
        usuarios.estado,
        usuarios.fecha_registro,
        roles.nombre AS rol
    FROM usuarios
    INNER JOIN roles
        ON usuarios.id_rol = roles.id
    ORDER BY usuarios.id DESC
";

$resultadoUsuarios = mysqli_query(
    $conexion,
    $consultaUsuarios
);


/*
Si la consulta falla, mostramos el error durante el desarrollo.

Cuando el proyecto esté terminado, se puede reemplazar
por un mensaje más general.
*/
if (!$resultadoUsuarios) {
    die(
        "Error al consultar los usuarios: "
        . mysqli_error($conexion)
    );
}


/*
|--------------------------------------------------------------------------
| CARGAR LA INTERFAZ
|--------------------------------------------------------------------------
*/

include 'header_usuario.php';
include '../includes/sidebar.php';

?>

<main class="contenido-admin">

    <!-- =====================================================
         ENCABEZADO DEL MÓDULO
    ====================================================== -->

    <header class="cabecera-usuarios">

        <div>

            <span class="texto-secundario">
                Administración
            </span>

            <h1>
                Usuarios
            </h1>

            <p>
                Crea y administra las cuentas de Administradores
                y Asistentes que tienen acceso al panel.
            </p>

        </div>

        <a
            href="nuevo_usuario.php"
            class="btn-agregar-usuario">

            Agregar usuario

        </a>

    </header>


    <!-- =====================================================
         MENSAJES DE RESULTADO
    ====================================================== -->

    <?php if (isset($_GET['mensaje'])) { ?>

        <?php

        /*
        Recuperamos el mensaje enviado mediante la URL.

        Ejemplos:
        usuarios.php?mensaje=creado
        usuarios.php?mensaje=actualizado
        */
        $mensaje = $_GET['mensaje'];

        /*
        Los mensajes de error tendrán una clase diferente
        a los mensajes de éxito.
        */
        $claseMensaje = in_array(
            $mensaje,
            ['error', 'protegido', 'ultimo_admin'],
            true
        )
            ? 'mensaje-error'
            : 'mensaje-exito';

        ?>

        <div class="mensaje-usuario <?php echo $claseMensaje; ?>">

            <?php

            switch ($mensaje) {

                case 'creado':
                    echo "El usuario fue creado correctamente.";
                    break;

                case 'actualizado':
                    echo "El usuario fue actualizado correctamente.";
                    break;

                case 'estado':
                    echo "El estado del usuario fue modificado.";
                    break;

                case 'eliminado':
                    echo "El usuario fue eliminado correctamente.";
                    break;

                case 'protegido':
                    echo "No puedes desactivar o eliminar tu propia cuenta.";
                    break;

                case 'ultimo_admin':
                    echo "No se puede desactivar o eliminar al último Administrador activo.";
                    break;

                default:
                    echo "No se pudo completar la operación.";
                    break;
            }

            ?>

        </div>

    <?php } ?>


    <!-- =====================================================
         TABLA DE USUARIOS
    ====================================================== -->

    <section class="contenedor-tabla-usuarios">

        <table class="tabla-usuarios">

            <thead>

                <tr>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <?php if (
                    mysqli_num_rows($resultadoUsuarios) > 0
                ) { ?>

                    <?php while (
                        $usuario = mysqli_fetch_assoc(
                            $resultadoUsuarios
                        )
                    ) { ?>

                        <?php

                        /*
                        Comprobar si la fila corresponde al usuario
                        que actualmente tiene la sesión iniciada.
                        */
                        $esUsuarioActual =
                            (int) $usuario['id'] ===
                            (int) $_SESSION['usuario_id'];

                        ?>

                        <tr>

                            <!-- Nombre del usuario -->
                            <td>

                                <div class="datos-usuario">

                                    <strong>
                                        <?php
                                        echo htmlspecialchars(
                                            $usuario['nombre']
                                        );
                                        ?>
                                    </strong>

                                    <?php if ($esUsuarioActual) { ?>

                                        <small class="usuario-actual">
                                            Tu cuenta
                                        </small>

                                    <?php } ?>

                                </div>

                            </td>


                            <!-- Correo -->
                            <td>

                                <span class="correo-usuario">

                                    <?php
                                    echo htmlspecialchars(
                                        $usuario['correo']
                                    );
                                    ?>

                                </span>

                            </td>


                            <!-- Rol -->
                            <td>

                                <span class="
                                    etiqueta-rol
                                    <?php
                                    echo $usuario['rol'] === 'Administrador'
                                        ? 'rol-administrador'
                                        : 'rol-asistente';
                                    ?>
                                ">

                                    <?php
                                    echo htmlspecialchars(
                                        $usuario['rol']
                                    );
                                    ?>

                                </span>

                            </td>


                            <!-- Estado -->
                            <td>

                                <?php if (
                                    (int) $usuario['estado'] === 1
                                ) { ?>

                                    <span class="estado-usuario activo">
                                        Activo
                                    </span>

                                <?php } else { ?>

                                    <span class="estado-usuario inactivo">
                                        Inactivo
                                    </span>

                                <?php } ?>

                            </td>


                            <!-- Fecha de registro -->
                            <td>

                                <?php if (
                                    !empty($usuario['fecha_registro'])
                                ) { ?>

                                    <?php
                                    echo date(
                                        'd/m/Y',
                                        strtotime(
                                            $usuario['fecha_registro']
                                        )
                                    );
                                    ?>

                                <?php } else { ?>

                                    Sin fecha

                                <?php } ?>

                            </td>


                            <!-- Acciones -->
                            <td>

                                <div class="acciones-usuario">

                                    <!-- Editar perfil -->
                                    <a
                                        href="editar_usuario.php?id=<?php echo $usuario['id']; ?>"
                                        class="btn-editar-usuario">

                                        Editar

                                    </a>


                                    <!-- Activar o desactivar -->
                                    <form
                                        action="../actions/usuario/cambiar_estado_usuario.php"
                                        method="POST">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo $usuario['id']; ?>">

                                        <button
                                            type="submit"
                                            class="btn-estado-usuario">

                                            <?php
                                            echo (int) $usuario['estado'] === 1
                                                ? 'Desactivar'
                                                : 'Activar';
                                            ?>

                                        </button>

                                    </form>


                                    <!-- Eliminar usuario -->
                                    <form
                                        action="../actions/usuario/eliminar_usuario.php"
                                        method="POST"
                                        onsubmit="return confirm('¿Deseas eliminar este usuario?');">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo $usuario['id']; ?>">

                                        <button
                                            type="submit"
                                            class="btn-eliminar-usuario">

                                            Eliminar

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td
                            colspan="6"
                            class="tabla-usuarios-vacia">

                            No existen usuarios registrados.

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </section>

</main>

<?php include '../includes/footer.php'; ?>