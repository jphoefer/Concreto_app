<?php
class Resistencia {
    private $conn;
    private $table_name = "resistencias";

    public $id;
    public $nombre;
    public $descripcion;
    public $precio_venta;
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
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->precio_venta = $row['precio_venta'];
            $this->fecha_creacion = $row['fecha_creacion'];
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nombre, descripcion, precio_venta) VALUES (:nombre, :descripcion, :precio_venta)";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio_venta", $this->precio_venta);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nombre = :nombre, descripcion = :descripcion, precio_venta = :precio_venta WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio_venta", $this->precio_venta);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        // Primero eliminar los detalles de la resistencia
        $query = "DELETE FROM resistencia_detalles WHERE resistencia_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        // Luego eliminar la resistencia
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para obtener los detalles de la resistencia con costos proporcionales
    public function getDetalles() {
        $query = "SELECT rd.*, m.nombre as material_nombre, m.tipo, m.unidad_salida, m.costo_unitario,
                         (rd.cantidad * m.costo_unitario) as costo_total_material,
                         ROUND((rd.cantidad * m.costo_unitario) / NULLIF((
                             SELECT SUM(rd2.cantidad * m2.costo_unitario) 
                             FROM resistencia_detalles rd2 
                             JOIN materiales m2 ON rd2.material_id = m2.id 
                             WHERE rd2.resistencia_id = rd.resistencia_id
                         ), 0) * 100, 2) as porcentaje_costo
                  FROM resistencia_detalles rd 
                  JOIN materiales m ON rd.material_id = m.id 
                  WHERE rd.resistencia_id = ? 
                  ORDER BY (rd.cantidad * m.costo_unitario) DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Método para obtener un detalle específico
    public function getDetalle($detalle_id) {
        $query = "SELECT rd.*, m.nombre as material_nombre, m.tipo 
                  FROM resistencia_detalles rd 
                  JOIN materiales m ON rd.material_id = m.id 
                  WHERE rd.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $detalle_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para agregar un material a la resistencia
    public function agregarMaterial($material_id, $cantidad, $unidad) {
        $query = "INSERT INTO resistencia_detalles (resistencia_id, material_id, cantidad, unidad) 
                  VALUES (:resistencia_id, :material_id, :cantidad, :unidad)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":resistencia_id", $this->id);
        $stmt->bindParam(":material_id", $material_id);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":unidad", $unidad);
        
        return $stmt->execute();
    }

    // Método para eliminar un material de la resistencia
    public function eliminarMaterial($detalle_id) {
        $query = "DELETE FROM resistencia_detalles WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $detalle_id);
        return $stmt->execute();
    }

    // Método para actualizar un material de la resistencia
    public function actualizarMaterial($detalle_id, $cantidad, $unidad) {
        $query = "UPDATE resistencia_detalles SET cantidad = :cantidad, unidad = :unidad WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":unidad", $unidad);
        $stmt->bindParam(":id", $detalle_id);
        return $stmt->execute();
    }

    // Método para calcular el costo total de la resistencia
    public function getCostoTotal() {
        $query = "SELECT COALESCE(SUM(rd.cantidad * m.costo_unitario), 0) as costo_total
                  FROM resistencia_detalles rd 
                  JOIN materiales m ON rd.material_id = m.id 
                  WHERE rd.resistencia_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['costo_total'] : 0;
    }

    // Método para obtener análisis de rentabilidad
    public function getAnalisisRentabilidad() {
        $query = "SELECT * FROM analisis_rentabilidad WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para obtener estadísticas de costos de todas las resistencias
    public function getEstadisticasCostos() {
        $query = "SELECT 
                    COUNT(*) as total_resistencias,
                    AVG(cr.costo_total) as costo_promedio,
                    MIN(cr.costo_total) as costo_minimo,
                    MAX(cr.costo_total) as costo_maximo,
                    SUM(cr.costo_total) as costo_total_resistencias,
                    AVG(cr.precio_venta) as precio_venta_promedio,
                    AVG(cr.porcentaje_margen) as margen_promedio
                  FROM analisis_rentabilidad cr";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
