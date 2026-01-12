<?php
namespace App\Controllers;

use App\Models\Usuario;

class ControladorAutenticacion {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];
            
            $modelo = new Usuario();
            $usuario = $modelo->login($correo, $contrasena);

            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_correo'] = $usuario['correo'];
                header('Location: index.php?p=home');
                exit;
            } else {
                $error = "Credenciales incorrectas";
                require_once 'app/views/login.php';
            }
        } else {
            require_once 'app/views/login.php';
        }
    }

    public function registro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $contrasena = $_POST['contrasena'];

            $modelo = new Usuario();
            if ($modelo->registrar($nombre, $correo, $contrasena)) {
                header('Location: index.php?p=login');
                exit;
            } else {
                $error = "Error al registrar";
                require_once 'app/views/registro.php';
            }
        } else {
            require_once 'app/views/registro.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?p=login');
        exit;
    }
}
