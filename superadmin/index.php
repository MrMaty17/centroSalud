<?php
session_start();
include '../auth/conexion.php';
 
if (!isset($_SESSION['id_rol']) || !in_array($_SESSION['id_rol'], [1, 2])) {
    die("No tenes permiso para ver esta pagina");
}


//valores para el form
$buscarForm = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";
$desdeForm = isset($_GET['desde']) ? $_GET['desde'] : "";
$hastaForm = isset($_GET['hasta']) ? $_GET['hasta'] : "";
$doctorFiltro = isset($_GET['doctor']) ? (int)$_GET['doctor'] : 0;


$condiciones = [];
if ($buscar !== "") {
    $condiciones[] = "(pp.nombre_completo LIKE '%$buscar%' OR pp.dni LIKE '%$buscar%')";
}
if ($doctorFiltro > 0) {
    $condiciones[] = "t.dni_doctor = $doctorFiltro";
}
if ($desde !== "") {
    $condiciones[] = "DATE(a.fecha_atencion) >= '$desde'";
}
if ($hasta !== "") {
    $condiciones[] = "DATE(a.fecha_atencion) <= '$hasta'";
}


$where = count($condiciones) > 0 ? "WHERE " . implode(" AND ", $condiciones) : "";

$atenciones = $conexion->query("
    SELECT a.id_atencion, a.fecha_atencion, a.diagnostico, a.observaciones, a.archivo_pdf,
           pp.dni AS dni_paciente, pp.nombre_completo AS paciente,
           pd.nombre_completo AS doctor, e.nombre AS especialidad
    FROM atencion a
    JOIN turno t ON t.id_turno = a.id_turno
    JOIN persona pp ON pp.dni = t.dni_paciente
    JOIN persona pd ON pd.dni = t.dni_doctor
    JOIN especialidad e ON e.id_especialidad = t.id_especialidad
    $where
    ORDER BY a.fecha_atencion DESC
");

$doctores = $conexion->query("SELECT p.dni, p.nombre_completo FROM doctor d JOIN persona p ON p.dni = d.dni ORDER BY p.nombre_completo");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="GET">
        <input type="text" name="buscar" placeholder="Paciente (por dni o nombre)" value="<?php echo htmlspecialchars($buscarForm); ?>">
        <select name="doctor" id="">
            <option value="0">Todos los doctores</option>
            <?php 
                while($d = $doctores->fetch_assoc()) {
                     $sel = ($doctorFiltro == $d['dni']) ? "selected" : "";
                    echo "<option value='{$d['dni']}' {$sel}>{$d['nombre_completo']}</option>";
                }
            ?>
        </select>

        <label>Desde</label>
        <input type="date" name="desde" value="<?php echo htmlspecialchars($desdeForm); ?>">
        <label>Hasta</label>
        <input type="date" name="hasta" value="<?php echo htmlspecialchars($hastaForm); ?>">
    </form>

    <?php if ($atenciones->num_rows === 0) : ?>
        <p>No hay atenciones para mostrar</p>
    <?php else : ?>
        <p>Total: <?php echo $atenciones->num_rows; ?> atenciones</p>
        <table border="1">
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
        </table>
    <?php endif; ?>
</body>
</html>