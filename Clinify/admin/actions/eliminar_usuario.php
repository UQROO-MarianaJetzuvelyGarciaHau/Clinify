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

/* No permitir eliminar la cuenta actualmente conectada */
if ($id === (int) $_SESSION['usuario_id']) {
    header("Location: ../usuarios.php?mensaje=protegido");
    exit();
}

/* Consultar el rol del perfil */
$consultaUsuario = "SELECT roles.nombre AS rol
                    FROM usuarios
                    INNER JOIN roles ON usuarios.id_rol = roles.id
                    WHERE usuarios.id = ?
                    LIMIT 1";

$stmtUsuario = mysqli_prepare($conexion, $consultaUsuario);

mysqli_stmt_bind_param($stmtUsuario, "i", $id);
mysqli_stmt_execute($stmtUsuario);

$resultadoUsuario = mysqli_stmt_get_result($stmtUsuario);
$usuario = mysqli_fetch_assoc($resultadoUsuario);

mysqli_stmt_close($stmtUsuario);

if (!$usuario) {
    header("Location: ../usuarios.php?mensaje=error");
    exit();
}

/* No permitir borrar al último administrador */
if ($usuario['rol'] === 'Administrador') {

    $resultadoAdmins = mysqli_query(
        $conexion,
        "SELECT COUNT(*) AS total
         FROM usuarios
         INNER JOIN roles ON usuarios.id_rol = roles.id
         WHERE roles.nombre = 'Administrador'"
    );

    $filaAdmins = mysqli_fetch_assoc($resultadoAdmins);

    if ((int) $filaAdmins['total'] <= 1) {
        header("Location: ../usuarios.php?mensaje=ultimo_admin");
        exit();
    }
}

/* Eliminar mediante consulta preparada */
$consultaEliminar = "DELETE FROM usuarios WHERE id = ?";
$stmtEliminar = mysqli_prepare($conexion, $consultaEliminar);

mysqli_stmt_bind_param($stmtEliminar, "i", $id);

if (mysqli_stmt_execute($stmtEliminar)) {
    mysqli_stmt_close($stmtEliminar);

    header("Location: ../usuarios.php?mensaje=eliminado");
    exit();
}

mysqli_stmt_close($stmtEliminar);

header("Location: ../usuarios.php?mensaje=error");
exit();