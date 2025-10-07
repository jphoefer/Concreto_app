<?php
// Incluir configuración
include_once 'config/database.php';

// Obtener conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Incluir modelos (con manejo de errores)
$model_files = [
    'models/Produccion.php',
    'models/Cliente.php',
    'models/Inventario.php',
    'models/Resistencia.php',
    'models/Entrada.php',
    'models/Material.php',
    'models/Proveedor.php'
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
    'controllers/ProduccionController.php',
    'controllers/ClienteController.php',
    'controllers/InventarioController.php',
    'controllers/ResistenciaController.php',
    'controllers/EntradaController.php',
	'controllers/MaterialController.php',
    'controllers/ProveedorController.php'
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
        
    // Módulo de Resistencias
    case 'resistencias':
        $controller = new ResistenciaController($db);
        $controller->index();
        break;
    case 'resistencias_create':
        $controller = new ResistenciaController($db);
        $controller->create();
        break;
    case 'resistencias_edit':
        $controller = new ResistenciaController($db);
        $controller->edit();
        break;
    case 'resistencias_delete':
        $controller = new ResistenciaController($db);
        $controller->delete();
        break;
    case 'resistencias_view':
        $controller = new ResistenciaController($db);
        $controller->view();
        break;
    case 'resistencias_agregar_material':
        $controller = new ResistenciaController($db);
        $controller->agregarMaterial();
        break;
    case 'resistencias_eliminar_material':
        $controller = new ResistenciaController($db);
        $controller->eliminarMaterial();
        break;
    case 'resistencias_editar_material':
        $controller = new ResistenciaController($db);
        $controller->editarMaterial();
        break;
        
    // Módulo de Entradas Mejorado
    case 'entradas':
        $controller = new EntradaController($db);
        $controller->index();
        break;
    case 'entradas_create':
        $controller = new EntradaController($db);
        $controller->create();
        break;
    case 'entradas_historial_precios':
        $controller = new EntradaController($db);
        $controller->historial_precios();
        break;

    // Módulo de Producciones
    case 'producciones':
        $controller = new ProduccionController($db);
        $controller->index();
        break;
    case 'producciones_create':
        $controller = new ProduccionController($db);
        $controller->create();
        break;
    case 'producciones_edit':
        $controller = new ProduccionController($db);
        $controller->edit();
        break;
    case 'producciones_view':
        $controller = new ProduccionController($db);
        $controller->view();
        break;
    case 'producciones_delete':
        $controller = new ProduccionController($db);
        $controller->delete();
        break;
    case 'producciones_cambiar_estado':
        $controller = new ProduccionController($db);
        $controller->cambiarEstado();
        break;

    // Módulo de Inventario
    case 'inventario':
        $controller = new InventarioController($db);
        $controller->index();
        break;

    // Módulo de Proveedores
    case 'proveedores':
        $controller = new ProveedorController($db);
        $controller->index();
        break;
    case 'proveedores_create':
        $controller = new ProveedorController($db);
        $controller->create();
        break;
    case 'proveedores_edit':
        $controller = new ProveedorController($db);
        $controller->edit();
        break;
    case 'proveedores_view':
        $controller = new ProveedorController($db);
        $controller->view();
        break;
    case 'proveedores_delete':
        $controller = new ProveedorController($db);
        $controller->delete();
        break;

    // Módulo de Clientes
    case 'clientes':
        $controller = new ClienteController($db);
        $controller->index();
        break;
    case 'clientes_create':
        $controller = new ClienteController($db);
        $controller->create();
        break;
    case 'clientes_edit':
        $controller = new ClienteController($db);
        $controller->edit();
        break;
    case 'clientes_view':
        $controller = new ClienteController($db);
        $controller->view();
        break;
    case 'clientes_delete':
        $controller = new ClienteController($db);
        $controller->delete();
        break;
    case 'clientes_search':
        $controller = new ClienteController($db);
        $controller->search();
        break;

    // Dashboard por defecto
    default:
        include_once 'views/dashboard.php';
        break;
}
?>
