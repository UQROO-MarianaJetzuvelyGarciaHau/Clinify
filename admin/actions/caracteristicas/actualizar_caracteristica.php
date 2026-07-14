<?php

/* Iniciar la sesión */
session_start();

/* Validar que el usuario tenga permiso */
if (
    !isset($_SESSION['usuario_id']) ||
    !in_array(
        $_SESSION['rol'] ?? '',
        ['Administrador', 'Asistente'],
        true
    )
) {
    header(
        "Location: ../../dashboard.php?acceso=denegado"
    );
    exit();
}

/* Conectar con la base de datos */
require_once '../../../config/conexion.php';

/* Solo aceptar formularios enviados por POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(
        "Location: ../../caracteristicas/caracteristicas.php"
    );
    exit();
}

/* Recuperar y limpiar los datos */
$id = (int) ($_POST['id'] ?? 0);
$titulo = trim($_POST['titulo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$estado = (int) ($_POST['estado'] ?? -1);

/* Validar el ID */
if ($id <= 0) {
    header(
        "Location: ../../caracteristicas/caracteristicas.php?mensaje=error"
    );
    exit();
}

/* Validar el título */
if (
    mb_strlen($titulo) < 3 ||
    mb_strlen($titulo) > 100
) {
    header(
        "Location: ../../caracteristicas/editar_caracteristica.php?id=$id&error=titulo"
    );
    exit();
}

/* Validar la descripción */
if (
    mb_strlen($descripcion) < 10 ||
    mb_strlen($descripcion) > 500
) {
    header(
        "Location: ../../caracteristicas/editar_caracteristica.php?id=$id&error=descripcion"
    );
    exit();
}

/* Validar el estado */
if (!in_array($estado, [0, 1], true)) {
    header(
        "Location: ../../caracteristicas/editar_caracteristica.php?id=$id&error=estado"
    );
    exit();
}

/* Consulta preparada para actualizar */
$consulta = "
    UPDATE caracteristicas
    SET
        titulo = ?,
        descripcion = ?,
        estado = ?
    WHERE id = ?
";

$stmt = mysqli_prepare(
    $conexion,
    $consulta
);

if (!$stmt) {
    header(
        "Location: ../../caracteristicas/editar_caracteristica.php?id=$id&error=general"
    );
    exit();
}

/* Asociar los valores */
mysqli_stmt_bind_param(
    $stmt,
    "ssii",
    $titulo,
    $descripcion,
    $estado,
    $id
);

/* Ejecutar la actualización */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header(
        "Location: ../../caracteristicas/caracteristicas.php?mensaje=actualizada"
    );
    exit();
}

/* Si ocurre un error */
mysqli_stmt_close($stmt);

header(
    "Location: ../../caracteristicas/editar_caracteristica.php?id=$id&error=general"
);
exit();