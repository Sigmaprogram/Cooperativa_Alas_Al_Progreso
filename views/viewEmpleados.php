<?php
// viewEmpleados.php
include '../includes/db.php';

// Lógica para eliminar un empleado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_empleado'])) {
    $id = intval($_POST['id']);

    if (is_numeric($id)) {
        // Preparar la consulta para eliminar el empleado
        $stmt = $conn->prepare("DELETE FROM empleados WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Empleado eliminado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el empleado: ' . $stmt->error]);
        }

        $stmt->close();
        exit; // Terminar la ejecución después de enviar la respuesta JSON
    } else {
        echo json_encode(['success' => false, 'message' => 'ID no válido.']);
        exit;
    }
}

// Obtener empleados
$result = $conn->query("SELECT * FROM empleados");
if (!$result) {
    die("Error al obtener los empleados: " . $conn->error);
}
$empleados = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Empleados</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <section class="butonsection">
        <div>
            <a href="../main.php" class="btn-vintage">Volver</a>
            <a href="../forms/empleados.php" class="btn-vintage">Crear Empleado</a>
        </div>
    </section>
    <h1>Lista de Empleados</h1>
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
                    <th>Puesto</th>
                    <th>Salario</th>
                    <th>Fecha Contratación</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empleados as $empleado): ?>
                <tr id="fila-<?php echo $empleado['id']; ?>">
                    <td><?php echo htmlspecialchars($empleado['id']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['cedula']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['fecha_nacimiento']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['genero']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['direccion']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['email']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['puesto']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['salario']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['fecha_contratacion']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['estado']); ?></td>
                    <td>
                        <a href="../forms/empleados.php?editar_empleado=<?php echo $empleado['id']; ?>">Editar</a>
                        <a href="#" onclick="confirmarEliminacion(<?php echo $empleado['id']; ?>)">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <script>
    function confirmarEliminacion(id) {
        if (confirm("¿Estás seguro de que deseas eliminar este empleado?")) {
            // Enviar una solicitud POST para eliminar el empleado
            fetch('viewEmpleados.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `eliminar_empleado=1&id=${id}`
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