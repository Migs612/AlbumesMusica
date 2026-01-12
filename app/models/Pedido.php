<?php
namespace App\Models;

use App\Config\BaseDeDatos;
use PDO;

class Pedido extends Modelo {
    private $tabla_pedidos = 'pedidos';
    private $tabla_detalle = 'detalle_pedido';

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
                $sentencia_detalle->execute([
                    ':id_pedido' => $id_pedido,
                    ':id_prod' => $item['id_producto'],
                    ':cant' => $item['cantidad'],
                    ':precio' => $item['precio']
                ]);
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
        $consulta = "SELECT p.*, u.id_usuario, u.nombre, u.correo 
                     FROM " . $this->tabla_pedidos . " p
                     JOIN usuarios u ON p.id_usuario = u.id_usuario
                     WHERE p.id_pedido = :id";
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

    public function obtenerTodosConFiltros($filtros = []) {
        $sql = "SELECT p.*, u.nombre as nombre_usuario, u.correo 
                FROM " . $this->tabla_pedidos . " p
                JOIN usuarios u ON p.id_usuario = u.id_usuario";
        
        $params = [];
        $conditions = [];

        if (!empty($filtros['usuario'])) {
            $conditions[] = "p.id_usuario = :usuario";
            $params[':usuario'] = $filtros['usuario'];
        }

        if (!empty($filtros['precio_min'])) {
            $conditions[] = "p.total >= :precio_min";
            $params[':precio_min'] = $filtros['precio_min'];
        }
        if (!empty($filtros['precio_max'])) {
            $conditions[] = "p.total <= :precio_max";
            $params[':precio_max'] = $filtros['precio_max'];
        }

        $agrupar = false;
        if (!empty($filtros['producto'])) {
            $sql .= " JOIN " . $this->tabla_detalle . " d ON p.id_pedido = d.id_pedido 
                      JOIN productos prod ON d.id_producto = prod.id_producto";
            $conditions[] = "prod.titulo LIKE :producto";
            $params[':producto'] = "%" . $filtros['producto'] . "%";
            
            $agrupar = true;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        if ($agrupar) {
            $sql .= " GROUP BY p.id_pedido";
        }

        $sql .= " ORDER BY p.fecha_pedido DESC";

        $sentencia = $this->conexion->prepare($sql);
        foreach ($params as $key => $val) {
            $sentencia->bindValue($key, $val);
        }
        $sentencia->execute();
        return $sentencia->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarEstado($id_pedido, $nuevo_estado) {
        $sql = "UPDATE " . $this->tabla_pedidos . " SET estado = :estado WHERE id_pedido = :id";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            ':estado' => $nuevo_estado,
            ':id' => $id_pedido
        ]);
    }

    public function eliminar($id_pedido) {
        try {
            $this->conexion->beginTransaction();
            
            $sql1 = "DELETE FROM " . $this->tabla_detalle . " WHERE id_pedido = :id";
            $stmt1 = $this->conexion->prepare($sql1);
            $stmt1->execute([':id' => $id_pedido]);

            $sql2 = "DELETE FROM " . $this->tabla_pedidos . " WHERE id_pedido = :id";
            $stmt2 = $this->conexion->prepare($sql2);
            $stmt2->execute([':id' => $id_pedido]);
            
            return $this->conexion->commit();
        } catch (\Exception $e) {
            $this->conexion->rollBack();
            return false;
        }
    }
}
