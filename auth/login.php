<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $conexion->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    $result = $conexion->query("SELECT dni, nombre_completo, password, id_rol FROM persona WHERE email = '$email'");

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {
            $_SESSION["dni"] = $row["dni"];
            $_SESSION["nombre_completo"] = $row["nombre_completo"];
            $_SESSION["id_rol"] = $row["id_rol"];

            switch ($row["id_rol"]) {
                case 1: // administrador
                case 2: // supervisor
                    header("Location: ../superadmin/index.php");
                    break;
                case 3: // doctor
                    header("Location: ../doctor/index.php");
                    break;
                case 4: // secretario
                    header("Location: ../secretario/index.php");
                    break;
                case 5: // paciente
                    header("Location: ../usuario/index.php");
                    break;
                default:
                    header("Location: ../index.php");
            }
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "No existe un usuario con ese email";
    }
}
?>