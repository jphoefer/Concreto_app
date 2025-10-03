<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'planta_concreto';
    private $username = 'concreto_user';
    private $password = '110766Jph@Pto';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("pgsql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // PostgreSQL no usa "set names utf8" como MySQL
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
