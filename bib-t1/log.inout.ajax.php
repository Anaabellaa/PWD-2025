<?php
include_once("libreria/config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) && !isset($_SESSION['userid'])) {
    if ($idcnx = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)) {

        // LOGIN
        if (isset($_POST['login_username'])) {
            $sql = 'SELECT user,passwd,id,rol FROM personas WHERE user="' . $_POST['login_username'] . '" AND passwd="' . md5($_POST['login_userpass']) . '" LIMIT 1';
            if ($res = mysqli_query($idcnx, $sql)) {
                if (mysqli_num_rows($res) == 1) {
                    $user = mysqli_fetch_array($res);
                    $_SESSION['username'] = $user['user'];
                    $_SESSION['userid'] = $user['id'];
                    $_SESSION['rol'] = $user['rol'];
                    echo 1;
                } else {
                    echo 0;
                }
            } else {
                echo 0;
            }
            exit; 
        }

        // REGISTRO
        if (isset($_POST['rec_username'])) {



            $nombre   = isset($_POST['rec_nombre']) ? $_POST['rec_nombre'] : '';
            $apellido = isset($_POST['rec_apellido']) ? $_POST['rec_apellido'] : '';
            $sexo     = isset($_POST['rec_sexo']) ? $_POST['rec_sexo'] : '';
            $dni      = isset($_POST['rec_dni']) ? $_POST['rec_dni'] : '';
            $carrera  = isset($_POST['rec_carrera']) ? $_POST['rec_carrera'] : '';
            $telefono = isset($_POST['rec_telefono']) ? $_POST['rec_telefono'] : '';
            $email    = isset($_POST['rec_email']) ? $_POST['rec_email'] : '';
            $username = $_POST['rec_username'];
            $passwd   = md5($_POST['rec_userpass']);

            $sql_check = "SELECT id FROM personas WHERE user = '$username' LIMIT 1";
            $res_check = mysqli_query($idcnx, $sql_check);

            if (mysqli_num_rows($res_check) > 0) {
                echo "El nombre de usuario ya existe, por favor elija otro.";
            } else {
                $sql = "INSERT INTO personas (
                    nombre,
                    apellido,
                    sexo,
                    dni,
                    carrera,
                    telefono,
                    email,
                    user,
                    passwd,
                    rol
                ) VALUES (
                    '$nombre',
                    '$apellido',
                    '$sexo',
                    '$dni',
                    '$carrera',
                    '$telefono',
                    '$email',
                    '$username',
                    '$passwd',
                    'Estudiante'
                )";

                if (mysqli_query($idcnx, $sql)) {
                    echo 1;
                } else {
                    echo "Error al registrar: " . mysqli_error($idcnx);
                }
            }
            exit; 
        }

       
        echo 0;

    } else {
       
        echo "Error de conexión a la base de datos";
    }
} else {
    
    echo 0;
}
?>
