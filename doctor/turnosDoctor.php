<?php
session_start();
include '../auth/conexion.php';
 
if (!isset($_SESSION['dni']) || $_SESSION['id_rol'] != 3) {
    die("Acceso no autorizado");
    header("location: ../index");
}
 
$dniDoctor = $_SESSION['dni'];
$mensaje = "";
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idTurno = (int)$_POST['id_turno'];
    $accion = $_POST['accion'];
 
    
    $turno = $conexion->query("
        SELECT id_turno, DATEDIFF(fecha, CURDATE()) AS dias
        FROM turno
        WHERE id_turno = $idTurno AND dni_doctor = $dniDoctor AND estado IN ('pendiente', 'confirmado')
    ")->fetch_assoc();
 
    if (!$turno) {
        $mensaje = "Turno no encontrado o ya cerrado";
 
    } elseif ($accion === 'realizar' && $turno['dias'] <= 0) {
        $diagnostico = $conexion->real_escape_string($_POST['diagnostico']);
        $observaciones = $conexion->real_escape_string($_POST['observaciones']);
 
        $conexion->query("INSERT INTO atencion (id_turno, diagnostico, observaciones) VALUES ($idTurno, '$diagnostico', '$observaciones')");
        $conexion->query("UPDATE turno SET estado = 'realizado' WHERE id_turno = $idTurno");
        $mensaje = "Turno marcado como realizado";
 
    } elseif ($accion === 'ausente' && $turno['dias'] <= 0) {
        $conexion->query("UPDATE turno SET estado = 'ausente' WHERE id_turno = $idTurno");
        $mensaje = "Turno marcado como ausente";
 
    } elseif ($accion === 'cambiar') {
        $fechaNueva = $conexion->real_escape_string($_POST['fecha_nueva']);
        $horaNueva = $conexion->real_escape_string($_POST['hora_nueva']);
 
        $choca = $conexion->query("
            SELECT id_turno FROM turno
            WHERE dni_doctor = $dniDoctor AND fecha = '$fechaNueva' AND hora = '$horaNueva'
            AND estado != 'cancelado' AND id_turno != $idTurno
        ");
 
        if ($choca->num_rows > 0) {
            $mensaje = "Ya tenés un turno en ese horario";
        } else {
            $conexion->query("UPDATE turno SET fecha = '$fechaNueva', hora = '$horaNueva' WHERE id_turno = $idTurno");
            $mensaje = "Turno reprogramado";
        }
 
    } else {
        $mensaje = "Acción no permitida";
    }
}
 
$turnos = $conexion->query("
    SELECT t.id_turno, t.fecha, t.hora, t.estado, pp.nombre_completo AS paciente, e.nombre AS especialidad,
           DATEDIFF(t.fecha, CURDATE()) AS dias
    FROM turno t
    JOIN persona pp ON pp.dni = t.dni_paciente
    JOIN especialidad e ON e.id_especialidad = t.id_especialidad
    WHERE t.dni_doctor = $dniDoctor AND t.estado IN ('pendiente', 'confirmado')
    ORDER BY t.fecha, t.hora
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis turnos</title>
</head>
<body>
    <h2>Mis turnos</h2>
 
    <?php if ($mensaje !== "") echo "<p>$mensaje</p>"; ?>
 
    <?php if ($turnos->num_rows === 0) : ?>
        <p>No tenés turnos pendientes</p>
    <?php else : ?>
        <?php
            $fechaActual = "";
            while ($t = $turnos->fetch_assoc()) {
                // para dividir las tablas por fechas
                if ($t['fecha'] !== $fechaActual) {
                    if ($fechaActual !== "") echo "</table>";
                    echo "<h3>{$t['fecha']}</h3>
                    <table border='1'>
                        <tr>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Especialidad</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>";
                    $fechaActual = $t['fecha'];
                }
 
                echo "<tr>
                    <td>{$t['hora']}</td>
                    <td>{$t['paciente']}</td>
                    <td>{$t['especialidad']}</td>
                    <td>{$t['estado']}</td>
                    <td>
                        <form method='POST' action=''>
                            <input type='hidden' name='id_turno' value='{$t['id_turno']}'>
                            <input type='hidden' name='accion' value='cambiar'>
                            <input type='date' name='fecha_nueva' required>
                            <input type='time' name='hora_nueva' required>
                            <button type='submit'>Cambiar fecha/hora</button>
                        </form>";

                if ($t['dias'] <= 0) {
                    echo "<form method='POST' action=''>
                            <input type='hidden' name='id_turno' value='{$t['id_turno']}'>
                            <input type='hidden' name='accion' value='realizar'>
                            <textarea name='diagnostico' placeholder='Diagnóstico' required></textarea>
                            <textarea name='observaciones' placeholder='Observaciones'></textarea>
                            <button type='submit'>Marcar como realizado</button>
                        </form>
                        <form method='POST' action=''>
                            <input type='hidden' name='id_turno' value='{$t['id_turno']}'>
                            <input type='hidden' name='accion' value='ausente'>
                            <button type='submit'>Paciente ausente</button>
                        </form>";
                }
 
                echo "</td></tr>";
            }
            echo "</table>";
        ?>
    <?php endif; ?>
</body>
</html>