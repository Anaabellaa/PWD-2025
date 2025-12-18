<?php
include_once("libreria/motor.php");
include("menu_bs.php");

// Mensaje de resultado
$mensaje = "";

// Procesar devolución
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_prestamo = intval($_POST['id_prestamo']);
    $fecha_devolucion = mysqli_real_escape_string($con, $_POST['fecha_devolucion']);

    $update = "UPDATE prestamos 
               SET fecha_devolucion = '$fecha_devolucion' 
               WHERE id_prestamo = $id_prestamo";

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
        $mensaje = "❌ Error al registrar devolución.";
    }
}

// Obtener préstamos sin devolver
$query = "
    SELECT p.id_prestamo, l.Titulo, per.nombre, per.apellido, p.fecha_prestamo
    FROM prestamos p
    JOIN libros_d l ON p.id_libro = l.id_libro
    JOIN personas per ON p.id_persona = per.id
    WHERE p.fecha_devolucion IS NULL
";

$result = mysqli_query($con, $query);
?>

<div class="container-fluid">

    <!-- CONTENIDO -->
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <h3>Registrar devolución</h3>

            <?php if ($mensaje): ?>
                <div class="alert alert-info"><?= $mensaje ?></div>
            <?php endif; ?>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <form method="POST" class="form-horizontal">

                    <div class="form-group">
                        <label class="control-label col-sm-4">Préstamo:</label>
                        <div class="col-sm-8">
                            <select name="id_prestamo" class="form-control" required>
                                <?php while ($row = mysqli_fetch_assoc($result)):
                                    $label = $row['Titulo']." - ".$row['nombre']." ".$row['apellido']." (".$row['fecha_prestamo'].")";
                                ?>
                                    <option value="<?= $row['id_prestamo'] ?>">
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-4">Fecha devolución:</label>
                        <div class="col-sm-8">
                            <input type="date" name="fecha_devolucion" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary">
                            Registrar devolución
                        </button>
                    </div>

                </form>
            <?php else: ?>
                <div class="alert alert-warning">
                    No hay préstamos pendientes de devolución.
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
