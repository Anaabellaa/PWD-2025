<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>Registro de usuarios con PHP y Ajax</title>
        <link rel="stylesheet" type="text/css" href="bootstrap/stylesheet.css" />
		<script src="bootstrap/js/functions.ajax.js"></script>
    </head>
    <body>
	<div id="allContent"><table cellpadding="0" cellspacing="0" border="0" height="100%" width="100%"><tr><td align="center" valign="middle" height="100%" width="100%">
         
        <div id="alertBoxes"></div>
        <span class="loginBlock"><span class="inner">
    
	    
        <form method="post" action="log.inout.ajax.php" id="registerForm">
    <table>
        <tr><td>Nombre:</td><td><input type="text" name="rec_nombre" id="rec_nombre" required /></td></tr>
        <tr><td>Apellido:</td><td><input type="text" name="rec_apellido" id="rec_apellido" required /></td></tr>
        <tr><td>Sexo:</td><td><input type="text" name="rec_sexo" id="rec_sexo" required /></td></tr>
        <tr><td>DNI:</td><td><input type="text" name="rec_dni" id="rec_dni" required /></td></tr>
        <tr><td>Carrera:</td><td><input type="text" name="rec_carrera" id="rec_carrera" required /></td></tr>
        <tr><td>Teléfono:</td><td><input type="text" name="rec_telefono" id="rec_telefono" required /></td></tr>
        <tr><td>Usuario:</td><td><input type="text" name="rec_username" id="rec_username" required /></td></tr>
        <tr><td>Contraseña:</td><td><input type="password" name="rec_userpass" id="rec_userpass" required /></td></tr>
        <tr><td>Repetir Contraseña:</td><td><input type="password" name="rec_userpass_1" id="rec_userpass_1" required /></td></tr>
        <tr><td>e-mail:</td><td><input type="email" name="rec_email" id="rec_email" required /></td></tr>
        <tr><td colspan="2" align="right"><button type="submit" id="rec_userbttn">Registrar</button></td></tr>
    </table>
</form>


         
        </span></span>
         
    </td></tr></table></div></body>
</html>
