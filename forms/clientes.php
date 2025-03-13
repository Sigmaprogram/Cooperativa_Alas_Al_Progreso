<?php
include '../includes/db.php';

// Lógica para crear o editar cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear_cliente'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $genero = $_POST['genero'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $fecha_registro = $_POST['fecha_registro'];
        $estado = $_POST['estado'];

        $stmt = $conn->prepare("INSERT INTO clientes (nombre, apellido, cedula, fecha_nacimiento, genero, direccion, telefono, email, fecha_registro, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $nombre, $apellido, $cedula, $fecha_nacimiento, $genero, $direccion, $telefono, $email, $fecha_registro, $estado);
        $stmt->execute();
        $stmt->close();

        // Redireccionar después de crear el cliente
        header("Location: ../views/ViewClientes.php");
        exit();
    } elseif (isset($_POST['editar_cliente'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $genero = $_POST['genero'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $fecha_registro = $_POST['fecha_registro'];
        $estado = $_POST['estado'];

        $stmt = $conn->prepare("UPDATE clientes SET nombre = ?, apellido = ?, cedula = ?, fecha_nacimiento = ?, genero = ?, direccion = ?, telefono = ?, email = ?, fecha_registro = ?, estado = ? WHERE id = ?");
        $stmt->bind_param("ssssssssssi", $nombre, $apellido, $cedula, $fecha_nacimiento, $genero, $direccion, $telefono, $email, $fecha_registro, $estado, $id);
        $stmt->execute();
        $stmt->close();

        // Redireccionar después de editar el cliente
        header("Location: ../views/ViewClientes.php");
        exit();
    }
}

// Obtener cliente para editar
$cliente_editar = null;
if (isset($_GET['editar_cliente'])) {
    $id = $_GET['editar_cliente'];
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente_editar = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cliente</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<section class="butonsection">    
    <div>
        <a href="../main.php" class="btn-vintage">Volver</a>
    </div>
</section>
<h1>Crear Cliente</h1>
<form method="POST">
    <?php if ($cliente_editar): ?>
        <input type="hidden" name="id" value="<?php echo $cliente_editar['id']; ?>">
    <?php endif; ?>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $cliente_editar ? $cliente_editar['nombre'] : ''; ?>" required>

    <label for="apellido">Apellido:</label>
    <input type="text" id="apellido" name="apellido" placeholder="Apellido" value="<?php echo $cliente_editar ? $cliente_editar['apellido'] : ''; ?>" required>

    <label for="cedula">Cédula:</label>
    <input type="text" id="cedula" name="cedula" placeholder="Cédula" value="<?php echo $cliente_editar ? $cliente_editar['cedula'] : ''; ?>" required>

    <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" value="<?php echo $cliente_editar ? $cliente_editar['fecha_nacimiento'] : ''; ?>" required>

    <label for="genero">Género:</label>
    <select id="genero" name="genero" required>
        <option value="Masculino" <?php echo ($cliente_editar && $cliente_editar['genero'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
        <option value="Femenino" <?php echo ($cliente_editar && $cliente_editar['genero'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
        <option value="Otro" <?php echo ($cliente_editar && $cliente_editar['genero'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
    </select>

    <label for="direccion">Dirección:</label>
    <input type="text" id="direccion" name="direccion" placeholder="Dirección" value="<?php echo $cliente_editar ? $cliente_editar['direccion'] : ''; ?>" required>

    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" placeholder="Teléfono" value="<?php echo $cliente_editar ? $cliente_editar['telefono'] : ''; ?>" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" placeholder="Email" value="<?php echo $cliente_editar ? $cliente_editar['email'] : ''; ?>" required>

    <label for="fecha_registro">Fecha de Registro:</label>
    <input type="date" id="fecha_registro" name="fecha_registro" placeholder="Fecha de Registro" value="<?php echo $cliente_editar ? $cliente_editar['fecha_registro'] : ''; ?>" required>

    <label for="estado">Estado:</label>
    <select id="estado" name="estado" required>
        <option value="Activo" <?php echo ($cliente_editar && $cliente_editar['estado'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
        <option value="Inactivo" <?php echo ($cliente_editar && $cliente_editar['estado'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
    </select>

    <?php if ($cliente_editar): ?>
        <button type="submit" name="editar_cliente">Guardar Cambios</button>
    <?php else: ?>
        <button type="submit" name="crear_cliente">Crear Cliente</button>
    <?php endif; ?>
</form>
</body>
</html>