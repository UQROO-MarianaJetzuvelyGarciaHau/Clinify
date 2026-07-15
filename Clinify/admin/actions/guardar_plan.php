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

/* Recuperar los datos del formulario */
$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$precio = (float) ($_POST['precio'] ?? 0);
$periodo = trim($_POST['periodo'] ?? '');
$almacenamiento = trim($_POST['almacenamiento'] ?? '');
$cantidadEstudios = trim($_POST['cantidad_estudios'] ?? '');
$soporte = trim($_POST['soporte'] ?? '');
$estado = (int) ($_POST['estado'] ?? 0);
$masVendido = (int) ($_POST['es_mas_vendido'] ?? 0);

/* Validar la información */
if (
    $nombre === '' ||
    $descripcion === '' ||
    $precio < 0 ||
    $periodo === '' ||
    $almacenamiento === '' ||
    $cantidadEstudios === '' ||
    $soporte === '' ||
    !in_array($estado, [0, 1], true) ||
    !in_array($masVendido, [0, 1], true)
) {
    header("Location: ../nuevo_plan.php?error=datos");
    exit();
}

/*
Si el nuevo plan será "Más vendido",
se retira esa etiqueta de los demás.
*/
if ($masVendido === 1) {
    mysqli_query(
        $conexion,
        "UPDATE planes SET es_mas_vendido = 0"
    );
}

/* Insertar mediante consulta preparada */
$consulta = "INSERT INTO planes
             (
                nombre,
                descripcion,
                precio,
                periodo,
                almacenamiento,
                cantidad_estudios,
                soporte,
                estado,
                es_mas_vendido
             )
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexion, $consulta);

mysqli_stmt_bind_param(
    $stmt,
    "ssdssssii",
    $nombre,
    $descripcion,
    $precio,
    $periodo,
    $almacenamiento,
    $cantidadEstudios,
    $soporte,
    $estado,
    $masVendido
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: ../planes.php?mensaje=creado");
    exit();
}

mysqli_stmt_close($stmt);

header("Location: ../nuevo_plan.php?error=general");
exit();