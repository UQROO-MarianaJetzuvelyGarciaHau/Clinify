<?php

/* Iniciar la sesión únicamente si todavía no está activa */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Impedir el acceso al panel cuando no existe una sesión */
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

/* Comprobar si el usuario actual es Administrador */
function esAdministrador(): bool
{
    return isset($_SESSION['rol'])
        && $_SESSION['rol'] === 'Administrador';
}

/* Proteger páginas exclusivas del Administrador */
function requerirAdministrador(): void
{
    if (!esAdministrador()) {
        header("Location: dashboard.php?acceso=denegado");
        exit();
    }
}

/*
Permitir el acceso a Administradores y Asistentes.

Actualmente ambos roles pueden administrar:
- Planes
- Características
- Contactos
*/
function puedeGestionarContenido(): bool
{
    $rolesPermitidos = ['Administrador', 'Asistente'];

    return isset($_SESSION['rol'])
        && in_array($_SESSION['rol'], $rolesPermitidos, true);
}

/* Proteger las páginas de contenido */
function requerirGestorContenido(): void
{
    if (!puedeGestionarContenido()) {
        header("Location: dashboard.php?acceso=denegado");
        exit();
    }
}