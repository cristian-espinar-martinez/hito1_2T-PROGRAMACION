<?php
// Incluimos el controlador de usuarios para poder trabajar con ellos
require_once '../controlador/UsuariosController.php';
$controller = new UsuariosController();

// Obtenemos la lista de usuarios llamando al método del controlador
$usuarios = $controller->listarUsuarios();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Usuarios Registrados</h1>

        <div class="table-responsive">
            <table class="table table-striped table-hover border">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Edad</th>
                        <th>Plan Base</th>
                        <th>Duración</th>
                        <th>Paquetes Adicionales</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['id'] ?></td>
                        <td><?= $usuario['nombre'] ?></td>
                        <td><?= $usuario['apellidos'] ?></td>
                        <td><?= $usuario['correo'] ?></td>
                        <td><?= $usuario['edad'] ?></td>
                        <td><?= $usuario['plan_base'] ?></td>
                        <td><?= $usuario['duracion'] ?></td>
                        <td>
                            <?= isset($usuario['paquetes']) && !empty($usuario['paquetes']) ? implode(', ', $usuario['paquetes']) : "No tiene paquetes adicionales"; ?>
                        </td>
                        <td>
                            <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-secondary btn-sm">Editar</a>
                            <a href="eliminar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-outline-danger btn-sm">Eliminar</a>
                            <a href="costototal.php?id=<?= $usuario['id'] ?>" class="btn btn-success btn-sm">Costo Total</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="alta_usuario.php" class="btn btn-dark">Agregar un nuevo usuario</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

