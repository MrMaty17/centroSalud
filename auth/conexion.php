<?php

$conexion = new mysqli('localhost', 'root', 'Abhsa367?1!sgj', 'centrosalud'); #cambien la contraseña onda dejenla vacia ''
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}
$conexion->set_charset('utf8mb4');