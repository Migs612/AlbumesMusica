<?php
namespace App\Models;

use App\Config\BaseDeDatos;
use PDO;

class Pedido {
    private $conexion;
    private $tabla_pedidos = 'pedidos';
    private $tabla_detalle = 'detalle_pedido';

    public function __construct() {
        $base_datos = new BaseDeDatos();
        $this->conexion = $base_datos->obtenerConexion();
    }

    public function crearPedido($id_usuario, $carrito, $total) {
        try {
            $this->conexion->beginTransaction();

            
            $consulta = "INSERT INTO " . $this->tabla_pedidos . " (id_usuario, total) VALUES (:id_usuario, :total)";
            $sentencia = $this->conexion->prepare($consulta);
            $sentencia->bindParam(':id_usuario', $id_usuario);
            $sentencia->bindParam(':total', $total);
            $sentencia->execute();
            $id_pedido = $this->conexion->lastInsertId();

            
            $consulta_detalle = "INSERT INTO " . $this->tabla_detalle . " (id_pedido, id_producto, cantidad, precio_unitario) VALUES (:id_pedido, :id_prod, :cant, :precio)";
            $sentencia_detalle = $this->conexion->prepare($consulta_detalle);

            foreach ($carrito as $item) {
                $sentencia_detalle->bindParam(':id_pedido', $id_pedido);
                $sentencia_detalle->bindParam(':id_prod', $item['id_producto']);
                $sentencia_detalle->bindParam(':cant', $item['cantidad']);
                $sentencia_detalle->bindParam(':precio', $item['precio']);
                $sentencia_detalle->execute();
            }

            $this->conexion->commit();
            return $id_pedido;

        } catch (\Exception $e) {
            $this->conexion->rollBack();
            
            echo "Error SQL: " . $e->getMessage();
            return false;
        }
    }

    public function obtenerPorUsuario($id_usuario) {
        $consulta = "SELECT * FROM " . $this->tabla_pedidos . " WHERE id_usuario = :id ORDER BY fecha_pedido DESC";
        $sentencia = $this->conexion->prepare($consulta);
        $sentencia->bindParam(':id', $id_usuario);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPedido($id_pedido) {
        $consulta = "SELECT * FROM " . $this->tabla_pedidos . " WHERE id_pedido = :id";
        $sentencia = $this->conexion->prepare($consulta);
        $sentencia->bindParam(':id', $id_pedido);
        $sentencia->execute();
        return $sentencia->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerDetalles($id_pedido) {
        $consulta = "SELECT d.*, p.titulo, p.artista, p.imagen_url 
                     FROM " . $this->tabla_detalle . " d
                     JOIN productos p ON d.id_producto = p.id_producto
                     WHERE d.id_pedido = :id";
        $sentencia = $this->conexion->prepare($consulta);
        $sentencia->bindParam(':id', $id_pedido);
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }
}
