<aside class="sidebar">

    <div class="logo-admin">

        <a href="dashboard.php">
            Clinify
        </a>

        <span>
            <?php echo htmlspecialchars($_SESSION['rol']); ?>
        </span>

    </div>

    <nav class="menu-admin">

        <a href="dashboard.php">
            Dashboard
        </a>

        <!-- Ambos roles pueden acceder -->
        <a href="planes.php">
            Planes
        </a>

        <a href="caracteristicas.php">
            Características
        </a>

        <a href="contactos.php">
            Contactos
        </a>

        <!-- Opciones exclusivas del Administrador -->
        <?php if (esAdministrador()) { ?>

            <a href="equipo.php">
                Equipo
            </a>

            <a href="usuarios.php">
                Perfiles
            </a>

        <?php } ?>

    </nav>

    <div class="sidebar-pie">

        <p>
            <?php
            echo htmlspecialchars($_SESSION['usuario_nombre']);
            ?>
        </p>

        <span class="rol-usuario">
            <?php echo htmlspecialchars($_SESSION['rol']); ?>
        </span>

        <a href="../index.php" class="enlace-sitio">
            Ver sitio público
        </a>

        <a
            href="actions/cerrar_sesion.php"
            class="cerrar-sesion">
            Cerrar sesión
        </a>

    </div>

</aside>