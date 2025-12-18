<?php
set_time_limit(0);

$host = '127.0.0.1';
$port = 8080;

// Crear socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, $host, $port);
socket_listen($socket);

$clients = [];

echo "WebSocket iniciado en $host:$port\n";

while (true) {
    $read = $clients;
    $read[] = $socket;

    $write = null;
    $except = null;

    socket_select($read, $write, $except, 0, 200000);

    // Nuevo cliente
    if (in_array($socket, $read)) {
        $client = socket_accept($socket);
        $clients[] = $client;
        $read = array_diff($read, [$socket]);
    }

    // Leer datos de clientes desconectados
    foreach ($read as $key => $client) {
        $data = @socket_read($client, 1024, PHP_NORMAL_READ);
        if ($data === false) {
            unset($clients[$key]);
            socket_close($client);
        }
    }

    // Broadcast del archivo con usuarios online
    $archivo = __DIR__ . "/ws_push.txt";
    if (file_exists($archivo)) {
        $usuarios_html = file_get_contents($archivo);
        $usuarios_html = mb_convert_encoding($usuarios_html, 'UTF-8', 'auto'); // opcional
    } else {
        $usuarios_html = "<p>No hay usuarios conectados</p>";
    }

    foreach ($clients as $key => $send) {
        if (@socket_write($send, $usuarios_html, strlen($usuarios_html)) === false) {
            unset($clients[$key]);
            socket_close($send);
        }
    }

    usleep(200000); // 0.2 segundos
}
