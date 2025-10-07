<?php
class ProduccionController {
    private $model;
    private $clienteModel;
    private $resistenciaModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new Produccion($db);
        $this->clienteModel = new Cliente($db);
        $this->resistenciaModel = new Resistencia($db);
    }

    public function index() {
        try {
            // Obtener estadísticas
            $stats = $this->model->getEstadisticas();
            
            // Obtener todas las órdenes de producción
            $stmt = $this->model->read();
            $ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Incluir la vista
            include_once 'views/producciones/index.php';
            
        } catch (Exception $e) {
            $error = "Error al cargar las órdenes de producción: " . $e->getMessage();
            // Inicializar variables para evitar errores en la vista
            $stats = [
                'total_ordenes' => 0,
                'total_m3_producidos' => 0,
                'promedio_m3_por_orden' => 0,
                'ordenes_completadas' => 0,
                'ordenes_pendientes' => 0
            ];
            $ordenes = [];
            include_once 'views/producciones/index.php';
        }
    }

    public function create() {
        try {
            // Obtener datos para los selects
            $resistencias = $this->resistenciaModel->read()->fetchAll(PDO::FETCH_ASSOC);
            $clientes = $this->clienteModel->read()->fetchAll(PDO::FETCH_ASSOC);
            
            if ($_POST) {
                // Procesar formulario de creación
                $this->model->resistencia_id = $_POST['resistencia_id'];
                $this->model->cantidad = $_POST['cantidad'];
                
                // Determinar el valor del cliente (del select o del campo nuevo)
                $cliente_valor = '';
                if (!empty($_POST['cliente_nuevo'])) {
                    $cliente_valor = $_POST['cliente_nuevo'];
                } else {
                    $cliente_valor = $_POST['cliente'];
                }
                $this->model->cliente = $cliente_valor;
                
                $this->model->lote = $_POST['lote'];
                $this->model->usuario = $_SESSION['username'] ?? 'Sistema';
                
                if ($orden_id = $this->model->create()) {
                    $message = "Orden de producción #$orden_id creada exitosamente";
                    header("Location: index.php?action=producciones&message=" . urlencode($message));
                    exit();
                } else {
                    $error = "Error al crear la orden de producción. Verifique que la resistencia tenga materiales configurados.";
                }
            }
            
            // Incluir vista de creación
            include_once 'views/producciones/create.php';
            
        } catch (Exception $e) {
            $error = "Error al crear orden de producción: " . $e->getMessage();
            include_once 'views/producciones/create.php';
        }
    }

    public function edit() {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception("ID de orden no especificado");
            }
            
            $this->model->id = $_GET['id'];
            $orden = $this->model->readOne();
            
            if (!$orden) {
                throw new Exception("Orden de producción no encontrada");
            }
            
            // Obtener datos para los selects
            $resistencias = $this->resistenciaModel->read()->fetchAll(PDO::FETCH_ASSOC);
            $clientes = $this->clienteModel->read()->fetchAll(PDO::FETCH_ASSOC);
            
            if ($_POST) {
                // Procesar actualización
                $this->model->resistencia_id = $_POST['resistencia_id'];
                $this->model->cantidad = $_POST['cantidad'];
                
                // Determinar el valor del cliente (del select o del campo nuevo)
                $cliente_valor = '';
                if (!empty($_POST['cliente_nuevo'])) {
                    $cliente_valor = $_POST['cliente_nuevo'];
                } else {
                    $cliente_valor = $_POST['cliente'];
                }
                $this->model->cliente = $cliente_valor;
                
                $this->model->lote = $_POST['lote'];
                $this->model->usuario = $_SESSION['username'] ?? 'Sistema';
                
                if ($this->model->update()) {
                    $message = "Orden de producción actualizada exitosamente";
                    header("Location: index.php?action=producciones&message=" . urlencode($message));
                    exit();
                } else {
                    $error = "Error al actualizar la orden de producción";
                }
            }
            
            include_once 'views/producciones/edit.php';
            
        } catch (Exception $e) {
            $error = $e->getMessage();
            include_once 'views/producciones/edit.php';
        }
    }

    public function view() {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception("ID de orden no especificado");
            }
            
            $this->model->id = $_GET['id'];
            $orden = $this->model->readOne();
            
            if (!$orden) {
                throw new Exception("Orden de producción no encontrada");
            }
            
            // Obtener detalles de materiales
            $detalles = $this->model->getDetallesMateriales();
            $materiales_detalle = $detalles->fetchAll(PDO::FETCH_ASSOC);
            
            // Calcular costo total
            $costo_total = $this->model->getCostoTotal();
            
            include_once 'views/producciones/view.php';
            
        } catch (Exception $e) {
            $error = $e->getMessage();
            // Asegurar que las variables estén definidas incluso en caso de error
            $orden = [];
            $materiales_detalle = [];
            $costo_total = 0;
            include_once 'views/producciones/view.php';
        }
    }

    public function cambiarEstado() {
        try {
            if (!isset($_GET['id']) || !isset($_POST['nuevo_estado'])) {
                throw new Exception("Datos incompletos para cambiar estado");
            }
            
            $this->model->id = $_GET['id'];
            $nuevo_estado = $_POST['nuevo_estado'];
            
            if ($this->model->cambiarEstado($nuevo_estado)) {
                $message = "Estado de la orden actualizado a: " . $nuevo_estado;
                
                // Mensaje adicional si se completó la orden
                if ($nuevo_estado == 'completada') {
                    $message .= ". Inventario descontado correctamente.";
                }
                
            } else {
                $error = "Error al cambiar el estado de la orden. ";
                
                // Mensaje específico para inventario insuficiente
                if ($nuevo_estado == 'completada') {
                    $error .= "No hay suficiente inventario para completar esta orden.";
                } else {
                    $error .= "Verifique los datos e intente nuevamente.";
                }
            }
            
            header("Location: index.php?action=producciones_view&id=" . $this->model->id . "&message=" . urlencode($message ?? ($error ?? '')));
            exit();
            
        } catch (Exception $e) {
            $error = $e->getMessage();
            header("Location: index.php?action=producciones_view&id=" . $_GET['id'] . "&error=" . urlencode($error));
            exit();
        }
    }

    public function delete() {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception("ID de orden no especificado");
            }
            
            $this->model->id = $_GET['id'];
            
            // Primero verificar que existe
            $orden = $this->model->readOne();
            if (!$orden) {
                throw new Exception("Orden de producción no encontrada");
            }
            
            // Si la orden está completada, revertir el inventario primero
            if (isset($orden['estado']) && $orden['estado'] == 'completada') {
                if (!$this->model->revertirInventario()) {
                    throw new Exception("Error al revertir el inventario. No se puede eliminar la orden.");
                }
            }
            
            // Eliminar la orden (las FK con CASCADE eliminarán los detalles)
            $query = "DELETE FROM producciones WHERE id = ?";
            $stmt = $this->db->prepare($query);
            
            if ($stmt->execute([$this->model->id])) {
                $message = "Orden de producción eliminada exitosamente";
            } else {
                $error = "Error al eliminar la orden de producción";
            }
            
            header("Location: index.php?action=producciones&message=" . urlencode($message ?? ''));
            exit();
            
        } catch (Exception $e) {
            $error = $e->getMessage();
            header("Location: index.php?action=producciones&error=" . urlencode($error));
            exit();
        }
    }
}
?>
