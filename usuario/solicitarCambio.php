<?php
session_start();
include '../auth/conexion.php';

if (!isset($_SESSION['dni']) || $_SESSION['id_rol'] != 5) {
    die("Acceso no autorizado");
}

$dniSesion = $_SESSION['dni'];
$idTurno = (int)$_GET['id_turno'];

$turno = $conexion->query("
    SELECT t.id_turno, t.dni_doctor, t.fecha, t.hora, pd.nombre_completo AS doctor, e.nombre AS especialidad,
           DATEDIFF(t.fecha, CURDATE()) AS dias_para_turno
    FROM turno t
    JOIN persona pd ON pd.dni = t.dni_doctor
    JOIN especialidad e ON e.id_especialidad = t.id_especialidad
    WHERE t.id_turno = $idTurno AND t.dni_paciente = $dniSesion
")->fetch_assoc();

if (!$turno) {
    die("Turno no encontrado");
}

if ($turno['dias_para_turno'] < 1) {
    die("Ya no se puede solicitar un cambio para este turno (falta menos de un día).");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fechaNueva = $_POST['fecha_nueva'];
    $horaNueva = $_POST['hora_nueva'];

    $conexion->query("
        INSERT INTO solicitud_cambio (id_turno, fecha_nueva, hora_nueva, estado)
        VALUES ($idTurno, '$fechaNueva', '$horaNueva', 'pendiente')
    ");

    echo "Solicitud enviada. Un secretario la va a confirmar.";
    exit();
}

$fechaNueva = isset($_GET['fecha_nueva']) ? $_GET['fecha_nueva'] : "";

if ($fechaNueva === "") {
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar cambio de turno</title>
</head>
<body>
    <h2>Turno actual: <?php echo "{$turno['fecha']} {$turno['hora']} - {$turno['doctor']} ({$turno['especialidad']})"; ?></h2>

    <form method="GET" action="">
        <input type="hidden" name="id_turno" value="<?php echo $idTurno; ?>">
        <label>Elegí la nueva fecha</label>
        <input type="date" name="fecha_nueva" required>
        <button type="submit">Ver horarios disponibles</button>
    </form>
</body>
</html>
<?php
    exit();
}

$horariosPosibles = [];
for ($h = 8; $h < 18; $h++) {
    $horariosPosibles[] = sprintf("%02d:00:00", $h);
    $horariosPosibles[] = sprintf("%02d:30:00", $h);
}

$ocupados = [];
$resultOcupados = $conexion->query("
    SELECT hora FROM turno
    WHERE dni_doctor = {$turno['dni_doctor']}
    AND fecha = '$fechaNueva'
    AND estado != 'cancelado'
");
while ($fila = $resultOcupados->fetch_assoc()) {
    $ocupados[] = $fila['hora'];
}

$disponibles = array_diff($horariosPosibles, $ocupados);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar cambio de turno</title>
</head>
<body>
    <h2>Turno actual: <?php echo "{$turno['fecha']} {$turno['hora']} - {$turno['doctor']} ({$turno['especialidad']})"; ?></h2>
    <p>Nueva fecha elegida: <?php echo $fechaNueva; ?></p>

    <?php if (count($disponibles) === 0) : ?>
        <p>No hay horarios disponibles para ese día. Probá con otra fecha.</p>
    <?php else : ?>
        <form method="POST" action="">
            <input type="hidden" name="fecha_nueva" value="<?php echo $fechaNueva; ?>">
            <label>Elegí un horario disponible</label>
            <select name="hora_nueva" required>
                <?php foreach ($disponibles as $hora) : ?>
                    <option value="<?php echo $hora; ?>"><?php echo substr($hora, 0, 5); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Confirmar solicitud de cambio</button>
        </form>
    <?php endif; ?>
</body>
</html>