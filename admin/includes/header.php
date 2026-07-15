<?php

/*
Cargar una sola vez el archivo que contiene
la sesión y las funciones de permisos.
*/
require_once __DIR__ . '/autenticacion.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <!-- Permite que el panel sea responsivo -->
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Panel administrativo | Clinify</title>

    <!-- Estilos generales del panel -->
    <link
        rel="stylesheet"
        href="/Clinify/admin/css/admin.css">

    <!-- Estilos del módulo de planes -->
    <link
        rel="stylesheet"
        href="/Clinify/admin/css/plan.css">
</head>

<body>

<div class="admin-contenedor">