<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="register.php">
    DNI: <input type="number" name="dni" required><br>
    Nombre completo: <input type="text" name="nombre_completo" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Teléfono: <input type="text" name="telefono"><br>
    Fecha de nacimiento: <input type="date" name="fecha_nacimiento" required><br>
    <button type="submit">Registrarme</button>
</form>
</body>
</html>