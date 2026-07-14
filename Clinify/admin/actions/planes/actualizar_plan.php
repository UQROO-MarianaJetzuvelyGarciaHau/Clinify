<?php

/* Iniciar la sesión */
session_start();

/* Validar usuario y rol */
if (
    !isset($_SESSION['usuario_id']) ||
    !in_array(
        $_SESSION['rol'] ?? '',
        ['Administrador', 'Asistente'],
        true
    )
) {
    header(
        "Location: /Clinify/admin/dashboard.php?acceso=denegado"
    );
    exit();
}

/* Ruta correcta hacia la conexión */
require_once '../../../config/conexion.php';

/* Solo aceptar formularios enviados mediante POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(
        "Location: /Clinify/admin/plan/planes.php"
    );
    exit();
}

/* Recuperar los datos */
$id = (int) ($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = (float) ($_POST['precio'] ?? -1);
$periodo = trim($_POST['periodo'] ?? '');
$almacenamiento = trim(
    $_POST['almacenamiento'] ?? ''
);
$cantidadEstudios = trim(
    $_POST['cantidad_estudios'] ?? ''
);
$soporte = trim($_POST['soporte'] ?? '');
$estado = $_POST['estado'] ?? '';

$estadosPermitidos = [
    'Activo',
    'Proximamente',
    'Desactivado'
];

/* Validar la información */
if (
    $id <= 0 ||
    mb_strlen($nombre) < 3 ||
    mb_strlen($nombre) > 100 ||
    mb_strlen($descripcion) < 10 ||
    mb_strlen($descripcion) > 500 ||
    $precio < 0 ||
    $periodo === '' ||
    $almacenamiento === '' ||
    $cantidadEstudios === '' ||
    $soporte === '' ||
    !in_array($estado, $estadosPermitidos, true)
) {
    header(
        "/Clinify/admin/plan/editar_plan.php?id=$id&error=datos"
    );
    exit();
}

/* Consulta para actualizar el plan */
$consulta = "
    UPDATE planes
    SET
        nombre = ?,
        descripcion = ?,
        precio = ?,
        periodo = ?,
        almacenamiento = ?,
        cantidad_estudios = ?,
        soporte = ?,
        estado = ?
    WHERE id = ?
";

$stmt = mysqli_prepare($conexion, $consulta);

if (!$stmt) {
    header(
        "Location: /Clinify/admin/plan/editar_plan.php?id=$id&error=general"
    );
    exit();
}

/* Asociar los datos */
mysqli_stmt_bind_param(
    $stmt,
    "ssdsssssi",
    $nombre,
    $descripcion,
    $precio,
    $periodo,
    $almacenamiento,
    $cantidadEstudios,
    $soporte,
    $estado,
    $id
);

/* Ejecutar la actualización */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header(
        "Location: /Clinify/admin/plan/planes.php?mensaje=actualizado"
    );
    exit();
}

mysqli_stmt_close($stmt);

header(
    "Location: /Clinify/admin/plan/editar_plan.php?id=$id&error=general"
);
exit();