<?php
// models/models_cita.php
require_once __DIR__ . '/../core/db.php';

class Cita {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conectar();
    }

    public function registrarCita($id_paciente, $id_doctor, $fecha, $hora) {
        $sql = "INSERT INTO citas (id_paciente, id_doctor, fecha, hora, estado)
                VALUES (:id_paciente, :id_doctor, :fecha, :hora, 'Pendiente')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id_paciente", $id_paciente);
        $stmt->bindParam(":id_doctor", $id_doctor);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":hora", $hora);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }
}
