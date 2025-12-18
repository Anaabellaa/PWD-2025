<?php
session_start();

$userid = $_SESSION['userid'] ?? null;
$username = $_SESSION['username'] ?? '';
$rol = $_SESSION['rol'] ?? '';

include_once("libreria/config.php");

// Eliminar de tabla usuarios_online
if($userid){
    $con->query("DELETE FROM usuarios_online WHERE id=$userid");

    // 🔹 AÑADIDO: actualizar ws_push.txt
    $usuarios_online = [];
    $result = $con->query("SELECT username, rol FROM usuarios_online"); 
    while ($u = $result->fetch_assoc()) {
        $usuarios_online[] = $u;
    }

    $html = "<p>Total usuarios conectados: ".count($usuarios_online)."</p>";
    foreach ($usuarios_online as $u) {
        $html .= "<p>{$u['rol']} - {$u['username']}</p>";
    }

    file_put_contents(__DIR__ . "/ws_push.txt", $html);
}

// Notificar WebSocket
$mensaje = json_encode([
    'accion' => 'logout',
    'username' => $username,
    'rol' => $rol
]);

$host = '127.0.0.1';
$port = 8080;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if(@socket_connect($socket, $host, $port)){
    socket_write($socket, $mensaje, strlen($mensaje));
    socket_close($socket);
}

// Destruir sesión
session_destroy();
header('Location: ./');
exit;
?>
