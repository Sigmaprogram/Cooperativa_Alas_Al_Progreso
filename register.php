<?php
// register.php

// Conexión a la base de datos
$host = "localhost";
$dbname = "cooperativa";
$username = "root"; // Usuario de MySQL
$password = ""; // Contraseña de MySQL

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Procesar el formulario de registro
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Insertar el usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre, username, password) VALUES (:nombre, :username, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);


try {

} catch (PDO){

};
    if ($stmt->execute()) {
        $success = "Registro exitoso.";
    } else {
        $error = "Error al registrar el usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="./css/styles.css">
    <script>
        // Mostrar errores o mensajes de éxito con alerts
        window.onload = function() {
            <?php if (!empty($error)): ?>
                alert("<?php echo $error; ?>");
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                alert("<?php echo $success; ?>");
                
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <h1>Registro</h1>
    <form method="POST" action="">
        <label for="nombre">Nombre completo:</label>
        <input type="text" name="nombre" required><br><br>
        <label for="username">Nombre de usuario:</label>
        <input type="text" name="username" required><br><br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br><br>
        <button style="width: 100%;" type="submit">Registrarse</button>
        <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión</a></p>
    </form>
   
</body>
</html>