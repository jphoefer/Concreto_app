<?php
class ProduccionController {
    private $model;

    public function __construct($db) {
        $this->model = new Produccion($db);
    }

    public function index() {
        $stmt = $this->model->read();
        include_once 'views/producciones/index.php';
    }
}
?>
