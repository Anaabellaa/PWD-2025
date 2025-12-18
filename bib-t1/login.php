<?php
session_start();
include_once(__DIR__ . "/libreria/config.php");

// Mostrar errores para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['login_username'];
    $password = $_POST['login_userpass'];

    // Validar usuario en tabla personas
    $stmt = $con->prepare("SELECT id, user, rol, passwd FROM personas WHERE user=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows === 1){
        $user = $res->fetch_assoc();
        if(md5($password) === $user['passwd']){

            // Sesiones
            $_SESSION['userid'] = $user['id'];
            $_SESSION['username'] = $user['user'];
            $_SESSION['rol'] = $user['rol'];

            // Insertar o actualizar usuarios_online
            $con->query("INSERT INTO usuarios_online (id, username, rol, last_ping)
                         VALUES ({$user['id']}, '{$user['user']}', '{$user['rol']}', NOW())
                         ON DUPLICATE KEY UPDATE last_ping=NOW()");

            // Actualizar archivo ws_push.txt
            $usuarios_online = [];
            $result = $con->query("
               SELECT u.id, u.rol, p.nombre, p.apellido 
               FROM usuarios_online u
               JOIN personas p ON u.id = p.id
            ");
            while ($u = $result->fetch_assoc()) {
            $usuarios_online[] = $u;
            }

            $html = "<p>Total usuarios conectados: ".count($usuarios_online)."</p>";
            foreach ($usuarios_online as $u) {
            $html .= "<p>{$u['rol']} - {$u['nombre']} {$u['apellido']}</p>";
            }

            file_put_contents(__DIR__ . "/ws_push.txt", $html);


            // Notificar WebSocket
            $mensaje = json_encode([
                'accion' => 'login',
                'username' => $user['user'],
                'rol' => $user['rol']
            ]);

            $host = '127.0.0.1';
            $port = 8080;
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if(@socket_connect($socket, $host, $port)){
                socket_write($socket, $mensaje, strlen($mensaje));
                socket_close($socket);
            }
            
            echo "ok";

        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }

    exit;
}
?>
