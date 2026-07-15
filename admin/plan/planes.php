<?php

/*
Comprobar que el usuario haya iniciado sesión
y tenga permiso para administrar planes.
*/
require_once '../includes/autenticacion.php';
requerirGestorContenido();

/* Cargar componentes visuales */
include '../includes/header.php';
include '../includes/sidebar.php';

/*
Desde admin/plan debemos subir dos niveles
para llegar a config/conexion.php.
*/
require_once '../../config/conexion.php';


/*
Consultar todos los planes.

La subconsulta cuenta cuántos formularios
de contacto seleccionaron cada plan.
*/
$consultaPlanes = "
    SELECT
        p.id,
        p.nombre,
        p.descripcion,
        p.precio,
        p.periodo,
        p.almacenamiento,
        p.cantidad_estudios,
        p.soporte,
        p.estado,
        (
            SELECT COUNT(*)
            FROM contactos AS c
            WHERE c.plan_interes = p.id
        ) AS total_interesados
    FROM planes AS p
    ORDER BY p.id DESC
";

$resultadoPlanes = mysqli_query(
    $conexion,
    $consultaPlanes
);


/*
Buscar el plan con mayor cantidad
de personas interesadas.
*/
$consultaMasVendido = "
    SELECT
        plan_interes,
        COUNT(*) AS total
    FROM contactos
    WHERE plan_interes IS NOT NULL
    GROUP BY plan_interes
    ORDER BY total DESC, plan_interes ASC
    LIMIT 1
";

$resultadoMasVendido = mysqli_query(
    $conexion,
    $consultaMasVendido
);

/* Inicialmente no existe un plan más vendido */
$planMasVendidoId = null;

if (
    $resultadoMasVendido &&
    mysqli_num_rows($resultadoMasVendido) > 0
) {
    $filaMasVendido = mysqli_fetch_assoc(
        $resultadoMasVendido
    );

    $planMasVendidoId =
        (int) $filaMasVendido['plan_interes'];
}

?>

<main class="contenido-admin">

    <!-- Encabezado del módulo -->
    <header class="cabecera-planes">

        <div>

            <span class="texto-secundario">
                Contenido
            </span>

            <h1>Planes</h1>

            <p>
                Administra los planes, sus estados y consulta
                la cantidad de personas interesadas.
            </p>

        </div>

        <a
            href="nuevo_plan.php"
            class="btn-agregar-plan">

            Agregar plan

        </a>

    </header>


    <!-- Mensajes de resultado -->
    <?php if (isset($_GET['mensaje'])) { ?>

        <?php
        /*
        Guardamos el mensaje recibido en una variable
        para decidir qué texto mostrar.
        */
        $mensaje = $_GET['mensaje'];

        $claseMensaje = $mensaje === 'error'
            ? 'mensaje-error'
            : 'mensaje-exito';
        ?>

        <div class="mensaje-plan <?php echo $claseMensaje; ?>">

            <?php

            switch ($mensaje) {
                case 'creado':
                    echo "El plan fue creado correctamente.";
                    break;

                case 'actualizado':
                    echo "El plan fue actualizado correctamente.";
                    break;

                case 'eliminado':
                    echo "El plan fue eliminado correctamente.";
                    break;

                default:
                    echo "No se pudo completar la operación.";
                    break;
            }

            ?>

        </div>

    <?php } ?>


    <!-- Tabla de planes -->
    <section class="contenedor-tabla-planes">

        <table class="tabla-planes">

            <thead>

                <tr>
                    <th>Plan</th>
                    <th>Precio</th>
                    <th>Periodo</th>
                    <th>Estado</th>
                    <th>Interesados</th>
                    <th>Resultado</th>
                    <th>Acciones</th>
                </tr>

            </thead>

            <tbody>

                <?php if (
                    $resultadoPlanes &&
                    mysqli_num_rows($resultadoPlanes) > 0
                ) { ?>

                    <?php while (
                        $plan = mysqli_fetch_assoc($resultadoPlanes)
                    ) { ?>

                        <?php

                        /*
                        Comprobar si el plan actual es el que
                        tiene más contactos interesados.
                        */
                        $esMasVendido =
                            $planMasVendidoId !== null &&
                            (int) $plan['id'] === $planMasVendidoId;

                        /*
                        Crear la clase CSS correspondiente
                        al estado del plan.
                        */
                        $claseEstado =
                            'estado-' . strtolower($plan['estado']);

                        ?>

                        <tr>

                            <!-- Información principal -->
                            <td>

                                <div class="informacion-plan">

                                    <strong>
                                        <?php
                                        echo htmlspecialchars(
                                            $plan['nombre']
                                        );
                                        ?>
                                    </strong>

                                    <small>
                                        <?php
                                        echo htmlspecialchars(
                                            $plan['descripcion']
                                        );
                                        ?>
                                    </small>

                                </div>

                            </td>

                            <!-- Precio -->
                            <td class="precio-tabla">

                                $<?php
                                echo number_format(
                                    (float) $plan['precio'],
                                    2
                                );
                                ?> MXN

                            </td>

                            <!-- Periodo -->
                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $plan['periodo']
                                );
                                ?>

                            </td>

                            <!-- Estado -->
                            <td>

                                <span class="
                                    etiqueta-estado
                                    <?php echo $claseEstado; ?>
                                ">

                                    <?php
                                    echo htmlspecialchars(
                                        $plan['estado']
                                    );
                                    ?>

                                </span>

                            </td>

                            <!-- Número de interesados -->
                            <td class="celda-centrada">

                                <span class="numero-interesados">

                                    <?php
                                    echo (int) $plan['total_interesados'];
                                    ?>

                                </span>

                            </td>

                            <!-- Más vendido -->
                            <td class="celda-centrada">

                                <?php if ($esMasVendido) { ?>

                                    <span class="etiqueta-mas-vendido">
                                        Más vendido
                                    </span>

                                <?php } else { ?>

                                    <span class="sin-resultado">
                                        —
                                    </span>

                                <?php } ?>

                            </td>

                            <!-- Acciones -->
                            <td>

                                <div class="acciones-plan">

                                    <a
                                        href="editar_plan.php?id=<?php echo $plan['id']; ?>"
                                        class="btn-editar-plan">

                                        Editar

                                    </a>

                                    <form
                                        action="../actions/planes/eliminar_plan.php"
                                        method="POST"
                                        onsubmit="return confirm('¿Deseas eliminar este plan?');">

                                        <!-- ID que se enviará al archivo eliminar -->
                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo $plan['id']; ?>">

                                        <button
                                            type="submit"
                                            class="btn-eliminar-plan">

                                            Eliminar

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td
                            colspan="7"
                            class="tabla-planes-vacia">

                            No existen planes registrados.

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </section>

</main>

<?php include '../includes/footer.php'; ?>