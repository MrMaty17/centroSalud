<?php
session_start();
include '../auth/conexion.php';

if (!isset($_SESSION["id_rol"]) || !in_array($_SESSION["id_rol"], [1, 2, 4])) {
    die("No tenés permiso para ver esta página");
}


$solicitudes = $conexion->query("
    SELECT sc.id_solicitud, sc.fecha_nueva, sc.hora_nueva, sc.fecha_solicitud,
           t.fecha AS fecha_actual, t.hora AS hora_actual,
           pp.nombre_completo AS paciente, pd.nombre_completo AS doctor, e.nombre AS especialidad
    FROM solicitud_cambio sc
    JOIN turno t ON t.id_turno = sc.id_turno
    JOIN persona pp ON pp.dni = t.dni_paciente
    JOIN persona pd ON pd.dni = t.dni_doctor
    JOIN especialidad e ON e.id_especialidad = t.id_especialidad
    WHERE sc.estado = 'pendiente'
    ORDER BY sc.fecha_solicitud
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitudes de cambio de turno</title>
</head>
<body>
    <h2>Solicitudes de cambio pendientes</h2>

    <?php if ($solicitudes->num_rows === 0) : ?>
        <p>No hay solicitudes pendientes</p>
    <?php else : ?>
        <table border="1">
            <tr>
                <th>Paciente</th>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Turno actual</th>
                <th>Turno solicitado</th>
                <th>Acciones</th>
            </tr>
            <?php
                while ($s = $solicitudes->fetch_assoc()) {
                    echo "<tr>
                        <td>{$s['paciente']}</td>
                        <td>{$s['doctor']}</td>
                        <td>{$s['especialidad']}</td>
                        <td>{$s['fecha_actual']} {$s['hora_actual']}</td>
                        <td>{$s['fecha_nueva']} {$s['hora_nueva']}</td>
                        <td>
                            <form method='POST' action='guardarCambio.php' style='display:inline'>
                                <input type='hidden' name='id_solicitud' value='{$s['id_solicitud']}'>
                                <input type='hidden' name='accion' value='aprobar'>
                                <button type='submit'>Aprobar</button>
                            </form>
                            <form method='POST' action='guardarCambio.php' style='display:inline'>
                                <input type='hidden' name='id_solicitud' value='{$s['id_solicitud']}'>
                                <input type='hidden' name='accion' value='rechazar'>
                                <button type='submit'>Rechazar</button>
                            </form>
                        </td>
                    </tr>";
                }
            ?>
        </table>
    <?php endif; ?>
</body>
</html>