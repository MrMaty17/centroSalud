<?php
include '../auth/conexion.php';
session_start();


$sql = "
    SELECT t.id_turno, t.fecha, t.hora, t.estado, t.costo_final,
           pd.nombre_completo AS doctor,
           pp.nombre_completo AS paciente, t.dni_paciente,
           e.nombre AS especialidad
    FROM turno t
    JOIN persona pd ON pd.dni = t.dni_doctor
    JOIN persona pp ON pp.dni = t.dni_paciente
    JOIN especialidad e ON e.id_especialidad = t.id_especialidad
";

$dniDoctor = isset($_GET["doctor"]) ? $_GET["doctor"] : "";

if ($dniDoctor !== "") {
    $sql .= " WHERE t.dni_doctor = '$dniDoctor' ORDER BY t.fecha, t.hora";
    $turnos = $conexion->query($sql);
} else {
    $sql .= " ORDER BY t.fecha, t.hora";
    $turnos = $conexion->query($sql);
}

$doctores = $conexion->query("SELECT p.dni, p.nombre_completo FROM doctor d JOIN persona p ON p.dni = d.dni ORDER BY p.nombre_completo");

$conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form method="GET">
        <label>Filtrar por doctor</label>
        <select name="doctor" onchange="this.form.submit()">
        <option value="">Todos los doctores</option>
            <?php
                while ($d = $doctores->fetch_assoc()) {
                    $sel = ($dniDoctor == $d['dni']) ? "selected" : "";
                    echo "<option value='{$d['dni']}' {$sel}>{$d['nombre_completo']}</option>";
                    }
                ?>
            </select>
    </form>


     <table border="1">
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
    </table>
</body>
</html>