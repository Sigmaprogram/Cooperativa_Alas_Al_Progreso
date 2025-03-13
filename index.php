<?php
// login.php

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

// Procesar el formulario de inicio de sesión
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Buscar el usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Inicio de sesión exitoso
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'];
        header("Location: ./main.php"); // Redirigir
        exit();
    } else {
        $error = "Nombre de usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <h1>Iniciar Sesión</h1>
    <form method="POST" action="">
        <label for="username">Nombre de usuario:</label>
        <input type="text" name="username" required><br><br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br><br>
        <button style="width: 100%;" type="submit">Iniciar Sesión</button>
        <p>¿No tienes una cuenta? <a href="register.php">Regístrate</a></p>
    </form>

    <script>
        // Mostrar errores con alerts
        window.onload = function() {
            <?php if (!empty($error)): ?>
                alert("<?php echo $error; ?>");
            <?php endif; ?>
        };
    </script>
</body>
</html>
