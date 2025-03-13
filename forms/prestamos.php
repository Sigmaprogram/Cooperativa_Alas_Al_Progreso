<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cooperativa";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que todos los campos estén presentes
    if (isset($_POST['cedula_cliente'], $_POST['monto_prestamo'], $_POST['tasa_interes'], $_POST['fecha_inicio'], $_POST['fecha_fin'])) {
        $cedula_cliente = $_POST['cedula_cliente'];
        $monto_prestamo = $_POST['monto_prestamo'];
        $tasa_interes = $_POST['tasa_interes'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'];

        // Buscar el ID del cliente basado en la cédula
        $sql_check = "SELECT id FROM clientes WHERE cedula = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $cedula_cliente);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            // El cliente existe, obtener su ID
            $stmt_check->bind_result($cliente_id);
            $stmt_check->fetch();

            // Insertar el préstamo
            $sql = "INSERT INTO prestamos (cliente_id, monto_prestamo, tasa_interes, fecha_inicio, fecha_fin)
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iddss", $cliente_id, $monto_prestamo, $tasa_interes, $fecha_inicio, $fecha_fin);

            if ($stmt->execute()) {
                // Redirigir a una ruta específica después de registrar el préstamo
                header("Location: ../views/viewPrestamo.php"); // Cambia "exito.php" por la ruta que desees
                exit();
            } else {
                echo "<script>alert('Error al registrar el préstamo: " . addslashes($stmt->error) . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error: No se encontró un cliente con la cédula $cedula_cliente.');</script>";
        }

        $stmt_check->close();
    } else {
        echo "<script>alert('Error: Todos los campos del formulario son requeridos.');</script>";
    }
} else {
    
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Préstamos</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<section class="butonsection">
        <div>
            <a href="../main.php" class="btn-vintage">Volver</a>
        </div>
    </section>
    <h1>Registrar Nuevo Préstamo</h1>
    <form action="prestamos.php" method="post">
        <label for="cedula_cliente">Cédula del Cliente:</label>
        <input type="text" id="cedula_cliente" name="cedula_cliente" required><br><br>

        <label for="monto_prestamo">Monto del Préstamo:</label>
        <input type="number" step="0.01" id="monto_prestamo" name="monto_prestamo" required><br><br>

        <label for="tasa_interes">Tasa de Interés (%):</label>
        <input type="number" step="0.01" id="tasa_interes" name="tasa_interes" required><br><br>

        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" required><br><br>

        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin" required><br><br>
            <button type="submit" name="">Registrar Pago</button>
    </form>
</body>
</html>