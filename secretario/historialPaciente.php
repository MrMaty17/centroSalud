<?php
session_start();
include '../auth/conexion.php';

// Solo administrador (1) y supervisor (2)
if (!isset($_SESSION['id_rol']) || !in_array($_SESSION['id_rol'], [1, 2])) {
    die("No tenés permiso para ver esta página");
}

$atenciones = $conexion->query("
    SELECT a.id_atencion, a.fecha_atencion, a.diagnostico, a.observaciones, a.archivo_pdf,
           pp.dni AS dni_paciente, pp.nombre_completo AS paciente,
           pd.nombre_completo AS doctor, e.nombre AS especialidad
    FROM atencion a
    JOIN turno t ON t.id_turno = a.id_turno
    JOIN persona pp ON pp.dni = t.dni_paciente
    JOIN persona pd ON pd.dni = t.dni_doctor
    JOIN especialidad e ON e.id_especialidad = t.id_especialidad
    ORDER BY a.fecha_atencion DESC
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Atenciones</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
</head>
<body>
    <h2>Atenciones registradas</h2>

    <table id="tabla" border="1">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Paciente</th>
                <th>DNI</th>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Diagnóstico</th>
                <th>Observaciones</th>
                <th>Estudio</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while ($a = $atenciones->fetch_assoc()) {
                    $pdf = $a['archivo_pdf'] ? "<a href='../{$a['archivo_pdf']}'>Descargar PDF</a>" : "-";
                    echo "<tr>
                        <td>{$a['fecha_atencion']}</td>
                        <td>{$a['paciente']}</td>
                        <td>{$a['dni_paciente']}</td>
                        <td>{$a['doctor']}</td>
                        <td>{$a['especialidad']}</td>
                        <td>{$a['diagnostico']}</td>
                        <td>{$a['observaciones']}</td>
                        <td>{$pdf}</td>
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
            order: [[0, 'desc']],
            columnDefs: [{ targets: -1, orderable: false, searchable: false }]
        });
    </script>
</body>
</html>