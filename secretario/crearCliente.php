<?php
include '../auth/conexion.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $dni = $_POST['dni'];
    $nombreCompleto = $_POST['nombreCompleto'];
    $email = $_POST['email'];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $tel = $_POST['tel'];
    $fechaNacimiento = $_POST['fNacimiento'];
    $idPlan = $_POST['plan'] === "" ? "NULL" : (int)$_POST['plan'];

    
    $conexion->query("INSERT INTO paciente (dni, fecha_nacimiento, id_plan) VALUES ($dni, '$fecha_nacimiento', $idPlan)");
    $conexion->query("INSERT INTO persona (dni, nombre_completo, email, password, telefono, id_rol) VALUES ($dni, '$nombreCompleto', '$email', '$password', $tel, $idRol)");

}
?>