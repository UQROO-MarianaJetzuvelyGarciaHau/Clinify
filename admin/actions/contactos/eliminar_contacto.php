<?php

/* Iniciar la sesión */
session_start();

/* Validar sesión y rol */
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
        "Location: ../../contactos/contactos.php"
    );
    exit();
} /**Aseguramos que solo se acepten solicitudes POST */

/* Recuperar el ID */
$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    header(
        "Location: ../../contactos/contactos.php?mensaje=error"
    );
    exit();
}

/* Eliminar el contacto */
$consulta = "
    DELETE FROM contactos
    WHERE id = ?
";

$stmt = mysqli_prepare(
    $conexion,
    $consulta
);

if (!$stmt) {
    header(
        "Location: ../../contactos/contactos.php?mensaje=error"
    );
    exit();
}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header(
        "Location: ../../contactos/contactos.php?mensaje=eliminado"
    );
    exit();
}

mysqli_stmt_close($stmt);

header(
    "Location: ../../contactos/contactos.php?mensaje=error"
);
exit();