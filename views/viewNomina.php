<?php
include '../includes/db.php';

// Lógica para eliminar nómina
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_nomina'])) {
    $id = intval($_POST['id']);

    if (is_numeric($id)) {
        // Preparar la consulta para eliminar la nómina
        $stmt = $conn->prepare("DELETE FROM nomina WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Nómina eliminada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la nómina: ' . $stmt->error]);
        }

        $stmt->close();
        exit; // Terminar la ejecución después de enviar la respuesta JSON
    } else {
        echo json_encode(['success' => false, 'message' => 'ID no válido.']);
        exit;
    }
}

// Obtener nóminas
$result = $conn->query("SELECT nomina.*, empleados.nombre AS empleado_nombre FROM nomina JOIN empleados ON nomina.empleado_id = empleados.id");
if (!$result) {
    die("Error al obtener las nóminas: " . $conn->error);
}
$nominas = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nómina</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <section class="butonsection">
        <div>
            <a href="../main.php" class="btn-vintage">Volver</a>
            <a href="../forms/nomina.php" class="btn-vintage">Añadir a Nomina</a>
        </div>
    </section>
    <h1>Nómina</h1>
    <section class="tableContainer">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Empleado</th>
                    <th>Fecha</th>
                    <th>Horas Trabajadas</th>
                    <th>Pago por Hora</th>
                    <th>Bonificaciones</th>
                    <th>Deducciones</th>
                    <th>Monto Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($nominas as $nomina): ?>
                <tr id="fila-<?php echo $nomina['id']; ?>">
                    <td><?php echo htmlspecialchars($nomina['id']); ?></td>
                    <td><?php echo htmlspecialchars($nomina['empleado_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($nomina['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($nomina['horas_trabajadas']); ?></td>
                    <td><?php echo htmlspecialchars($nomina['pago_por_hora']); ?></td>
                    <td><?php echo htmlspecialchars($nomina['bonificaciones']); ?></td>
                    <td><?php echo htmlspecialchars($nomina['deducciones']); ?></td>
                    <td><?php echo htmlspecialchars($nomina['monto_total']); ?></td>
                    <td>
                        <a href="../forms/nomina.php?editar_nomina=<?php echo $nomina['id']; ?>">Editar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <script>
    function confirmarEliminacion(id) {
        if (confirm("¿Estás seguro de que deseas eliminar esta nómina?")) {
            // Enviar una solicitud POST para eliminar la nómina
            fetch('tabla_nomina.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `eliminar_nomina=1&id=${id}`
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