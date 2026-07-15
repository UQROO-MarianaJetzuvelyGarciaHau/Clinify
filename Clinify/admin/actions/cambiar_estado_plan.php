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

$consulta = "UPDATE planes
             SET estado = IF(estado = 1, 0, 1)
             WHERE id = ?";

$stmt = mysqli_prepare($conexion, $consulta);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: ../planes.php?mensaje=estado");
    exit();
}

header("Location: ../planes.php?mensaje=error");
exit();