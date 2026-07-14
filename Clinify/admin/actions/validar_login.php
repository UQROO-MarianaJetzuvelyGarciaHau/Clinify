<?php

/*
Iniciar la sesión para guardar los datos
del usuario cuando las credenciales sean correctas.
*/
session_start();

/*
Este archivo está dentro de admin/actions.

Subimos dos niveles para llegar a:
config/conexion.php
*/
require_once '../../config/conexion.php';

/*
Evitar que este archivo sea ejecutado directamente
desde la barra de direcciones.
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit();
}

/* Recuperar los datos enviados por el formulario */
$correo = trim($_POST['correo'] ?? '');
$password = $_POST['password'] ?? '';

/* Comprobar que los campos no estén vacíos */
if ($correo === '' || $password === '') {
    header("Location: ../login.php?error=campos");
    exit();
}

/* Verificar que el correo tenga formato válido */
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../login.php?error=correo");
    exit();
}

/*
Buscar al usuario y recuperar el nombre de su rol.

No buscamos la contraseña escrita directamente,
porque la contraseña de la base está almacenada como hash.
*/
$consulta = "
    SELECT
        usuarios.id,
        usuarios.nombre,
        usuarios.correo,
        usuarios.password,
        usuarios.estado,
        roles.id AS rol_id,
        roles.nombre AS rol
    FROM usuarios
    INNER JOIN roles
        ON usuarios.id_rol = roles.id
    WHERE usuarios.correo = ?
    LIMIT 1
";

$stmt = mysqli_prepare($conexion, $consulta);

/* Verificar que la consulta se haya preparado */
if (!$stmt) {
    header("Location: ../login.php?error=consulta");
    exit();
}

/* Asociar el correo al signo ? de la consulta */
mysqli_stmt_bind_param(
    $stmt,
    "s",
    $correo
);

/* Ejecutar la consulta */
mysqli_stmt_execute($stmt);

/* Obtener el usuario encontrado */
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

/*
Si el correo no existe, regresar al login.
*/
if (!$usuario) {
    mysqli_stmt_close($stmt);

    header("Location: ../login.php?error=credenciales");
    exit();
}

/*
Una cuenta con estado 0 no puede iniciar sesión.
*/
if ((int) $usuario['estado'] !== 1) {
    mysqli_stmt_close($stmt);

    header("Location: ../login.php?error=inactivo");
    exit();
}

/*
Comparar la contraseña ingresada contra el hash
almacenado en MySQL.
*/
if (!password_verify($password, $usuario['password'])) {
    mysqli_stmt_close($stmt);

    header("Location: ../login.php?error=credenciales");
    exit();
}

/*
Generar un nuevo identificador de sesión
después de iniciar sesión correctamente.
*/
session_regenerate_id(true);

/* Guardar los datos necesarios dentro de la sesión */
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_nombre'] = $usuario['nombre'];
$_SESSION['usuario_correo'] = $usuario['correo'];
$_SESSION['rol_id'] = $usuario['rol_id'];
$_SESSION['rol'] = $usuario['rol'];

mysqli_stmt_close($stmt);

/* Enviar al usuario al dashboard */
header("Location: ../dashboard.php");
exit();