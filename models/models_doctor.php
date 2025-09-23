<?php
// models/models_doctor.php
require_once __DIR__ . '/../core/db.php';

class Doctor {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->conectar();
    }

    public function getByEspecialidad($id_especialidad) {
        $sql = "SELECT id_doctor, nombre, id_especialidad FROM doctores WHERE id_especialidad = :id ORDER BY nombre";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id_especialidad);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id_doctor) {
        $sql = "SELECT id_doctor, nombre, id_especialidad FROM doctores WHERE id_doctor = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id_doctor);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarDoctores() {
    $sql = "SELECT id_doctor, nombre, id_especialidad, genero FROM doctores ORDER BY nombre";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    
    
    public function listarPorEspecialidad($idEspecialidad){
        $stmt = $this->db->prepare("SELECT id, nombre FROM doctores WHERE id_especialidad = ?");
        $stmt->bind_param("i", $idEspecialidad);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
}

