<?php

/* Iniciar la sesión */
session_start();

/*
Solo el rol Administrador puede modificar
la información del equipo.
*/
if (
    !isset($_SESSION['usuario_id']) ||
    ($_SESSION['rol'] ?? '') !== 'Administrador'
) {
    header(
        "Location: ../../dashboard.php?acceso=denegado"
    );
    exit();
}

/*
Desde admin/actions/equipo subimos tres niveles
para llegar a config/conexion.php.
*/
require_once '../../../config/conexion.php';

/* Solo aceptar formularios enviados por POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(
        "Location: ../../equipo/equipo.php"
    );
    exit();
}

/* Recuperar y limpiar los datos */
$id = (int) ($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$rol = trim($_POST['rol'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

/* Validar el ID */
if ($id <= 0) {
    header(
        "Location: ../../equipo/equipo.php?mensaje=error"
    );
    exit();
}

/* Validar el nombre */
if (
    mb_strlen($nombre) < 3 ||
    mb_strlen($nombre) > 100
) {
    header(
        "Location: ../../equipo/editar_integrante.php?id=$id&error=nombre"
    );
    exit();
}

/* Validar el rol */
if (
    mb_strlen($rol) < 3 ||
    mb_strlen($rol) > 100
) {
    header(
        "Location: ../../equipo/editar_integrante.php?id=$id&error=rol"
    );
    exit();
}

/* Validar la descripción */
if (
    mb_strlen($descripcion) < 10 ||
    mb_strlen($descripcion) > 500
) {
    header(
        "Location: ../../equipo/editar_integrante.php?id=$id&error=descripcion"
    );
    exit();
}

/*
Actualizar el integrante mediante una
consulta preparada.
*/
$consulta = "
    UPDATE equipo
    SET
        nombre = ?,
        rol = ?,
        descripcion = ?
    WHERE id = ?
";

$stmt = mysqli_prepare(
    $conexion,
    $consulta
);

if (!$stmt) {
    header(
        "Location: ../../equipo/editar_integrante.php?id=$id&error=general"
    );
    exit();
}

/*
Tipos:

s = nombre
s = rol
s = descripción
i = ID
*/
mysqli_stmt_bind_param(
    $stmt,
    "sssi",
    $nombre,
    $rol,
    $descripcion,
    $id
);

/* Ejecutar la actualización */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header(
        "Location: ../../equipo/equipo.php?mensaje=actualizado"
    );
    exit();
}

/* Si ocurre un error */
mysqli_stmt_close($stmt);

header(
    "Location: ../../equipo/editar_integrante.php?id=$id&error=general"
);
exit();