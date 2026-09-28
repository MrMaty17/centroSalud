<?php
include '../auth/conexion.php';

$busqueda = isset($_GET['buscar']) ? $conexion->real_escape_string(trim($_GET['buscar'])) : "";

$pacientes = $conexion->query("
    SELECT pe.dni, pe.nombre_completo, pa.fecha_nacimiento,
           COALESCE(CONCAT(os.nombre, ' - ', pl.nombre), 'Particular') AS plan,
           pe.telefono
    FROM paciente pa
    JOIN persona pe ON pe.dni = pa.dni
    LEFT JOIN plan pl ON pl.id_plan = pa.id_plan
    LEFT JOIN obra_social os ON os.id_obra_social = pl.id_obra_social
    WHERE pe.nombre_completo LIKE '%$busqueda%' OR pe.dni LIKE '%$busqueda%'
    ORDER BY pe.nombre_completo
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="GET" action="">
        <input type="text" name="buscar" placeholder="Buscar por DNI o nombre" value="<?php echo htmlspecialchars($busqueda); ?>">
        <button type="submit">Buscar</button>
    </form>

    <table border="1">
        <tr>
            <th>DNI</th>
            <th>Nombre completo</th>
            <th>Fecha de nacimiento</th>
            <th>Plan</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
        <?php
            while ($p = $pacientes->fetch_assoc()) {
                echo "<tr>
                    <td>{$p['dni']}</td>
                    <td>{$p['nombre_completo']}</td>
                    <td>{$p['fecha_nacimiento']}</td>
                    <td>{$p['plan']}</td>
                    <td>{$p['telefono']}</td>
                    <td>
                        <form method='GET' action='historialPaciente.php'>
                            <input type='hidden' name='dni' value='{$p['dni']}'>
                            <button type='submit'>Ver historial</button>
                        </form>
                    </td>
                </tr>";
            }
        ?>
    </table>
</body>
</html>