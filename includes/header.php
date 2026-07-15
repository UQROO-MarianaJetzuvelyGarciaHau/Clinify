
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

    <meta name="description" content="<?php echo htmlspecialchars($meta_description ?? 'Clinify organiza tus estudios médicos personales de forma sencilla, segura y accesible.', ENT_QUOTES, 'UTF-8'); ?>">

    <!-- Estilos críticos cargados de forma prioritaria -->
    <link rel="preload" href="assets/css/style.css" as="style">
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Estilos secundarios cargados de forma diferida para mejorar el rendimiento inicial -->
    <link rel="preload" href="assets/css/styleNos.css" as="style">
    <link rel="stylesheet" href="assets/css/styleNos.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="assets/css/styleNos.css"></noscript>

    <link rel="preload" href="assets/css/styleplan.css" as="style">
    <link rel="stylesheet" href="assets/css/styleplan.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="assets/css/styleplan.css"></noscript>

    <link rel="preload" href="assets/css/stylecar.css" as="style">
    <link rel="stylesheet" href="assets/css/stylecar.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="assets/css/stylecar.css"></noscript>

    <link rel="preload" href="assets/css/stylecontac.css" as="style">
    <link rel="stylesheet" href="assets/css/stylecontac.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="assets/css/stylecontac.css"></noscript>

    <link rel="preload" href="assets/css/msjEmergente.css" as="style">
    <link rel="stylesheet" href="assets/css/msjEmergente.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="assets/css/msjEmergente.css"></noscript>
</head>
<body>