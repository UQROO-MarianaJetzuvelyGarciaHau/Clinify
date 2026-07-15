<?php

$meta_description = 'Explora los planes de Clinify y elige la opción ideal para organizar y consultar tus estudios médicos.';

/* Componentes generales */
include 'includes/header.php';
include 'includes/navbar.php';

/* Conexión con MySQL */
include 'config/conexion.php';

/*
Obtener el plan con mayor cantidad de contactos
interesados.

Solo se consideran planes visibles:
Activo y Proximamente.
*/
$consultaMasVendido = "
    SELECT
        planes.id,
        COUNT(contactos.id) AS total_interesados
    FROM planes
    LEFT JOIN contactos
        ON contactos.plan_interes = planes.id
    WHERE planes.estado IN ('Activo', 'Proximamente')
    GROUP BY planes.id
    HAVING COUNT(contactos.id) > 0
    ORDER BY total_interesados DESC, planes.id ASC
    LIMIT 1
";

$resultadoMasVendido = mysqli_query(
    $conexion,
    $consultaMasVendido
);

$planMasVendidoId = null;

if (
    $resultadoMasVendido &&
    mysqli_num_rows($resultadoMasVendido) > 0
) {
    $filaMasVendido = mysqli_fetch_assoc(
        $resultadoMasVendido
    );

    $planMasVendidoId = (int) $filaMasVendido['id'];
}

/*
Mostrar únicamente los planes que pueden
ser vistos por los visitantes.

Los desactivados solo aparecen en administración.
*/
$consultaPlanes = "
    SELECT *
    FROM planes
    WHERE estado IN ('Activo', 'Proximamente')
    ORDER BY precio ASC
";

$resultadoPlanes = mysqli_query(
    $conexion,
    $consultaPlanes
);

?>

<main>

    <!-- Encabezado -->
    <section class="hero hero-simple">

        <div class="contenedor">

            <span class="etiqueta">
                Planes Clinify
            </span>

            <h1>
                Elige el plan que mejor se adapte a ti
            </h1>

            <p>
                Conoce nuestras opciones para organizar tu
                información médica personal.
            </p>

        </div>

    </section>


    <!-- Planes -->
    <section class="seccion">

        <div class="contenedor">

            <div class="grid-planes">

                <?php if (
                    $resultadoPlanes &&
                    mysqli_num_rows($resultadoPlanes) > 0
                ) { ?>

                    <?php while (
                        $plan = mysqli_fetch_assoc($resultadoPlanes)
                    ) { ?>

                        <?php

                        /*
                        Comprobar si el plan todavía no se encuentra
                        disponible.
                        */
                        $esProximamente =
                            $plan['estado'] === 'Proximamente';

                        /*
                        Comprobar si este plan tiene la mayor cantidad
                        de contactos interesados.
                        */
                        $esMasVendido =
                            $planMasVendidoId !== null &&
                            (int) $plan['id'] === $planMasVendidoId;

                        ?>

                        <article class="
                            card-plan
                            <?php echo $esProximamente
                                ? 'plan-proximamente'
                                : ''; ?>
                            <?php echo $esMasVendido
                                ? 'plan-destacado'
                                : ''; ?>
                        ">

                            <?php if ($esMasVendido) { ?>

                                <span class="badge">
                                    Más vendido
                                </span>

                            <?php } ?>

                            <?php if ($esProximamente) { ?>

                                <span class="badge-proximamente">
                                    Próximamente
                                </span>

                            <?php } ?>

                            <h2>
                                <?php
                                echo htmlspecialchars(
                                    $plan['nombre']
                                );
                                ?>
                            </h2>

                            <p class="precio">

                                $<?php
                                echo number_format(
                                    $plan['precio'],
                                    2
                                );
                                ?> MXN

                            </p>

                            <p class="periodo">

                                <?php
                                echo htmlspecialchars(
                                    $plan['periodo']
                                );
                                ?>

                            </p>

                            <p class="descripcion-plan">

                                <?php
                                echo htmlspecialchars(
                                    $plan['descripcion']
                                );
                                ?>

                            </p>

                            <ul class="lista-plan">

                                <li>
                                    <?php
                                    echo htmlspecialchars(
                                        $plan['cantidad_estudios']
                                    );
                                    ?>
                                </li>

                                <li>
                                    <?php
                                    echo htmlspecialchars(
                                        $plan['almacenamiento']
                                    );
                                    ?>
                                </li>

                                <li>
                                    <?php
                                    echo htmlspecialchars(
                                        $plan['soporte']
                                    );
                                    ?>
                                </li>

                            </ul>

                            <?php if (!$esProximamente) { ?>

                                <a
                                    href="contacto.php?plan=<?php echo $plan['id']; ?>"
                                    class="btn-principal">

                                    Me interesa

                                </a>

                            <?php } else { ?>

                                <span class="btn-plan-bloqueado">
                                    Disponible próximamente
                                </span>

                            <?php } ?>

                        </article>

                    <?php } ?>

                <?php } else { ?>

                    <p class="sin-resultados">
                        Actualmente no hay planes disponibles.
                    </p>

                <?php } ?>

            </div>

        </div>

    </section>

</main>

<?php include 'includes/footer.php'; ?>