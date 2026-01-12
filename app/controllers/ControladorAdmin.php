<?php
namespace App\Controllers;

use App\Models\Pedido;
use App\Models\Usuario;

class ControladorAdmin {

    private function verificarAdmin() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_correo'])) {
             header('Location: index.php?p=login');
             exit;
        }

        $admin_email = $_ENV['ADMIN_EMAIL'] ?? '';

        if ($_SESSION['usuario_correo'] !== $admin_email) {
            header('Location: index.php');
            exit;
        }
    }

    public function index() {
        $this->verificarAdmin();
        $this->pedidos();
    }

    public function pedidos() {
        $this->verificarAdmin();
        
        $modeloPedido = new Pedido();
        $modeloUsuario = new Usuario();

        $filtros = [
            'usuario' => isset($_GET['usuario']) ? $_GET['usuario'] : null,
            'precio_min' => isset($_GET['precio_min']) ? $_GET['precio_min'] : null,
            'precio_max' => isset($_GET['precio_max']) ? $_GET['precio_max'] : null,
            'producto' => isset($_GET['producto']) ? $_GET['producto'] : null
        ];

        $pedidos = $modeloPedido->obtenerTodosConFiltros($filtros);
        $usuarios = $modeloUsuario->obtenerTodos();

        require_once 'app/views/admin_pedidos.php';
    }

    public function historialUsuario() {
        $this->verificarAdmin();
        $id_usuario = $_GET['id'] ?? null;
        if($id_usuario) {
            $modeloPedido = new Pedido();
            $pedidos_usuario = $modeloPedido->obtenerPorUsuario($id_usuario);
            
            $modeloUsuario = new Usuario();
        }
    }
    
    public function verPedido() {
        $this->verificarAdmin();
        $id_pedido = $_GET['id'] ?? null;
        
        if ($id_pedido) {
            $modeloPedido = new Pedido();
            $pedido = $modeloPedido->obtenerPedido($id_pedido);
            $detalles = $modeloPedido->obtenerDetalles($id_pedido);
            
            $usuario_pedido = [
                'nombre' => $pedido['nombre'] ?? 'Desconocido',
                'correo' => $pedido['correo'] ?? ''
            ];

            require_once 'app/views/admin_detalle_pedido.php';
        } else {
            header('Location: index.php?p=admin_pedidos');
        }
    }
    
    public function verHistorialUsuario() {
        $this->verificarAdmin();
        $id_usuario = $_GET['id'] ?? null;
        
        if ($id_usuario) {
            $modeloPedido = new Pedido();
            $pedidos_usuario = $modeloPedido->obtenerPorUsuario($id_usuario);
            
            $modeloUsuario = new Usuario();
            $usuario_ver = $modeloUsuario->obtenerPorId($id_usuario);
            
            require_once 'app/views/admin_historial_usuario.php';
        } else {
            header('Location: index.php?p=admin_usuarios');
        }
    }

    public function usuarios() {
        $this->verificarAdmin();
        
        $modeloUsuario = new Usuario();
        $usuarios = $modeloUsuario->obtenerTodos();

        require_once 'app/views/admin_usuarios.php';
    }

    public function devolverPedido() {
        $this->verificarAdmin();
        
        if (isset($_POST['id_pedido'])) {
            $modeloPedido = new Pedido();
            $modeloPedido->actualizarEstado($_POST['id_pedido'], 'devuelto');
        }
        
        header('Location: index.php?p=admin_pedidos');
    }

    public function eliminarPedido() {
        $this->verificarAdmin();
        
        if (isset($_POST['id_pedido'])) {
            $modeloPedido = new Pedido();
            $modeloPedido->eliminar($_POST['id_pedido']);
        }
        
        header('Location: index.php?p=admin_pedidos');
    }
}
