<?php
class MaterialController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new Material($db);
        $this->db = $db;
    }

    public function index() {
        $stmt = $this->model->read();
        
        // Calcular costos de inventario para cada material
        $materiales_con_costo = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->model->id = $row['id'];
            $row['costo_inventario'] = $this->model->getCostoInventario();
            $materiales_con_costo[] = $row;
        }
        
        // Calcular estadísticas
        $stats = $this->calculateMaterialStats();
        
        // Pasar variables a la vista
        include_once 'views/materiales/index.php';
    }

    public function create() {
        if($_POST) {
            $this->model->nombre = $_POST['nombre'];
            $this->model->tipo = $_POST['tipo'];
            $this->model->unidad_entrada = $_POST['unidad_entrada'];
            $this->model->unidad_salida = $_POST['unidad_salida'];
            $this->model->densidad = $_POST['densidad'];
            $this->model->costo_unitario = $_POST['costo_unitario'];
            $this->model->estado = isset($_POST['estado']) ? 1 : 0;

            if($this->model->create()) {
                header("Location: index.php?action=materiales");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al crear el material.</div>";
            }
        }
        include_once 'views/materiales/create.php';
    }

    public function edit() {
        if(isset($_GET['id'])) {
            $this->model->id = $_GET['id'];
            
            // DEBUG: Verificar que el ID se está recibiendo correctamente
            error_log("Editando material ID: " . $this->model->id);
            
            if($_POST) {
                $this->model->nombre = $_POST['nombre'];
                $this->model->tipo = $_POST['tipo'];
                $this->model->unidad_entrada = $_POST['unidad_entrada'];
                $this->model->unidad_salida = $_POST['unidad_salida'];
                $this->model->densidad = $_POST['densidad'];
                $this->model->costo_unitario = $_POST['costo_unitario'];
                $this->model->estado = isset($_POST['estado']) ? 1 : 0;

                if($this->model->update()) {
                    header("Location: index.php?action=materiales");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Error al actualizar el material.</div>";
                }
            }
            
            // Obtener datos actuales del material
            if($this->model->readOne()) {
                $material_actual = [
                    'id' => $this->model->id,
                    'nombre' => $this->model->nombre,
                    'tipo' => $this->model->tipo,
                    'unidad_entrada' => $this->model->unidad_entrada,
                    'unidad_salida' => $this->model->unidad_salida,
                    'densidad' => $this->model->densidad,
                    'costo_unitario' => $this->model->costo_unitario,
                    'estado' => $this->model->estado
                ];
                
                // DEBUG: Verificar datos cargados
                error_log("Material cargado: " . print_r($material_actual, true));
                
                include_once 'views/materiales/edit.php';
            } else {
                // DEBUG: Información adicional sobre el error
                error_log("No se pudo cargar el material con ID: " . $this->model->id);
                echo "<div class='alert alert-danger'>No se encontró el material solicitado. ID: " . $this->model->id . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>No se especificó el ID del material.</div>";
        }
    }

    public function delete() {
        if(isset($_GET['id'])) {
            $this->model->id = $_GET['id'];

            if($this->model->delete()) {
                header("Location: index.php?action=materiales");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al eliminar el material.</div>";
            }
        }
    }

    // Método para calcular estadísticas de materiales con costos
    private function calculateMaterialStats() {
        $stats = [
            'total_materiales' => 0,
            'materiales_activos' => 0,
            'costo_total_inventario' => 0,
            'por_tipo' => [
                'agregado' => 0,
                'cemento' => 0,
                'aditivo_liquido' => 0,
                'aditivo_solido' => 0,
                'agua' => 0
            ]
        ];

        try {
            // Obtener todos los materiales
            $query = "SELECT * FROM materiales";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $stats['total_materiales']++;
                
                if ($row['estado']) {
                    $stats['materiales_activos']++;
                }
                
                // Contar por tipo
                if (isset($stats['por_tipo'][$row['tipo']])) {
                    $stats['por_tipo'][$row['tipo']]++;
                }
                
                // Calcular costo total del inventario
                $this->model->id = $row['id'];
                $stats['costo_total_inventario'] += $this->model->getCostoInventario();
            }
            
        } catch (PDOException $e) {
            error_log("Error al calcular estadísticas: " . $e->getMessage());
        }
        
        return $stats;
    }
}
?>
