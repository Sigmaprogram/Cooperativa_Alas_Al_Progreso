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

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si las claves existen en $_POST antes de acceder a ellas
    $cedula_cliente = isset($_POST['cedula_cliente']) ? $_POST['cedula_cliente'] : null;
    $monto_pago = isset($_POST['monto_pago']) ? $_POST['monto_pago'] : null;
    $fecha_pago = isset($_POST['fecha_pago']) ? $_POST['fecha_pago'] : null;

    // Validar que los valores no sean nulos
    if ($cedula_cliente && $monto_pago && $fecha_pago) {
        // Iniciar una transacción
        $conn->begin_transaction();

        try {
            // Obtener el ID del préstamo basado en la cédula del cliente
            $stmt = $conn->prepare("SELECT p.id FROM prestamos p JOIN clientes c ON p.cliente_id = c.id WHERE c.cedula = ?");
            $stmt->bind_param("s", $cedula_cliente);
            $stmt->execute();
            $result = $stmt->get_result();
            $prestamo = $result->fetch_assoc();

            if (!$prestamo) {
                throw new Exception("No se encontró un préstamo para la cédula proporcionada.");
            }

            $prestamo_id = $prestamo['id'];

            // Insertar el pago en la tabla pagos
            $stmt = $conn->prepare("INSERT INTO pagos (prestamo_id, monto_pago, fecha_pago) VALUES (?, ?, ?)");
            $stmt->bind_param("ids", $prestamo_id, $monto_pago, $fecha_pago);

            if (!$stmt->execute()) {
                throw new Exception("Error al registrar el pago: " . $stmt->error);
            }

            // Actualizar el monto del préstamo en la tabla prestamos
            $stmt = $conn->prepare("UPDATE prestamos SET monto_prestamo = monto_prestamo - ? WHERE id = ?");
            $stmt->bind_param("di", $monto_pago, $prestamo_id);

            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar el monto del préstamo: " . $stmt->error);
            }

            // Commit de la transacción
            $conn->commit();

            // Redirigir a la tabla de préstamos
            header("Location: ../views/viewPrestamo.php");
            exit();
        } catch (Exception $e) {
            // Rollback en caso de error
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error: Todos los campos son requeridos.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/styles.css">
    <title>Realizar cobros</title>
</head>
<body>
<section class="butonsection">
        <div>
            <a href="../main.php" class="btn-vintage">Volver</a>
        </div>
    </section>
    <h1>Registrar cobros</h1>
    <form action="#" method="post">
        <label for="cedula_cliente">Cédula del Cliente:</label>
        <input type="text" id="cedula_cliente" name="cedula_cliente" required><br><br>

        <label for="monto_pago">Monto del Pago:</label>
        <input type="number" step="0.01" id="monto_pago" name="monto_pago" required><br><br>

        <label for="fecha_pago">Fecha del Pago:</label>
        <input type="date" id="fecha_pago" name="fecha_pago" required><br><br>

        <input type="submit" value="Registrar Pago">
    </form>
</body>
</html>