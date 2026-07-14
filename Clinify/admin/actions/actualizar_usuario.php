<?php

session_start();

if (
    !isset($_SESSION['usuario_id']) ||
    ($_SESSION['rol'] ?? '') !== 'Administrador'
) {
    header("Location: ../dashboard.php?acceso=denegado");
    exit();
}

include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../usuarios.php");
    exit();
}

/* Recuperar datos */
$id = (int) ($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$idRol = (int) ($_POST['id_rol'] ?? 0);
$password = $_POST['password'] ?? '';
$confirmarPassword = $_POST['confirmar_password'] ?? '';

if (
    $id <= 0 ||
    $nombre === '' ||
    !filter_var($correo, FILTER_VALIDATE_EMAIL) ||
    $idRol <= 0
) {
    header("Location: ../editar_usuario.php?id=$id&error=campos");
    exit();
}

/* Verificar que el correo no pertenezca a otra cuenta */
$consultaCorreo = "SELECT id
                   FROM usuarios
                   WHERE correo = ? AND id != ?
                   LIMIT 1";

$stmtCorreo = mysqli_prepare($conexion, $consultaCorreo);

mysqli_stmt_bind_param($stmtCorreo, "si", $correo, $id);
mysqli_stmt_execute($stmtCorreo);

$resultadoCorreo = mysqli_stmt_get_result($stmtCorreo);
$correoExiste = mysqli_fetch_assoc($resultadoCorreo);

mysqli_stmt_close($stmtCorreo);

if ($correoExiste) {
    header("Location: ../editar_usuario.php?id=$id&error=correo");
    exit();
}

/*
Si el campo de contraseña está vacío,
solo actualizamos los datos básicos.
*/
if ($password === '') {

    $consulta = "UPDATE usuarios
                 SET nombre = ?, correo = ?, id_rol = ?
                 WHERE id = ?";

    $stmt = mysqli_prepare($conexion, $consulta);

    mysqli_stmt_bind_param(
        $stmt,
        "ssii",
        $nombre,
        $correo,
        $idRol,
        $id
    );

} else {

    /* Validar la nueva contraseña */
    if (
        strlen($password) < 8 ||
        $password !== $confirmarPassword
    ) {
        header("Location: ../editar_usuario.php?id=$id&error=password");
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $consulta = "UPDATE usuarios
                 SET nombre = ?,
                     correo = ?,
                     id_rol = ?,
                     password = ?
                 WHERE id = ?";

    $stmt = mysqli_prepare($conexion, $consulta);

    mysqli_stmt_bind_param(
        $stmt,
        "ssisi",
        $nombre,
        $correo,
        $idRol,
        $passwordHash,
        $id
    );
}

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: ../usuarios.php?mensaje=actualizado");
    exit();
}

mysqli_stmt_close($stmt);

header("Location: ../editar_usuario.php?id=$id&error=general");
exit();