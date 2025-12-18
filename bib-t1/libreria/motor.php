<?php
include ("config.php");
include ("conexion.php");
//include ("estudiante.php");
//include ("libro_d.php");
$objConexion = new Conexion();

if (isset($_SESSION['username']) && isset($_SESSION['id_usuario_logueado'])) {
    
    $current_user_id = (int)$_SESSION['id_usuario_logueado']; 
    $timestamp = time(); 
    
    $sql_update_activity = "UPDATE personas 
                            SET last_activity = $timestamp 
                            WHERE id = $current_user_id";
    
    if (isset($objConexion) && method_exists($objConexion->enlace, 'query')) {
        $objConexion->enlace->query($sql_update_activity);
    }
}
?>