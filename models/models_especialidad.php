<?php
// models/Especialidad.php
require_once __DIR__ . '/../core/db.php';

class Especialidad {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->conectar();
    }

    public function listarTodas() {
        $sql = "SELECT id_especialidad, nombre FROM especialidades ORDER BY nombre";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
