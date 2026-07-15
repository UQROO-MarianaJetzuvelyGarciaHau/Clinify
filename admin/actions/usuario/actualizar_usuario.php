<?php

/* Iniciar sesión */
session_start();

/*
Solo un Administrador puede actualizar usuarios.
*/
if (
    !isset($_SESSION['usuario_id']) ||
    ($_SESSION['rol'] ?? '') !== 'Administrador'
) {
    header(
        "Location: ../../dashboard.php?acceso=denegado"
    );
    exit();
}

/* Conectar con MySQL */
require_once '../../../config/conexion.php';

/* Solo aceptar formularios POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(
        "Location: ../../usuario/usuarios.php"
    );
    exit();
}


/* Recuperar datos */
$id = (int) ($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$idRol = (int) ($_POST['id_rol'] ?? 0);
$estado = (int) ($_POST['estado'] ?? -1);

$password = $_POST['password'] ?? '';
$confirmarPassword = $_POST['confirmar_password'] ?? '';


/* Validar ID */
if ($id <= 0) {
    header(
        "Location: ../../usuario/usuarios.php?mensaje=error"
    );
    exit();
}


/* Validar nombre */
if (
    mb_strlen($nombre) < 3 ||
    mb_strlen($nombre) > 100 ||
    !preg_match(
        "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/u",
        $nombre
    )
) {
    header(
        "Location: ../../usuario/editar_usuario.php?id=$id&error=nombre"
    );
    exit();
}


/* Validar correo */
if (
    !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
    mb_strlen($correo) > 120
) {
    header(
        "Location: ../../usuario/editar_usuario.php?id=$id&error=correo"
    );
    exit();
}


/* Validar estado */
if (!in_array($estado, [0, 1], true)) {
    header(
        "Location: ../../usuario/editar_usuario.php?id=$id&error=datos"
    );
    exit();
}


/*
Comprobar que el rol exista.
*/
$consultaRol = "
    SELECT nombre
    FROM roles
    WHERE id = ?
    LIMIT 1
";

$stmtRol = mysqli_prepare(
    $conexion,
    $consultaRol
);

if (!$stmtRol) {
    header(
        "Location: ../../usuario/editar_usuario.php?id=$id&error=rol"
    );
    exit();
}

mysqli_stmt_bind_param(
    $stmtRol,
    "i",
    $idRol
);

mysqli_stmt_execute($stmtRol);

$resultadoRol = mysqli_stmt_get_result($stmtRol);
$rol = mysqli_fetch_assoc($resultadoRol);

mysqli_stmt_close($stmtRol);

if (!$rol) {
    header(
        "Location: ../../usuario/editar_usuario.php?id=$id&error=rol"
    );
    exit();
}


/*
Impedir que el usuario conectado se cambie
a Asistente o se desactive a sí mismo.
*/
if (
    $id === (int) $_SESSION['usuario_id'] &&
    (
        $rol['nombre'] !== 'Administrador' ||
        $estado !== 1
    )
) {
    header(
        "Location: ../../usuario/editar_usuario.php?id=$id&error=protegido"
    );
    exit();
}


/*
Comprobar que el correo no pertenezca
a otro usuario.
*/
$consultaCorreo = "
    SELECT id
    FROM usuarios
    WHERE correo = ?
    AND id != ?
    LIMIT 1
";

$stmtCorreo = mysqli_prepare(
    $conexion,
    $consultaCorreo
);

if (!$stmtCorreo) {
    header(
        "Location: ../../usuario/editar_usuario.php?id=$id&error=general"
    );
    exit();
}

mysqli_stmt_bind_param(
    $stmtCorreo,
    "si",
    $correo,
    $id
);

mysqli_stmt_execute($stmtCorreo);

$resultadoCorreo = mysqli_stmt_get_result(
    $stmtCorreo
);

$correoExistente = mysqli_fetch_assoc(
    $resultadoCorreo
);

mysqli_stmt_close($stmtCorreo);

if ($correoExistente) {
    header(
        "Location: ../../usuario/editar_usuario.php?id=$id&error=correo_existente"
    );
    exit();
}


/*
Si no se escribió una contraseña nueva,
se actualizan únicamente los datos básicos.
*/
if ($password === '') {

    $consultaActualizar = "
        UPDATE usuarios
        SET
            nombre = ?,
            correo = ?,
            id_rol = ?,
            estado = ?
        WHERE id = ?
    ";

    $stmtActualizar = mysqli_prepare(
        $conexion,
        $consultaActualizar
    );

    if (!$stmtActualizar) {
        header(
            "Location: ../../usuario/editar_usuario.php?id=$id&error=general"
        );
        exit();
    }

    mysqli_stmt_bind_param(
        $stmtActualizar,
        "ssiii",
        $nombre,
        $correo,
        $idRol,
        $estado,
        $id
    );

} else {

    /*
    Validar la nueva contraseña.
    */
    $passwordValida =
        strlen($password) >= 8 &&
        strlen($password) <= 100 &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[a-z]/', $password) &&
        preg_match('/[0-9]/', $password);

    if (!$passwordValida) {
        header(
            "Location: ../../usuario/editar_usuario.php?id=$id&error=password"
        );
        exit();
    }

    if ($password !== $confirmarPassword) {
        header(
            "Location: ../../usuario/editar_usuario.php?id=$id&error=confirmacion"
        );
        exit();
    }

    /* Crear el nuevo hash */
    $passwordHash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $consultaActualizar = "
        UPDATE usuarios
        SET
            nombre = ?,
            correo = ?,
            password = ?,
            id_rol = ?,
            estado = ?
        WHERE id = ?
    ";

    $stmtActualizar = mysqli_prepare(
        $conexion,
        $consultaActualizar
    );

    if (!$stmtActualizar) {
        header(
            "Location: ../../usuario/editar_usuario.php?id=$id&error=general"
        );
        exit();
    }

    mysqli_stmt_bind_param(
        $stmtActualizar,
        "sssiii",
        $nombre,
        $correo,
        $passwordHash,
        $idRol,
        $estado,
        $id
    );
}


/* Ejecutar actualización */
if (mysqli_stmt_execute($stmtActualizar)) {

    mysqli_stmt_close($stmtActualizar);

    /*
    Si el Administrador editó su propia cuenta,
    actualizamos también los datos de la sesión.
    */
    if ($id === (int) $_SESSION['usuario_id']) {
        $_SESSION['usuario_nombre'] = $nombre;
        $_SESSION['usuario_correo'] = $correo;
        $_SESSION['rol'] = $rol['nombre'];
        $_SESSION['rol_id'] = $idRol;
    }

    header(
        "Location: ../../usuario/usuarios.php?mensaje=actualizado"
    );
    exit();
}

mysqli_stmt_close($stmtActualizar);

header(
    "Location: ../../usuario/editar_usuario.php?id=$id&error=general"
);
exit();