<?php
// Incluir el controlador de usuarios para poder gestionar las operaciones
require_once '../controlador/UsuariosController.php';
$controller = new UsuariosController();

// Verificamos si se ha pasado un ID de usuario en la URL
if (isset($_GET['id'])) {
    $id_usuario = $_GET['id']; // Guardamos el ID del usuario
    
    // Llamamos al método del controlador para eliminar el usuario con el ID recibido
    $controller->eliminarUsuario($id_usuario);
    
    // Una vez eliminado, redirigimos a la lista de usuarios
    header("Location: lista_usuarios.php");
    exit(); // Terminamos el script para evitar que se ejecute más código
} else {
    // Si no se ha recibido un ID válido, también redirigimos a la lista de usuarios
    header("Location: lista_usuarios.php");
    exit();
}
?>

