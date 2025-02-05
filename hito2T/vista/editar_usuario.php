<?php
// Importamos el controlador para poder gestionar los usuarios
require_once '../controlador/UsuariosController.php';
$controller = new UsuariosController();

// Obtenemos el ID del usuario desde la URL para cargar sus datos
$id_usuario = $_GET['id'];
$usuario = $controller->obtenerUsuarioPorId($id_usuario);

// Variables para mostrar mensajes de error o éxito
$error_message = '';
$success_message = '';

// Comprobamos si el formulario se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recogemos los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $edad = $_POST['edad'];
    $planBase = $_POST['planBase'];
    $paquetesAdicionales = isset($_POST['paquetesAdicionales']) ? $_POST['paquetesAdicionales'] : [];
    $duracionSuscripcion = $_POST['duracionSuscripcion'];

    // Restricción 1: Si el usuario es menor de 18, solo puede contratar el Pack Infantil
    if ($edad < 18 && !in_array("Infantil", $paquetesAdicionales)) {
        $error_message = "Los usuarios menores de 18 años solo pueden contratar el Pack Infantil.";
    } 
    // Restricción 2: Si tiene el Plan Básico, solo puede añadir 1 paquete adicional
    elseif ($planBase == 'Basico' && count($paquetesAdicionales) > 1) {
        $error_message = "Los usuarios del Plan Básico solo pueden seleccionar un paquete adicional.";
    } 
    // Restricción 3: El Pack Deporte solo se permite con una suscripción anual
    elseif (in_array("Deporte", $paquetesAdicionales) && $duracionSuscripcion != "Anual") {
        $error_message = "El Pack Deporte solo puede ser contratado si la duración de la suscripción es anual.";
    } 
    // Si pasa todas las restricciones, actualizamos la suscripción
    else {
        $controller->actualizarUsuario($id_usuario, $nombre, $apellido, $email, $edad, $planBase, $paquetesAdicionales, $duracionSuscripcion);
        $success_message = "Suscripción actualizada con éxito.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Suscripción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
        }
        .container {
            max-width: 600px;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 15px rgba(255, 255, 255, 0.1);
        }
        input, select {
            background-color: #333;
            color: #fff;
            border: none;
        }
        input:focus, select:focus {
            background-color: #444;
        }
        .btn-primary {
            background-color: #6200ea;
            border: none;
        }
        .btn-secondary {
            background-color: #bb86fc;
        }
        .alert {
            background-color: #cf6679;
            color: white;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Editar Suscripción</h1>

        <!-- Mensajes de error o éxito -->
        <?php if ($error_message): ?>
            <div class="alert alert-danger"> <?= $error_message; ?> </div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="alert alert-success"> <?= $success_message; ?> </div>
        <?php endif; ?>

        <!-- Formulario de edición -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $usuario['nombre'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido:</label>
                <input type="text" class="form-control" id="apellido" name="apellido" value="<?= $usuario['apellidos'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $usuario['correo'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="edad" class="form-label">Edad:</label>
                <input type="number" class="form-control" id="edad" name="edad" value="<?= $usuario['edad'] ?>" min="0" required>
            </div>

            <div class="mb-3">
                <label for="planBase" class="form-label">Plan Base:</label>
                <select class="form-control" id="planBase" name="planBase" required>
                    <option value="Basico" <?= $usuario['plan_base'] == 'Basico' ? 'selected' : '' ?>>Básico</option>
                    <option value="Estandar" <?= $usuario['plan_base'] == 'Estandar' ? 'selected' : '' ?>>Estándar</option>
                    <option value="Premium" <?= $usuario['plan_base'] == 'Premium' ? 'selected' : '' ?>>Premium</option>
                </select>
            </div>

            <!-- Selección de paquetes adicionales -->
            <div class="mb-3">
                <label class="form-label">Paquetes Adicionales:</label><br>
                <input type="checkbox" id="deporte" name="paquetesAdicionales[]" value="Deporte" <?= in_array("Deporte", $usuario['paquetes']) ? 'checked' : '' ?>>
                <label for="deporte">Deporte</label><br>
                <input type="checkbox" id="cine" name="paquetesAdicionales[]" value="Cine" <?= in_array("Cine", $usuario['paquetes']) ? 'checked' : '' ?>>
                <label for="cine">Cine</label><br>
                <input type="checkbox" id="infantil" name="paquetesAdicionales[]" value="Infantil" <?= in_array("Infantil", $usuario['paquetes']) ? 'checked' : '' ?>>
                <label for="infantil">Infantil</label>
            </div>

            <!-- Selección de duración de la suscripción -->
            <div class="mb-3">
                <label for="duracionSuscripcion" class="form-label">Duración de la Suscripción:</label>
                <select class="form-control" id="duracionSuscripcion" name="duracionSuscripcion" required>
                    <option value="Mensual" <?= $usuario['duracion'] == 'Mensual' ? 'selected' : '' ?>>Mensual</option>
                    <option value="Anual" <?= $usuario['duracion'] == 'Anual' ? 'selected' : '' ?>>Anual</option>
                </select>
            </div>

            <!-- Botón para actualizar la suscripción -->
            <button type="submit" class="btn btn-primary w-100">Actualizar Suscripción</button>
        </form>

        <!-- Botón para volver a la lista de usuarios -->
        <a href="lista_usuarios.php" class="btn btn-secondary w-100 mt-3">Volver a la Lista</a>
    </div>
    <div class="restrictions"class="mb-3">
            <h3>Restricciones:</h3>
            <ul>
                <li>Los usuarios menores de 18 años solo pueden contratar el Pack Infantil.</li>
                <li>Los usuarios del Plan Básico solo pueden seleccionar un paquete adicional.</li>
                <li>El Pack Deporte solo puede ser contratado si la duración de la suscripción es de 1 año.</li>
            </ul>
        </div>
</body>
</html>
            