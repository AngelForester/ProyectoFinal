<?php
// conexion.php
$host = "localhost"; // Normalmente es localhost en Hostinger
$dbname = "u274244872_hosanna_db"; // El nombre de tu base de datos
$username = "u274244872_Forester1"; // Tu usuario de BD en Hostinger
$password = "NZqhQyiNZ3Tg8JJ"; // Tu contraseña de BD

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die(json_encode(["status" => "error", "message" => "Error de conexión a la Base de Datos."]));
}
?>