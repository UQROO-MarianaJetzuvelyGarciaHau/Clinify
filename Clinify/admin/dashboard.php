<?php

/*
El header protege la página y carga las funciones
de autenticación y permisos.
*/
require_once 'includes/autenticacion.php';

/* Después cargamos la estructura visual */
include 'includes/header.php';
include 'includes/sidebar.php';

/* Conexión con la base de datos */
require_once '../config/conexion.php';

/* Valores iniciales del resumen */
$totalPlanes = 0;
$totalCaracteristicas = 0;
$totalContactos = 0;
$totalEquipo = 0;
$totalUsuarios = 0;

/* Contar planes */
$resultado = mysqli_query(
    $conexion,
    "SELECT COUNT(*) AS total FROM planes"
);

if ($resultado) {
    $fila = mysqli_fetch_assoc($resultado);
    $totalPlanes = $fila['total'];
}

/* Contar características */
$resultado = mysqli_query(
    $conexion,
    "SELECT COUNT(*) AS total FROM caracteristicas"
);

if ($resultado) {
    $fila = mysqli_fetch_assoc($resultado);
    $totalCaracteristicas = $fila['total'];
}

/* Contar contactos */
$resultado = mysqli_query(
    $conexion,
    "SELECT COUNT(*) AS total FROM contactos"
);

if ($resultado) {
    $fila = mysqli_fetch_assoc($resultado);
    $totalContactos = $fila['total'];
}

/*
Estos conteos solamente los necesita visualizar
el Administrador.
*/
if (esAdministrador()) {

    $resultado = mysqli_query(
        $conexion,
        "SELECT COUNT(*) AS total FROM equipo"
    );

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $totalEquipo = $fila['total'];
    }

    $resultado = mysqli_query(
        $conexion,
        "SELECT COUNT(*) AS total FROM usuarios"
    );

    if ($resultado) {
        $fila = mysqli_fetch_assoc($resultado);
        $totalUsuarios = $fila['total'];
    }
}

?>

<main class="contenido-admin">

    <header class="cabecera-admin">

        <span class="texto-secundario">
            Panel administrativo
        </span>

        <h1>
            Bienvenido,
            <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
        </h1>

        <p>
            Has iniciado sesión como
            <strong>
                <?php echo htmlspecialchars($_SESSION['rol']); ?>
            </strong>.
        </p>

    </header>

    <?php if (isset($_GET['acceso'])) { ?>

        <div class="mensaje-admin error">
            No tienes permisos para acceder a esa sección.
        </div>

    <?php } ?>

    <!-- Tarjetas disponibles para ambos roles -->
    <section class="resumen-admin">

        <article class="tarjeta-resumen">
            <span>Planes</span>
            <strong><?php echo $totalPlanes; ?></strong>
        </article>

        <article class="tarjeta-resumen">
            <span>Características</span>
            <strong><?php echo $totalCaracteristicas; ?></strong>
        </article>

        <article class="tarjeta-resumen">
            <span>Contactos</span>
            <strong><?php echo $totalContactos; ?></strong>
        </article>

        <!-- Información exclusiva del administrador -->
        <?php if (esAdministrador()) { ?>

            <article class="tarjeta-resumen">
                <span>Equipo</span>
                <strong><?php echo $totalEquipo; ?></strong>
            </article>

            <article class="tarjeta-resumen">
                <span>Perfiles</span>
                <strong><?php echo $totalUsuarios; ?></strong>
            </article>

        <?php } ?>

    </section>

    <section class="modulos-admin">

        <article class="card-admin">

            <h2>Planes</h2>

            <p>
                Agrega, modifica o elimina los planes disponibles.
            </p>

            <a href="plan/planes.php" class="btn-admin">
                Administrar planes
            </a>

        </article>

        <article class="card-admin">

            <h2>Características</h2>

            <p>
                Gestiona las características mostradas en el sitio.
            </p>

            <a href="caracteristicas/caracteristicas.php" class="btn-admin">
                Administrar características
            </a>

        </article>

        <article class="card-admin">

            <h2>Contactos</h2>

            <p>
                Consulta y elimina los mensajes recibidos.
            </p>

            <a href="contactos/contactos.php" class="btn-admin">
                Ver contactos
            </a>

        </article>

        <?php if (esAdministrador()) { ?>

            <article class="card-admin">

                <h2>Equipo</h2>

                <p>
                    Administra los integrantes mostrados en Nosotros.
                </p>

                <a href="equipo/equipo.php" class="btn-admin">
                    Administrar equipo
                </a>

            </article>

            <article class="card-admin">

                <h2>Perfiles</h2>

                <p>
                    Crea y administra usuarios Administradores o Asistentes.
                </p>

                <a href="usuarios.php" class="btn-admin">
                    Administrar perfiles
                </a>

            </article>

        <?php } ?>

    </section>

</main>

<?php include 'includes/footer.php'; ?>