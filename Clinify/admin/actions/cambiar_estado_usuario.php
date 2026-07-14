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

$id = (int) ($_POST['id'] ?? 0);

/* Impedir que el usuario desactive su propia cuenta */
if ($id === (int) $_SESSION['usuario_id']) {
    header("Location: ../usuarios.php?mensaje=protegido");
    exit();
}

/* Obtener el perfil actual y su rol */
$consulta = "SELECT usuarios.estado, roles.nombre AS rol
             FROM usuarios
             INNER JOIN roles ON usuarios.id_rol = roles.id
             WHERE usuarios.id = ?
             LIMIT 1";

$stmt = mysqli_prepare($conexion, $consulta);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

if (!$usuario) {
    header("Location: ../usuarios.php?mensaje=error");
    exit();
}

/*
Si se intenta desactivar un Administrador,
comprobamos que exista otro administrador activo.
*/
if (
    $usuario['rol'] === 'Administrador' &&
    (int) $usuario['estado'] === 1
) {
    $resultadoAdmins = mysqli_query(
        $conexion,
        "SELECT COUNT(*) AS total
         FROM usuarios
         INNER JOIN roles ON usuarios.id_rol = roles.id
         WHERE roles.nombre = 'Administrador'
         AND usuarios.estado = 1"
    );

    $filaAdmins = mysqli_fetch_assoc($resultadoAdmins);

    if ((int) $filaAdmins['total'] <= 1) {
        header("Location: ../usuarios.php?mensaje=ultimo_admin");
        exit();
    }
}

/* Alternar entre activo e inactivo */
$nuevoEstado = (int) $usuario['estado'] === 1 ? 0 : 1;

$consultaActualizar = "UPDATE usuarios
                       SET estado = ?
                       WHERE id = ?";

$stmtActualizar = mysqli_prepare($conexion, $consultaActualizar);

mysqli_stmt_bind_param(
    $stmtActualizar,
    "ii",
    $nuevoEstado,
    $id
);

if (mysqli_stmt_execute($stmtActualizar)) {
    mysqli_stmt_close($stmtActualizar);

    header("Location: ../usuarios.php?mensaje=estado");
    exit();
}

mysqli_stmt_close($stmtActualizar);

header("Location: ../usuarios.php?mensaje=error");
exit();