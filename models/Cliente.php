<?php
class Cliente {
    private $conn;
    private $table_name = "clientes";

    // Propiedades del objeto
    public $id;
    public $nombre;
    public $contacto;
    public $telefono;
    public $email;
    public $direccion;
    public $notas;
    public $estado;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer todos los clientes
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer un solo cliente
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
            $this->notas = $row['notas'] ?? '';
            $this->estado = $row['estado'] ?? 1;
            $this->created_at = $row['created_at'] ?? date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }

    // Crear cliente
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 (nombre, contacto, telefono, email, direccion, notas, estado) 
                 VALUES (:nombre, :contacto, :telefono, :email, :direccion, :notas, :estado)";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->contacto = htmlspecialchars(strip_tags($this->contacto));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->notas = htmlspecialchars(strip_tags($this->notas));

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":contacto", $this->contacto);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":notas", $this->notas);
        $stmt->bindParam(":estado", $this->estado);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Actualizar cliente
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET nombre=:nombre, contacto=:contacto, telefono=:telefono, 
                     email=:email, direccion=:direccion, notas=:notas, estado=:estado
                 WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Limpiar datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->contacto = htmlspecialchars(strip_tags($this->contacto));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->notas = htmlspecialchars(strip_tags($this->notas));

        // Vincular valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":contacto", $this->contacto);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":notas", $this->notas);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar cliente
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Buscar clientes
    public function search($keywords) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE nombre LIKE ? OR contacto LIKE ? OR email LIKE ?
                 ORDER BY nombre";

        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        $stmt->execute();
        return $stmt;
    }
}
?>
