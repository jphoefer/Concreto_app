<?php
class ResistenciaController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new Resistencia($db);
        $this->db = $db;
    }

    public function index() {
        $stmt = $this->model->read();
        
        // Obtener análisis de rentabilidad para cada resistencia
        $resistencias_con_analisis = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->model->id = $row['id'];
            $row['costo_total'] = $this->model->getCostoTotal();
            $row['analisis'] = $this->model->getAnalisisRentabilidad();
            $resistencias_con_analisis[] = $row;
        }
        
        // Obtener estadísticas generales
        $stats = $this->model->getEstadisticasCostos();
        
        include_once 'views/resistencias/index.php';
    }

    public function create() {
        if ($_POST) {
            $this->model->nombre = $_POST['nombre'];
            $this->model->descripcion = $_POST['descripcion'];
            $this->model->precio_venta = $_POST['precio_venta'];

            if ($this->model->create()) {
                header("Location: index.php?action=resistencias");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al crear la resistencia.</div>";
            }
        }
        include_once 'views/resistencias/create.php';
    }

    public function edit() {
        if (isset($_GET['id'])) {
            $this->model->id = $_GET['id'];

            // Determinar el modo de edición
            $modo = isset($_GET['modo']) ? $_GET['modo'] : 'resistencia';
            
            // Modo: Editar material específico
            if ($modo === 'material' && isset($_GET['detalle_id'])) {
                $this->editarMaterial($_GET['detalle_id']);
                return;
            }
            
            // Modo: Editar información básica de la resistencia
            if ($_POST) {
                if (isset($_POST['editar_resistencia'])) {
                    // Procesar edición de resistencia
                    $this->model->nombre = $_POST['nombre'];
                    $this->model->descripcion = $_POST['descripcion'];
                    $this->model->precio_venta = $_POST['precio_venta'];

                    if ($this->model->update()) {
                        header("Location: index.php?action=resistencias_view&id=" . $this->model->id);
                        exit();
                    } else {
                        echo "<div class='alert alert-danger'>Error al actualizar la resistencia.</div>";
                    }
                } elseif (isset($_POST['agregar_material'])) {
                    // Procesar agregar material
                    $this->agregarMaterialDesdeEdit();
                    return;
                }
            }

            if ($this->model->readOne()) {
                $resistencia_actual = [
                    'id' => $this->model->id,
                    'nombre' => $this->model->nombre,
                    'descripcion' => $this->model->descripcion,
                    'precio_venta' => $this->model->precio_venta,
                    'fecha_creacion' => $this->model->fecha_creacion
                ];

                // Obtener los detalles de la resistencia (materiales)
                $detalles = $this->model->getDetalles();

                // Obtener lista de materiales para agregar
                $materialModel = new Material($this->db);
                $materiales = $materialModel->read();

                include_once 'views/resistencias/edit.php';
            } else {
                echo "<div class='alert alert-danger'>No se encontró la resistencia solicitada.</div>";
            }
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->model->id = $_GET['id'];

            if ($this->model->delete()) {
                header("Location: index.php?action=resistencias");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al eliminar la resistencia.</div>";
            }
        }
    }

    public function view() {
        if (isset($_GET['id'])) {
            $this->model->id = $_GET['id'];

            if ($this->model->readOne()) {
                $resistencia_actual = [
                    'id' => $this->model->id,
                    'nombre' => $this->model->nombre,
                    'descripcion' => $this->model->descripcion,
                    'precio_venta' => $this->model->precio_venta,
                    'fecha_creacion' => $this->model->fecha_creacion
                ];

                // Obtener los detalles de la resistencia (materiales)
                $detalles = $this->model->getDetalles();

                // Obtener análisis de rentabilidad
                $analisis = $this->model->getAnalisisRentabilidad();

                // Obtener lista de materiales para agregar
                $materialModel = new Material($this->db);
                $materiales = $materialModel->read();

                include_once 'views/resistencias/view.php';
            } else {
                echo "<div class='alert alert-danger'>No se encontró la resistencia solicitada.</div>";
            }
        }
    }

    public function agregarMaterial() {
        if (isset($_POST['resistencia_id']) && isset($_POST['material_id']) && isset($_POST['cantidad']) && isset($_POST['unidad'])) {
            $this->model->id = $_POST['resistencia_id'];
            if ($this->model->agregarMaterial($_POST['material_id'], $_POST['cantidad'], $_POST['unidad'])) {
                header("Location: index.php?action=resistencias_view&id=" . $this->model->id);
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al agregar el material.</div>";
            }
        }
    }

    private function agregarMaterialDesdeEdit() {
        if (isset($_POST['resistencia_id']) && isset($_POST['material_id']) && isset($_POST['cantidad']) && isset($_POST['unidad'])) {
            $this->model->id = $_POST['resistencia_id'];
            if ($this->model->agregarMaterial($_POST['material_id'], $_POST['cantidad'], $_POST['unidad'])) {
                header("Location: index.php?action=resistencias_edit&id=" . $this->model->id);
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al agregar el material.</div>";
            }
        }
    }

    public function eliminarMaterial() {
        if (isset($_GET['resistencia_id']) && isset($_GET['detalle_id'])) {
            $this->model->id = $_GET['resistencia_id'];
            if ($this->model->eliminarMaterial($_GET['detalle_id'])) {
                header("Location: index.php?action=resistencias_edit&id=" . $this->model->id);
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al eliminar el material.</div>";
            }
        }
    }

    private function editarMaterial($detalle_id) {
        $detalle = $this->model->getDetalle($detalle_id);
        
        if ($_POST) {
            $cantidad = $_POST['cantidad'];
            $unidad = $_POST['unidad'];
            
            if ($this->model->actualizarMaterial($detalle_id, $cantidad, $unidad)) {
                header("Location: index.php?action=resistencias_edit&id=" . $_POST['resistencia_id']);
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al actualizar el material.</div>";
            }
        }
        
        // Pasar variables a la vista
        $resistencia_actual = [
            'id' => $detalle['resistencia_id'],
            'nombre' => '',
            'descripcion' => '',
            'precio_venta' => '',
            'fecha_creacion' => ''
        ];
        
        // Cargar información de la resistencia si es necesario
        $this->model->id = $detalle['resistencia_id'];
        if ($this->model->readOne()) {
            $resistencia_actual['nombre'] = $this->model->nombre;
            $resistencia_actual['descripcion'] = $this->model->descripcion;
            $resistencia_actual['precio_venta'] = $this->model->precio_venta;
            $resistencia_actual['fecha_creacion'] = $this->model->fecha_creacion;
        }
        
        include_once 'views/resistencias/edit.php';
    }
}
?>
