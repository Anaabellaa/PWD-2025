<?php
include('config.php');  // Incluye la configuración y función conectar()

$con = conectar();     // Establece la conexión a la base de datos

function formatDate($date){
    return date('g:i a', strtotime($date));
}
?>
