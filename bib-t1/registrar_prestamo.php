<?php
include_once("libreria/motor.php");
include("menu_bs.php");

$mensaje = "";
$alertClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_libro = intval($_POST['id_libro']);
    $id_persona = intval($_POST['id_persona']);
    $fecha_prestamo = mysqli_real_escape_string($con, $_POST['fecha_prestamo']);

    $query = "INSERT INTO prestamos (id_libro, id_persona, fecha_prestamo)
              VALUES ($id_libro, $id_persona, '$fecha_prestamo')";

    if (mysqli_query($con, $query)) {
        mysqli_query($con, "UPDATE libros_d SET estado = 'prestado' WHERE id_libro = $id_libro");
        $mensaje = "Préstamo registrado correctamente.";
        $alertClass = "alert-success";
    } else {
        $mensaje = "Error al registrar el préstamo: " . mysqli_error($con);
        $alertClass = "alert-danger";
    }
}

$libros = mysqli_query($con, "
    SELECT id_libro, Titulo 
    FROM libros_d 
    WHERE formato = 'impreso' AND estado = 'disponible'
");

$personas = mysqli_query($con, "
    SELECT id, nombre, apellido 
    FROM personas
");
?>

<div class="container-fluid">

    <!-- CONTENIDO -->
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">

            <h3>Registrar Préstamo</h3>

            <?php if ($mensaje): ?>
                <div class="alert <?= $alertClass ?>">
                    <?= $mensaje ?>
                </div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($libros) > 0 && mysqli_num_rows($personas) > 0): ?>

            <form method="POST" class="form-horizontal">

                <div class="form-group">
                    <label class="control-label col-sm-4">Libro</label>
                    <div class="col-sm-8">
                        <select name="id_libro" class="form-control" required>
                            <?php while ($l = mysqli_fetch_assoc($libros)): ?>
                                <option value="<?= $l['id_libro'] ?>">
                                    <?= htmlspecialchars($l['Titulo']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Persona</label>
                    <div class="col-sm-8">
                        <select name="id_persona" class="form-control" required>
                            <?php while ($p = mysqli_fetch_assoc($personas)): ?>
                                <option value="<?= $p['id'] ?>">
                                    <?= htmlspecialchars($p['nombre']." ".$p['apellido']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-4">Fecha de préstamo</label>
                    <div class="col-sm-8">
                        <input type="date" name="fecha_prestamo" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                        <button type="submit" class="btn btn-primary">
                            Registrar préstamo
                        </button>
                    </div>
                </div>

            </form>

            <?php else: ?>
                <div class="alert alert-warning">
                    No hay libros disponibles o personas registradas.
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
