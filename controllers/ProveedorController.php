<?php
class ProveedorController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new Proveedor($db);
        $this->db = $db;
    }

    public function index() {
        $stmt = $this->model->read();
        $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calcular estadísticas de proveedores
        $stats = $this->calculateProveedorStats();
        
        // Pasar variables a la vista
        include_once 'views/proveedores/index.php';
    }

    public function view() {
        if(isset($_GET['id'])) {
            $this->model->id = $_GET['id'];
            
            if($this->model->readOne()) {
                $proveedor = [
                    'id' => $this->model->id,
                    'nombre' => $this->model->nombre,
                    'contacto' => $this->model->contacto,
                    'telefono' => $this->model->telefono,
                    'email' => $this->model->email,
                    'direccion' => $this->model->direccion,
                    'notas' => $this->model->notas,
                    'created_at' => $this->model->created_at
                ];
                
                // Obtener estadísticas de compras del proveedor
                $compras_stats = $this->getComprasStats($this->model->id);
                
                include_once 'views/proveedores/view.php';
            } else {
                echo "<div class='alert alert-danger'>No se encontró el proveedor solicitado.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>No se especificó el ID del proveedor.</div>";
        }
    }

    public function create() {
        if($_POST) {
            $this->model->nombre = $_POST['nombre'];
            $this->model->contacto = $_POST['contacto'];
            $this->model->telefono = $_POST['telefono'];
            $this->model->email = $_POST['email'];
            $this->model->direccion = $_POST['direccion'];
            $this->model->notas = $_POST['notas'];

            if($this->model->create()) {
                header("Location: index.php?action=proveedores");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al crear el proveedor.</div>";
            }
        }
        include_once 'views/proveedores/create.php';
    }

    public function edit() {
        if(isset($_GET['id'])) {
            $this->model->id = $_GET['id'];
            
            if($_POST) {
                $this->model->nombre = $_POST['nombre'];
                $this->model->contacto = $_POST['contacto'];
                $this->model->telefono = $_POST['telefono'];
                $this->model->email = $_POST['email'];
                $this->model->direccion = $_POST['direccion'];
                $this->model->notas = $_POST['notas'];

                if($this->model->update()) {
                    header("Location: index.php?action=proveedores");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Error al actualizar el proveedor.</div>";
                }
            }
            
            // Obtener datos actuales del proveedor
            if($this->model->readOne()) {
                $proveedor_actual = [
                    'id' => $this->model->id,
                    'nombre' => $this->model->nombre,
                    'contacto' => $this->model->contacto,
                    'telefono' => $this->model->telefono,
                    'email' => $this->model->email,
                    'direccion' => $this->model->direccion,
                    'notas' => $this->model->notas
                ];
                
                include_once 'views/proveedores/edit.php';
            } else {
                echo "<div class='alert alert-danger'>No se encontró el proveedor solicitado.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>No se especificó el ID del proveedor.</div>";
        }
    }

    public function delete() {
        if(isset($_GET['id'])) {
            $this->model->id = $_GET['id'];

            if($this->model->delete()) {
                header("Location: index.php?action=proveedores");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al eliminar el proveedor.</div>";
            }
        }
    }

    // Método para calcular estadísticas de proveedores - CORREGIDO para PostgreSQL
    private function calculateProveedorStats() {
        $stats = [
            'total_proveedores' => 0,
            'proveedores_con_compras' => 0,
            'total_compras' => 0,
            'monto_total_compras' => 0
        ];

        try {
            // Obtener estadísticas básicas
            $query = "SELECT COUNT(*) as total FROM proveedores";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_proveedores'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Obtener estadísticas de compras
            $query = "SELECT 
                         COUNT(DISTINCT proveedor_id) as proveedores_con_compras,
                         COUNT(*) as total_compras,
                         COALESCE(SUM(costo_total), 0) as monto_total
                      FROM entradas 
                      WHERE proveedor_id IS NOT NULL";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $compras_stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $stats['proveedores_con_compras'] = $compras_stats['proveedores_con_compras'] ?? 0;
            $stats['total_compras'] = $compras_stats['total_compras'] ?? 0;
            $stats['monto_total_compras'] = $compras_stats['monto_total'] ?? 0;
            
        } catch (PDOException $e) {
            error_log("Error al calcular estadísticas de proveedores: " . $e->getMessage());
        }
        
        return $stats;
    }

    // Método para obtener estadísticas de compras por proveedor - CORREGIDO para PostgreSQL
    private function getComprasStats($proveedor_id) {
        $stats = [
            'total_compras' => 0,
            'total_materiales' => 0,
            'monto_total' => 0,
            'ultima_compra' => null
        ];

        try {
            $query = "SELECT 
                         COUNT(*) as total_compras,
                         COALESCE(SUM(cantidad), 0) as total_materiales,
                         COALESCE(SUM(costo_total), 0) as monto_total,
                         MAX(fecha) as ultima_compra
                      FROM entradas 
                      WHERE proveedor_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$proveedor_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $stats['total_compras'] = $result['total_compras'] ?? 0;
                $stats['total_materiales'] = $result['total_materiales'] ?? 0;
                $stats['monto_total'] = $result['monto_total'] ?? 0;
                $stats['ultima_compra'] = $result['ultima_compra'] ?? null;
            }
            
        } catch (PDOException $e) {
            error_log("Error al obtener estadísticas de compras: " . $e->getMessage());
        }
        
        return $stats;
    }
}
?>
