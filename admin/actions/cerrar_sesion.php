<?php

/* Recuperar la sesión actual */
session_start();

/* Vaciar los datos almacenados */
$_SESSION = [];

/* Eliminar la cookie de sesión si existe */
if (ini_get("session.use_cookies")) {

    $parametros = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $parametros["path"],
        $parametros["domain"],
        $parametros["secure"],
        $parametros["httponly"]
    );
}

/* Destruir la sesión */
session_destroy();

/* Regresar al inicio de sesión */
header("Location: ../login.php?sesion=cerrada");
exit();