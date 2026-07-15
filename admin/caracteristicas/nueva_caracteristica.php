<?php

/*
Cargar las funciones de autenticación.

Este módulo puede ser utilizado por:
- Administrador
- Asistente
*/
require_once '../includes/autenticacion.php';
requerirGestorContenido();

/* Cargar la estructura visual del módulo */
include 'header_caracteristicas.php';
include '../includes/sidebar.php';

?>

<main class="contenido-admin">

    <!-- Encabezado del formulario -->
    <header class="encabezado-formulario-caracteristica">

        <span class="texto-secundario">
            Características
        </span>

        <h1>
            Agregar característica
        </h1>

        <p>
            Completa la información para registrar una nueva
            característica de Clinify.
        </p>

    </header>


    <!-- Contenedor del formulario -->
    <section class="contenedor-formulario-caracteristica">

        <form
            action="../actions/caracteristicas/guardar_caracteristica.php"
            method="POST"
            class="formulario-caracteristica">

            <!-- Mensaje cuando ocurre un error -->
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


            <!-- Campo título -->
            <div class="campo-caracteristica">

                <label for="titulo">
                    Título
                </label>

                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    minlength="3"
                    maxlength="100"
                    placeholder="Ejemplo: Acceso rápido"
                    required>

                <small>
                    Escribe un título breve y fácil de entender.
                </small>

            </div>


            <!-- Campo descripción -->
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
                    placeholder="Describe la función o ventaja de esta característica."
                    required></textarea>

                <small>
                    Mínimo 10 y máximo 500 caracteres.
                </small>

            </div>


            <!-- Campo estado -->
            <div class="campo-caracteristica">

                <label for="estado">
                    Estado
                </label>

                <select
                    id="estado"
                    name="estado"
                    required>

                    <option value="1">
                        Activa
                    </option>

                    <option value="0">
                        Desactivada
                    </option>

                </select>

                <small>
                    Las características desactivadas no aparecen
                    en el sitio público.
                </small>

            </div>


            <!-- Botones -->
            <div class="acciones-formulario-caracteristica">

                <button
                    type="submit"
                    class="btn-guardar-caracteristica">

                    Guardar característica

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