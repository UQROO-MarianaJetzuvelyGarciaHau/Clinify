<?php

session_start();

if (
    !isset($_SESSION['usuario_id']) ||
    !in_array($_SESSION['rol'] ?? '', ['Administrador', 'Asistente'], true)
) {
    header("Location: ../dashboard.php?acceso=denegado");
    exit();
}

include '../../config/conexion.php';

$id = (int) ($_POST['id'] ?? 0);

$consulta = "DELETE FROM planes WHERE id = ?";
$stmt = mysqli_prepare($conexion, $consulta);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../planes.php?mensaje=eliminado");
    exit();
}

header("Location: ../planes.php?mensaje=error");
exit();