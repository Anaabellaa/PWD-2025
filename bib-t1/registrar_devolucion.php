<?php
include_once("libreria/motor.php");

// Mensaje de resultado
$mensaje = "";

// Procesar devolución
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_prestamo = intval($_POST['id_prestamo']);
    $fecha_devolucion = mysqli_real_escape_string($con, $_POST['fecha_devolucion']); 

    // Actualizar la fecha de devolución
    $update = "UPDATE prestamos SET fecha_devolucion = '$fecha_devolucion' WHERE id_prestamo = $id_prestamo";
    if (mysqli_query($con, $update)) {
        $result = mysqli_query($con, "SELECT id_libro FROM prestamos WHERE id_prestamo = $id_prestamo");
        if ($row = mysqli_fetch_assoc($result)) {
            $id_libro = $row['id_libro'];

            mysqli_query($con, "UPDATE libros_d SET estado = 'disponible' WHERE id_libro = $id_libro");

            $mensaje = "✅ Devolución registrada correctamente.";
        } else {
            $mensaje = "❌ No se encontró el préstamo.";
        }
    } else {
        $mensaje = "❌ Error al registrar devolución: " . mysqli_error($con);
    }
}

// Obtener préstamos sin devolver
$query = "
    SELECT p.id_prestamo, l.id_libro, l.Titulo, per.nombre, per.apellido, p.fecha_prestamo
    FROM prestamos p
    JOIN libros_d l ON p.id_libro = l.id_libro
    JOIN personas per ON p.id_persona = per.id
    WHERE p.fecha_devolucion IS NULL
";

$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Devolución</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body { padding: 20px; background-color: #f7f7f7; font-family: Arial, sans-serif; }
        .form-container { background-color: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        h2 { margin-bottom: 20px; text-align: center; }
        .form-group { margin-bottom: 15px; }
        .btn-group { margin-top: 15px; text-align: center; }
        .back-link { display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Registrar Devolución</h2>

    <?php if ($mensaje): ?>
        <div class="alert <?= $alertClass ?>"><?= $mensaje ?></div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <form method="POST" class="form-horizontal">

            <div class="form-group">
                <label class="control-label col-sm-4">Préstamo a Devolver:</label>
                <div class="col-sm-8">
                    <select name="id_prestamo" class="form-control" required>
                        <?php while ($row = mysqli_fetch_assoc($result)) {
                            $label = "{$row['Titulo']} - {$row['nombre']} {$row['apellido']} (prestado el {$row['fecha_prestamo']})";
                            echo "<option value='{$row['id_prestamo']}'>{$label}</option>";
                        } ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-4">Fecha de Devolución:</label>
                <div class="col-sm-8">
                    <input type="date" name="fecha_devolucion" class="form-control" required>
                </div>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Registrar Devolución</button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-warning text-center">No hay préstamos pendientes de devolución.</div>
    <?php endif; ?>

    <a href="libros_imp.php" class="btn btn-default">&larr; Volver a Libros Impresos</a>
    <a href="index.php" class="btn btn-default">Ir al Menú Principal</a>
</div>

</body>
</html>