<?php
include '../auth/conexion.php';
if($_SERVER['REQUEST_METHOD'] == "POST") {
    $dniPaciente = $_POST['dniPaciente'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    list($dniDoctor, $idEspecialidad) = explode("-", $_POST["doctor"]);

    $obSo = $conexion->query("SELECT costo_base FROM especialidad WHERE id_especialidad = '$idEspecialidad'");
    $costoBase = $obSo->fetch_assoc()["costo_base"];

    $resultPlan = $conexion->query("SELECT pl.porcentaje_cobertura FROM paciente pa JOIN plan pl ON pl.id_plan = pa.id_plan WHERE pa.dni = '$dniPaciente'");
    $costoFinal = $costoBase;

    if($resultPlan->num_rows === 1) {
        $cobertura = $resultPlan->fetch_assoc()["porcentaje_cobertura"];
        $costoFinal = $costoBase - ($costoBase * $cobertura / 100);
    }

    $conexion->query("INSERT INTO turno (dni_paciente, dni_doctor, id_especialidad, fecha, hora, estado, costo_final) VALUES ($dniPaciente, $dniDoctor, $idEspecialidad, '$fecha', '$hora', 'pendiente', $costoFinal)");
}

header('Location: index.php');

?>