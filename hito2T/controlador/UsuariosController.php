<?php
require_once '../modelo/class_usuario.php';  // Importamos el modelo de usuario.

class UsuariosController
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new Usuario();  // Instanciamos el modelo de usuario para interactuar con la base de datos.
    }

    // Método para agregar una nueva suscripción de usuario.
    public function agregarSuscripcion($nombre, $apellido, $email, $edad, $planBase, $paquetesAdicionales, $duracionSuscripcion, $precioPlan, $precioPaquetes)
    {
        // Verificamos si el correo ya está registrado para evitar duplicados.
        if ($this->modelo->verificarCorreoExistente($email)) {
            return false; // Si el correo ya existe, retornamos falso.
        }

        // Agregamos el usuario a la base de datos y obtenemos su ID.
        $usuarioId = $this->modelo->agregarUsuario($nombre, $apellido, $email, $edad, $planBase, $duracionSuscripcion, $precioPlan, $precioPaquetes);

        // Si hay paquetes adicionales, los añadimos al usuario.
        if (!empty($paquetesAdicionales)) {
            foreach ($paquetesAdicionales as $paquete) {
                $this->modelo->agregarPaqueteUsuario($usuarioId, $paquete);
            }
        }

        return true;  // Retornamos verdadero si todo salió bien.
    }

    // Método para obtener la lista de todos los usuarios registrados.
    public function listarUsuarios()
    {
        return $this->modelo->obtenerUsuarios();
    }

    // Método para obtener un usuario en específico por su ID.
    public function obtenerUsuarioPorId($id_usuario)
    {
        return $this->modelo->obtenerUsuarioPorId($id_usuario);
    }

    // Método para actualizar la información de un usuario.
    public function actualizarUsuario($id_usuario, $nombre, $apellido, $email, $edad, $planBase, $paquetesAdicionales, $duracionSuscripcion)
    {
        // Actualizamos los datos básicos del usuario.
        $this->modelo->actualizarUsuario($id_usuario, $nombre, $apellido, $email, $edad, $planBase, $duracionSuscripcion);

        // Eliminamos los paquetes anteriores para evitar duplicados.
        $this->modelo->eliminarPaquetesUsuario($id_usuario);

        // Añadimos los nuevos paquetes seleccionados.
        foreach ($paquetesAdicionales as $paquete) {
            $this->modelo->agregarPaqueteUsuario($id_usuario, $paquete);
        }
    }

    // Método para eliminar un usuario de la base de datos.
    public function eliminarUsuario($id_usuario)
    {
        // Verificamos que el ID es válido antes de eliminar.
        if (is_numeric($id_usuario) && $id_usuario > 0) {
            $this->modelo->eliminarUsuario($id_usuario);
        }
    }
}
?>
