<header class="header">
    <nav class="navbar contenedor">
        <a href="index.php" class="logo">Clinify</a>

        <ul class="menu" id="menu">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="caracteristicas.php">Características</a></li>
            <li><a href="planes.php">Planes</a></li>
            <li><a href="nosotros.php">Nosotros</a></li>
            <li><a href="contacto.php">Contacto</a></li>
        </ul>

        <?php if (isset($_SESSION['usuario_id'])) { ?>

        <!--
    Cuando existe una sesión, el botón permite
    regresar al panel administrativo.
    -->
        <a href="admin/dashboard.php" class="btn-login">

            Volver al dashboard

        </a>

        <?php } else { ?>

        <!--
    Cuando no hay sesión, se muestra el enlace
    normal para iniciar sesión.
    -->
        <a href="admin/login.php" class="btn-login">

            Iniciar sesión

        </a>

        <?php } ?>


        <!-- Botón para menú móvil -->
        <button class="btn-menu" id="btnMenu">☰</button>
    </nav>
</header>