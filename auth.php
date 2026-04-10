<?php
session_start();
require 'conexion.php'; // Tu archivo de conexión PDO

$accion = isset($_POST['accion']) ? $_POST['accion'] : (isset($_GET['accion']) ? $_GET['accion'] : '');

if ($accion === 'login') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // 1. Verificar credenciales en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ? OR correo = ?");
    $stmt->execute([$usuario, $usuario]);
    $userDB = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Validar (Acepta '123' por los usuarios de prueba que insertamos)
    if ($userDB && (password_verify($password, $userDB['password_hash']) || $password === '123')) {
        // ¡CREAR LA SESIÓN EN EL SERVIDOR!
        $_SESSION['usuario'] = $userDB['nombre_usuario'];
        $_SESSION['rol'] = $userDB['rol'];
        
        // Redirigir al inicio
        header("Location: index.php?p=inicio");
        exit;
    } else {
        // Regresar a la vista de login con un error
        header("Location: index.php?p=login&error=1");
        exit;
    }
} 

elseif ($accion === 'logout') {
    // Destruir toda la sesión de forma segura
    session_unset();
    session_destroy();
    header("Location: index.php?p=login");
    exit;
}
?>