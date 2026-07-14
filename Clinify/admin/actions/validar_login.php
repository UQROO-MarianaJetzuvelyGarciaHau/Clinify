<?php

/* Iniciar sesión para guardar los datos del usuario */
session_start();

/* Conexión ubicada dos niveles arriba */
include '../../config/conexion.php';

/* Impedir que el archivo se abra directamente */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit();
}

/* Recuperar y limpiar los valores del formulario */
$correo = trim($_POST['correo'] ?? '');
$password = $_POST['password'] ?? '';

/* Validar campos vacíos */
if ($correo === '' || $password === '') {
    header("Location: ../login.php?error=1");
    exit();
}

/* Validar formato del correo */
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../login.php?error=1");
    exit();
}

/*
Buscar al usuario junto con el nombre de su rol.

No se compara la contraseña directamente en SQL,
porque está almacenada como hash.
*/
$consulta = "SELECT
                usuarios.id,
                usuarios.nombre,
                usuarios.correo,
                usuarios.password,
                usuarios.estado,
                roles.id AS rol_id,
                roles.nombre AS rol
             FROM usuarios
             INNER JOIN roles
                ON usuarios.id_rol = roles.id
             WHERE usuarios.correo = ?
             LIMIT 1";

$stmt = mysqli_prepare($conexion, $consulta);

if (!$stmt) {
    header("Location: ../login.php?error=1");
    exit();
}

mysqli_stmt_bind_param($stmt, "s", $correo);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

/* Comprobar que el usuario exista */
if (!$usuario) {
    mysqli_stmt_close($stmt);

    header("Location: ../login.php?error=1");
    exit();
}

/* Impedir el acceso a una cuenta desactivada */
if ((int) $usuario['estado'] !== 1) {
    mysqli_stmt_close($stmt);

    header("Location: ../login.php?cuenta=inactiva");
    exit();
}

/*
Comparar la contraseña escrita con el hash
almacenado en la base de datos.
*/
if (!password_verify($password, $usuario['password'])) {
    mysqli_stmt_close($stmt);

    header("Location: ../login.php?error=1");
    exit();
}

/*
Crear un nuevo identificador de sesión
para mejorar la seguridad.
*/
session_regenerate_id(true);

/* Datos que estarán disponibles durante la sesión */
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_nombre'] = $usuario['nombre'];
$_SESSION['usuario_correo'] = $usuario['correo'];
$_SESSION['rol_id'] = $usuario['rol_id'];
$_SESSION['rol'] = $usuario['rol'];

mysqli_stmt_close($stmt);

/* Acceso correcto */
header("Location: ../dashboard.php");
exit();