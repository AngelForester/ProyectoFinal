<?php
// api.php
session_start();
header("Content-Type: application/json; charset=UTF-8");
require_once 'conexion.php';

// Leer lo que envía Javascript
$json = file_get_contents('php://input');
$data = json_decode($json, true);
$accion = $data['accion'] ?? '';

// --- 1. LOGIN ---
if ($accion === 'login') {
    $usuario = trim($data['usuario'] ?? '');
    $passwordIngresado = $data['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ? OR correo = ?");
    $stmt->execute([$usuario, $usuario]);
    $userDB = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validamos la contraseña (Aceptamos '123' para los usuarios de prueba)
    if ($userDB && (password_verify($passwordIngresado, $userDB['password_hash']) || $passwordIngresado === '123')) {
        
        // Creamos la sesión en el servidor
        $_SESSION['usuario'] = $userDB['nombre_usuario'];
        $_SESSION['rol'] = $userDB['rol'];

        echo json_encode([
            "status" => "success", 
            "usuario" => $userDB['nombre_usuario'], 
            "rol" => $userDB['rol']
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
    }
    exit;
}

// --- 2. LOGOUT ---
if ($accion === 'logout') {
    session_unset();
    session_destroy();
    echo json_encode(["status" => "success"]);
    exit;
}

// --- 3. GUARDAR CONTACTO ---
if ($accion === 'guardar_contacto') {
    $nombre = strip_tags(trim($data['nombre'] ?? ''));
    $correo = filter_var(trim($data['correo'] ?? ''), FILTER_SANITIZE_EMAIL);
    $mensaje = strip_tags(trim($data['mensaje'] ?? ''));

    if ($nombre && $correo && $mensaje) {
        $stmt = $pdo->prepare("INSERT INTO mensajes_contacto (nombre, correo, mensaje) VALUES (?, ?, ?)");
        if ($stmt->execute([$nombre, $correo, $mensaje])) {
            echo json_encode(["status" => "success"]);
            exit;
        }
    }
    echo json_encode(["status" => "error"]);
    exit;
}

// --- 4. OBTENER PRODUCTOS (Desde SQL a la Tienda) ---
if ($accion === 'obtener_productos') {
    $stmt = $pdo->query("SELECT * FROM productos");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $productos]);
    exit;
}

// --- 5. GUARDAR PEDIDO Y DETALLES DEL CARRITO ---
if ($accion === 'crear_pedido') {
    // Verificar si el usuario inició sesión
    if (!isset($_SESSION['usuario'])) {
        echo json_encode(["status" => "error", "message" => "Debes iniciar sesión para comprar"]);
        exit;
    }

    // Buscar el ID del usuario actual
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE nombre_usuario = ?");
    $stmt->execute([$_SESSION['usuario']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(["status" => "error", "message" => "Usuario inválido"]);
        exit;
    }

    $id_usuario = $user['id_usuario'];
    $carrito = $data['carrito'] ?? [];
    $total = $data['total'] ?? 0;

    try {
        // Iniciamos una transacción SQL (Para que se guarde el pedido Y los detalles juntos)
        $pdo->beginTransaction();

        // A. Guardar en la tabla "pedidos"
        $stmtPedido = $pdo->prepare("INSERT INTO pedidos (id_usuario, total, estado_pedido) VALUES (?, ?, 'Pendiente')");
        $stmtPedido->execute([$id_usuario, $total]);
        $id_pedido = $pdo->lastInsertId(); // Obtenemos el ID del pedido recién creado

        // B. Guardar en la tabla "detalles_pedido"
        $stmtDetalle = $pdo->prepare("INSERT INTO detalles_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        
        foreach ($carrito as $item) {
            $stmtDetalle->execute([$id_pedido, $item['id_producto'], $item['cantidad'], $item['precio']]);
        }

        // Confirmamos y guardamos los cambios en la BD
        $pdo->commit();
        echo json_encode(["status" => "success", "message" => "¡Pedido guardado correctamente en SQL!"]);
    } catch (Exception $e) {
        $pdo->rollBack(); // Si hay error, cancelamos todo para evitar datos corruptos
        echo json_encode(["status" => "error", "message" => "Error al procesar el pedido."]);
    }
    exit;
}

echo json_encode(["status" => "error", "message" => "Acción no válida"]);
?>