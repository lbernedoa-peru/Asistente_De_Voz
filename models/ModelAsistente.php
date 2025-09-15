<?php
// models/ModelAsistente.php
require_once __DIR__ . '/../core/db.php';

class ModelAsistente {
    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->conectar();
    }

    // 1. Verificar si el paciente existe por DNI
    public function getPacienteByDni($dni) {
        $stmt = $this->pdo->prepare("SELECT * FROM pacientes WHERE dni = ?");
        $stmt->execute([$dni]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Registrar un nuevo paciente
    public function insertPaciente($dni, $nombre, $correo, $telefono) {
        $stmt = $this->pdo->prepare("INSERT INTO pacientes (dni, nombre, correo, telefono) VALUES (?, ?, ?, ?)");
        $stmt->execute([$dni, $nombre, $correo, $telefono]);
        return $this->pdo->lastInsertId();
    }

    // 3. Listar especialidades
    public function getEspecialidades() {
        $stmt = $this->pdo->query("SELECT * FROM especialidades ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Listar doctores por especialidad
    public function getDoctoresByEspecialidad($id_especialidad) {
        $stmt = $this->pdo->prepare("SELECT * FROM doctores WHERE id_especialidad = ?");
        $stmt->execute([$id_especialidad]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5. Insertar cita
    public function insertCita($id_paciente, $id_doctor, $fecha, $hora) {
        $stmt = $this->pdo->prepare("INSERT INTO citas (id_paciente, id_doctor, fecha, hora) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id_paciente, $id_doctor, $fecha, $hora]);
        return $this->pdo->lastInsertId();
    }
}
