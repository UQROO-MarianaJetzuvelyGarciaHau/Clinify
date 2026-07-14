<?php

/*
Cargar autenticación y permisos.
*/
require_once '../includes/autenticacion.php';

/* Solo Administradores pueden editar el equipo */
requerirAdministrador();

/* Conectar con MySQL */
require_once '../../config/conexion.php';

/* Recuperar el ID enviado en la URL */
$id = (int) ($_GET['id'] ?? 0);

/* Validar que el ID sea correcto */
if ($id <= 0) {
    header("Location: equipo.php?mensaje=error");
    exit();
}

/*
Buscar el integrante que se desea editar.
*/
$consulta = "
    SELECT
        id,
        nombre,
        rol,
        descripcion
    FROM equipo
    WHERE id = ?
    LIMIT 1
";

$stmt = mysqli_prepare(
    $conexion,
    $consulta
);

if (!$stmt) {
    header("Location: equipo.php?mensaje=error");
    exit();
}

/* Asociar el ID al signo ? */
mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

/* Ejecutar la consulta */
mysqli_stmt_execute($stmt);

/* Recuperar el registro */
$resultado = mysqli_stmt_get_result($stmt);
$integrante = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

/* Regresar si no se encontró el integrante */
if (!$integrante) {
    header("Location: equipo.php?mensaje=error");
    exit();
}

/* Cargar la interfaz */
include 'header_equipo.php';
include '../includes/sidebar.php';

?>

<main class="contenido-admin">

    <!-- Encabezado -->
    <header class="encabezado-formulario-equipo">

        <span class="texto-secundario">
            Equipo
        </span>

        <h1>
            Editar integrante
        </h1>

        <p>
            Modifica el nombre, rol o descripción del integrante.
        </p>

    </header>


    <!-- Formulario -->
    <section class="contenedor-formulario-equipo">

        <form
            action="../actions/equipo/actualizar_integrante.php"
            method="POST"
            class="formulario-equipo">

            <!-- ID oculto del integrante -->
            <input
                type="hidden"
                name="id"
                value="<?php echo $integrante['id']; ?>">

            <!-- Mensajes de error -->
            <?php if (isset($_GET['error'])) { ?>

                <div class="mensaje-equipo mensaje-error">

                    <?php

                    switch ($_GET['error']) {

                        case 'nombre':
                            echo "El nombre debe tener entre 3 y 100 caracteres.";
                            break;

                        case 'rol':
                            echo "El rol debe tener entre 3 y 100 caracteres.";
                            break;

                        case 'descripcion':
                            echo "La descripción debe tener entre 10 y 500 caracteres.";
                            break;

                        default:
                            echo "Revisa los datos ingresados.";
                            break;
                    }

                    ?>

                </div>

            <?php } ?>


            <!-- Nombre -->
            <div class="campo-equipo">

                <label for="nombre">
                    Nombre completo
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?php echo htmlspecialchars($integrante['nombre']); ?>"
                    minlength="3"
                    maxlength="100"
                    required>

            </div>


            <!-- Rol -->
            <div class="campo-equipo">

                <label for="rol">
                    Rol dentro del proyecto
                </label>

                <input
                    type="text"
                    id="rol"
                    name="rol"
                    value="<?php echo htmlspecialchars($integrante['rol']); ?>"
                    minlength="3"
                    maxlength="100"
                    placeholder="Ejemplo: Diseño y programación"
                    required>

            </div>


            <!-- Descripción -->
            <div class="campo-equipo">

                <label for="descripcion">
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="6"
                    minlength="10"
                    maxlength="500"
                    required><?php echo htmlspecialchars($integrante['descripcion']); ?></textarea>

                <small>
                    Escribe una descripción breve de sus responsabilidades.
                </small>

            </div>


            <!-- Botones -->
            <div class="acciones-formulario-equipo">

                <button
                    type="submit"
                    class="btn-guardar-equipo">

                    Guardar cambios

                </button>

                <a
                    href="equipo.php"
                    class="btn-cancelar-equipo">

                    Cancelar

                </a>

            </div>

        </form>

    </section>

</main>

<?php include '../includes/footer.php'; ?>