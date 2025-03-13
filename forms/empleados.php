<?php
include '../includes/db.php';

$error_message = "";

// Crear o editar empleado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear_empleado'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $genero = $_POST['genero'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $puesto = $_POST['puesto'];
        $salario = $_POST['salario'];
        $fecha_contratacion = $_POST['fecha_contratacion'];
        $estado = $_POST['estado'];

        try {
            $stmt = $conn->prepare("INSERT INTO empleados (nombre, apellido, cedula, fecha_nacimiento, genero, direccion, telefono, email, puesto, salario, fecha_contratacion, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssdss", $nombre, $apellido, $cedula, $fecha_nacimiento, $genero, $direccion, $telefono, $email, $puesto, $salario, $fecha_contratacion, $estado);
            $stmt->execute();
            $stmt->close();

            // Redirigir a la lista de empleados después de crear
            header("Location: ../views/viewEmpleados.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Código de error para entrada duplicada
                $error_message = "La cédula ingresada ya está registrada.";
            } else {
                $error_message = "Ocurrió un error al intentar crear el empleado.";
            }
        }
    } elseif (isset($_POST['editar_empleado'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $genero = $_POST['genero'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $puesto = $_POST['puesto'];
        $salario = $_POST['salario'];
        $fecha_contratacion = $_POST['fecha_contratacion'];
        $estado = $_POST['estado'];

        try {
            $stmt = $conn->prepare("UPDATE empleados SET nombre = ?, apellido = ?, cedula = ?, fecha_nacimiento = ?, genero = ?, direccion = ?, telefono = ?, email = ?, puesto = ?, salario = ?, fecha_contratacion = ?, estado = ? WHERE id = ?");
            $stmt->bind_param("sssssssssdssi", $nombre, $apellido, $cedula, $fecha_nacimiento, $genero, $direccion, $telefono, $email, $puesto, $salario, $fecha_contratacion, $estado, $id);
            $stmt->execute();
            $stmt->close();

            // Redirigir a la vista de empleados después de crear o editar
            header("Location: ../views/viewEmpleados.php");
            exit();
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Código de error para entrada duplicada
                $error_message = "La cédula ingresada ya está registrada.";
            } else {
                $error_message = "Ocurrió un error al intentar editar el empleado.";
            }
        }
    }
}

// Obtener empleado para editar
$empleado_editar = null;
if (isset($_GET['editar_empleado'])) {
    $id = $_GET['editar_empleado'];
    $stmt = $conn->prepare("SELECT * FROM empleados WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $empleado_editar = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Empleados</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <section class="butonsection">
        <div>
            <a href="../main.php" class="btn-vintage">Volver</a>
        </div>
    </section>
    <h1>Crear Empleado</h1>
    <?php if (!empty($error_message)): ?>
        <script>
            alert("<?php echo $error_message; ?>");
        </script>
    <?php endif; ?>

    <form method="POST">
        <?php if ($empleado_editar): ?>
            <input type="hidden" name="id" value="<?php echo $empleado_editar['id']; ?>">
        <?php endif; ?>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $empleado_editar ? $empleado_editar['nombre'] : ''; ?>" required>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" placeholder="Apellido" value="<?php echo $empleado_editar ? $empleado_editar['apellido'] : ''; ?>" required>

        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" placeholder="Cédula" value="<?php echo $empleado_editar ? $empleado_editar['cedula'] : ''; ?>" required>

        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" value="<?php echo $empleado_editar ? $empleado_editar['fecha_nacimiento'] : ''; ?>" required>

        <label for="genero">Género:</label>
        <select id="genero" name="genero" required>
            <option value="Masculino" <?php echo ($empleado_editar && $empleado_editar['genero'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
            <option value="Femenino" <?php echo ($empleado_editar && $empleado_editar['genero'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
            <option value="Otro" <?php echo ($empleado_editar && $empleado_editar['genero'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
        </select>

        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" placeholder="Dirección" value="<?php echo $empleado_editar ? $empleado_editar['direccion'] : ''; ?>" required>

        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" placeholder="Teléfono" value="<?php echo $empleado_editar ? $empleado_editar['telefono'] : ''; ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $empleado_editar ? $empleado_editar['email'] : ''; ?>" required>

        <label for="puesto">Puesto:</label>
        <input type="text" id="puesto" name="puesto" placeholder="Puesto" value="<?php echo $empleado_editar ? $empleado_editar['puesto'] : ''; ?>" required>

        <label for="salario">Salario:</label>
        <input type="number" id="salario" name="salario" placeholder="Salario" step="0.01" value="<?php echo $empleado_editar ? $empleado_editar['salario'] : ''; ?>" required>

        <label for="fecha_contratacion">Fecha de Contratación:</label>
        <input type="date" id="fecha_contratacion" name="fecha_contratacion" placeholder="Fecha de Contratación" value="<?php echo $empleado_editar ? $empleado_editar['fecha_contratacion'] : ''; ?>" required>

        <label for="estado">Estado:</label>
        <select id="estado" name="estado" required>
            <option value="Activo" <?php echo ($empleado_editar && $empleado_editar['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
            <option value="Inactivo" <?php echo ($empleado_editar && $empleado_editar['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
        </select>

        <?php if ($empleado_editar): ?>
            <button type="submit" name="editar_empleado">Guardar Cambios</button>
        <?php else: ?>
            <button type="submit" name="crear_empleado">Crear Empleado</button>
        <?php endif; ?>
    </form>
</body>
</html>