<?php
include '../auth/conexion.php';

$dni = (int)$_GET['dni'];

$paciente = $conexion->query("SELECT pe.nombre_completo FROM persona pe JOIN paciente pa ON pa.dni = pe.dni WHERE pe.dni = $dni")->fetch_assoc();

$historial = $conexion->query("
    SELECT t.fecha, t.hora, pd.nombre_completo AS doctor, e.nombre AS especialidad,
           a.diagnostico, a.observaciones, a.archivo_pdf
    FROM turno t
    JOIN atencion a ON a.id_turno = t.id_turno
    JOIN persona pd ON pd.dni = t.dni_doctor
    JOIN especialidad e ON e.id_especialidad = t.id_especialidad
    WHERE t.dni_paciente = $dni
    ORDER BY t.fecha DESC, t.hora DESC
");

$pendientes = $conexion->query("
    SELECT t.fecha, t.hora, pd.nombre_completo AS doctor, e.nombre AS especialidad, t.estado, t.costo_final
    FROM turno t
    JOIN persona pd ON pd.dni = t.dni_doctor
    JOIN especialidad e ON e.id_especialidad = t.id_especialidad
    WHERE t.dni_paciente = $dni AND t.estado IN ('pendiente', 'confirmado')
    ORDER BY t.fecha, t.hora
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
    <h2>Paciente: <?php echo $paciente['nombre_completo']; ?> (DNI <?php echo $dni; ?>)</h2>

    <h3>Historial médico</h3>
    <table border="1">
        <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Doctor</th>
            <th>Especialidad</th>
            <th>Diagnóstico</th>
            <th>Observaciones</th>
            <th>Estudio</th>
        </tr>
        <?php
            while ($h = $historial->fetch_assoc()) {
                $pdf = $h['archivo_pdf'] ? "<a href='../{$h['archivo_pdf']}'>Descargar PDF</a>" : "-";
                echo "<tr>
                    <td>{$h['fecha']}</td>
                    <td>{$h['hora']}</td>
                    <td>{$h['doctor']}</td>
                    <td>{$h['especialidad']}</td>
                    <td>{$h['diagnostico']}</td>
                    <td>{$h['observaciones']}</td>
                    <td>{$pdf}</td>
                </tr>";
            }
        ?>
    </table>

    <?php if ($pendientes->num_rows === 0) :?>
        <h3>No hay turnos pendientes</h3>
    <?php else : ?>
        <h3>Turnos pendientes</h3>
        <table border="1">
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Estado</th>
                <th>Costo</th>
            </tr>
            <?php
                while ($t = $pendientes->fetch_assoc()) {
                    echo "<tr>
                        <td>{$t['fecha']}</td>
                        <td>{$t['hora']}</td>
                        <td>{$t['doctor']}</td>
                        <td>{$t['especialidad']}</td>
                        <td>{$t['estado']}</td>
                        <td>\${$t['costo_final']}</td>
                    </tr>";
                }
            ?>
        </table>
    <?php endif; ?>
</body>
</html>