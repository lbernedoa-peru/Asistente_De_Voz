-- ======================================
-- CREACIÓN DE BASE DE DATOS
-- ======================================
CREATE DATABASE IF NOT EXISTS clinica
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE clinica;

-- ======================================
-- ELIMINAR TABLAS SI EXISTEN (para pruebas limpias)
-- ======================================
DROP TABLE IF EXISTS citas;
DROP TABLE IF EXISTS pacientes;
DROP TABLE IF EXISTS doctores;
DROP TABLE IF EXISTS especialidades;

-- ======================================
-- TABLAS
-- ======================================

-- Tabla de especialidades
CREATE TABLE especialidades (
  id_especialidad INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
);

-- Tabla de doctores
CREATE TABLE doctores (
  id_doctor INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  genero ENUM('M','F') DEFAULT NULL, -- 'M' = masculino, 'F' = femenino
  id_especialidad INT NOT NULL,
  FOREIGN KEY (id_especialidad) REFERENCES especialidades(id_especialidad)
    ON DELETE CASCADE ON UPDATE CASCADE
);

-- Tabla de pacientes
CREATE TABLE pacientes (
  id_paciente INT AUTO_INCREMENT PRIMARY KEY,
  dni VARCHAR(20) UNIQUE,
  nombre VARCHAR(200) NOT NULL,
  correo VARCHAR(150),
  telefono VARCHAR(50)
);

-- Tabla de citas
CREATE TABLE citas (
  id_cita INT AUTO_INCREMENT PRIMARY KEY,
  id_paciente INT NOT NULL,
  id_doctor INT NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  estado ENUM('Pendiente','Confirmada','Cancelada') DEFAULT 'Pendiente',
  FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_doctor) REFERENCES doctores(id_doctor)
    ON DELETE CASCADE ON UPDATE CASCADE
);

-- ======================================
-- DATOS INICIALES
-- ======================================

-- Especialidades
INSERT INTO especialidades (nombre)
VALUES 
  ('Cardiología'),
  ('Pediatría'),
  ('Odontología'),
  ('Dermatología'),
  ('Ginecología'),
  ('Neurología'),
  ('Traumatología'),
  ('Oftalmología');

-- Doctores
INSERT INTO doctores (nombre, genero, id_especialidad)
VALUES 
  -- Cardiología (id 1)
  ('Carlos Pérez', 'M', 1),
  ('María Gómez', 'F', 1),
  ('Luis Fernández', 'M', 1),
  ('Ana Torres', 'F', 1),
  ('Pedro Castillo', 'M', 1),

  -- Pediatría (id 2)
  ('José Ruiz', 'M', 2),
  ('Laura Castillo', 'F', 2),
  ('Ricardo Morales', 'M', 2),
  ('Mónica Herrera', 'F', 2),

  -- Odontología (id 3)
  ('Andrea Torres', 'F', 3),
  ('Miguel Sánchez', 'M', 3),
  ('Paola Ramírez', 'F', 3),
  ('Héctor Vargas', 'M', 3),

  -- Dermatología (id 4)
  ('Gabriela López', 'F', 4),
  ('Fernando Díaz', 'M', 4),
  ('Natalia Campos', 'F', 4),

  -- Ginecología (id 5)
  ('Carmen Silva', 'F', 5),
  ('Roberto Herrera', 'M', 5),
  ('Elena Rojas', 'F', 5),

  -- Neurología (id 6)
  ('Patricia Vargas', 'F', 6),
  ('Jorge Medina', 'M', 6),
  ('Diego Paredes', 'M', 6),

  -- Traumatología (id 7)
  ('Sofía Aguilar', 'F', 7),
  ('Andrés Salazar', 'M', 7),
  ('Claudia Ortiz', 'F', 7),

  -- Oftalmología (id 8)
  ('Felipe Guzmán', 'M', 8),
  ('Rosa Delgado', 'F', 8),
  ('Martín Cárdenas', 'M', 8);

-- Pacientes de prueba
--INSERT INTO pacientes (dni, nombre, correo, telefono)
--VALUES
-- ('12345678', 'Juan Pérez', 'juanperez@mail.com', '999111222'),
 -- ('87654321', 'María Torres', 'mariatorres@mail.com', '988777666');

-- Citas de prueba
--INSERT INTO citas (id_paciente, id_doctor, fecha, hora, estado)
--VALUES
  --(1, 2, '2025-09-25', '10:00:00', 'Pendiente'),
  --(2, 5, '2025-09-26', '15:30:00', 'Confirmada');

