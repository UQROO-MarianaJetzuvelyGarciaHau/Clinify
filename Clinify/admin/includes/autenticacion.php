<?php

/*
Iniciar la sesión solamente cuando todavía
no existe una sesión activa.
*/
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
Si no existe un usuario autenticado,
regresamos al inicio de sesión.
*/
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

/*
Comprobar si el usuario actual tiene
el rol Administrador.
*/
function esAdministrador()
{
    return isset($_SESSION['rol'])
        && $_SESSION['rol'] === 'Administrador';
}

/*
Proteger páginas exclusivas del Administrador.
*/
function requerirAdministrador()
{
    if (!esAdministrador()) {
        header("Location: dashboard.php?acceso=denegado");
        exit();
    }
}

/*
Comprobar si el usuario puede administrar
planes, características y contactos.
*/
function puedeGestionarContenido()
{
    if (!isset($_SESSION['rol'])) {
        return false;
    }

    return in_array(
        $_SESSION['rol'],
        ['Administrador', 'Asistente'],
        true
    );
}

/*
Proteger módulos disponibles para
Administradores y Asistentes.
*/
function requerirGestorContenido()
{
    if (!puedeGestionarContenido()) {
        header("Location: dashboard.php?acceso=denegado");
        exit();
    }
}