<?php
session_start();
include '../auth/conexion.php';

// Solo secretario (4), supervisor (2) o administrador (1)
if (!isset($_SESSION["id_rol"]) || !in_array($_SESSION["id_rol"], [1, 2, 4])) {
    die("No tenés permiso para ver esta página");
}

$turnos = $conexion->query("
    SELECT t.id_turno, t.fecha, t.hora, t.estado, t.costo_final,
           pd.nombre_completo AS doctor,
           pp.nombre_completo AS paciente, t.dni_paciente,
           e.nombre AS especialidad
    FROM turno t
    JOIN persona pd ON pd.dni = t.dni_doctor
    JOIN persona pp ON pp.dni = t.dni_paciente
    JOIN especialidad e ON e.id_especialidad = t.id_especialidad
    ORDER BY t.fecha, t.hora
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Turnos</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
</head>
<body>
    <h2>Turnos</h2>

    <table id="tabla" border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Paciente</th>
                <th>DNI paciente</th>
                <th>Estado</th>
                <th>Costo</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while ($t = $turnos->fetch_assoc()) {
                    echo "<tr>
                        <td>{$t['id_turno']}</td>
                        <td>{$t['fecha']}</td>
                        <td>{$t['hora']}</td>
                        <td>{$t['doctor']}</td>
                        <td>{$t['especialidad']}</td>
                        <td>{$t['paciente']}</td>
                        <td>{$t['dni_paciente']}</td>
                        <td>{$t['estado']}</td>
                        <td>\${$t['costo_final']}</td>
                    </tr>";
                }
            ?>
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
        new DataTable('#tabla', {
            language: { url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json' },
            order: [[1, 'asc'], [2, 'asc']]
        });
    </script>
</body>
</html>