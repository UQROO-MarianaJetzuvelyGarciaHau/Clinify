<?php

/* Iniciar la sesión */
session_start();

/*
Administradores y asistentes pueden dar
seguimiento a los contactos.
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

/* Conectar con la base de datos */
require_once '../../../config/conexion.php';

/* Solo procesar formularios enviados mediante POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header(
        "Location: ../../contactos/contactos.php"
    );
    exit();
}

/* Recuperar el ID y la página de regreso */
$id = (int) ($_POST['id'] ?? 0);
$regresar = $_POST['regresar'] ?? '';

if ($id <= 0) {
    header(
        "Location: ../../contactos/contactos.php?mensaje=error"
    );
    exit();
}

/*
Cambiar automáticamente el estado:

Nuevo    -> Atendido
Atendido -> Nuevo
*/
$consulta = "
    UPDATE contactos
    SET estado =
        CASE
            WHEN estado = 'Nuevo'
                THEN 'Atendido'
            ELSE 'Nuevo'
        END
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

    /*
    Si el cambio se hizo desde el detalle,
    regresamos al mismo mensaje.
    */
    if ($regresar === 'detalle') {
        header(
            "Location: ../../contactos/ver_contacto.php?id=$id"
        );
        exit();
    }

    header(
        "Location: ../../contactos/contactos.php?mensaje=estado"
    );
    exit();
}

mysqli_stmt_close($stmt);

header(
    "Location: ../../contactos/contactos.php?mensaje=error"
);
exit();