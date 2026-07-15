<?php

/* Verificar sesión y permisos */
require_once '../includes/autenticacion.php';
requerirGestorContenido();

/* Conectar con la base de datos */
require_once '../../config/conexion.php';

/* Recuperar el ID enviado en la URL */
$id = (int) ($_GET['id'] ?? 0);

/* Comprobar que el ID sea válido */
if ($id <= 0) {
    header(
        "Location: caracteristicas.php?mensaje=error"
    );
    exit();
}

/*
Buscar la característica que se desea editar.
*/
$consulta = "
    SELECT
        id,
        titulo,
        descripcion,
        estado
    FROM caracteristicas
    WHERE id = ?
    LIMIT 1
";

$stmt = mysqli_prepare(
    $conexion,
    $consulta
);

if (!$stmt) {
    header(
        "Location: caracteristicas.php?mensaje=error"
    );
    exit();
}

/* Asociar el ID al marcador ? */
mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

/* Ejecutar la consulta */
mysqli_stmt_execute($stmt);

/* Recuperar el registro */
$resultado = mysqli_stmt_get_result($stmt);
$caracteristica = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

/* Regresar si no existe el registro */
if (!$caracteristica) {
    header(
        "Location: caracteristicas.php?mensaje=error"
    );
    exit();
}

/* Cargar la estructura visual */
include 'header_caracteristicas.php';
include '../includes/sidebar.php';

?>

<main class="contenido-admin">

    <!-- Encabezado -->
    <header class="encabezado-formulario-caracteristica">

        <span class="texto-secundario">
            Características
        </span>

        <h1>
            Editar característica
        </h1>

        <p>
            Modifica la información o el estado de la característica.
        </p>

    </header>


    <!-- Formulario -->
    <section class="contenedor-formulario-caracteristica">

        <form
            action="../actions/caracteristicas/actualizar_caracteristica.php"
            method="POST"
            class="formulario-caracteristica">

            <!-- ID oculto del registro -->
            <input
                type="hidden"
                name="id"
                value="<?php echo $caracteristica['id']; ?>">

            <!-- Mensaje de error -->
            <?php if (isset($_GET['error'])) { ?>

                <div class="mensaje-caracteristica mensaje-error">

                    <?php

                    switch ($_GET['error']) {

                        case 'titulo':
                            echo "El título debe tener entre 3 y 100 caracteres.";
                            break;

                        case 'descripcion':
                            echo "La descripción debe tener entre 10 y 500 caracteres.";
                            break;

                        case 'estado':
                            echo "Selecciona un estado válido.";
                            break;

                        default:
                            echo "Revisa los datos ingresados.";
                            break;
                    }

                    ?>

                </div>

            <?php } ?>


            <!-- Título -->
            <div class="campo-caracteristica">

                <label for="titulo">
                    Título
                </label>

                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    value="<?php
                        echo htmlspecialchars(
                            $caracteristica['titulo']
                        );
                    ?>"
                    minlength="3"
                    maxlength="100"
                    required>

            </div>


            <!-- Descripción -->
            <div class="campo-caracteristica">

                <label for="descripcion">
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="6"
                    minlength="10"
                    maxlength="500"
                    required><?php
                        echo htmlspecialchars(
                            $caracteristica['descripcion']
                        );
                    ?></textarea>

                <small>
                    Mínimo 10 y máximo 500 caracteres.
                </small>

            </div>


            <!-- Estado -->
            <div class="campo-caracteristica">

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
                        echo (int) $caracteristica['estado'] === 1
                            ? 'selected'
                            : '';
                        ?>>

                        Activa

                    </option>

                    <option
                        value="0"
                        <?php
                        echo (int) $caracteristica['estado'] === 0
                            ? 'selected'
                            : '';
                        ?>>

                        Desactivada

                    </option>

                </select>

            </div>


            <!-- Botones -->
            <div class="acciones-formulario-caracteristica">

                <button
                    type="submit"
                    class="btn-guardar-caracteristica">

                    Guardar cambios

                </button>

                <a
                    href="caracteristicas.php"
                    class="btn-cancelar-caracteristica">

                    Cancelar

                </a>

            </div>

        </form>

    </section>

</main>

<?php include '../includes/footer.php'; ?>