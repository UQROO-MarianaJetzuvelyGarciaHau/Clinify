<?php

/* Iniciar sesión */
session_start();

/* Solo el Administrador puede eliminar usuarios */
if (
    !isset($_SESSION['usuario_id']) ||
    ($_SESSION['rol'] ?? '') !== 'Administrador'
) {
    header(
        "Location: ../../dashboard.php?acceso=denegado"
    );
    exit();
}

/* Conexión */
require_once '../../../config/conexion.php';

/* Solo aceptar POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(
        "Location: ../../usuario/usuarios.php"
    );
    exit();
}

/* Recuperar ID */
$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    header(
        "Location: ../../usuario/usuarios.php?mensaje=error"
    );
    exit();
}


/*
No permitir eliminar la cuenta
que tiene la sesión iniciada.
*/
if ($id === (int) $_SESSION['usuario_id']) {
    header(
        "Location: ../../usuario/usuarios.php?mensaje=protegido"
    );
    exit();
}


/*
Consultar el rol del usuario.
*/
$consultaUsuario = "
    SELECT roles.nombre AS rol
    FROM usuarios
    INNER JOIN roles
        ON usuarios.id_rol = roles.id
    WHERE usuarios.id = ?
    LIMIT 1
";

$stmtUsuario = mysqli_prepare(
    $conexion,
    $consultaUsuario
);

if (!$stmtUsuario) {
    header(
        "Location: ../../usuario/usuarios.php?mensaje=error"
    );
    exit();
}

mysqli_stmt_bind_param(
    $stmtUsuario,
    "i",
    $id
);

mysqli_stmt_execute($stmtUsuario);

$resultadoUsuario = mysqli_stmt_get_result(
    $stmtUsuario
);

$usuario = mysqli_fetch_assoc(
    $resultadoUsuario
);

mysqli_stmt_close($stmtUsuario);

if (!$usuario) {
    header(
        "Location: ../../usuario/usuarios.php?mensaje=error"
    );
    exit();
}


/*
No permitir eliminar al último Administrador.
*/
if ($usuario['rol'] === 'Administrador') {

    $consultaAdministradores = "
        SELECT COUNT(*) AS total
        FROM usuarios
        INNER JOIN roles
            ON usuarios.id_rol = roles.id
        WHERE roles.nombre = 'Administrador'
    ";

    $resultadoAdministradores = mysqli_query(
        $conexion,
        $consultaAdministradores
    );

    if (!$resultadoAdministradores) {
        header(
            "Location: ../../usuario/usuarios.php?mensaje=error"
        );
        exit();
    }

    $filaAdministradores = mysqli_fetch_assoc(
        $resultadoAdministradores
    );

    if ((int) $filaAdministradores['total'] <= 1) {
        header(
            "Location: ../../usuario/usuarios.php?mensaje=ultimo_admin"
        );
        exit();
    }
}


/* Eliminar usuario */
$consultaEliminar = "
    DELETE FROM usuarios
    WHERE id = ?
";

$stmtEliminar = mysqli_prepare(
    $conexion,
    $consultaEliminar
);

if (!$stmtEliminar) {
    header(
        "Location: ../../usuario/usuarios.php?mensaje=error"
    );
    exit();
}

mysqli_stmt_bind_param(
    $stmtEliminar,
    "i",
    $id
);

if (mysqli_stmt_execute($stmtEliminar)) {
    mysqli_stmt_close($stmtEliminar);

    header(
        "Location: ../../usuario/usuarios.php?mensaje=eliminado"
    );
    exit();
}

mysqli_stmt_close($stmtEliminar);

header(
    "Location: ../../usuario/usuarios.php?mensaje=error"
);
exit();