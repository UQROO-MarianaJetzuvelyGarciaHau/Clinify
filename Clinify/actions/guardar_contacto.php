<?php

/* Conectar con la base de datos */
include '../config/conexion.php';

/*
Este archivo únicamente debe ejecutarse cuando
el formulario se envía mediante POST.
*/
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../contacto.php");
    exit();
}

/* Recuperar y limpiar los datos enviados */
$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$planInteres = (int) ($_POST['plan_interes'] ?? 0);
$mensaje = trim($_POST['mensaje'] ?? '');

/* Validar que ningún campo obligatorio llegue vacío */
if (
    $nombre === '' ||
    $correo === '' ||
    $telefono === '' ||
    $planInteres <= 0 ||
    $mensaje === ''
) {
    header("Location: ../contacto.php?mensaje=error");
    exit();
}

/*
Validar el nombre.

Se permiten letras, acentos, la letra ñ y espacios.
*/
if (
    !preg_match(
        "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/u",
        $nombre
    )
) {
    header("Location: ../contacto.php?mensaje=error");
    exit();
}

/* Validar la longitud del nombre */
if (
    mb_strlen($nombre) < 3 ||
    mb_strlen($nombre) > 80
) {
    header("Location: ../contacto.php?mensaje=error");
    exit();
}

/* Validar el formato del correo */
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../contacto.php?mensaje=error");
    exit();
}

/* Validar el tamaño máximo del correo */
if (mb_strlen($correo) > 120) {
    header("Location: ../contacto.php?mensaje=error");
    exit();
}

/* El teléfono debe contener exactamente 10 números */
if (!preg_match("/^[0-9]{10}$/", $telefono)) {
    header("Location: ../contacto.php?mensaje=error");
    exit();
}

/* Validar la longitud del mensaje */
if (
    mb_strlen($mensaje) < 15 ||
    mb_strlen($mensaje) > 500
) {
    header("Location: ../contacto.php?mensaje=error");
    exit();
}

/*
Comprobar que el plan exista y que su estado permita
que aparezca en el sitio público.

Esto protege el backend por si alguien modifica
el valor del select desde el navegador.
*/
$consultaPlan = "
    SELECT id
    FROM planes
    WHERE id = ?
    AND estado IN ('Activo', 'Proximamente')
    LIMIT 1
";

$stmtPlan = mysqli_prepare(
    $conexion,
    $consultaPlan
);

if (!$stmtPlan) {
    header("Location: ../contacto.php?mensaje=error");
    exit();
}

/* Asociar el ID recibido al marcador ? */
mysqli_stmt_bind_param(
    $stmtPlan,
    "i",
    $planInteres
);

/* Ejecutar la consulta */
mysqli_stmt_execute($stmtPlan);

/* Recuperar el resultado */
$resultadoPlan = mysqli_stmt_get_result($stmtPlan);
$planValido = mysqli_fetch_assoc($resultadoPlan);

mysqli_stmt_close($stmtPlan);

/* Rechazar el formulario si el plan no es válido */
if (!$planValido) {
    header("Location: ../contacto.php?mensaje=error");
    exit();
}

/*
Insertar el mensaje en la tabla contactos.

Se utiliza una consulta preparada para evitar
inyección SQL.
*/
$consulta = "
    INSERT INTO contactos
    (
        nombre,
        correo,
        telefono,
        plan_interes,
        mensaje
    )
    VALUES (?, ?, ?, ?, ?)
";

$stmt = mysqli_prepare(
    $conexion,
    $consulta
);

if (!$stmt) {
    header("Location: ../contacto.php?mensaje=error");
    exit();
}

/*
Tipos de datos:

s = nombre
s = correo
s = teléfono
i = ID del plan
s = mensaje
*/
mysqli_stmt_bind_param(
    $stmt,
    "sssis",
    $nombre,
    $correo,
    $telefono,
    $planInteres,
    $mensaje
);

/* Ejecutar el registro */
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: ../contacto.php?mensaje=exito");
    exit();
}

/* Si ocurre un error, regresar al formulario */
mysqli_stmt_close($stmt);

header("Location: ../contacto.php?mensaje=error");
exit();