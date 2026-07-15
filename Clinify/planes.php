<?php

/* Componentes generales del sitio */
include 'includes/header.php';
include 'includes/navbar.php';

/* Conexión con MySQL */
include 'config/conexion.php';

/*
Obtener el plan con más registros de interés.

Solo se consideran planes visibles:
- Activo
- Proximamente

HAVING COUNT(contactos.id) > 0 evita mostrar
la etiqueta cuando todavía no existe ningún interés.
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
    $planMasVendido = mysqli_fetch_assoc(
        $resultadoMasVendido
    );

    $planMasVendidoId = (int) $planMasVendido['id'];
}

/*
Mostrar únicamente planes activos o próximos.

Los planes desactivados solamente serán visibles
desde el panel administrativo.
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

    <!-- Encabezado de la página -->
    <section class="hero hero-simple">

        <div class="contenedor">

            <span class="etiqueta">
                Planes Clinify
            </span>

            <h1>
                Elige el plan que mejor se adapte a ti
            </h1>

            <p>
                Conoce nuestras opciones y selecciona el plan
                que se adapte a tus necesidades de organización médica.
            </p>

        </div>

    </section>

    <!-- Planes obtenidos desde MySQL -->
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
                        La tarjeta recibe una clase especial cuando
                        el estado sea Proximamente.
                        */
                        $esProximamente =
                            $plan['estado'] === 'Proximamente';

                        /*
                        Comprobamos si este es el plan con más
                        solicitudes registradas.
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

                            <!-- Etiqueta calculada desde la BD -->
                            <?php if ($esMasVendido) { ?>

                                <span class="badge">
                                    Más vendido
                                </span>

                            <?php } ?>

                            <!-- Estado próximo -->
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

                                <!--
                                Enviamos el ID por GET para que el
                                formulario lo seleccione automáticamente.
                                -->
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