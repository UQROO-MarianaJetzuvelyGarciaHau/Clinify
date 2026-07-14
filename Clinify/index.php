<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<main>

    <!-- Sección principal -->
    <section class="hero">
        <div class="contenedor hero-contenido">
            <div class="hero-texto">
                <span class="etiqueta">Gestión médica personal</span>
                <h1>Organiza tus estudios médicos en un solo lugar</h1>
                <p>
                    Clinify te ayuda a mantener tu información médica ordenada,
                    accesible y fácil de consultar cuando más la necesitas.
                </p>

                <div class="hero-botones">
                    <a href="planes.php" class="btn-principal">Ver planes</a>
                    <a href="contacto.php" class="btn-secundario">Solicitar información</a>
                </div>
            </div>

            <div class="hero-imagen">
                <img src="assets/img/logo-clinify.png" alt="Vista del logo de Clinify">
            </div>
        </div>
    </section>

    <!-- Beneficios principales -->
    <section class="seccion">
        <div class="contenedor">
            <div class="titulo-seccion">
                <span>Beneficios</span>
                <h2>Una forma más simple de cuidar tu información médica</h2>
            </div>

            <div class="grid-3">
                <article class="card">
                    <div class="numero">1</div>
                    <h3>Información organizada</h3>
                    <p>Guarda tus estudios médicos de manera ordenada y fácil de encontrar.</p>
                </article>

                <article class="card">
                    <div class="numero">2</div>
                    <h3>Acceso rápido</h3>
                    <p>Consulta tus documentos importantes desde cualquier dispositivo.</p>
                </article>

                <article class="card">
                    <div class="numero">3</div>
                    <h3>Mayor confianza</h3>
                    <p>Ten a la mano tu historial médico cuando visites a un especialista.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Sobre Clinify -->
    <section class="seccion-info">
        <div class="contenedor info-contenido">
            <div class="info-imagen">
                <img src="assets/img/image-clinify.png" alt="Aplicación de salud Clinify">
            </div>

            <div class="info-texto">
                <span>¿Qué es Clinify?</span>
                <h2>Una plataforma pensada para pacientes y familias</h2>
                <p>
                    Clinify es una propuesta digital para centralizar estudios médicos personales,
                    evitando pérdidas de documentos físicos y facilitando la consulta de información.
                </p>

                <ul>
                    <li>Historial médico más ordenado.</li>
                    <li>Planes adaptados a diferentes necesidades.</li>
                    <li>Interfaz sencilla para usuarios con conocimientos básicos.</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Planes destacados -->
    <section class="seccion">
        <div class="contenedor">
            <div class="titulo-seccion">
                <span>Planes</span>
                <h2>Elige el plan ideal para ti</h2>
            </div>

            <div class="grid-3">
                <article class="card-plan">
                    <h3>Básico</h3>
                    <p class="precio">$99 MXN</p>
                    <p class="text-descripcion">Ideal para usuarios que desean organizar sus estudios personales.</p>
                    <a href="planes.php" class="btn-principal">Ver más</a>
                </article>

                <article class="card-plan destacado">
                    <span class="badge">Más vendido</span>
                    <h3>Familiar</h3>
                    <p class="precio">$199 MXN</p>
                    <p class="text-descripcion">Perfecto para familias que desean mantener varios perfiles organizados.</p>
                    <a href="planes.php" class="btn-principal">Ver más</a>
                </article>

                <article class="card-plan">
                    <h3>Premium</h3>
                    <p class="precio">$299 MXN</p>
                    <p class="text-descripcion">Para usuarios que buscan mayor capacidad y funciones avanzadas.</p>
                    <a href="planes.php" class="btn-principal">Ver más</a>
                </article>
            </div>
        </div>
    </section>

    <!-- Testimonios simulados con JavaScript -->
    <section class="seccion testimonios">
        <div class="contenedor">
            <div class="titulo-seccion">
                <span>Testimonios</span>
                <h2>Lo que opinan nuestros usuarios</h2>
            </div>

            <div class="grid-3" id="contenedorTestimonios">
                <!-- Aquí JavaScript insertará testimonios simulando una API -->
            </div>
        </div>
    </section>

    <!-- Preguntas frecuentes simuladas con JavaScript -->
    <section class="seccion faq">
        <div class="contenedor">
            <div class="titulo-seccion">
                <span>FAQ</span>
                <h2>Preguntas frecuentes</h2>
            </div>

            <div id="contenedorFaq">
                <!-- Aquí JavaScript insertará las preguntas frecuentes -->
            </div>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>