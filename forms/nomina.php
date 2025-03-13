<?php
include '../includes/db.php';

// Lógica para crear o editar nómina
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear_nomina'])) {
        $empleado_id = $_POST['empleado_id'];
        $fecha = $_POST['fecha'];
        $horas_trabajadas = $_POST['horas_trabajadas'];
        $pago_por_hora = $_POST['pago_por_hora'];
        $bonificaciones = $_POST['bonificaciones'];
        $deducciones = $_POST['deducciones'];
        $monto_total = ($horas_trabajadas * $pago_por_hora) + $bonificaciones - $deducciones;

        // Verificar si el empleado_id existe en la tabla empleados
        $stmt = $conn->prepare("SELECT id FROM empleados WHERE id = ?");
        $stmt->bind_param("i", $empleado_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // El empleado_id existe, proceder con la inserción
            $stmt = $conn->prepare("INSERT INTO nomina (empleado_id, fecha, horas_trabajadas, pago_por_hora, bonificaciones, deducciones, monto_total) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isidddd", $empleado_id, $fecha, $horas_trabajadas, $pago_por_hora, $bonificaciones, $deducciones, $monto_total);
            $stmt->execute();
            $stmt->close();

            // Redirigir a la página de visualización de nóminas
            header("Location: ../views/viewNomina.php");
            exit(); // Asegúrate de salir del script después de la redirección
        } else {
            // El empleado_id no existe, mostrar un mensaje de error
            echo "Error: El empleado seleccionado no existe.";
        }
    } elseif (isset($_POST['editar_nomina'])) {
        $id = $_POST['id'];
        $empleado_id = $_POST['empleado_id'];
        $fecha = $_POST['fecha'];
        $horas_trabajadas = $_POST['horas_trabajadas'];
        $pago_por_hora = $_POST['pago_por_hora'];
        $bonificaciones = $_POST['bonificaciones'];
        $deducciones = $_POST['deducciones'];
        $monto_total = ($horas_trabajadas * $pago_por_hora) + $bonificaciones - $deducciones;

        // Verificar si el empleado_id existe en la tabla empleados
        $stmt = $conn->prepare("SELECT id FROM empleados WHERE id = ?");
        $stmt->bind_param("i", $empleado_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // El empleado_id existe, proceder con la actualización
            $stmt = $conn->prepare("UPDATE nomina SET empleado_id = ?, fecha = ?, horas_trabajadas = ?, pago_por_hora = ?, bonificaciones = ?, deducciones = ?, monto_total = ? WHERE id = ?");
            $stmt->bind_param("isiddddi", $empleado_id, $fecha, $horas_trabajadas, $pago_por_hora, $bonificaciones, $deducciones, $monto_total, $id);
            $stmt->execute();
            $stmt->close();

            // Redirigir a la página de visualización de nóminas
            header("Location: ../views/viewNomina.php");
            exit(); // Asegúrate de salir del script después de la redirección
        } else {
            // El empleado_id no existe, mostrar un mensaje de error
            echo "Error: El empleado seleccionado no existe.";
        }
    }
}

// Obtener nómina para editar
$nomina_editar = null;
if (isset($_GET['editar_nomina'])) {
    $id = $_GET['editar_nomina'];
    $stmt = $conn->prepare("SELECT * FROM nomina WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $nomina_editar = $result->fetch_assoc();
    $stmt->close();
}

// Obtener empleados para el select
$empleados = $conn->query("SELECT id, nombre FROM empleados")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Nómina</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <section class="butonsection">    
        <div>
            <a href="../main.php" class="btn-vintage">Volver</a>
        </div>
    </section>
    <h1>Formulario de Nómina</h1>
    <form method="POST">
        <?php if ($nomina_editar): ?>
            <input type="hidden" name="id" value="<?php echo $nomina_editar['id']; ?>">
        <?php endif; ?>

        <label for="empleado_id">Empleado:</label>
        <select id="empleado_id" name="empleado_id" required>
            <?php foreach ($empleados as $empleado): ?>
                <option value="<?php echo $empleado['id']; ?>" <?php echo ($nomina_editar && $nomina_editar['empleado_id'] == $empleado['id']) ? 'selected' : ''; ?>>
                    <?php echo $empleado['nombre']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="fecha">Fecha de agregación:</label>
        <input type="date" id="fecha" name="fecha" placeholder="Fecha" value="<?php echo $nomina_editar ? $nomina_editar['fecha'] : ''; ?>" required>

        <label for="horas_trabajadas">Horas Trabajadas:</label>
        <input type="number" id="horas_trabajadas" name="horas_trabajadas" placeholder="Horas Trabajadas" value="<?php echo $nomina_editar ? $nomina_editar['horas_trabajadas'] : ''; ?>" required>

        <label for="pago_por_hora">Pago por Hora:</label>
        <input type="number" id="pago_por_hora" name="pago_por_hora" placeholder="Pago por Hora" step="0.01" value="<?php echo $nomina_editar ? $nomina_editar['pago_por_hora'] : ''; ?>" required>

        <label for="bonificaciones">Bonificaciones:</label>
        <input type="number" id="bonificaciones" name="bonificaciones" placeholder="Bonificaciones" step="0.01" value="<?php echo $nomina_editar ? $nomina_editar['bonificaciones'] : ''; ?>" required>

        <label for="deducciones">Deducciones:</label>
        <input type="number" id="deducciones" name="deducciones" placeholder="Deducciones" step="0.01" value="<?php echo $nomina_editar ? $nomina_editar['deducciones'] : ''; ?>" required>

        <?php if ($nomina_editar): ?>
            <button type="submit" name="editar_nomina">Guardar Cambios</button>
        <?php else: ?>
            <button type="submit" name="crear_nomina">Crear Nómina</button>
        <?php endif; ?>
    </form>
</body>
</html>