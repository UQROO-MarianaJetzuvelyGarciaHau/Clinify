<?php

/*
|--------------------------------------------------------------------------
| SEGURIDAD
|--------------------------------------------------------------------------
| El módulo Usuarios es exclusivo del Administrador.
*/

require_once '../includes/autenticacion.php';
requerirAdministrador();


/*
|--------------------------------------------------------------------------
| CONEXIÓN
|--------------------------------------------------------------------------
| Necesitamos consultar los roles disponibles.
*/

require_once '../../config/conexion.php';


/*
|--------------------------------------------------------------------------
| CONSULTAR ROLES
|--------------------------------------------------------------------------
| Recuperamos los roles para mostrarlos en el select.
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


/* Cargar la estructura visual */
include 'header_usuario.php';
include '../includes/sidebar.php';

?>

<main class="contenido-admin">

    <!-- Encabezado del formulario -->
    <header class="encabezado-formulario-usuario">

        <span class="texto-secundario">
            Usuarios
        </span>

        <h1>
            Agregar usuario
        </h1>

        <p>
            Crea una cuenta de Administrador o Asistente
            con acceso al panel de Clinify.
        </p>

    </header>


    <!-- Formulario -->
    <section class="contenedor-formulario-usuario">

        <form
            action="../actions/usuario/guardar_usuario.php"
            method="POST"
            class="formulario-usuario">

            <!-- Mensajes de error -->
            <?php if (isset($_GET['error'])) { ?>

                <div class="mensaje-usuario mensaje-error">

                    <?php

                    switch ($_GET['error']) {

                        case 'nombre':
                            echo "El nombre debe tener entre 3 y 100 caracteres.";
                            break;

                        case 'correo':
                            echo "Ingresa un correo electrónico válido.";
                            break;

                        case 'correo_existente':
                            echo "El correo ya está registrado.";
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

                        default:
                            echo "Revisa los datos ingresados.";
                            break;
                    }

                    ?>

                </div>

            <?php } ?>


            <!-- Nombre completo -->
            <div class="campo-usuario">

                <label for="nombre">
                    Nombre completo
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    minlength="3"
                    maxlength="100"
                    pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                    title="Escribe únicamente letras y espacios."
                    placeholder="Ejemplo: Mariana García"
                    autocomplete="name"
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
                        maxlength="120"
                        placeholder="usuario@clinify.com"
                        autocomplete="email"
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

                        <option value="">
                            Selecciona un rol
                        </option>

                        <?php while (
                            $rol = mysqli_fetch_assoc($resultadoRoles)
                        ) { ?>

                            <option value="<?php echo $rol['id']; ?>">

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


            <!-- Contraseña y confirmación -->
            <div class="fila-campos-usuario">

                <div class="campo-usuario">

                    <label for="password">
                        Contraseña
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        minlength="8"
                        maxlength="100"
                        placeholder="Mínimo 8 caracteres"
                        autocomplete="new-password"
                        required>

                    <small>
                        Debe incluir una mayúscula, una minúscula
                        y un número.
                    </small>

                </div>


                <div class="campo-usuario">

                    <label for="confirmar_password">
                        Confirmar contraseña
                    </label>

                    <input
                        type="password"
                        id="confirmar_password"
                        name="confirmar_password"
                        minlength="8"
                        maxlength="100"
                        placeholder="Repite la contraseña"
                        autocomplete="new-password"
                        required>

                </div>

            </div>


            <!-- Estado inicial -->
            <div class="campo-usuario">

                <label for="estado">
                    Estado
                </label>

                <select
                    id="estado"
                    name="estado"
                    required>

                    <option value="1">
                        Activo
                    </option>

                    <option value="0">
                        Inactivo
                    </option>

                </select>

                <small>
                    Los usuarios inactivos no podrán iniciar sesión.
                </small>

            </div>


            <!-- Botones -->
            <div class="acciones-formulario-usuario">

                <button
                    type="submit"
                    class="btn-guardar-usuario">

                    Guardar usuario

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