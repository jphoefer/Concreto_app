<?php
class Material {
    private $conn;
    private $table_name = "materiales";

    public $id;
    public $nombre;
    public $tipo;
    public $unidad_entrada;
    public $unidad_salida;
    public $densidad;
    public $costo_unitario;
    public $estado;
    public $fecha_creacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY nombre";
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
            $this->tipo = $row['tipo'];
            $this->unidad_entrada = $row['unidad_entrada'];
            $this->unidad_salida = $row['unidad_salida'];
            $this->densidad = $row['densidad'];
            $this->costo_unitario = $row['costo_unitario'];
            $this->estado = $row['estado'];
            $this->fecha_creacion = $row['fecha_creacion'];
            
            // DEBUG: Verificar datos cargados
            error_log("Material encontrado: ID=" . $this->id . ", Nombre=" . $this->nombre);
            
            return true;
        } else {
            // DEBUG: Material no encontrado
            error_log("Material NO encontrado: ID=" . $this->id);
            return false;
        }
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                (nombre, tipo, unidad_entrada, unidad_salida, densidad, costo_unitario, estado)
                VALUES (:nombre, :tipo, :unidad_entrada, :unidad_salida, :densidad, :costo_unitario, :estado)";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->unidad_entrada = htmlspecialchars(strip_tags($this->unidad_entrada));
        $this->unidad_salida = htmlspecialchars(strip_tags($this->unidad_salida));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":unidad_entrada", $this->unidad_entrada);
        $stmt->bindParam(":unidad_salida", $this->unidad_salida);
        $stmt->bindParam(":densidad", $this->densidad);
        $stmt->bindParam(":costo_unitario", $this->costo_unitario);
        $stmt->bindParam(":estado", $this->estado);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                SET nombre = :nombre, tipo = :tipo, unidad_entrada = :unidad_entrada, 
                    unidad_salida = :unidad_salida, densidad = :densidad, 
                    costo_unitario = :costo_unitario, estado = :estado
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->tipo = htmlspecialchars(strip_tags($this->tipo));
        $this->unidad_entrada = htmlspecialchars(strip_tags($this->unidad_entrada));
        $this->unidad_salida = htmlspecialchars(strip_tags($this->unidad_salida));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":tipo", $this->tipo);
        $stmt->bindParam(":unidad_entrada", $this->unidad_entrada);
        $stmt->bindParam(":unidad_salida", $this->unidad_salida);
        $stmt->bindParam(":densidad", $this->densidad);
        $stmt->bindParam(":costo_unitario", $this->costo_unitario);
        $stmt->bindParam(":estado", $this->estado);
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

    // Método para obtener el costo total de un material en inventario
    public function getCostoInventario() {
        $query = "SELECT 
                    COALESCE(SUM(e.cantidad * m.costo_unitario), 0) as costo_total_inventario
                  FROM materiales m
                  LEFT JOIN entradas e ON m.id = e.material_id
                  LEFT JOIN produccion_detalles pd ON m.id = pd.material_id
                  WHERE m.id = :id
                  GROUP BY m.id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['costo_total_inventario'] : 0;
    }
}
?>
