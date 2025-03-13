CREATE DATABASE cooperativa;

USE cooperativa;

-- Tabla de Empleados
CREATE TABLE empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    fecha_nacimiento DATE NOT NULL,
    genero ENUM('Masculino', 'Femenino', 'Otro') NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    puesto VARCHAR(100) NOT NULL,
    salario DECIMAL(10, 2) NOT NULL,
    fecha_contratacion DATE NOT NULL,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo'
);

-- Tabla de Clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    fecha_nacimiento DATE NOT NULL,
    genero ENUM('Masculino', 'Femenino', 'Otro') NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    fecha_registro DATE NOT NULL,
    estado ENUM('Activo', 'Inactivo') DEFAULT 'Activo'
);

-- Tabla de Nómina
CREATE TABLE nomina (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empleado_id INT NOT NULL,
    fecha DATE NOT NULL,
    horas_trabajadas INT NOT NULL,
    pago_por_hora DECIMAL(10, 2) NOT NULL,
    bonificaciones DECIMAL(10, 2) DEFAULT 0,
    deducciones DECIMAL(10, 2) DEFAULT 0,
    monto_total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (empleado_id) REFERENCES empleados(id)
);

ALTER TABLE nomina
DROP FOREIGN KEY nomina_ibfk_1; -- Reemplaza con el nombre real de la FK si es diferente

ALTER TABLE nomina
ADD CONSTRAINT nomina_ibfk_1 
FOREIGN KEY (empleado_id) 
REFERENCES empleados(id) 
ON DELETE CASCADE;

CREATE TABLE servicios_pendientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    fecha_solicitud DATE NOT NULL,
    estado ENUM('Pendiente', 'Completado') DEFAULT 'Pendiente',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

ALTER TABLE clientes CHANGE genero genero ENUM('Masculino', 'Femenino', 'Otro') NOT NULL;

-- Tabla de Préstamos
CREATE TABLE prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    monto_prestamo DECIMAL(10, 2) NOT NULL,
    tasa_interes DECIMAL(5, 2) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('Activo', 'Pagado', 'Vencido') DEFAULT 'Activo',
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Tabla de Pagos
CREATE TABLE pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prestamo_id INT NOT NULL,
    monto_pago DECIMAL(10, 2) NOT NULL,
    fecha_pago DATE NOT NULL,
    FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE CASCADE
);

USE cooperativa;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE, -- Nombre de usuario único
    password VARCHAR(255) NOT NULL
);
