<?php

/* Iniciar o recuperar la sesión */
session_start();

/*
Si el usuario ya inició sesión,
lo enviamos directamente al dashboard.
*/
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Clinify</title>

    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <main class="login-pagina">

        <section class="login-contenedor">

            <!-- Presentación del panel -->
            <div class="login-presentacion">

                <a href="../index.php" class="logo-login">
                    Clinify
                </a>

                <span class="etiqueta-login">
                    Panel administrativo
                </span>

                <h1>
                    Gestiona el contenido de Clinify
                </h1>

                <p>
                    Inicia sesión para administrar los planes,
                    características, contactos y demás contenidos
                    permitidos según tu perfil.
                </p>

                <a href="../index.php" class="volver-sitio">
                    ← Regresar al sitio público
                </a>

            </div>

            <!-- Formulario de acceso -->
            <form
                action="actions/validar_login.php"
                method="POST"
                class="formulario-login">

                <h2>Iniciar sesión</h2>

                <p class="texto-login">
                    Ingresa tus credenciales de acceso.
                </p>

                <?php if (isset($_GET['error'])) { ?>

                    <div class="mensaje-login error">
                        Correo o contraseña incorrectos.
                    </div>

                <?php } ?>

                <?php if (
                    isset($_GET['sesion']) &&
                    $_GET['sesion'] === 'cerrada'
                ) { ?>

                    <div class="mensaje-login exito">
                        La sesión se cerró correctamente.
                    </div>

                <?php } ?>

                <?php if (
                    isset($_GET['cuenta']) &&
                    $_GET['cuenta'] === 'inactiva'
                ) { ?>

                    <div class="mensaje-login error">
                        Esta cuenta se encuentra desactivada.
                    </div>

                <?php } ?>

                <div class="campo-login">

                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        maxlength="120"
                        placeholder="usuario@clinify.com"
                        autocomplete="email"
                        required>

                </div>

                <div class="campo-login">

                    <label for="password">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        minlength="8"
                        maxlength="100"
                        placeholder="Ingresa tu contraseña"
                        autocomplete="current-password"
                        required>

                </div>

                <button type="submit" class="btn-ingresar">
                    Ingresar
                </button>

            </form>

        </section>

    </main>

</body>

</html>