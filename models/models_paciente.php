<?php
// models/models_paciente.php
require_once __DIR__ . '/../core/db.php';

class Paciente {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->conectar();
    }

    public function buscarPorDni($dni) {
        $sql = "SELECT id_paciente, dni, nombre, correo, telefono FROM pacientes WHERE dni = :dni LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row : null;
    }

    public function registrar($dni, $nombre, $correo, $telefono) {
        $sql = "INSERT INTO pacientes (dni, nombre, correo, telefono) VALUES (:dni, :nombre, :correo, :telefono)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }
    public function registrarPaciente($dni, $nombre, $correo, $telefono){
    $sql = "INSERT INTO pacientes (dni, nombre, correo, telefono) VALUES (:dni, :nombre, :correo, :telefono)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":dni", $dni);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":correo", $correo);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->execute();
    return $this->conn->lastInsertId();
}
    
    
}
