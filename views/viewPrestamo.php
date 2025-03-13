<?php
include '../includes/db.php';

// Lógica para eliminar un préstamo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_prestamo'])) {
    $id = intval($_POST['id']);

    if (is_numeric($id)) {
        // Preparar la consulta para eliminar el préstamo
        $stmt = $conn->prepare("DELETE FROM prestamos WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Préstamo eliminado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el préstamo: ' . $stmt->error]);
        }

        $stmt->close();
        exit; // Terminar la ejecución después de enviar la respuesta JSON
    } else {
        echo json_encode(['success' => false, 'message' => 'ID no válido.']);
        exit;
    }
}

// Obtener préstamos
$result = $conn->query("SELECT * FROM prestamos");
if (!$result) {
    die("Error al obtener los préstamos: " . $conn->error);
}
$prestamos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Préstamos</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <section class="butonsection">
        <div>
            <a href="../main.php" class="btn-vintage">Volver</a>
            <a href="../forms/prestamos.php" class="btn-vintage">Crear Préstamo</a>
        </div>
    </section>
    <h1>Lista de Préstamos</h1>
    <section class="tableContainer">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente ID</th>
                    <th>Monto Préstamo</th>
                    <th>Tasa de Interés</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestamos as $prestamo): ?>
                <tr id="fila-<?php echo $prestamo['id']; ?>">
                    <td><?php echo htmlspecialchars($prestamo['id']); ?></td>
                    <td><?php echo htmlspecialchars($prestamo['cliente_id']); ?></td>
                    <td><?php echo htmlspecialchars($prestamo['monto_prestamo']); ?></td>
                    <td><?php echo htmlspecialchars($prestamo['tasa_interes']); ?></td>
                    <td><?php echo htmlspecialchars($prestamo['fecha_inicio']); ?></td>
                    <td><?php echo htmlspecialchars($prestamo['fecha_fin']); ?></td>
                    <td><?php echo htmlspecialchars($prestamo['estado']); ?></td>
                    <td>
                        <a href="#" onclick="confirmarEliminacion(<?php echo $prestamo['id']; ?>)">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <script>
    function confirmarEliminacion(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este préstamo?")) {
            // Enviar una solicitud POST para eliminar el préstamo
            fetch('viewPrestamos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `eliminar_prestamo=1&id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    document.getElementById(`fila-${id}`).remove(); // Eliminar la fila de la tabla
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }
    </script>
</body>
</html>