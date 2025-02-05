<?php
// Importamos el archivo del controlador para poder usar sus funciones
require_once '../controlador/UsuariosController.php';
$controller = new UsuariosController(); // Creamos una instancia del controlador

// Comprobamos si nos han pasado un ID por la URL
if (isset($_GET['id'])) {
    $id_usuario = $_GET['id']; // Guardamos el ID que nos llega por GET
    $usuario = $controller->obtenerUsuarioPorId($id_usuario); // Buscamos el usuario en la base de datos

    // Si no encontramos al usuario, mostramos un mensaje y detenemos la ejecución
    if (!$usuario) {
        echo "Usuario no encontrado.";
        exit;
    }

    // Inicializamos la variable donde guardaremos el precio del plan base
    $precioPlan = 0;

    // Dependiendo del tipo de plan base que tenga el usuario, le asignamos un precio
    switch ($usuario['plan_base']) {
        case 'Básico':
            $precioPlan = 9.99;
            break;
        case 'Estándar':
            $precioPlan = 13.99;
            break;
        case 'Premium':
            $precioPlan = 17.99;
            break;
    }

    // Ahora calculamos el precio de los paquetes adicionales que tenga el usuario
    $precioPaquetes = 0;
    foreach ($usuario['paquetes'] as $paquete) {
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

    // Sumamos el precio del plan base y el de los paquetes adicionales para obtener el costo total mensual
    $costoTotalMensual = $precioPlan + $precioPaquetes;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Costo Total de la Suscripción</title>
    
    <!-- Enlazamos Bootstrap para darle mejor diseño -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1c1c1e;
            color: #f8f9fa;
        }
        .container {
            background-color: #2c2c2e;
            padding: 30px;
            border-radius: 10px;
            margin-top: 50px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .list-group-item {
            background-color: #3a3a3c;
            color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Costo Total Mensual</h1>

        <!-- Mostramos los datos del usuario -->
        <h3>Detalles del Usuario:</h3>
        <p><strong>Nombre:</strong> <?= $usuario['nombre'] ?> <?= $usuario['apellidos'] ?></p>
        <p><strong>Email:</strong> <?= $usuario['correo'] ?></p>
        <p><strong>Edad:</strong> <?= $usuario['edad'] ?></p>
        <p><strong>Plan Base:</strong> <?= $usuario['plan_base'] ?></p>
        <p><strong>Duración de la Suscripción:</strong> <?= $usuario['duracion'] ?></p>

        <!-- Lista de paquetes adicionales -->
        <h4>Paquetes Adicionales:</h4>
        <ul class="list-group mb-3">
            <?php if (!empty($usuario['paquetes'])): ?>
                <?php foreach ($usuario['paquetes'] as $paquete): ?>
                    <li class="list-group-item"> <?= $paquete ?> </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item">No tiene paquetes adicionales</li>
            <?php endif; ?>
        </ul>

        <!-- Mostramos el costo total mensual -->
        <h3 class="text-center">Costo Total Mensual:</h3>
        <p><strong>Plan Base:</strong> $<?= number_format($precioPlan, 2) ?></p>
        <p><strong>Paquetes Adicionales:</strong> $<?= number_format($precioPaquetes, 2) ?></p>
        <p class="fs-4 text-warning"><strong>Costo Total:</strong> $<?= number_format($costoTotalMensual, 2) ?></p>

        <!-- Botón para volver a la lista de usuarios -->
        <div class="text-center">
            <a href="lista_usuarios.php" class="btn btn-primary">Volver a la lista de usuarios</a>
        </div>
    </div>
</body>
</html>
