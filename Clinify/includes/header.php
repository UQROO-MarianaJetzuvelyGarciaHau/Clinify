
<?php

/*
Recuperar la sesión del administrador o asistente.

Esto permite que el menú público sepa si existe
una sesión iniciada.
*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <!-- Hace que el sitio sea responsivo en celulares y tablets -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Clinify | Gestión de estudios médicos</title>

    <!-- Archivo principal de estilos -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/styleNos.css">
    <link rel="stylesheet" href="assets/css/styleplan.css">
    <link rel="stylesheet" href="assets/css/stylecar.css">
    <link rel="stylesheet" href="assets/css/stylecontac.css">
</head>
<body>