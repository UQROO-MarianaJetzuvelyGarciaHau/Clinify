<?php

$meta_description = 'Conoce a nuestro equipo y descubre la misión, visión y valores detrás de Clinify.';

include 'includes/header.php';
include 'includes/navbar.php';
include 'config/conexion.php';

/* Obtener los integrantes del equipo */
$consulta = "SELECT * FROM equipo ORDER BY id ASC";

$resultado = mysqli_query($conexion, $consulta);

?>

<main>

    <section class="hero hero-simple">

        <div class="contenedor">

            <span class="etiqueta">
                Sobre nosotros
            </span>

            <h1>
                Conoce al equipo detrás de Clinify
            </h1>

            <p>
                Somos estudiantes de Ingeniería en Redes desarrollando una propuesta
                web para promocionar la plataforma Clinify.
            </p>

        </div>

    </section>

    <!--MISIÓN - VISIÓN - VALORES-->

    <section class="seccion">

        <div class="contenedor nosotros-grid">

            <article class="card">

                <h2>Misión</h2>

                <p>
                    Dar a conocer Clinify mediante un sitio web moderno,
                    intuitivo y accesible para cualquier usuario.
                </p>

            </article>

            <article class="card">

                <h2>Visión</h2>

                <p>
                    Ser una plataforma reconocida por facilitar la organización
                    de estudios médicos personales.
                </p>

            </article>

            <article class="card">

                <h2>Valores</h2>

                <p>
                    Innovación, organización, confianza y accesibilidad.
                </p>

            </article>

        </div>

    </section>

    <!--EQUIPO-->

    <section class="seccion">

        <div class="contenedor">

            <div class="titulo-seccion">

                <span>Equipo</span>

                <h2>
                    Integrantes del proyecto
                </h2>

            </div>

            <div class="grid-3"> <!-- mediante el while se van a ir generando los integrantes del equipo -->
    
                <?php while($integrante = mysqli_fetch_assoc($resultado)){ ?> 

                    <article class="card miembro">

                        <img
                            src="assets/img/<?php echo $integrante['id']; ?>.webp"
                            alt="<?php echo $integrante['nombre']; ?>"
                            loading="lazy"
                            decoding="async">

                        <h2>

                            <?php echo $integrante['nombre']; ?>

                        </h2>

                        <p class="rol-miembro">

                            <?php echo $integrante['rol']; ?>

                        </p>

                        <p class="descripcion-miembro">

                            <?php echo $integrante['descripcion']; ?>

                        </p>

                    </article>

                <?php } ?>

            </div>

        </div>

    </section>

</main>

<?php include 'includes/footer.php'; ?>