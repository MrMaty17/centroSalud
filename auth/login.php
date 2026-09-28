<?php
session_start();
require "db.php";
 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
 
    $stmt = $conn->prepare("SELECT dni, nombre_completo, password, id_rol FROM persona WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
 
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
 
        if (password_verify($password, $row["password"])) {
            $_SESSION["dni"] = $row["dni"];
            $_SESSION["nombre_completo"] = $row["nombre_completo"];
            $_SESSION["id_rol"] = $row["id_rol"];
 
            header("Location: index.php");
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "No existe un usuario con ese email";
    }
}
?>