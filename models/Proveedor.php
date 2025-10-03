<?php
class Proveedor {
    private $conn;
    private $table_name = "proveedores";

    public $id;
    public $nombre;
    public $contacto;
    public $telefono;
    public $email;
    public $direccion;
    public $rfc;
    public $is_active;
    public $fecha_creacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = TRUE ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nombre = $row['nombre'];
            $this->contacto = $row['contacto'];
            $this->telefono = $row['telefono'];
            $this->email = $row['email'];
            $this->direccion = $row['direccion'];
            $this->rfc = $row['rfc'];
            $this->is_active = $row['is_active'];
            $this->fecha_creacion = $row['fecha_creacion'];
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                (nombre, contacto, telefono, email, direccion, rfc, is_active)
                VALUES (:nombre, :contacto, :telefono, :email, :direccion, :rfc, :is_active)";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->contacto = htmlspecialchars(strip_tags($this->contacto));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->rfc = htmlspecialchars(strip_tags($this->rfc));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":contacto", $this->contacto);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":rfc", $this->rfc);
        $stmt->bindParam(":is_active", $this->is_active);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET nombre = :nombre, contacto = :contacto, telefono = :telefono, 
                    email = :email, direccion = :direccion, rfc = :rfc, is_active = :is_active
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->contacto = htmlspecialchars(strip_tags($this->contacto));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->rfc = htmlspecialchars(strip_tags($this->rfc));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":contacto", $this->contacto);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":rfc", $this->rfc);
        $stmt->bindParam(":is_active", $this->is_active);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para obtener estadísticas del proveedor
    public function getEstadisticas() {
        $query = "SELECT * FROM analisis_compras WHERE proveedor_id = :proveedor_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":proveedor_id", $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
