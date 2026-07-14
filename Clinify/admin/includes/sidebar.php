<aside class="sidebar">

    <!-- Nombre del sistema y rol -->
    <div class="logo-admin">

        <a href="/Clinify/admin/dashboard.php">
            Clinify
        </a>

        <span>
            <?php
            echo htmlspecialchars(
                $_SESSION['rol'] ?? ''
            );
            ?>
        </span>

    </div>

    <!-- Menú principal -->
    <nav class="menu-admin">

        <a href="/Clinify/admin/dashboard.php">
            Dashboard
        </a>

        <!-- Administrador y Asistente -->
        <a href="/Clinify/admin/plan/planes.php">
            Planes
        </a>

        <a href="/Clinify/admin/caracteristicas/caracteristicas.php">
            Características
        </a>

        <a href="/Clinify/admin/contactos/contactos.php">
            Contactos
        </a>

        <!-- Opciones exclusivas del Administrador -->
        <?php if (esAdministrador()) { ?>

            <a href="/Clinify/admin/equipo/equipo.php">
                Equipo
            </a>

            <a href="/Clinify/admin/usuario/usuarios.php">
                Perfiles
            </a>

        <?php } ?>

    </nav>

    <!-- Información del usuario -->
    <div class="sidebar-pie">

        <p>
            <?php
            echo htmlspecialchars(
                $_SESSION['usuario_nombre'] ?? ''
            );
            ?>
        </p>

        <span class="rol-usuario">
            <?php
            echo htmlspecialchars(
                $_SESSION['rol'] ?? ''
            );
            ?>
        </span>

        <a
            href="/Clinify/index.php"
            class="enlace-sitio">

            Ver sitio público

        </a>

        <a
            href="/Clinify/admin/actions/cerrar_sesion.php"
            class="cerrar-sesion">

            Cerrar sesión

        </a>

    </div>

</aside>