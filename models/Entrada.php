<?php
class Entrada {
    private $conn;
    private $table_name = "entradas";

    public $id;
    public $material_id;
    public $cantidad;
    public $costo_total;
    public $fecha;
    public $lote;
    public $proveedor;
    public $usuario;
    public $proveedor_id;
    public $factura;
    public $fecha_factura;
    public $precio_unitario;
    public $iva;
    public $total_factura;
    public $observaciones;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT e.*, m.nombre as material_nombre, m.unidad_entrada, m.costo_unitario, p.nombre as proveedor_nombre 
                  FROM " . $this->table_name . " e
                  JOIN materiales m ON e.material_id = m.id
                  LEFT JOIN proveedores p ON e.proveedor_id = p.id
                  ORDER BY e.fecha DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create() {
        // Calcular el costo_total si no se proporciona (usando precio_unitario * cantidad)
        if ($this->precio_unitario > 0 && $this->cantidad > 0) {
            $this->costo_total = $this->precio_unitario * $this->cantidad;
        }

        $query = "INSERT INTO " . $this->table_name . " 
                 (material_id, cantidad, costo_total, fecha, lote, proveedor, usuario, 
                  proveedor_id, factura, fecha_factura, precio_unitario, iva, total_factura, observaciones)
                 VALUES (:material_id, :cantidad, :costo_total, :fecha, :lote, :proveedor, :usuario, 
                         :proveedor_id, :factura, :fecha_factura, :precio_unitario, :iva, :total_factura, :observaciones)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":material_id", $this->material_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":costo_total", $this->costo_total);
        $stmt->bindParam(":fecha", $this->fecha);
        $stmt->bindParam(":lote", $this->lote);
        $stmt->bindParam(":proveedor", $this->proveedor);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":proveedor_id", $this->proveedor_id);
        $stmt->bindParam(":factura", $this->factura);
        $stmt->bindParam(":fecha_factura", $this->fecha_factura);
        $stmt->bindParam(":precio_unitario", $this->precio_unitario);
        $stmt->bindParam(":iva", $this->iva);
        $stmt->bindParam(":total_factura", $this->total_factura);
        $stmt->bindParam(":observaciones", $this->observaciones);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para obtener estadísticas de costos de entradas
    public function getEstadisticasCostos() {
        $query = "SELECT 
                    COUNT(*) as total_entradas,
                    SUM(cantidad) as total_cantidad,
                    SUM(costo_total) as total_costo,
                    AVG(costo_total) as costo_promedio
                  FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para obtener el historial de precios de un material
    public function getHistorialPrecios($material_id) {
        $query = "SELECT * FROM historial_precios 
                  WHERE material_id = :material_id 
                  ORDER BY fecha_compra DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":material_id", $material_id);
        $stmt->execute();
        return $stmt;
    }

    // Método para obtener análisis de compras por proveedor
    public function getAnalisisCompras() {
        $query = "SELECT * FROM analisis_compras";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
