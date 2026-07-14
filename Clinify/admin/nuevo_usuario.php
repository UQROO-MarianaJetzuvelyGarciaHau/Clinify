<?php

require_once 'includes/autenticacion.php';
requerirAdministrador();

include 'includes/header.php';
include 'includes/sidebar.php';
include '../config/conexion.php';

/* Obtener los roles disponibles para llenar el select */
$roles = mysqli_query(
    $conexion,
    "SELECT id, nombre FROM roles ORDER BY id ASC"
);

?>

<main class="contenido-admin">

    <header class="cabecera-admin">

        <span class="texto-secundario">Perfiles</span>

        <h1>Crear perfil</h1>

        <p>
            Registra una cuenta de Administrador o Asistente.
        </p>

    </header>

    <section class="formulario-admin-contenedor">

        <form
            action="actions/guardar_usuario.php"
            method="POST"
            class="formulario-admin">

            <?php if (isset($_GET['error'])) { ?>

                <div class="mensaje-admin error">

                    <?php
                    switch ($_GET['error']) {
                        case 'correo':
                            echo "El correo ya se encuentra registrado.";
                            break;

                        case 'password':
                            echo "La contraseña debe tener mínimo 8 caracteres.";
                            break;

                        case 'confirmacion':
                            echo "Las contraseñas no coinciden.";
                            break;

                        default:
                            echo "Revisa la información ingresada.";
                    }
                    ?>

                </div>

            <?php } ?>

            <div class="campo-admin">

                <label for="nombre">Nombre completo</label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    minlength="3"
                    maxlength="100"
                    pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ ]+"
                    title="Utiliza únicamente letras y espacios."
                    required>

            </div>

            <div class="campo-admin">

                <label for="correo">Correo electrónico</label>

                <input
                    type="email"
                    id="correo"
                    name="correo"
                    maxlength="120"
                    placeholder="usuario@clinify.com"
                    required>

            </div>

            <div class="campo-admin">

                <label for="id_rol">Rol</label>

                <select id="id_rol" name="id_rol" required>

                    <option value="">Selecciona un rol</option>

                    <?php while ($rol = mysqli_fetch_assoc($roles)) { ?>

                        <option value="<?php echo $rol['id']; ?>">
                            <?php echo htmlspecialchars($rol['nombre']); ?>
                        </option>

                    <?php } ?>

                </select>

            </div>

            <div class="campo-admin">

                <label for="password">Contraseña</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    minlength="8"
                    maxlength="100"
                    required>

                <small>
                    Debe contener al menos 8 caracteres.
                </small>

            </div>

            <div class="campo-admin">

                <label for="confirmar_password">
                    Confirmar contraseña
                </label>

                <input
                    type="password"
                    id="confirmar_password"
                    name="confirmar_password"
                    minlength="8"
                    maxlength="100"
                    required>

            </div>

            <div class="acciones-formulario">

                <button type="submit" class="btn-admin">
                    Guardar perfil
                </button>

                <a href="usuarios.php" class="btn-secundario-admin">
                    Cancelar
                </a>

            </div>

        </form>

    </section>

</main>

<?php include 'includes/footer.php'; ?>