<?php
// Datos de conexión local con XAMPP
$host = "localhost";
$usuario = "root";
$password = "";
$base_datos = "clinify";

// Crear conexión con MySQL
$conexion = mysqli_connect($host, $usuario, $password, $base_datos);

// Verificar si hubo error en la conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Permite usar correctamente acentos y caracteres especiales
mysqli_set_charset($conexion, "utf8");
?>