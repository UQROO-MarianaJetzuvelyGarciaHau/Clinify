<?php

/* Iniciar sesión */
session_start();

/* Validar permisos */
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

/* Conectar con MySQL */
require_once '../../../config/conexion.php';

/* Solo aceptar POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(
        "Location: /Clinify/admin/plan/planes.php"
    );
    exit();
}

/* Recuperar el ID */
$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    header(
        "Location: /Clinify/admin/plan/planes.php?mensaje=error"
    );
    exit();
}

/* Eliminar mediante consulta preparada */
$consulta = "
    DELETE FROM planes
    WHERE id = ?
";

$stmt = mysqli_prepare($conexion, $consulta);

if (!$stmt) {
    header(
        "Location: /Clinify/admin/plan/planes.php?mensaje=error"
    );
    exit();
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

/* Ejecutar la eliminación */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header(
        "Location: /Clinify/admin/plan/planes.php?mensaje=eliminado"
    );
    exit();
}

mysqli_stmt_close($stmt);

header(
    "Location: /Clinify/admin/plan/planes.php?mensaje=error"
);
exit();