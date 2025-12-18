<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>BASES PWD</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <script src="bootstrap/js/funciones_gral.js"></script>
    <link rel="stylesheet" href="bootstrap/css/style_chat.css" media="all"/>    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="bootstrap/cust.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


    <style>
        #background {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-image: url('images/b_bkg_3.jpg');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: 100%;
            opacity: 0.6;
        }
    </style>
</head>
<body style="padding:0;">
<div id="background"></div>
<div class="container-fluid">

    <!-- MENÚ -->
    <nav class="navbar navbar-inverse navbar-static-top navbar2" role="navigation" >
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo1.png" width="30" height="30" alt="Logo Ana Bejarano" style="display:inline-block; margin-top:-5px;">
                Biblioteca de Anabella Bejarano
            </a>
        </div>
        <ul class="nav navbar-nav ">
            <li><a href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="cartelera.php">Cartelera</a></li>
            <li><a href="abm_ld.php">Libros</a></li>
            <li><a class="nav-link" href="cartelera.php?cat=Ayuda">Ayuda</a></li>
            <li><a href="prestamos_activos.php">Préstamos</a></li>

            <?php if (isset($_SESSION['username']) && $_SESSION['rol']=='administrador'): ?>
                <li><a href="abm_p.php">Usuarios</a></li>
                <li><a href="abm_c.php">Carteles</a></li>
            <?php endif; ?>
        </ul>

        <ul class="nav navbar-nav navbar-right" style="padding-right: 10px;">
            <?php if (isset($_SESSION['username'])): ?>
                <li class="navbar-brand"><?= $_SESSION['rol'] ?> : <?= $_SESSION['username'] ?></li>
            <?php endif; ?>

            <?php if (!isset($_SESSION['username'])): ?>
                <li><a href="#" data-toggle="modal" data-target="#loginModal"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                <li><a href="registro.php" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-user"></span> Registro</a></li>
            <?php else: ?>
                <li><a href="i_chat.php">Chat</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- PANEL DE USUARIOS CONECTADOS (ADMIN) -->
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
        <div class="panel panel-default" style="margin: 15px;">
            <div class="panel-heading"><strong>Usuarios conectados (tiempo real)</strong></div>
            <div class="panel-body" id="onlineUsersList">
                Cargando...
            </div>
        </div>

        <script>
            function actualizarUsuarios() {
    $("#onlineUsersList").load("ws_push.txt");
}

setInterval(actualizarUsuarios, 500); // cada 0.5 segundos
actualizarUsuarios(); // carga inicial
</script>
    <?php endif; ?>


    <!-- MODAL LOGIN -->
    <div id="loginModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Login</h4>
          </div>
          <div class="modal-body">
            <form id="loginForm">
                <label>Usuario:</label>
                <input type="text" name="login_username" id="login_username" class="form-control" required><br>
                <label>Contraseña:</label>
                <input type="password" name="login_userpass" id="login_userpass" class="form-control" required><br>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <div id="loginResult" style="margin-top:10px;color:red;"></div>
          </div>
        </div>
      </div>
    </div>

    <script>
        $("#loginForm").submit(function(e){
            e.preventDefault();
            $.post("login.php", $(this).serialize(), function(resp){
                if(resp.trim() === "ok"){
                    location.reload(); // recarga para mostrar panel de usuarios
                } else {
                    $("#loginResult").text(resp);
                }
            });
        });
    </script>

 <!-- Modal -->
 
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


</div>
</body>
</html>
