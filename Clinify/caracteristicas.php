<?php
include 'includes/header.php';
include 'includes/navbar.php';
include 'config/conexion.php';

/* Consultamos únicamente las características activas */
$consulta = "SELECT * FROM caracteristicas WHERE estado = 1 ORDER BY id ASC";
$resultado = mysqli_query($conexion, $consulta);

/* Guardamos los datos en un arreglo para usarlos en el diseño */
$caracteristicas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
?>

<main>

    <section class="hero hero-simple">
        <div class="contenedor">
            <span class="etiqueta">Características</span>
            <h1>Funciones pensadas para organizar tu información médica</h1>
            <p>
                Clinify presenta herramientas que ayudan a consultar, ordenar y centralizar
                estudios médicos personales de forma sencilla.
            </p>
        </div>
    </section>

    <section class="seccion">
        <div class="contenedor caracteristicas-grid">

            <article class="card caracteristica-card">
                <img 
                    src="https://cdn-icons-png.flaticon.com/512/2991/2991112.png" 
                    alt="Organización de estudios">

                <h3><?php echo $caracteristicas[0]['titulo']; ?></h3> <!-- Mostramos el título de la primera característica -->
                <p><?php echo $caracteristicas[0]['descripcion']; ?></p>
            </article>

            <article class="card caracteristica-card">
                <img 
                    src="https://cdn-icons-png.flaticon.com/512/1828/1828911.png" 
                    alt="Acceso rápido">

                <h3><?php echo $caracteristicas[1]['titulo']; ?></h3>
                <p><?php echo $caracteristicas[1]['descripcion']; ?></p>
            </article>

            <article class="card caracteristica-card">
                <img 
                    src="https://cdn-icons-png.flaticon.com/512/3774/3774299.png" 
                    alt="Historial médico">

                <h3><?php echo $caracteristicas[2]['titulo']; ?></h3>
                <p><?php echo $caracteristicas[2]['descripcion']; ?></p>
            </article>

            <article class="card caracteristica-card">
                <img 
                    src="https://cdn-icons-png.flaticon.com/512/3064/3064197.png" 
                    alt="Seguridad médica">

                <h3><?php echo $caracteristicas[3]['titulo']; ?></h3>
                <p><?php echo $caracteristicas[3]['descripcion']; ?></p>
            </article>
        </div>
    </section>

    <section class="seccion-info">
        <div class="contenedor info-contenido">
            <div class="info-texto">
                <span>Ventajas</span>
                <h2>Una experiencia simple para usuarios con conocimientos básicos</h2>
                <p>
                    Clinify busca presentar una solución clara, accesible y fácil de usar
                    para personas interesadas en organizar su información médica.
                </p>

                <ul>
                    <li>Diseño limpio y fácil de navegar.</li>
                    <li>Información clara sobre la plataforma.</li>
                    <li>Contenido dinámico desde base de datos.</li>
                </ul>
            </div>

            <div class="info-imagen">
                <img 
                    src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&w=900&q=80" 
                    alt="Tecnología médica">
            </div>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>