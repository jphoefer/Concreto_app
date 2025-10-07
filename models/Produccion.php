<?php
class Produccion {
    private $conn;
    private $table_ordenes = "producciones";
    private $table_detalles = "produccion_detalles";

    public $id;
    public $resistencia_id;
    public $cantidad;
    public $fecha;
    public $cliente;
    public $lote;
    public $usuario;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Leer todas las órdenes de producción
    public function read() {
        $query = "SELECT p.*, r.nombre as resistencia_nombre, r.precio_venta
                  FROM " . $this->table_ordenes . " p
                  LEFT JOIN resistencias r ON p.resistencia_id = r.id
                  ORDER BY p.fecha DESC, p.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Leer una orden específica
    public function readOne() {
        $query = "SELECT p.*, r.nombre as resistencia_nombre, r.precio_venta
                  FROM " . $this->table_ordenes . " p
                  LEFT JOIN resistencias r ON p.resistencia_id = r.id
                  WHERE p.id = ? LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->resistencia_id = $row['resistencia_id'];
            $this->cantidad = $row['cantidad'];
            $this->fecha = $row['fecha'];
            $this->cliente = $row['cliente'];
            $this->lote = $row['lote'];
            $this->usuario = $row['usuario'];
            $this->estado = $row['estado'] ?? 'pendiente';
            return $row;
        }
        return false;
    }

    // Crear nueva orden de producción
    public function create() {
        $query = "INSERT INTO " . $this->table_ordenes . " 
                  (resistencia_id, cantidad, cliente, lote, usuario, estado)
                  VALUES (:resistencia_id, :cantidad, :cliente, :lote, :usuario, 'pendiente')";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->resistencia_id = htmlspecialchars(strip_tags($this->resistencia_id));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->cliente = htmlspecialchars(strip_tags($this->cliente));
        $this->lote = htmlspecialchars(strip_tags($this->lote));
        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        
        // Bind parameters
        $stmt->bindParam(":resistencia_id", $this->resistencia_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":cliente", $this->cliente);
        $stmt->bindParam(":lote", $this->lote);
        $stmt->bindParam(":usuario", $this->usuario);
        
        if($stmt->execute()) {
            $orden_id = $this->conn->lastInsertId();
            // Crear detalles de producción basados en la resistencia (SOLO cálculo, NO descuento)
            if ($this->crearDetallesProduccion($orden_id)) {
                return $orden_id;
            } else {
                // Si falla la creación de detalles, eliminar la orden
                $this->deleteOrder($orden_id);
                return false;
            }
        }
        return false;
    }

    // Eliminar orden (método auxiliar)
    private function deleteOrder($orden_id) {
        $query = "DELETE FROM " . $this->table_ordenes . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $orden_id);
        $stmt->execute();
    }

    // Crear detalles de producción basados en la resistencia (SOLO cálculo)
    private function crearDetallesProduccion($orden_id) {
        try {
            // Obtener los materiales de la resistencia
            $query = "SELECT rd.material_id, rd.cantidad, rd.unidad, m.costo_unitario
                      FROM resistencia_detalles rd
                      JOIN materiales m ON rd.material_id = m.id
                      WHERE rd.resistencia_id = ?";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->resistencia_id);
            $stmt->execute();
            $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($materiales)) {
                error_log("No se encontraron materiales para la resistencia ID: " . $this->resistencia_id);
                return false;
            }
            
            // Insertar detalles de producción (SOLO para cálculo de costos)
            foreach ($materiales as $material) {
                $cantidad_total = $material['cantidad'] * $this->cantidad;
                
                $insert_query = "INSERT INTO " . $this->table_detalles . " 
                                (produccion_id, material_id, cantidad, unidad)
                                VALUES (:produccion_id, :material_id, :cantidad, :unidad)";
                
                $insert_stmt = $this->conn->prepare($insert_query);
                $insert_stmt->bindParam(":produccion_id", $orden_id);
                $insert_stmt->bindParam(":material_id", $material['material_id']);
                $insert_stmt->bindParam(":cantidad", $cantidad_total);
                $insert_stmt->bindParam(":unidad", $material['unidad']);
                
                if (!$insert_stmt->execute()) {
                    error_log("Error al insertar detalle de producción: " . implode(", ", $insert_stmt->errorInfo()));
                    return false;
                }
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error en crearDetallesProduccion: " . $e->getMessage());
            return false;
        }
    }

    // Actualizar orden de producción
    public function update() {
        $query = "UPDATE " . $this->table_ordenes . " 
                  SET resistencia_id = :resistencia_id, cantidad = :cantidad, 
                      cliente = :cliente, lote = :lote, usuario = :usuario
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->resistencia_id = htmlspecialchars(strip_tags($this->resistencia_id));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->cliente = htmlspecialchars(strip_tags($this->cliente));
        $this->lote = htmlspecialchars(strip_tags($this->lote));
        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        
        // Bind parameters
        $stmt->bindParam(":resistencia_id", $this->resistencia_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":cliente", $this->cliente);
        $stmt->bindParam(":lote", $this->lote);
        $stmt->bindParam(":usuario", $this->usuario);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Cambiar estado de la orden
    public function cambiarEstado($nuevo_estado) {
        $estado_actual = $this->getEstadoActual();
        
        // SOLO descontar inventario cuando cambia a 'completada'
        if ($nuevo_estado == 'completada' && $estado_actual != 'completada') {
            if (!$this->descontarInventario()) {
                return false; // No se pudo descontar el inventario
            }
        }
        
        // SOLO revertir inventario cuando cambia de 'completada' a otro estado
        if ($estado_actual == 'completada' && $nuevo_estado != 'completada') {
            if (!$this->revertirInventario()) {
                return false; // No se pudo revertir el inventario
            }
        }
        
        $query = "UPDATE " . $this->table_ordenes . " 
                  SET estado = :estado 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":estado", $nuevo_estado);
        $stmt->bindParam(":id", $this->id);
        
        return $stmt->execute();
    }

    // Obtener estado actual de la orden
    private function getEstadoActual() {
        $query = "SELECT estado FROM " . $this->table_ordenes . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['estado'] : 'pendiente';
    }

    // Descontar inventario SOLO cuando la orden se completa
    private function descontarInventario() {
        try {
            // Obtener detalles de materiales de la producción
            $detalles = $this->getDetallesMateriales();
            $materiales = $detalles->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($materiales)) {
                throw new Exception("No hay detalles de materiales para esta orden");
            }
            
            // Verificar primero que hay suficiente inventario para todos los materiales
            foreach ($materiales as $material) {
                $check_query = "SELECT cantidad_disponible FROM materiales WHERE id = ?";
                $check_stmt = $this->conn->prepare($check_query);
                $check_stmt->bindParam(1, $material['material_id']);
                $check_stmt->execute();
                $inventario_actual = $check_stmt->fetchColumn();
                
                if ($inventario_actual < $material['cantidad']) {
                    throw new Exception("Inventario insuficiente para " . $material['material_nombre'] . 
                                      ". Disponible: " . $inventario_actual . ", Requerido: " . $material['cantidad']);
                }
            }
            
            // Descontar cada material del inventario
            foreach ($materiales as $material) {
                $query = "UPDATE materiales 
                          SET cantidad_disponible = cantidad_disponible - :cantidad
                          WHERE id = :material_id";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":cantidad", $material['cantidad']);
                $stmt->bindParam(":material_id", $material['material_id']);
                
                if (!$stmt->execute()) {
                    throw new Exception("Error al descontar inventario para: " . $material['material_nombre']);
                }
                
                // Registrar movimiento en inventario
                $this->registrarMovimientoInventario($material['material_id'], $material['cantidad'], 'salida', 'Producción #' . $this->id);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error al descontar inventario: " . $e->getMessage());
            return false;
        }
    }

    // Revertir inventario (ahora público para que el controlador pueda llamarlo)
    public function revertirInventario() {
        try {
            // Obtener detalles de materiales de la producción
            $detalles = $this->getDetallesMateriales();
            $materiales = $detalles->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($materiales)) {
                throw new Exception("No hay detalles de materiales para esta orden");
            }
            
            // Revertir cada material al inventario
            foreach ($materiales as $material) {
                $query = "UPDATE materiales 
                          SET cantidad_disponible = cantidad_disponible + :cantidad
                          WHERE id = :material_id";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":cantidad", $material['cantidad']);
                $stmt->bindParam(":material_id", $material['material_id']);
                $stmt->execute();
                
                // Registrar movimiento en inventario
                $this->registrarMovimientoInventario($material['material_id'], $material['cantidad'], 'entrada', 'Reversión producción #' . $this->id);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error al revertir inventario: " . $e->getMessage());
            return false;
        }
    }

    // Registrar movimiento en inventario
    private function registrarMovimientoInventario($material_id, $cantidad, $tipo, $observaciones) {
        // Verificar si existe la tabla movimientos_inventario
        $check_query = "SELECT EXISTS (SELECT FROM information_schema.tables 
                      WHERE table_schema = 'public' 
                      AND table_name = 'movimientos_inventario')";
        
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->execute();
        $table_exists = $check_stmt->fetchColumn();
        
        if ($table_exists) {
            $query = "INSERT INTO movimientos_inventario 
                      (material_id, cantidad, tipo, observaciones, fecha, usuario)
                      VALUES (:material_id, :cantidad, :tipo, :observaciones, CURRENT_TIMESTAMP, :usuario)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":material_id", $material_id);
            $stmt->bindParam(":cantidad", $cantidad);
            $stmt->bindParam(":tipo", $tipo);
            $stmt->bindParam(":observaciones", $observaciones);
            $stmt->bindParam(":usuario", $this->usuario);
            $stmt->execute();
        }
    }

    // Obtener detalles de materiales requeridos
    public function getDetallesMateriales() {
        $query = "SELECT pd.*, m.nombre as material_nombre, m.tipo, m.unidad_salida, m.costo_unitario
                  FROM " . $this->table_detalles . " pd
                  JOIN materiales m ON pd.material_id = m.id
                  WHERE pd.produccion_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        return $stmt;
    }

    // Calcular costo total de la orden
    public function getCostoTotal() {
        $query = "SELECT COALESCE(SUM(pd.cantidad * m.costo_unitario), 0) as costo_total
                  FROM " . $this->table_detalles . " pd
                  JOIN materiales m ON pd.material_id = m.id
                  WHERE pd.produccion_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['costo_total'] : 0;
    }

    // Obtener estadísticas de producción
    public function getEstadisticas() {
        $query = "SELECT 
                    COUNT(*) as total_ordenes,
                    SUM(cantidad) as total_m3_producidos,
                    AVG(cantidad) as promedio_m3_por_orden,
                    COUNT(CASE WHEN estado = 'completada' THEN 1 END) as ordenes_completadas,
                    COUNT(CASE WHEN estado = 'pendiente' THEN 1 END) as ordenes_pendientes,
                    COUNT(CASE WHEN estado = 'produccion' THEN 1 END) as ordenes_produccion
                  FROM " . $this->table_ordenes;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
