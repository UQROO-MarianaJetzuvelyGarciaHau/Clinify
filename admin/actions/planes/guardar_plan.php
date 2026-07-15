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

/*
Desde admin/actions/planes debemos subir tres
niveles para llegar a config/conexion.php.
*/
require_once '../../../config/conexion.php';

/* Solo permitir formularios enviados por POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(
        "Location: /Clinify/admin/plan/planes.php"
    );
    exit();
}

/* Recuperar y limpiar los datos */
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

/* Estados aceptados por el sistema */
$estadosPermitidos = [
    'Activo',
    'Proximamente',
    'Desactivado'
];

/* Validar información */
if (
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
        "Location: /Clinify/admin/plan/nuevo_plan.php?error=datos"
    );
    exit();
}

/* Consulta para registrar el plan */
$consulta = "
    INSERT INTO planes
    (
        nombre,
        descripcion,
        precio,
        periodo,
        almacenamiento,
        cantidad_estudios,
        soporte,
        estado
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = mysqli_prepare($conexion, $consulta);

if (!$stmt) {
    header(
        "Location: /Clinify/admin/plan/nuevo_plan.php?error=general"
    );
    exit();
}

/* Asociar los datos a la consulta */
mysqli_stmt_bind_param(
    $stmt,
    "ssdsssss",
    $nombre,
    $descripcion,
    $precio,
    $periodo,
    $almacenamiento,
    $cantidadEstudios,
    $soporte,
    $estado
);

/* Ejecutar el registro */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header(
        "Location: /Clinify/admin/plan/planes.php?mensaje=creado"
    );
    exit();
}

mysqli_stmt_close($stmt);

header(
    "Location: /Clinify/admin/plan/nuevo_plan.php?error=general"
);
exit();