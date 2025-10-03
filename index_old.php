<?php
// Incluir configuración
include_once 'config/database.php';

// Obtener conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Incluir modelos (con manejo de errores)
$model_files = [
    'models/Material.php',
    'models/Resistencia.php',
    'models/Entrada.php',
    'models/Produccion.php',
    'models/Inventario.php'
];

foreach ($model_files as $file) {
    if (file_exists($file)) {
        include_once $file;
    } else {
        error_log("Archivo no encontrado: " . $file);
    }
}

// Incluir controladores (con manejo de errores)
$controller_files = [
    'controllers/MaterialController.php',
    'controllers/ResistenciaController.php',
    'controllers/EntradaController.php',
    'controllers/ProduccionController.php',
    'controllers/InventarioController.php'
];

foreach ($controller_files as $file) {
    if (file_exists($file)) {
        include_once $file;
    } else {
        error_log("Archivo no encontrado: " . $file);
    }
}

// Determinar la acción solicitada
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

// Enrutamiento básico
switch($action) {
    case 'materiales':
        $controller = new MaterialController($db);
        $controller->index();
        break;
    case 'materiales_create':
        $controller = new MaterialController($db);
        $controller->create();
        break;
    case 'materiales_edit':
        $controller = new MaterialController($db);
        $controller->edit();
        break;
    case 'materiales_delete':
        $controller = new MaterialController($db);
        $controller->delete();
        break;
    case 'resistencias':
        $controller = new ResistenciaController($db);
        $controller->index();
        break;
    case 'entradas':
        $controller = new EntradaController($db);
        $controller->index();
        break;
    case 'producciones':
        $controller = new ProduccionController($db);
        $controller->index();
        break;
    case 'inventario':
        $controller = new InventarioController($db);
        $controller->index();
        break;
    default:
        include_once 'views/dashboard.php';
        break;
}
?>
