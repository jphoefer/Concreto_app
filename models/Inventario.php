<?php
class Inventario {
    private $conn;
    private $table_name = "inventario_actual";

    public $id;
    public $nombre;
    public $tipo;
    public $unidad_entrada;
    public $unidad_salida;
    public $densidad;
    public $cantidad_actual;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
