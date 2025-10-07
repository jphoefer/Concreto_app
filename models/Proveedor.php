<?php
class Proveedor {
    private $conn;
    private $table_name = "proveedores";

    // Propiedades del objeto
    public $id;
    public $nombre;
    public $contacto;
    public $telefono;
    public $email;
    public $direccion;
    public $notas;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer todos los proveedores
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer un solo proveedor - CORREGIDO para campos opcionales
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->nombre = $row['nombre'] ?? '';
            $this->contacto = $row['contacto'] ?? '';
            $this->telefono = $row['telefono'] ?? '';
            $this->email = $row['email'] ?? '';
            $this->direccion = $row['direccion'] ?? '';
            $this->notas = $row['notas'] ?? ''; // Campo opcional
            $this->created_at = $row['created_at'] ?? date('Y-m-d H:i:s'); // Campo opcional
            return true;
        }
        return false;
    }

    // Crear proveedor - CORREGIDO para campos opcionales
    public function create() {
        // Construir query dinámicamente basado en los campos proporcionados
        $fields = ['nombre', 'contacto', 'telefono', 'email', 'direccion', 'notas'];
        $placeholders = [];
        $values = [];
        
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $placeholders[] = ":$field";
                $values[":$field"] = $this->$field;
            }
        }
        
        if (empty($placeholders)) {
            return false;
        }
        
        $query = "INSERT INTO " . $this->table_name . " 
                 (" . implode(', ', $fields) . ") 
                 VALUES (" . implode(', ', $placeholders) . ")";

        $stmt = $this->conn->prepare($query);

        // Limpiar y vincular valores
        foreach ($values as $key => $value) {
            $clean_value = htmlspecialchars(strip_tags($value));
            $stmt->bindValue($key, $clean_value);
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Actualizar proveedor - CORREGIDO para campos opcionales
    public function update() {
        $fields = [];
        $values = [];
        
        // Campos que pueden actualizarse
        $updatable_fields = [
            'nombre' => $this->nombre,
            'contacto' => $this->contacto,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'notas' => $this->notas
        ];
        
        foreach ($updatable_fields as $field => $value) {
            if ($value !== null) {
                $fields[] = "$field = :$field";
                $values[":$field"] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $values[":id"] = $this->id;
        
        $query = "UPDATE " . $this->table_name . " 
                 SET " . implode(', ', $fields) . "
                 WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Limpiar y vincular valores
        foreach ($values as $key => $value) {
            $clean_value = htmlspecialchars(strip_tags($value));
            $stmt->bindValue($key, $clean_value);
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar proveedor
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
