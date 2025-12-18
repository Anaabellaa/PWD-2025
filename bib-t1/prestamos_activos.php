<?php
include_once("libreria/motor.php");
include("menu_bs.php");

// Traer solo préstamos NO devueltos
$query = "
    SELECT 
        p.id_prestamo,
        l.Titulo,
        per.nombre,
        per.apellido,
        p.fecha_prestamo
    FROM prestamos p
    JOIN libros_d l ON p.id_libro = l.id_libro
    JOIN personas per ON p.id_persona = per.id
    WHERE p.fecha_devolucion IS NULL
    ORDER BY p.fecha_prestamo DESC
";

$result = mysqli_query($con, $query);
?>

<div class="container-fluid">

    <!-- CONTENIDO -->
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">

            <h3>Préstamos activos</h3>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Libro</th>
                            <th>Persona</th>
                            <th>Fecha de préstamo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Titulo']) ?></td>
                            <td><?= htmlspecialchars($row['nombre']." ".$row['apellido']) ?></td>
                            <td><?= htmlspecialchars($row['fecha_prestamo']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">
                    No hay préstamos activos actualmente.
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>
