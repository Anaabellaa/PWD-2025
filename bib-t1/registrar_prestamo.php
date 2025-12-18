<?php
include_once("libreria/motor.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_libro = intval($_POST['id_libro']);
    $id_persona = intval($_POST['id_persona']);
    $fecha_prestamo = mysqli_real_escape_string($con, $_POST['fecha_prestamo']);

    // Registrar el préstamo
    $query = "INSERT INTO prestamos (id_libro, id_persona, fecha_prestamo)
              VALUES ($id_libro, $id_persona, '$fecha_prestamo')";

    if (mysqli_query($con, $query)) {
        // Cambiar estado del libro a "prestado"
        mysqli_query($con, "UPDATE libros_d SET estado = 'prestado' WHERE id_libro = $id_libro");
        $mensaje = "✅ Préstamo registrado correctamente.";
    } else {
        $mensaje = "❌ Error al registrar el préstamo: " . mysqli_error($con);
    }
}

// Obtener libros disponibles
$libros = mysqli_query($con, "SELECT id_libro, Titulo FROM libros_d WHERE formato = 'impreso' AND estado = 'disponible'");

// Obtener personas
$personas = mysqli_query($con, "SELECT id, nombre, apellido FROM personas");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Préstamo</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body { padding: 20px; background-color: #f7f7f7; font-family: Arial, sans-serif; }
        .form-container { background-color: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        h2 { margin-bottom: 20px; text-align: center; }
        .btn-group { margin-top: 15px; }
        .back-link { display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Registrar Préstamo</h2>

    <?php if ($mensaje): ?>
        <div class="alert <?= $alertClass ?>"><?= $mensaje ?></div>
    <?php endif; ?>

    <?php if (mysqli_num_rows($libros) > 0 && mysqli_num_rows($personas) > 0): ?>
        <form method="POST" class="form-horizontal">

            <div class="form-group">
                <label class="control-label col-sm-3">Libro:</label>
                <div class="col-sm-9">
                    <select name="id_libro" class="form-control" required>
                        <?php while ($l = mysqli_fetch_assoc($libros)): ?>
                            <option value="<?= $l['id_libro'] ?>"><?= htmlspecialchars($l['Titulo']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Persona:</label>
                <div class="col-sm-9">
                    <select name="id_persona" class="form-control" required>
                        <?php while ($p = mysqli_fetch_assoc($personas)): ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']." ".$p['apellido']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-3">Fecha de préstamo:</label>
                <div class="col-sm-9">
                    <input type="date" name="fecha_prestamo" class="form-control" required>
                </div>
            </div>

            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Registrar Préstamo</button>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">No hay libros disponibles o personas registradas.</div>
    <?php endif; ?>

    <a href="libros_imp.php" class="btn btn-default">&larr; Volver a Libros Impresos</a>
    <a href="index.php" class="btn btn-default">Ir al Menú Principal</a>
</div>

</body>
</html>