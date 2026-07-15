<?php

/* Mostrar errores mientras desarrollamos */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/* Validar sesión y permisos */
require_once '../includes/autenticacion.php';
requerirGestorContenido();

/* Conectar con la base de datos */
require_once '../../config/conexion.php';

/*
Consultar los contactos junto con el nombre del plan.

LEFT JOIN permite mostrar el contacto aunque el plan
haya sido eliminado.
*/
$consulta = "
    SELECT
        contactos.id,
        contactos.nombre,
        contactos.correo,
        contactos.telefono,
        contactos.mensaje,
        contactos.fecha_envio,
        contactos.estado,
        planes.nombre AS nombre_plan
    FROM contactos
    LEFT JOIN planes
        ON contactos.plan_interes = planes.id
    ORDER BY contactos.id DESC
";

$resultadoContactos = mysqli_query(
    $conexion,
    $consulta
);

/* Mostrar el error real si la consulta falla */
if (!$resultadoContactos) {
    die(
        "Error en la consulta: "
        . mysqli_error($conexion)
    );
}

/* Cargar la estructura visual */
include 'header_contactos.php';
include '../includes/sidebar.php';

?>

<main class="contenido-admin">

    <!-- Encabezado -->
    <header class="cabecera-contactos">

        <div>

            <span class="texto-secundario">
                Clientes interesados
            </span>

            <h1>
                Contactos
            </h1>

            <p>
                Consulta los mensajes enviados desde el formulario
                público y registra cuáles ya fueron atendidos.
            </p>

        </div>

    </header>


    <!-- Mensajes -->
    <?php if (isset($_GET['mensaje'])) { ?>

        <?php
        $mensaje = $_GET['mensaje'];

        $claseMensaje = $mensaje === 'error'
            ? 'mensaje-error'
            : 'mensaje-exito';
        ?>

        <div class="mensaje-contacto <?php echo $claseMensaje; ?>">

            <?php

            switch ($mensaje) {

                case 'estado':
                    echo "El estado del contacto fue actualizado.";
                    break;

                case 'eliminado':
                    echo "El contacto fue eliminado correctamente.";
                    break;

                default:
                    echo "No se pudo completar la operación.";
                    break;
            }

            ?>

        </div>

    <?php } ?>


    <!-- Tabla -->
    <section class="contenedor-tabla-contactos">

        <table class="tabla-contactos">

            <thead>

                <tr>
                    <th>Cliente</th>
                    <th>Contacto</th>
                    <th>Plan</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <?php if (
                    mysqli_num_rows($resultadoContactos) > 0
                ) { ?>

                    <?php while (
                        $contacto = mysqli_fetch_assoc(
                            $resultadoContactos
                        )
                    ) { ?>

                        <tr>

                            <!-- Nombre y mensaje -->
                            <td>

                                <div class="datos-cliente">

                                    <strong>
                                        <?php
                                        echo htmlspecialchars(
                                            $contacto['nombre']
                                        );
                                        ?>
                                    </strong>

                                    <small>
                                        <?php
                                        echo htmlspecialchars(
                                            mb_strimwidth(
                                                $contacto['mensaje'],
                                                0,
                                                70,
                                                '...'
                                            )
                                        );
                                        ?>
                                    </small>

                                </div>

                            </td>


                            <!-- Correo y teléfono -->
                            <td>

                                <div class="datos-contacto-tabla">

                                    <span>
                                        <?php
                                        echo htmlspecialchars(
                                            $contacto['correo']
                                        );
                                        ?>
                                    </span>

                                    <small>
                                        <?php
                                        echo htmlspecialchars(
                                            $contacto['telefono']
                                        );
                                        ?>
                                    </small>

                                </div>

                            </td>


                            <!-- Plan -->
                            <td>

                                <?php if (
                                    !empty($contacto['nombre_plan'])
                                ) { ?>

                                    <span class="plan-contacto">

                                        <?php
                                        echo htmlspecialchars(
                                            $contacto['nombre_plan']
                                        );
                                        ?>

                                    </span>

                                <?php } else { ?>

                                    <span class="sin-plan">
                                        Sin plan
                                    </span>

                                <?php } ?>

                            </td>


                            <!-- Fecha -->
                            <td>

                                <?php
                                echo date(
                                    'd/m/Y H:i',
                                    strtotime(
                                        $contacto['fecha_envio']
                                    )
                                );
                                ?>

                            </td>


                            <!-- Estado -->
                            <td>

                                <?php if (
                                    $contacto['estado'] === 'Nuevo'
                                ) { ?>

                                    <span class="estado-contacto nuevo">
                                        Nuevo
                                    </span>

                                <?php } else { ?>

                                    <span class="estado-contacto atendido">
                                        Atendido
                                    </span>

                                <?php } ?>

                            </td>


                            <!-- Acciones -->
                            <td>

                                <div class="acciones-contacto">

                                    <a
                                        href="ver_contacto.php?id=<?php echo $contacto['id']; ?>"
                                        class="btn-ver-contacto">

                                        Ver

                                    </a>


                                    <form
                                        action="../actions/contactos/cambiar_estado_contacto.php"
                                        method="POST">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo $contacto['id']; ?>">

                                        <button
                                            type="submit"
                                            class="btn-estado-contacto">

                                            <?php
                                            echo $contacto['estado'] === 'Nuevo'
                                                ? 'Marcar atendido'
                                                : 'Marcar nuevo';
                                            ?>

                                        </button>

                                    </form>


                                    <form
                                        action="../actions/contactos/eliminar_contacto.php"
                                        method="POST"
                                        onsubmit="return confirm('¿Deseas eliminar este contacto?');">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo $contacto['id']; ?>">

                                        <button
                                            type="submit"
                                            class="btn-eliminar-contacto">

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
                            class="tabla-contactos-vacia">

                            No existen mensajes de contacto.

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </section>

</main>

<?php include '../includes/footer.php'; ?>