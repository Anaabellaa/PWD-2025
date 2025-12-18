<?php
include_once("libreria/motor.php");

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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Préstamos Activos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body { background-color: #f7f7f7; padding: 20px; font-family: Arial, sans-serif; }
        .container { max-width: 900px; margin: auto; background-color: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        .table { margin-bottom: 20px; }
        .btn-group { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Préstamos Activos</h2>

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
                    <td><?= htmlspecialchars($row['nombre'] . " " . $row['apellido']) ?></td>
                    <td><?= htmlspecialchars($row['fecha_prestamo']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">No hay préstamos activos actualmente.</div>
    <?php endif; ?>

    <div class="btn-group">
        <a href="libros_imp.php" class="btn btn-primary">Ir a Libros Impresos</a>
        <a href="registrar_devolucion.php" class="btn btn-primary">Registrar devolución</a>
        <a href="index.php" class="btn btn-default">Ir al Menú Principal</a>
    </div>
</div>

</body>
</html>