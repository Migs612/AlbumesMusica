<?php
namespace App\Models;

use App\Config\BaseDeDatos;
use PDO;

class Usuario extends Modelo {
    private $tabla = 'usuarios';

    public function registrar($nombre, $correo, $contrasena) {
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $consulta = "INSERT INTO " . $this->tabla . " (nombre, correo, contrasena) VALUES (:nombre, :correo, :contrasena)";
        $sentencia = $this->conexion->prepare($consulta);
        $sentencia->bindParam(':nombre', $nombre);
        $sentencia->bindParam(':correo', $correo);
        $sentencia->bindParam(':contrasena', $hash);
        
        return $sentencia->execute();
    }

    public function login($correo, $contrasena) {
        $consulta = "SELECT * FROM " . $this->tabla . " WHERE correo = :correo LIMIT 1";
        $sentencia = $this->conexion->prepare($consulta);
        $sentencia->bindParam(':correo', $correo);
        $sentencia->execute();
        $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return $usuario;
        }
        return false;
    }

    public function obtenerTodos() {
        $consulta = "SELECT id_usuario, nombre, correo FROM " . $this->tabla;
        $sentencia = $this->conexion->prepare($consulta);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $consulta = "SELECT id_usuario, nombre, correo FROM " . $this->tabla . " WHERE id_usuario = :id";
        $sentencia = $this->conexion->prepare($consulta);
        $sentencia->bindParam(':id', $id);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
    }
}
