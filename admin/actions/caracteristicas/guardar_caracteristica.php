<?php

/*
Iniciar la sesión para comprobar que el usuario
tenga permiso de realizar esta acción.
*/
session_start();

/*
Solo Administradores y Asistentes pueden
administrar características.
*/
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

/*
Desde admin/actions/caracteristicas debemos subir
tres niveles para llegar a config/conexion.php.
*/
require_once '../../../config/conexion.php';

/*
El archivo solamente debe procesar formularios
enviados mediante POST.
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(
        "Location: ../../caracteristicas/caracteristicas.php"
    );
    exit();
}

/* Recuperar y limpiar los valores del formulario */
$titulo = trim($_POST['titulo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$estado = (int) ($_POST['estado'] ?? -1);

/* Validar la longitud del título */
if (
    mb_strlen($titulo) < 3 ||
    mb_strlen($titulo) > 100
) {
    header(
        "Location: ../../caracteristicas/nueva_caracteristica.php?error=titulo"
    );
    exit();
}

/* Validar la longitud de la descripción */
if (
    mb_strlen($descripcion) < 10 ||
    mb_strlen($descripcion) > 500
) {
    header(
        "Location: ../../caracteristicas/nueva_caracteristica.php?error=descripcion"
    );
    exit();
}

/* El estado solo puede ser cero o uno */
if (!in_array($estado, [0, 1], true)) {
    header(
        "Location: ../../caracteristicas/nueva_caracteristica.php?error=estado"
    );
    exit();
}

/*
Consulta preparada para guardar la característica.

Los signos ? se sustituyen de forma segura por
los valores recibidos.
*/
$consulta = "
    INSERT INTO caracteristicas
    (
        titulo,
        descripcion,
        estado
    )
    VALUES (?, ?, ?)
";

$stmt = mysqli_prepare(
    $conexion,
    $consulta
);

/* Comprobar que la consulta se haya preparado */
if (!$stmt) {
    header(
        "Location: ../../caracteristicas/nueva_caracteristica.php?error=general"
    );
    exit();
}

/*
Tipos de datos:

s = título
s = descripción
i = estado
*/
mysqli_stmt_bind_param(
    $stmt,
    "ssi",
    $titulo,
    $descripcion,
    $estado
);

/* Ejecutar la inserción */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header(
        "Location: ../../caracteristicas/caracteristicas.php?mensaje=creada"
    );
    exit();
}

/* Cerrar la consulta si ocurrió un error */
mysqli_stmt_close($stmt);

header(
    "Location: ../../caracteristicas/nueva_caracteristica.php?error=general"
);
exit();