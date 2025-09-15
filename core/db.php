<?php
// core/db.php
class Database {
    private $host = "localhost";
    private $db_name = "clinica"; // AJUSTA si tu BD tiene otro nombre
    private $username = "root";   // AJUSTA si usas otro usuario
    private $password = "";       // AJUSTA si tienes contraseña
    public $conn;

    public function conectar() {
        $this->conn = null;
        try {
            // usar charset en DSN y lanzar excepción si falla
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name};charset=utf8",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    // otras opciones si las necesitas
                ]
            );
        } catch (PDOException $e) {
            // lanzar excepción para que el router la capture y devuelva JSON
            throw new Exception("Error de conexión a BD: " . $e->getMessage());
        }
        return $this->conn;
    }
}


