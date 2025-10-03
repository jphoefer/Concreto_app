<?php
class InventarioController {
    private $model;

    public function __construct($db) {
        $this->model = new Inventario($db);
    }

    public function index() {
        $stmt = $this->model->read();
        include_once 'views/inventario/index.php';
    }
}
?>
