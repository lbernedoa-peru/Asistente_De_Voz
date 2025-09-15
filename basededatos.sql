CREATE DATABASE IF NOT EXISTS clinica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinica;

CREATE TABLE especialidades (
  id_especialidad INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
);

CREATE TABLE doctores (
  id_doctor INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  id_especialidad INT,
  FOREIGN KEY (id_especialidad) REFERENCES especialidades(id_especialidad)
);

CREATE TABLE pacientes (
  id_paciente INT AUTO_INCREMENT PRIMARY KEY,
  dni VARCHAR(20) UNIQUE,
  nombre VARCHAR(200),
  correo VARCHAR(150),
  telefono VARCHAR(50)
);

CREATE TABLE citas (
  id_cita INT AUTO_INCREMENT PRIMARY KEY,
  id_paciente INT,
  id_doctor INT,
  fecha DATE,
  hora TIME,
  estado VARCHAR(50),
  FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente),
  FOREIGN KEY (id_doctor) REFERENCES doctores(id_doctor)
);

-- Datos iniciales
INSERT INTO especialidades (nombre) VALUES ('Cardiología'),('Pediatría'),('Odontología');
INSERT INTO doctores (nombre, id_especialidad) VALUES ('Dr. Pérez',1),('Dra. Gómez',1),('Dr. Ruiz',2);
