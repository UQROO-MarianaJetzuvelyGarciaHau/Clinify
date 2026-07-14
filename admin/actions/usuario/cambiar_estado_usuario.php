<?php

/* Iniciar sesión */
session_start();

/* Solo el Administrador puede cambiar estados */
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
No permitir que un usuario se desactive
a sí mismo.
*/
if ($id === (int) $_SESSION['usuario_id']) {
    header(
        "Location: ../../usuario/usuarios.php?mensaje=protegido"
    );
    exit();
}


/*
Obtener estado y rol del usuario.
*/
$consultaUsuario = "
    SELECT
        usuarios.estado,
        roles.nombre AS rol
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
Si se intenta desactivar un Administrador,
comprobamos que no sea el último activo.
*/
if (
    $usuario['rol'] === 'Administrador' &&
    (int) $usuario['estado'] === 1
) {
    $consultaAdministradores = "
        SELECT COUNT(*) AS total
        FROM usuarios
        INNER JOIN roles
            ON usuarios.id_rol = roles.id
        WHERE roles.nombre = 'Administrador'
        AND usuarios.estado = 1
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


/* Alternar el estado */
$nuevoEstado =
    (int) $usuario['estado'] === 1
        ? 0
        : 1;


/* Actualizar el estado */
$consultaActualizar = "
    UPDATE usuarios
    SET estado = ?
    WHERE id = ?
";

$stmtActualizar = mysqli_prepare(
    $conexion,
    $consultaActualizar
);

if (!$stmtActualizar) {
    header(
        "Location: ../../usuario/usuarios.php?mensaje=error"
    );
    exit();
}

mysqli_stmt_bind_param(
    $stmtActualizar,
    "ii",
    $nuevoEstado,
    $id
);

if (mysqli_stmt_execute($stmtActualizar)) {
    mysqli_stmt_close($stmtActualizar);

    header(
        "Location: ../../usuario/usuarios.php?mensaje=estado"
    );
    exit();
}

mysqli_stmt_close($stmtActualizar);

header(
    "Location: ../../usuario/usuarios.php?mensaje=error"
);
exit();