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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../planes.php");
    exit();
}

$id = (int) ($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = (float) ($_POST['precio'] ?? 0);
$periodo = trim($_POST['periodo'] ?? '');
$almacenamiento = trim($_POST['almacenamiento'] ?? '');
$cantidadEstudios = trim($_POST['cantidad_estudios'] ?? '');
$soporte = trim($_POST['soporte'] ?? '');
$estado = (int) ($_POST['estado'] ?? 0);
$masVendido = (int) ($_POST['es_mas_vendido'] ?? 0);

if (
    $id <= 0 ||
    $nombre === '' ||
    $descripcion === '' ||
    $precio < 0
) {
    header("Location: ../editar_plan.php?id=$id&error=datos");
    exit();
}

if ($masVendido === 1) {
    $consultaQuitar = "UPDATE planes
                       SET es_mas_vendido = 0
                       WHERE id != ?";

    $stmtQuitar = mysqli_prepare($conexion, $consultaQuitar);
    mysqli_stmt_bind_param($stmtQuitar, "i", $id);
    mysqli_stmt_execute($stmtQuitar);
    mysqli_stmt_close($stmtQuitar);
}

$consulta = "UPDATE planes
             SET nombre = ?,
                 descripcion = ?,
                 precio = ?,
                 periodo = ?,
                 almacenamiento = ?,
                 cantidad_estudios = ?,
                 soporte = ?,
                 estado = ?,
                 es_mas_vendido = ?
             WHERE id = ?";

$stmt = mysqli_prepare($conexion, $consulta);

mysqli_stmt_bind_param(
    $stmt,
    "ssdssssiii",
    $nombre,
    $descripcion,
    $precio,
    $periodo,
    $almacenamiento,
    $cantidadEstudios,
    $soporte,
    $estado,
    $masVendido,
    $id
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: ../planes.php?mensaje=actualizado");
    exit();
}

mysqli_stmt_close($stmt);

header("Location: ../editar_plan.php?id=$id&error=general");
exit();