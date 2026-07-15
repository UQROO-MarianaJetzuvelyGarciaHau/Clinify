<?php

/*
|--------------------------------------------------------------------------
| SEGURIDAD
|--------------------------------------------------------------------------
| Solo el Administrador puede editar usuarios.
*/

require_once '../includes/autenticacion.php';
requerirAdministrador();


/*
|--------------------------------------------------------------------------
| CONEXIÓN
|--------------------------------------------------------------------------
*/

require_once '../../config/conexion.php';


/*
|--------------------------------------------------------------------------
| RECUPERAR ID
|--------------------------------------------------------------------------
| El ID llega mediante la URL:
| editar_usuario.php?id=1
*/

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: usuarios.php?mensaje=error");
    exit();
}


/*
|--------------------------------------------------------------------------
| CONSULTAR USUARIO
|--------------------------------------------------------------------------
*/

$consultaUsuario = "
    SELECT
        id,
        nombre,
        correo,
        id_rol,
        estado
    FROM usuarios
    WHERE id = ?
    LIMIT 1
";

$stmtUsuario = mysqli_prepare(
    $conexion,
    $consultaUsuario
);

if (!$stmtUsuario) {
    header("Location: usuarios.php?mensaje=error");
    exit();
}

mysqli_stmt_bind_param(
    $stmtUsuario,
    "i",
    $id
);

mysqli_stmt_execute($stmtUsuario);

$resultadoUsuario = mysqli_stmt_get_result(
    $stmtUsuario
);

$usuario = mysqli_fetch_assoc(
    $resultadoUsuario
);

mysqli_stmt_close($stmtUsuario);

/* Regresar si el usuario no existe */
if (!$usuario) {
    header("Location: usuarios.php?mensaje=error");
    exit();
}


/*
|--------------------------------------------------------------------------
| CONSULTAR ROLES
|--------------------------------------------------------------------------
*/

$consultaRoles = "
    SELECT
        id,
        nombre
    FROM roles
    ORDER BY id ASC
";

$resultadoRoles = mysqli_query(
    $conexion,
    $consultaRoles
);

if (!$resultadoRoles) {
    die(
        "Error al consultar los roles: "
        . mysqli_error($conexion)
    );
}


/* Cargar la interfaz */
include 'header_usuario.php';
include '../includes/sidebar.php';

?>

<main class="contenido-admin">

    <!-- Encabezado -->
    <header class="encabezado-formulario-usuario">

        <span class="texto-secundario">
            Usuarios
        </span>

        <h1>
            Editar usuario
        </h1>

        <p>
            Modifica los datos, rol, estado o contraseña
            de la cuenta seleccionada.
        </p>

    </header>


    <!-- Formulario -->
    <section class="contenedor-formulario-usuario">

        <form
            action="../actions/usuario/actualizar_usuario.php"
            method="POST"
            class="formulario-usuario">

            <!-- ID oculto del usuario -->
            <input
                type="hidden"
                name="id"
                value="<?php echo $usuario['id']; ?>">


            <!-- Mensajes de error -->
            <?php if (isset($_GET['error'])) { ?>

                <div class="mensaje-usuario mensaje-error">

                    <?php

                    switch ($_GET['error']) {

                        case 'nombre':
                            echo "El nombre debe tener entre 3 y 100 caracteres.";
                            break;

                        case 'correo':
                            echo "Ingresa un correo válido.";
                            break;

                        case 'correo_existente':
                            echo "El correo pertenece a otro usuario.";
                            break;

                        case 'password':
                            echo "La contraseña no cumple con los requisitos.";
                            break;

                        case 'confirmacion':
                            echo "Las contraseñas no coinciden.";
                            break;

                        case 'rol':
                            echo "Selecciona un rol válido.";
                            break;

                        case 'protegido':
                            echo "No puedes cambiar tu propia cuenta a Asistente o Inactiva.";
                            break;

                        default:
                            echo "Revisa los datos ingresados.";
                            break;
                    }

                    ?>

                </div>

            <?php } ?>


            <!-- Nombre -->
            <div class="campo-usuario">

                <label for="nombre">
                    Nombre completo
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?php
                        echo htmlspecialchars(
                            $usuario['nombre']
                        );
                    ?>"
                    minlength="3"
                    maxlength="100"
                    pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                    title="Escribe únicamente letras y espacios."
                    required>

            </div>


            <!-- Correo y rol -->
            <div class="fila-campos-usuario">

                <div class="campo-usuario">

                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        value="<?php
                            echo htmlspecialchars(
                                $usuario['correo']
                            );
                        ?>"
                        maxlength="120"
                        required>

                </div>


                <div class="campo-usuario">

                    <label for="id_rol">
                        Rol
                    </label>

                    <select
                        id="id_rol"
                        name="id_rol"
                        required>

                        <?php while (
                            $rol = mysqli_fetch_assoc(
                                $resultadoRoles
                            )
                        ) { ?>

                            <option
                                value="<?php echo $rol['id']; ?>"
                                <?php
                                echo (int) $usuario['id_rol'] ===
                                     (int) $rol['id']
                                    ? 'selected'
                                    : '';
                                ?>>

                                <?php
                                echo htmlspecialchars(
                                    $rol['nombre']
                                );
                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>

            </div>


            <!-- Estado -->
            <div class="campo-usuario">

                <label for="estado">
                    Estado
                </label>

                <select
                    id="estado"
                    name="estado"
                    required>

                    <option
                        value="1"
                        <?php
                        echo (int) $usuario['estado'] === 1
                            ? 'selected'
                            : '';
                        ?>>

                        Activo

                    </option>

                    <option
                        value="0"
                        <?php
                        echo (int) $usuario['estado'] === 0
                            ? 'selected'
                            : '';
                        ?>>

                        Inactivo

                    </option>

                </select>

            </div>


            <!-- Nueva contraseña -->
            <div class="fila-campos-usuario">

                <div class="campo-usuario">

                    <label for="password">
                        Nueva contraseña
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        minlength="8"
                        maxlength="100"
                        autocomplete="new-password">

                    <small>
                        Déjala vacía para conservar la contraseña actual.
                    </small>

                </div>


                <div class="campo-usuario">

                    <label for="confirmar_password">
                        Confirmar nueva contraseña
                    </label>

                    <input
                        type="password"
                        id="confirmar_password"
                        name="confirmar_password"
                        minlength="8"
                        maxlength="100"
                        autocomplete="new-password">

                </div>

            </div>


            <!-- Botones -->
            <div class="acciones-formulario-usuario">

                <button
                    type="submit"
                    class="btn-guardar-usuario">

                    Guardar cambios

                </button>

                <a
                    href="usuarios.php"
                    class="btn-cancelar-usuario">

                    Cancelar

                </a>

            </div>

        </form>

    </section>

</main>

<?php include '../includes/footer.php'; ?>