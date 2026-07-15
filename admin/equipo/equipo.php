<?php

/*
Cargar las funciones de autenticación y permisos.
*/
require_once '../includes/autenticacion.php';

/*
El módulo Equipo es exclusivo del Administrador.
Si entra un Asistente, será enviado al dashboard.
*/
requerirAdministrador();

/*
Conectar con la base de datos.

Desde admin/equipo debemos subir dos niveles
para llegar a config/conexion.php.
*/
require_once '../../config/conexion.php';

/*
Consultar todos los integrantes del equipo.
*/
$consultaEquipo = "
    SELECT
        id,
        nombre,
        rol,
        descripcion
    FROM equipo
    ORDER BY id ASC
";

$resultadoEquipo = mysqli_query(
    $conexion,
    $consultaEquipo
);

/*
Si ocurre un error en la consulta, detenemos
la página y mostramos el error durante el desarrollo.
*/
if (!$resultadoEquipo) {
    die(
        "Error al consultar el equipo: "
        . mysqli_error($conexion)
    );
}

/* Cargar la estructura visual */
include 'header_equipo.php';
include '../includes/sidebar.php';

?>

<main class="contenido-admin">

    <!-- Encabezado del módulo -->
    <header class="cabecera-equipo">

        <span class="texto-secundario">
            Administración
        </span>

        <h1>
            Equipo
        </h1>

        <p>
            Consulta y modifica la información de los integrantes
            que aparecen en la página Nosotros.
        </p>

    </header>


    <!-- Mensaje después de actualizar -->
    <?php if (isset($_GET['mensaje'])) { ?>

        <?php
        /*
        Determinar qué clase usar según el resultado.
        */
        $claseMensaje = $_GET['mensaje'] === 'error'
            ? 'mensaje-error'
            : 'mensaje-exito';
        ?>

        <div class="mensaje-equipo <?php echo $claseMensaje; ?>">

            <?php

            switch ($_GET['mensaje']) {

                case 'actualizado':
                    echo "La información del integrante fue actualizada correctamente.";
                    break;

                default:
                    echo "No se pudo completar la operación.";
                    break;
            }

            ?>

        </div>

    <?php } ?>


    <!-- Tabla de integrantes -->
    <section class="contenedor-tabla-equipo">

        <table class="tabla-equipo">

            <thead>

                <tr>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Descripción</th>
                    <th>Acción</th>
                </tr>

            </thead>

            <tbody>

                <?php if (
                    mysqli_num_rows($resultadoEquipo) > 0
                ) { ?>

                    <?php while (
                        $integrante = mysqli_fetch_assoc(
                            $resultadoEquipo
                        )
                    ) { ?>

                        <tr>

                            <!-- Nombre -->
                            <td>

                                <strong class="nombre-integrante">
                                    <?php
                                    echo htmlspecialchars(
                                        $integrante['nombre']
                                    );
                                    ?>
                                </strong>

                            </td>

                            <!-- Rol -->
                            <td>

                                <span class="rol-integrante">
                                    <?php
                                    echo htmlspecialchars(
                                        $integrante['rol']
                                    );
                                    ?>
                                </span>

                            </td>

                            <!-- Descripción -->
                            <td>

                                <p class="descripcion-integrante">
                                    <?php
                                    echo htmlspecialchars(
                                        $integrante['descripcion']
                                    );
                                    ?>
                                </p>

                            </td>

                            <!-- Única acción permitida -->
                            <td>

                                <a
                                    href="editar_integrante.php?id=<?php echo $integrante['id']; ?>"
                                    class="btn-editar-integrante">

                                    Editar

                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td
                            colspan="5"
                            class="tabla-equipo-vacia">

                            No existen integrantes registrados.

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </section>

</main>

<?php include '../includes/footer.php'; ?>