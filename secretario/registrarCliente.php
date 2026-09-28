<?php
include '../auth/conexion.php';

$planes = $conexion->query("SELECT pl.id_plan, os.nombre AS obra_social, pl.nombre AS plan, pl.porcentaje_cobertura FROM plan pl JOIN obra_social os ON os.id_obra_social = pl.id_obra_social ORDER BY os.nombre, pl.nombre");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main>
        <form action="crearCliente.php" method="POST">
            <label>DNI</label>
            <input type="text" name="dni" required>
            <label>nombre completo</label>
            <input type="text" name="nombreCompleto" required>
            <label>email</label>
            <input type="email" name="email" required>
            <label>contraseña</label>
            <input type="password" name="password" required>
            <label>telefono</label>
            <input type="number" name="tel" required>
            <label>fecha nacimiento</label>
            <input type="date" name="fNacimiento" required>
            <label>plan / obra social?</label>
            <select name="plan">
                <option value="">Particular (sin cobertura)</option>
                <?php
                    while ($fila = $planes->fetch_assoc()) {
                        echo "<option value='{$fila['id_plan']}'>{$fila['obra_social']} - {$fila['plan']} ({$fila['porcentaje_cobertura']}%)</option>";
                    }
                ?>
            </select>
            <button type="submit">Registrar</button>
        </form>
    </main>
</body>
</html>