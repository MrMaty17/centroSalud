<?php
session_start();
require "db.php";
 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dni = $_POST["dni"];
    $nombre_completo = $_POST["nombre_completo"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $telefono = $_POST["telefono"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];
    $id_rol = 5; // paciente
 
    $stmt = $conn->prepare("INSERT INTO persona (dni, nombre_completo, email, password, telefono, id_rol) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $dni, $nombre_completo, $email, $password, $telefono, $id_rol);
 
    if ($stmt->execute()) {
        $stmt2 = $conn->prepare("INSERT INTO paciente (dni, fecha_nacimiento) VALUES (?, ?)");
        $stmt2->bind_param("is", $dni, $fecha_nacimiento);
        $stmt2->execute();
 
        header("Location: login.php");
        exit();
    } else {
        echo "Error al registrar: " . $stmt->error;
    }
}
?>