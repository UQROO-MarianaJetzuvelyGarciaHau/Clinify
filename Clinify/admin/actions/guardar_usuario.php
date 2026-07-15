<?php

/* Iniciar la sesión para verificar quién realiza la acción */
session_start();

/* Solo un Administrador puede crear perfiles */
if (
    !isset($_SESSION['usuario_id']) ||
    !isset($_SESSION['rol']) ||
    $_SESSION['rol'] !== 'Administrador'
) {
    header("Location: ../dashboard.php?acceso=denegado");
    exit();
}

include '../../config/conexion.php';

/* El archivo solo debe recibir formularios por POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../nuevo_usuario.php");
    exit();
}

/* Recuperar y limpiar los datos */
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$idRol = (int) ($_POST['id_rol'] ?? 0);
$password = $_POST['password'] ?? '';
$confirmarPassword = $_POST['confirmar_password'] ?? '';

/* Validar campos obligatorios */
if (
    $nombre === '' ||
    $correo === '' ||
    $idRol <= 0 ||
    $password === '' ||
    $confirmarPassword === ''
) {
    header("Location: ../nuevo_usuario.php?error=campos");
    exit();
}

/* Validar nombre */
if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/u", $nombre)) {
    header("Location: ../nuevo_usuario.php?error=campos");
    exit();
}

/* Validar correo */
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../nuevo_usuario.php?error=campos");
    exit();
}

/* Verificar que el rol realmente exista */
$consultaRol = "SELECT id FROM roles WHERE id = ? LIMIT 1";
$stmtRol = mysqli_prepare($conexion, $consultaRol);

mysqli_stmt_bind_param($stmtRol, "i", $idRol);
mysqli_stmt_execute($stmtRol);

$resultadoRol = mysqli_stmt_get_result($stmtRol);
$rolExiste = mysqli_fetch_assoc($resultadoRol);

mysqli_stmt_close($stmtRol);

if (!$rolExiste) {
    header("Location: ../nuevo_usuario.php?error=campos");
    exit();
}

/* Validar contraseña */
if (strlen($password) < 8 || strlen($password) > 100) {
    header("Location: ../nuevo_usuario.php?error=password");
    exit();
}

/* Confirmar contraseña */
if ($password !== $confirmarPassword) {
    header("Location: ../nuevo_usuario.php?error=confirmacion");
    exit();
}

/* Comprobar si el correo ya está registrado */
$consultaCorreo = "SELECT id FROM usuarios WHERE correo = ? LIMIT 1";
$stmtCorreo = mysqli_prepare($conexion, $consultaCorreo);

mysqli_stmt_bind_param($stmtCorreo, "s", $correo);
mysqli_stmt_execute($stmtCorreo);

$resultadoCorreo = mysqli_stmt_get_result($stmtCorreo);
$correoExiste = mysqli_fetch_assoc($resultadoCorreo);

mysqli_stmt_close($stmtCorreo);

if ($correoExiste) {
    header("Location: ../nuevo_usuario.php?error=correo");
    exit();
}

/*
Convertir la contraseña en un hash seguro.
La contraseña original no se guarda en la base.
*/
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

/* Insertar el nuevo perfil */
$consulta = "INSERT INTO usuarios
             (nombre, correo, password, id_rol, estado)
             VALUES (?, ?, ?, ?, 1)";

$stmt = mysqli_prepare($conexion, $consulta);

mysqli_stmt_bind_param(
    $stmt,
    "sssi",
    $nombre,
    $correo,
    $passwordHash,
    $idRol
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: ../usuarios.php?mensaje=creado");
    exit();
}

mysqli_stmt_close($stmt);

header("Location: ../nuevo_usuario.php?error=general");
exit();