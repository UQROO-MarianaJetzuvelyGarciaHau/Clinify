<?php

/*
|--------------------------------------------------------------------------
| INICIAR SESIÓN
|--------------------------------------------------------------------------
| Necesitamos comprobar que quien realiza la acción
| sea un Administrador.
*/

session_start();


/*
|--------------------------------------------------------------------------
| VALIDAR PERMISOS
|--------------------------------------------------------------------------
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


/*
|--------------------------------------------------------------------------
| CONEXIÓN
|--------------------------------------------------------------------------
| Este archivo está en admin/actions/usuario.
| Subimos tres niveles para llegar a config/conexion.php.
*/

require_once '../../../config/conexion.php';


/*
|--------------------------------------------------------------------------
| VALIDAR MÉTODO
|--------------------------------------------------------------------------
| Solo se procesan formularios enviados mediante POST.
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(
        "Location: ../../usuario/usuarios.php"
    );
    exit();
}


/*
|--------------------------------------------------------------------------
| RECUPERAR DATOS
|--------------------------------------------------------------------------
*/

$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$idRol = (int) ($_POST['id_rol'] ?? 0);
$password = $_POST['password'] ?? '';
$confirmarPassword = $_POST['confirmar_password'] ?? '';
$estado = (int) ($_POST['estado'] ?? -1);


/*
|--------------------------------------------------------------------------
| VALIDAR NOMBRE
|--------------------------------------------------------------------------
*/

if (
    mb_strlen($nombre) < 3 ||
    mb_strlen($nombre) > 100 ||
    !preg_match(
        "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/u",
        $nombre
    )
) {
    header(
        "Location: ../../usuario/nuevo_usuario.php?error=nombre"
    );
    exit();
}


/*
|--------------------------------------------------------------------------
| VALIDAR CORREO
|--------------------------------------------------------------------------
*/

if (
    !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
    mb_strlen($correo) > 120
) {
    header(
        "Location: ../../usuario/nuevo_usuario.php?error=correo"
    );
    exit();
}


/*
|--------------------------------------------------------------------------
| VALIDAR ESTADO
|--------------------------------------------------------------------------
*/

if (!in_array($estado, [0, 1], true)) {
    header(
        "Location: ../../usuario/nuevo_usuario.php?error=datos"
    );
    exit();
}


/*
|--------------------------------------------------------------------------
| VALIDAR ROL
|--------------------------------------------------------------------------
| Comprobamos que el rol realmente exista en la base.
*/

$consultaRol = "
    SELECT id
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
        "Location: ../../usuario/nuevo_usuario.php?error=rol"
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
$rolExiste = mysqli_fetch_assoc($resultadoRol);

mysqli_stmt_close($stmtRol);

if (!$rolExiste) {
    header(
        "Location: ../../usuario/nuevo_usuario.php?error=rol"
    );
    exit();
}


/*
|--------------------------------------------------------------------------
| VALIDAR CONTRASEÑA
|--------------------------------------------------------------------------
| Requisitos:
| - Entre 8 y 100 caracteres.
| - Una letra mayúscula.
| - Una letra minúscula.
| - Un número.
*/

$passwordValida =
    strlen($password) >= 8 &&
    strlen($password) <= 100 &&
    preg_match('/[A-Z]/', $password) &&
    preg_match('/[a-z]/', $password) &&
    preg_match('/[0-9]/', $password);

if (!$passwordValida) {
    header(
        "Location: ../../usuario/nuevo_usuario.php?error=password"
    );
    exit();
}


/* Comprobar que las contraseñas coincidan */
if ($password !== $confirmarPassword) {
    header(
        "Location: ../../usuario/nuevo_usuario.php?error=confirmacion"
    );
    exit();
}


/*
|--------------------------------------------------------------------------
| COMPROBAR CORREO DUPLICADO
|--------------------------------------------------------------------------
*/

$consultaCorreo = "
    SELECT id
    FROM usuarios
    WHERE correo = ?
    LIMIT 1
";

$stmtCorreo = mysqli_prepare(
    $conexion,
    $consultaCorreo
);

if (!$stmtCorreo) {
    header(
        "Location: ../../usuario/nuevo_usuario.php?error=general"
    );
    exit();
}

mysqli_stmt_bind_param(
    $stmtCorreo,
    "s",
    $correo
);

mysqli_stmt_execute($stmtCorreo);

$resultadoCorreo = mysqli_stmt_get_result($stmtCorreo);
$correoExistente = mysqli_fetch_assoc($resultadoCorreo);

mysqli_stmt_close($stmtCorreo);

if ($correoExistente) {
    header(
        "Location: ../../usuario/nuevo_usuario.php?error=correo_existente"
    );
    exit();
}


/*
|--------------------------------------------------------------------------
| CIFRAR CONTRASEÑA
|--------------------------------------------------------------------------
| Nunca se guarda la contraseña original.
*/

$passwordHash = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/*
|--------------------------------------------------------------------------
| GUARDAR USUARIO
|--------------------------------------------------------------------------
*/

$consultaGuardar = "
    INSERT INTO usuarios
    (
        nombre,
        correo,
        password,
        id_rol,
        estado
    )
    VALUES (?, ?, ?, ?, ?)
";

$stmtGuardar = mysqli_prepare(
    $conexion,
    $consultaGuardar
);

if (!$stmtGuardar) {
    header(
        "Location: ../../usuario/nuevo_usuario.php?error=general"
    );
    exit();
}

mysqli_stmt_bind_param(
    $stmtGuardar,
    "sssii",
    $nombre,
    $correo,
    $passwordHash,
    $idRol,
    $estado
);


/*
|--------------------------------------------------------------------------
| EJECUTAR Y REDIRIGIR
|--------------------------------------------------------------------------
*/

if (mysqli_stmt_execute($stmtGuardar)) {
    mysqli_stmt_close($stmtGuardar);

    header(
        "Location: ../../usuario/usuarios.php?mensaje=creado"
    );
    exit();
}

mysqli_stmt_close($stmtGuardar);

header(
    "Location: ../../usuario/nuevo_usuario.php?error=general"
);
exit();