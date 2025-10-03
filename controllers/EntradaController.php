<?php
class EntradaController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new Entrada($db);
        $this->db = $db;
    }

    public function index() {
        $stmt = $this->model->read();
        
        // Obtener estadísticas
        $stats = $this->model->getEstadisticasCostos();
        
        // Obtener análisis de compras por proveedor
        $analisis_compras = $this->model->getAnalisisCompras();
        
        include_once 'views/entradas/index.php';
    }

    public function create() {
        // Obtener lista de materiales y proveedores para los dropdowns
        $materialModel = new Material($this->db);
        $materiales = $materialModel->read();

        $proveedorModel = new Proveedor($this->db);
        $proveedores = $proveedorModel->read();

        if ($_POST) {
            $this->model->material_id = $_POST['material_id'];
            $this->model->cantidad = $_POST['cantidad'];
            $this->model->fecha = $_POST['fecha'];
            $this->model->lote = $_POST['lote'];
            $this->model->proveedor = $_POST['proveedor']; // Este campo se mantiene por compatibilidad, pero se usará proveedor_id
            $this->model->usuario = $_SESSION['username'] ?? 'Sistema';
            $this->model->proveedor_id = $_POST['proveedor_id'];
            $this->model->factura = $_POST['factura'];
            $this->model->fecha_factura = $_POST['fecha_factura'];
            $this->model->precio_unitario = $_POST['precio_unitario'];
            $this->model->iva = $_POST['iva'];
            $this->model->total_factura = $_POST['total_factura'];
            $this->model->observaciones = $_POST['observaciones'];

            if ($this->model->create()) {
                header("Location: index.php?action=entradas");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al registrar la entrada.</div>";
            }
        }

        include_once 'views/entradas/create.php';
    }

    public function historial_precios() {
        $material_id = $_GET['material_id'] ?? null;
        
        if ($material_id) {
            $historial = $this->model->getHistorialPrecios($material_id);
            
            // Obtener información del material
            $materialModel = new Material($this->db);
            $materialModel->id = $material_id;
            $material_info = [];
            if ($materialModel->readOne()) {
                $material_info = [
                    'id' => $materialModel->id,
                    'nombre' => $materialModel->nombre,
                    'unidad_entrada' => $materialModel->unidad_entrada
                ];
            }
            
            include_once 'views/entradas/historial_precios.php';
        } else {
            echo "<div class='alert alert-danger'>No se especificó el material.</div>";
        }
    }
}
?>
