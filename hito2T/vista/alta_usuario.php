<?php

require_once '../controlador/UsuariosController.php';  // Importamos el controlador de usuarios.
$controller2 = new UsuariosController();

$error_message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturamos los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $edad = $_POST['edad'];
    $planBase = $_POST['planBase'];
    $paquetesAdicionales = isset($_POST['paquetesAdicionales']) ? $_POST['paquetesAdicionales'] : [];
    $duracionSuscripcion = $_POST['duracionSuscripcion'];

    // Validaciones según las restricciones del negocio
    if ($edad < 18 && !in_array("Infantil", $paquetesAdicionales)) {
        $error_message = "Los usuarios menores de 18 años solo pueden contratar el Pack Infantil.";
    } elseif ($planBase == 'Basico' && count($paquetesAdicionales) > 1) {
        $error_message = "Los usuarios del Plan Básico solo pueden seleccionar un paquete adicional.";
    } elseif (in_array("Deporte", $paquetesAdicionales) && $duracionSuscripcion != "Anual") {
        $error_message = "El Pack Deporte solo puede ser contratado si la duración de la suscripción es anual.";
    } else {
        // Calculamos el precio según el plan seleccionado
        $precioPlan = 0;
        switch ($planBase) {
            case 'Basico':
                $precioPlan = 9.99;
                break;
            case 'Estandar':
                $precioPlan = 13.99;
                break;
            case 'Premium':
                $precioPlan = 17.99;
                break;
        }

        // Calculamos el costo de los paquetes adicionales seleccionados
        $precioPaquetes = 0;
        foreach ($paquetesAdicionales as $paquete) {
            switch ($paquete) {
                case 'Deporte':
                    $precioPaquetes += 6.99;
                    break;
                case 'Cine':
                    $precioPaquetes += 7.99;
                    break;
                case 'Infantil':
                    $precioPaquetes += 4.99;
                    break;
            }
        }

        // Agregamos la suscripción a través del controlador
        $suscripcion = $controller2->agregarSuscripcion($nombre, $apellido, $email, $edad, $planBase, $paquetesAdicionales, $duracionSuscripcion, $precioPlan, $precioPaquetes);
        
        if (!$suscripcion) {
            $error_message = "Error al agregar suscripción. Por favor, verifica los datos.";
        } else {
            $success_message = "Suscripción agregada con éxito.";
            header("Location: ../vista/lista_usuarios.php"); // Redirige a la lista de usuarios
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Suscripción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1c1c1e;
            color: #f8f9fa;
        }
        .card {
            background-color: #2c2c2e;
            color: #f8f9fa;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .form-control, .form-select {
            background-color: #3a3a3c;
            color: #f8f9fa;
            border: 1px solid #6c757d;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h1 class="text-center text-primary mb-4">Formulario de Suscripción</h1>
            
            <!-- Mensajes de error o éxito -->
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Formulario de suscripción -->
            <form method="POST" action="" class="row g-3">
                <div class="col-md-6">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="col-md-6">
                    <label for="apellido" class="form-label">Apellido:</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Correo electrónico:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="col-md-6">
                    <label for="edad" class="form-label">Edad:</label>
                    <input type="number" class="form-control" id="edad" name="edad" min="0" required>
                </div>
                <div class="col-md-6">
                    <label for="planBase" class="form-label">Tipo de plan base:</label>
                    <select class="form-select" id="planBase" name="planBase" required>
                        <option value="Basico">Básico</option>
                        <option value="Estandar">Estándar</option>
                        <option value="Premium">Premium</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="duracionSuscripcion" class="form-label">Duración de la suscripción:</label>
                    <select class="form-select" id="duracionSuscripcion" name="duracionSuscripcion" required>
                        <option value="Mensual">Mensual</option>
                        <option value="Anual">Anual</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Paquetes adicionales:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="deporte" name="paquetesAdicionales[]" value="Deporte">
                        <label class="form-check-label" for="deporte">Deporte</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="cine" name="paquetesAdicionales[]" value="Cine">
                        <label class="form-check-label" for="cine">Cine</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="infantil" name="paquetesAdicionales[]" value="Infantil">
                        <label class="form-check-label" for="infantil">Infantil</label>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Registrar Suscripción</button>
                </div>
            </form>
            
            <div class="mt-4">
                <h5>Restricciones:</h5>
                <ul>
                    <li>Los usuarios menores de 18 años solo pueden contratar el Pack Infantil.</li>
                    <li>Los usuarios del Plan Básico solo pueden seleccionar un paquete adicional.</li>
                    <li>El Pack Deporte solo puede ser contratado si la duración de la suscripción es anual.</li>
                </ul>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>