<?php
include '../auth/conexion.php';

$doctores = $conexion->query("
    SELECT p.dni AS dni_doctor, p.nombre_completo, e.id_especialidad, e.nombre AS especialidad, e.costo_base
    FROM doctor d
    JOIN persona p ON p.dni = d.dni
    JOIN doctor_especialidad de ON de.dni_doctor = d.dni
    JOIN especialidad e ON e.id_especialidad = de.id_especialidad
    ORDER BY p.nombre_completo
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            background-color: #333
        }
    </style>
</head>
<body>

    <main>
        <form action="registrarTurno.php" method="POST">
            <label>Dni del paciente</label>
            <input type="text" name="dniPaciente" placeholder="XX.XXX.XXX" required>
            <label>Fecha</label>
            <input type="date" name="fecha" required>
            <label>Hora</label>
            <input type="time" name="hora" required>
            <label>Seleccione el doctor a cargo</label>
            <select name="doctor">
                <option value="0">Selecciona un doctor</option>
                <?php
                    while ($fila = $doctores->fetch_assoc()) {
                        $valor = $fila['dni_doctor'] . "-" . $fila['id_especialidad'];
                        echo "<option value='{$valor}'>{$fila['nombre_completo']} - {$fila['especialidad']} (\${$fila['costo_base']})</option>";
                    }
                ?>
            </select>
            <button value="submit">Enviar</button>
        </form>
    </main>
</body>
</html>