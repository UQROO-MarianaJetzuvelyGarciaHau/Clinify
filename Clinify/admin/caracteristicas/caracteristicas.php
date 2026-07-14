<?php

/*
Cargar las funciones de autenticación y permisos.

Este módulo puede ser administrado por:
- Administrador
- Asistente
*/
require_once '../includes/autenticacion.php';
requerirGestorContenido();

/*
Conectar con MySQL.

Desde admin/caracteristicas debemos subir dos niveles
para llegar a config/conexion.php.
*/
require_once '../../config/conexion.php';

/*
Consultar todas las características.

También se muestran las desactivadas porque estamos
dentro del panel administrativo.
*/
$consulta = "
    SELECT
        id,
        titulo,
        descripcion,
        estado
    FROM caracteristicas
    ORDER BY id DESC
";

$resultadoCaracteristicas = mysqli_query(
    $conexion,
    $consulta
);

/* Cargar la estructura visual después de las consultas */
include 'header_caracteristicas.php';
include '../includes/sidebar.php';

?>

<main class="contenido-admin">

    <!-- Encabezado del módulo -->
    <header class="cabecera-caracteristicas">

        <div>

            <span class="texto-secundario">
                Contenido
            </span>

            <h1>
                Características
            </h1>

            <p>
                Administra las características que se muestran
                en la página pública de Clinify.
            </p>

        </div>

        <a
            href="nueva_caracteristica.php"
            class="btn-agregar-caracteristica">

            Agregar característica

        </a>

    </header>


    <!-- Mensajes después de crear, modificar o eliminar -->
    <?php if (isset($_GET['mensaje'])) { ?>

        <?php
        /*
        Recuperamos el valor enviado mediante la URL.

        Ejemplos:
        caracteristicas.php?mensaje=creada
        caracteristicas.php?mensaje=actualizada
        */
        $mensaje = $_GET['mensaje'];

        /*
        Determinamos el estilo de la notificación.
        */
        $claseMensaje = $mensaje === 'error'
            ? 'mensaje-error'
            : 'mensaje-exito';
        ?>

        <div class="mensaje-caracteristica <?php echo $claseMensaje; ?>">

            <?php

            switch ($mensaje) {

                case 'creada':
                    echo "La característica fue creada correctamente.";
                    break;

                case 'actualizada':
                    echo "La característica fue actualizada correctamente.";
                    break;

                case 'eliminada':
                    echo "La característica fue eliminada correctamente.";
                    break;

                default:
                    echo "No se pudo completar la operación.";
                    break;
            }

            ?>

        </div>

    <?php } ?>


    <!-- Tabla de características -->
    <section class="contenedor-tabla-caracteristicas">

        <table class="tabla-caracteristicas">

            <thead>

                <tr>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <?php if (
                    $resultadoCaracteristicas &&
                    mysqli_num_rows($resultadoCaracteristicas) > 0
                ) { ?>

                    <?php while (
                        $caracteristica =
                            mysqli_fetch_assoc($resultadoCaracteristicas)
                    ) { ?>

                        <tr>

                            <!-- Título -->
                            <td>

                                <strong class="titulo-caracteristica">
                                    <?php
                                    echo htmlspecialchars(
                                        $caracteristica['titulo']
                                    );
                                    ?>
                                </strong>

                            </td>

                            <!-- Descripción -->
                            <td>

                                <p class="descripcion-caracteristica">
                                    <?php
                                    echo htmlspecialchars(
                                        $caracteristica['descripcion']
                                    );
                                    ?>
                                </p>

                            </td>

                            <!-- Estado -->
                            <td>

                                <?php if (
                                    (int) $caracteristica['estado'] === 1
                                ) { ?>

                                    <span class="estado-caracteristica activo">
                                        Activa
                                    </span>

                                <?php } else { ?>

                                    <span class="estado-caracteristica inactivo">
                                        Desactivada
                                    </span>

                                <?php } ?>

                            </td>

                            <!-- Acciones -->
                            <td>

                                <div class="acciones-caracteristica">

                                    <a
                                        href="editar_caracteristica.php?id=<?php echo $caracteristica['id']; ?>"
                                        class="btn-editar-caracteristica">

                                        Editar

                                    </a>

                                    <form
                                        action="../actions/caracteristicas/eliminar_caracteristica.php"
                                        method="POST"
                                        onsubmit="return confirm('¿Deseas eliminar esta característica?');">

                                        <!-- ID que recibirá el archivo de eliminación -->
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo $caracteristica['id']; ?>">

                                        <button
                                            type="submit"
                                            class="btn-eliminar-caracteristica">

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
                            colspan="4"
                            class="tabla-caracteristicas-vacia">

                            No existen características registradas.

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </section>

</main>

<?php include '../includes/footer.php'; ?>