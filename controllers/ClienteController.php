<?php
class ClienteController {
    private $model;
    private $db;

    public function __construct($db) {
        $this->model = new Cliente($db);
        $this->db = $db;
    }

    public function index() {
        $stmt = $this->model->read();
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calcular estadísticas
        $stats = $this->calculateClienteStats();
        
        // Pasar variables a la vista
        include_once 'views/clientes/index.php';
    }

    public function view() {
        if(isset($_GET['id'])) {
            $this->model->id = $_GET['id'];
            
            if($this->model->readOne()) {
                $cliente = [
                    'id' => $this->model->id,
                    'nombre' => $this->model->nombre,
                    'contacto' => $this->model->contacto,
                    'telefono' => $this->model->telefono,
                    'email' => $this->model->email,
                    'direccion' => $this->model->direccion,
                    'notas' => $this->model->notas,
                    'estado' => $this->model->estado,
                    'created_at' => $this->model->created_at
                ];
                
                include_once 'views/clientes/view.php';
            } else {
                echo "<div class='alert alert-danger'>No se encontró el cliente solicitado.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>No se especificó el ID del cliente.</div>";
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
            $this->model->estado = isset($_POST['estado']) ? 1 : 0;

            if($this->model->create()) {
                header("Location: index.php?action=clientes");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al crear el cliente.</div>";
            }
        }
        include_once 'views/clientes/create.php';
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
                $this->model->estado = isset($_POST['estado']) ? 1 : 0;

                if($this->model->update()) {
                    header("Location: index.php?action=clientes");
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Error al actualizar el cliente.</div>";
                }
            }
            
            // Obtener datos actuales del cliente
            if($this->model->readOne()) {
                $cliente_actual = [
                    'id' => $this->model->id,
                    'nombre' => $this->model->nombre,
                    'contacto' => $this->model->contacto,
                    'telefono' => $this->model->telefono,
                    'email' => $this->model->email,
                    'direccion' => $this->model->direccion,
                    'notas' => $this->model->notas,
                    'estado' => $this->model->estado
                ];
                
                include_once 'views/clientes/edit.php';
            } else {
                echo "<div class='alert alert-danger'>No se encontró el cliente solicitado.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>No se especificó el ID del cliente.</div>";
        }
    }

    public function delete() {
        if(isset($_GET['id'])) {
            $this->model->id = $_GET['id'];

            if($this->model->delete()) {
                header("Location: index.php?action=clientes");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Error al eliminar el cliente.</div>";
            }
        }
    }

    public function search() {
        if(isset($_POST['search'])) {
            $keywords = $_POST['keywords'];
            $stmt = $this->model->search($keywords);
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            include_once 'views/clientes/index.php';
        } else {
            $this->index();
        }
    }

    // Método para calcular estadísticas de clientes - CORREGIDO para PostgreSQL
    private function calculateClienteStats() {
        $stats = [
            'total_clientes' => 0,
            'clientes_activos' => 0,
            'clientes_inactivos' => 0
        ];

        try {
            // Total clientes
            $query = "SELECT COUNT(*) as total FROM clientes";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['total_clientes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Clientes activos
            $query = "SELECT COUNT(*) as activos FROM clientes WHERE estado = true";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['clientes_activos'] = $stmt->fetch(PDO::FETCH_ASSOC)['activos'];

            // Clientes inactivos
            $stats['clientes_inactivos'] = $stats['total_clientes'] - $stats['clientes_activos'];
            
        } catch (PDOException $e) {
            error_log("Error al calcular estadísticas de clientes: " . $e->getMessage());
        }
        
        return $stats;
    }
}
?>
