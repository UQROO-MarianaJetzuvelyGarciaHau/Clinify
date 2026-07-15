<?php

/* Validar sesión y permisos */
require_once '../includes/autenticacion.php';
requerirGestorContenido();

/* Conectar con MySQL */
require_once '../../config/conexion.php';

/* Recuperar el ID recibido en la URL */
$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: contactos.php?mensaje=error");
    exit();
}

/*
Buscar el contacto y el nombre del plan asociado.
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
    WHERE contactos.id = ?
    LIMIT 1
";

$stmt = mysqli_prepare(
    $conexion,
    $consulta
);

if (!$stmt) {
    header("Location: contactos.php?mensaje=error");
    exit();
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$contacto = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

if (!$contacto) {
    header("Location: contactos.php?mensaje=error");
    exit();
}

/* Cargar la interfaz */
include 'header_contactos.php';
include '../includes/sidebar.php';

?>

<main class="contenido-admin">

    <header class="encabezado-detalle-contacto">

        <span class="texto-secundario">
            Contactos
        </span>

        <h1>
            Detalle del mensaje
        </h1>

        <p>
            Consulta la información enviada por el cliente.
        </p>

    </header>


    <section class="detalle-contacto">

        <!-- Encabezado del mensaje -->
        <div class="detalle-contacto-cabecera">

            <div>

                <h2>
                    <?php
                    echo htmlspecialchars(
                        $contacto['nombre']
                    );
                    ?>
                </h2>

                <p>
                    Enviado el
                    <?php
                    echo date(
                        'd/m/Y H:i',
                        strtotime($contacto['fecha_envio'])
                    );
                    ?>
                </p>

            </div>

            <?php if ($contacto['estado'] === 'Nuevo') { ?>

                <span class="estado-contacto nuevo">
                    Nuevo
                </span>

            <?php } else { ?>

                <span class="estado-contacto atendido">
                    Atendido
                </span>

            <?php } ?>

        </div>


        <!-- Datos del cliente -->
        <div class="detalle-contacto-grid">

            <article class="dato-detalle-contacto">

                <span>Correo electrónico</span>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $contacto['correo']
                    );
                    ?>
                </strong>

            </article>

            <article class="dato-detalle-contacto">

                <span>Teléfono</span>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $contacto['telefono']
                    );
                    ?>
                </strong>

            </article>

            <article class="dato-detalle-contacto">

                <span>Plan de interés</span>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $contacto['nombre_plan']
                        ?? 'Sin plan asociado'
                    );
                    ?>
                </strong>

            </article>

        </div>


        <!-- Mensaje completo -->
        <div class="mensaje-completo-contacto">

            <h3>
                Mensaje
            </h3>

            <p>
                <?php
                echo nl2br(
                    htmlspecialchars(
                        $contacto['mensaje']
                    )
                );
                ?>
            </p>

        </div>


        <!-- Acciones -->
        <div class="acciones-detalle-contacto">

            <form
                action="../actions/contactos/cambiar_estado_contacto.php"
                method="POST">

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo $contacto['id']; ?>">

                <input
                    type="hidden"
                    name="regresar"
                    value="detalle">

                <button
                    type="submit"
                    class="btn-cambiar-estado-detalle">

                    <?php
                    echo $contacto['estado'] === 'Nuevo'
                        ? 'Marcar como atendido'
                        : 'Marcar como nuevo';
                    ?>

                </button>

            </form>

            <a
                href="contactos.php"
                class="btn-volver-contactos">

                Volver

            </a>

        </div>

    </section>

</main>

<?php include '../includes/footer.php'; ?>