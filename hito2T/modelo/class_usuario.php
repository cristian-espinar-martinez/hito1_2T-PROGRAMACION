<?php
require_once '../config/class_conexion.php';  // Importamos la clase de conexión con la base de datos.

class Usuario
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion(); // Iniciamos la conexión con la base de datos.
    }

    // Verifica si el correo ya está registrado en la base de datos.
    public function verificarCorreoExistente($email)
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE correo = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        return $count > 0;  // Retorna true si el correo ya existe.
    }

    // Agrega un nuevo usuario a la base de datos.
    public function agregarUsuario($nombre, $apellido, $email, $edad, $planBase, $duracionSuscripcion, $precioPlan, $precioPaquetes)
    {
        $sql = "INSERT INTO usuarios (nombre, apellidos, correo, edad, plan_base_id, duracion) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->conexion->prepare($sql);
        $planBaseId = $this->obtenerPlanBaseId($planBase);  // Se obtiene el ID del plan base.
        $stmt->bind_param('sssiis', $nombre, $apellido, $email, $edad, $planBaseId, $duracionSuscripcion);
        $stmt->execute();
        
        return $this->conexion->conexion->insert_id;  // Retorna el ID del usuario recién agregado.
    }

    // Obtiene todos los usuarios con sus datos y los paquetes asociados.
    public function obtenerUsuarios()
    {
        $sql = "SELECT u.id, u.nombre, u.apellidos, u.correo, u.edad, pb.nombre AS plan_base, u.duracion 
                FROM usuarios u
                INNER JOIN plan_base pb ON u.plan_base_id = pb.id
                ORDER BY u.id ASC";  // Ordenados por ID de manera ascendente.
        $result = $this->conexion->conexion->query($sql);
    
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $row['paquetes'] = $this->obtenerPaquetesPorUsuario($row['id']);  // Se obtienen los paquetes del usuario.
            $usuarios[] = $row;
        }
    
        return $usuarios;
    }

    // Obtiene un usuario por su ID.
    public function obtenerUsuarioPorId($id_usuario)
    {
        $sql = "SELECT u.id, u.nombre, u.apellidos, u.correo, u.edad, pb.nombre AS plan_base, u.duracion 
                FROM usuarios u
                INNER JOIN plan_base pb ON u.plan_base_id = pb.id
                WHERE u.id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param('i', $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        
        // Se obtienen los paquetes adicionales que tenga el usuario.
        $usuario['paquetes'] = $this->obtenerPaquetesPorUsuario($usuario['id']);

        return $usuario;
    }

    // Actualiza la información de un usuario en la base de datos.
    public function actualizarUsuario($id_usuario, $nombre, $apellido, $email, $edad, $planBase, $duracionSuscripcion)
    {
        $sql = "UPDATE usuarios SET nombre = ?, apellidos = ?, correo = ?, edad = ?, plan_base_id = ?, duracion = ? WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $planBaseId = $this->obtenerPlanBaseId($planBase);  // Se obtiene el ID del plan base.
        $stmt->bind_param('sssiisi', $nombre, $apellido, $email, $edad, $planBaseId, $duracionSuscripcion, $id_usuario);
        $stmt->execute();
    }

    // Elimina un usuario por su ID.
    public function eliminarUsuario($id_usuario)
    {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param('i', $id_usuario);
        $stmt->execute();
    }

    // Elimina los paquetes asociados a un usuario.
    public function eliminarPaquetesUsuario($id_usuario)
    {
        $sql = "DELETE FROM usuario_paquetes WHERE usuario_id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param('i', $id_usuario);
        $stmt->execute();
    }

    // Agrega un paquete a un usuario específico.
    public function agregarPaqueteUsuario($usuarioId, $paquete)
    {
        $paqueteId = $this->obtenerPaqueteId($paquete);  // Se obtiene el ID del paquete.
        $sql = "INSERT INTO usuario_paquetes (usuario_id, paquete_id) VALUES (?, ?)";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param('ii', $usuarioId, $paqueteId);
        $stmt->execute();
    }

    // Obtiene los paquetes contratados por un usuario.
    private function obtenerPaquetesPorUsuario($usuarioId)
    {
        $sql = "SELECT p.nombre 
                FROM usuario_paquetes up
                INNER JOIN paquetes p ON up.paquete_id = p.id
                WHERE up.usuario_id = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        $paquetes = [];
        while ($row = $result->fetch_assoc()) {
            $paquetes[] = $row['nombre'];
        }

        return $paquetes;
    }

    // Obtiene el ID de un plan base a partir de su nombre.
    private function obtenerPlanBaseId($nombrePlan)
    {
        $sql = "SELECT id FROM plan_base WHERE nombre = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param('s', $nombrePlan);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['id'];
    }

    // Obtiene el ID de un paquete a partir de su nombre.
    private function obtenerPaqueteId($nombrePaquete)
    {
        $sql = "SELECT id FROM paquetes WHERE nombre = ?";
        $stmt = $this->conexion->conexion->prepare($sql);
        $stmt->bind_param('s', $nombrePaquete);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['id'];
    }
}
?>
