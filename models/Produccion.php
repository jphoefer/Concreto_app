<?php
class Produccion {
    private $conn;
    private $table_name = "producciones";

    public $id;
    public $resistencia_id;
    public $cantidad;
    public $fecha;
    public $cliente;
    public $lote;
    public $usuario;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
