<?php

require_once 'includes/autenticacion.php';
requerirGestorContenido();

include 'includes/header.php';
include 'includes/sidebar.php';
include '../config/conexion.php';

/* Recuperar todos los planes */
$consulta = "SELECT *
             FROM planes
             ORDER BY id DESC";

$resultado = mysqli_query($conexion, $consulta);

?>

<main class="contenido-admin">

    <header class="cabecera-admin cabecera-flex">

        <div>
            <span class="texto-secundario">Contenido</span>
            <h1>Planes</h1>
            <p>Administra los planes disponibles en Clinify.</p>
        </div>

        <a href="nuevo_plan.php" class="btn-admin">
            Agregar plan
        </a>

    </header>

    <?php if (isset($_GET['mensaje'])) { ?>

        <div class="mensaje-admin <?php echo $_GET['mensaje'] === 'error' ? 'error' : 'exito'; ?>">

            <?php
            switch ($_GET['mensaje']) {
                case 'creado':
                    echo "El plan fue creado correctamente.";
                    break;

                case 'actualizado':
                    echo "El plan fue actualizado correctamente.";
                    break;

                case 'eliminado':
                    echo "El plan fue eliminado correctamente.";
                    break;

                case 'estado':
                    echo "El estado del plan fue modificado.";
                    break;

                default:
                    echo "No se pudo completar la operación.";
            }
            ?>

        </div>

    <?php } ?>

    <section class="tabla-contenedor">

        <table class="tabla-admin">

            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Periodo</th>
                    <th>Estado</th>
                    <th>Destacado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <?php if ($resultado && mysqli_num_rows($resultado) > 0) { ?>

                    <?php while ($plan = mysqli_fetch_assoc($resultado)) { ?>

                        <tr>
                            <td>
                                <?php echo htmlspecialchars($plan['nombre']); ?>
                            </td>

                            <td>
                                $<?php echo number_format($plan['precio'], 2); ?> MXN
                            </td>

                            <td>
                                <?php echo htmlspecialchars($plan['periodo']); ?>
                            </td>

                            <td>
                                <span class="estado-admin <?php echo $plan['estado'] == 1 ? 'activo' : 'inactivo'; ?>">
                                    <?php echo $plan['estado'] == 1 ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </td>

                            <td>
                                <?php echo $plan['es_mas_vendido'] == 1 ? 'Sí' : 'No'; ?>
                            </td>

                            <td>
                                <div class="acciones-tabla">

                                    <a
                                        href="editar_plan.php?id=<?php echo $plan['id']; ?>"
                                        class="btn-tabla editar">
                                        Editar
                                    </a>

                                    <form
                                        action="actions/cambiar_estado_plan.php"
                                        method="POST">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo $plan['id']; ?>">

                                        <button
                                            type="submit"
                                            class="btn-tabla estado">

                                            <?php echo $plan['estado'] == 1
                                                ? 'Desactivar'
                                                : 'Activar'; ?>

                                        </button>

                                    </form>

                                    <form
                                        action="actions/eliminar_plan.php"
                                        method="POST"
                                        onsubmit="return confirm('¿Eliminar este plan?');">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo $plan['id']; ?>">

                                        <button
                                            type="submit"
                                            class="btn-tabla eliminar">
                                            Eliminar
                                        </button>

                                    </form>

                                </div>
                            </td>
                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="6">No existen planes registrados.</td>
                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </section>

</main>

<?php include 'includes/footer.php'; ?>