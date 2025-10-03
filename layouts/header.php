<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Concreto Premezclado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f4f4f4; }
        .container { max-width: 1400px; margin: 0 auto; }
        .header { 
            background: #2c3e50; 
            color: white; 
            padding: 20px; 
            border-radius: 8px; 
            margin-bottom: 20px; 
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .back-button {
            background: #95a5a6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            display: inline-block;
            transition: background 0.3s;
        }
        .back-button:hover {
            background: #7f8c8d;
            text-decoration: none;
            color: white;
        }
        .navigation {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .nav-button {
            background: #3498db;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-button:hover {
            background: #2980b9;
            text-decoration: none;
            color: white;
        }
        .card { 
            background: white; 
            padding: 25px; 
            border-radius: 8px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
            margin-bottom: 25px; 
            border: none;
        }
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
            gap: 20px; 
            margin-bottom: 25px; 
        }
        .stat-card { 
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .stat-number { 
            font-size: 32px; 
            font-weight: bold; 
            margin: 10px 0;
        }
        .stat-title {
            font-size: 16px;
            opacity: 0.9;
        }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #2c3e50; }
        input, select, textarea { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 16px;
            transition: border 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        button { 
            padding: 12px 25px; 
            background: #2ecc71; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px;
            transition: background 0.3s;
        }
        button:hover { background: #27ae60; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        th, td { 
            padding: 15px; 
            text-align: left; 
            border-bottom: 1px solid #ecf0f1; 
        }
        th { 
            background: #34495e; 
            color: white; 
            font-weight: bold;
            position: sticky;
            top: 0;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .alert { 
            padding: 15px; 
            border-radius: 5px; 
            margin-bottom: 20px; 
            border: 1px solid transparent;
        }
        .alert-success { 
            background: #d4edda; 
            color: #155724; 
            border-color: #c3e6cb; 
        }
        .alert-error { 
            background: #f8d7da; 
            color: #721c24; 
            border-color: #f5c6cb; 
        }
        .stock-low { background: #fff3cd !important; color: #856404; }
        .stock-out { background: #f8d7da !important; color: #721c24; }
        .tabs { 
            display: flex; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #ecf0f1;
        }
        .tab { 
            padding: 15px 30px; 
            background: #ecf0f1; 
            border: none; 
            cursor: pointer; 
            font-size: 16px;
            transition: all 0.3s;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            margin-right: 5px;
        }
        .tab.active { 
            background: #3498db; 
            color: white; 
            font-weight: bold;
        }
        .tab:hover:not(.active) {
            background: #d6dbdf;
        }
        .tab-content { 
            display: none; 
            animation: fadeIn 0.5s;
        }
        .tab-content.active { 
            display: block; 
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .btn-edit {
            background: #3498db;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            margin-right: 5px;
            transition: background 0.3s;
        }
        .btn-edit:hover {
            background: #2980b9;
            text-decoration: none;
            color: white;
        }
        .btn-delete {
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            transition: background 0.3s;
        }
        .btn-delete:hover {
            background: #c0392b;
            text-decoration: none;
            color: white;
        }
        .btn-add {
            background: #2ecc71;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 16px;
            margin-bottom: 20px;
            transition: background 0.3s;
        }
        .btn-add:hover {
            background: #27ae60;
            text-decoration: none;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .search-bar {
            display: flex;
            margin-bottom: 20px;
            gap: 10px;
        }
        .search-input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .search-button {
            padding: 12px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .card-title {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header con botón de regreso -->
        <div class="header">
            <h1><i class="fas fa-industry"></i> Planta de Concreto Premezclado</h1>
            <div>
                <span style="margin-right: 15px;">Hola, <?php echo $_SESSION['username'] ?? 'Usuario'; ?></span>
                <a href="../includes/logout.php" class="back-button"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
            </div>
        </div>

        <!-- Navegación rápida -->
        <div class="navigation">
            <a href="index.php" class="nav-button"><i class="fas fa-home"></i> Dashboard</a>
            <a href="index.php?action=materiales" class="nav-button"><i class="fas fa-cubes"></i> Materiales</a>
            <a href="index.php?action=resistencias" class="nav-button"><i class="fas fa-tachometer-alt"></i> Resistencias</a>
            <a href="index.php?action=entradas" class="nav-button"><i class="fas fa-arrow-down"></i> Entradas</a>
            <a href="index.php?action=producciones" class="nav-button"><i class="fas fa-arrow-up"></i> Producción</a>
            <a href="index.php?action=inventario" class="nav-button"><i class="fas fa-boxes"></i> Inventario</a>
        </div>

        <?php if (isset($message) && $message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (isset($error) && $error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
