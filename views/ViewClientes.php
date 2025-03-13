<?php
include '../includes/db.php';

// Lógica para eliminar un cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_cliente'])) {
    $id = intval($_POST['id']);

    if (is_numeric($id)) {
        // Preparar la consulta para eliminar el cliente
        $stmt = $conn->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Cliente eliminado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el cliente: ' . $stmt->error]);
        }

        $stmt->close();
        exit; // Terminar la ejecución después de enviar la respuesta JSON
    } else {
        echo json_encode(['success' => false, 'message' => 'ID no válido.']);
        exit;
    }
}

// Obtener clientes
$result = $conn->query("SELECT * FROM clientes");
if (!$result) {
    die("Error al obtener los clientes: " . $conn->error);
}
$clientes = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <section class="butonsection">
        <div>
            <a href="../main.php" class="btn-vintage">Volver</a>
            <a href="../forms/clientes.php" class="btn-vintage">Crear Cliente</a>
        </div>
    </section>
    <h1>Lista de Clientes</h1>
    <section class="tableContainer">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Cédula</th>
                    <th>Fecha Nacimiento</th>
                    <th>Género</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Fecha Registro</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                <tr id="fila-<?php echo $cliente['id']; ?>">
                    <td><?php echo htmlspecialchars($cliente['id']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['cedula']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['fecha_nacimiento']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['genero']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['direccion']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['fecha_registro']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['estado']); ?></td>
                    <td>
                        <a href="../forms/clientes.php?editar_cliente=<?php echo $cliente['id']; ?>">Editar</a>
                        <a href="#" onclick="confirmarEliminacion(<?php echo $cliente['id']; ?>)">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <script>
    function confirmarEliminacion(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este cliente?")) {
            // Enviar una solicitud POST para eliminar el cliente
            fetch('viewClientes.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `eliminar_cliente=1&id=${id}`
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