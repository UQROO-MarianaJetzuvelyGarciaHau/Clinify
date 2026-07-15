<?php

/* Iniciar sesión */
session_start();

/* Comprobar permisos */
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

/* Conectar con MySQL */
require_once '../../../config/conexion.php';

/* Solo aceptar solicitudes POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(
        "Location: ../../caracteristicas/caracteristicas.php"
    );
    exit();
}

/* Recuperar el ID */
$id = (int) ($_POST['id'] ?? 0);

/* Validar el ID */
if ($id <= 0) {
    header(
        "Location: ../../caracteristicas/caracteristicas.php?mensaje=error"
    );
    exit();
}

/* Consulta preparada para eliminar */
$consulta = "
    DELETE FROM caracteristicas
    WHERE id = ?
";

$stmt = mysqli_prepare(
    $conexion,
    $consulta
);

if (!$stmt) {
    header(
        "Location: ../../caracteristicas/caracteristicas.php?mensaje=error"
    );
    exit();
}

/* Asociar el ID */
mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

/* Ejecutar la eliminación */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header(
        "Location: ../../caracteristicas/caracteristicas.php?mensaje=eliminada"
    );
    exit();
}

/* Si no se pudo eliminar */
mysqli_stmt_close($stmt);

header(
    "Location: ../../caracteristicas/caracteristicas.php?mensaje=error"
);
exit();