<?php 
$headerPath = __DIR__ . '/../../layouts/header.php';
$footerPath = __DIR__ . '/../../layouts/footer.php';

if (file_exists($headerPath)) {
    include_once $headerPath;
} else {
    die('Error: No se puede encontrar el archivo header.php');
}
?>

<div class="container">
    <div class="header">
        <h2><i class="fas fa-cubes"></i> Gestión de Materiales</h2>
        <a href="index.php" class="back-button"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Materiales</div>
            <div class="stat-number"><?php echo $stats['total_materiales']; ?></div>
            <div class="stat-desc">Registrados en el sistema</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Materiales Activos</div>
            <div class="stat-number"><?php echo $stats['materiales_activos']; ?></div>
            <div class="stat-desc">Disponibles para uso</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Costo Total Inventario</div>
            <div class="stat-number">$<?php echo number_format($stats['costo_total_inventario'], 2); ?></div>
            <div class="stat-desc">Valor del inventario</div>
        </div>
    </div>

    <!-- Pestañas -->
    <div class="tabs">
        <div class="tab active" onclick="showTab('lista-materiales')"><i class="fas fa-list"></i> Lista de Materiales</div>
        <div class="tab" onclick="showTab('agregar-material')"><i class="fas fa-plus"></i> Agregar Material</div>
        <div class="tab" onclick="showTab('estadisticas')"><i class="fas fa-chart-bar"></i> Estadísticas</div>
    </div>

    <!-- Pestaña: Lista de Materiales -->
    <div id="lista-materiales" class="tab-content active">
        <div class="search-bar">
            <input type="text" class="search-input" placeholder="Buscar material..." onkeyup="searchMaterials()" id="searchInput">
            <button class="search-button" onclick="searchMaterials()"><i class="fas fa-search"></i> Buscar</button>
        </div>

        <a href="#" class="btn-add" onclick="showTab('agregar-material'); return false;">
            <i class="fas fa-plus"></i> Nuevo Material
        </a>

        <div class="card">
            <h3 class="card-title">Lista de Materiales Registrados</h3>
            <table id="materials-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Unidad Entrada</th>
                        <th>Unidad Salida</th>
                        <th>Costo Unitario</th>
                        <th>Costo Inventario</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materiales_con_costo as $material): ?>
                    <tr>
                        <td><?php echo $material['id']; ?></td>
                        <td><?php echo $material['nombre']; ?></td>
                        <td><?php echo ucfirst(str_replace('_', ' ', $material['tipo'])); ?></td>
                        <td><?php echo $material['unidad_entrada']; ?></td>
                        <td><?php echo $material['unidad_salida']; ?></td>
                        <td>$<?php echo number_format($material['costo_unitario'], 2); ?></td>
                        <td>$<?php echo number_format($material['costo_inventario'], 2); ?></td>
                        <td>
                            <span class="status-badge <?php echo $material['estado'] ? 'status-active' : 'status-inactive'; ?>">
                                <?php echo $material['estado'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="index.php?action=materiales_edit&id=<?php echo $material['id']; ?>" class="btn-edit">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="index.php?action=materiales_delete&id=<?php echo $material['id']; ?>" class="btn-delete" onclick="return confirm('¿Está seguro de eliminar este material?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pestaña: Agregar Material -->
    <div id="agregar-material" class="tab-content">
        <div class="card">
            <h3 class="card-title">Agregar Nuevo Material</h3>
            <form method="POST" action="index.php?action=materiales_create">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Nombre *</label>
                        <input type="text" name="nombre" required placeholder="Ej: Grava, Cemento CPC-40">
                    </div>
                    <div class="form-group">
                        <label>Tipo *</label>
                        <select name="tipo" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="agregado">Agregado</option>
                            <option value="cemento">Cemento</option>
                            <option value="aditivo_liquido">Aditivo Líquido</option>
                            <option value="aditivo_solido">Aditivo Sólido</option>
                            <option value="agua">Agua</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Unidad de Entrada *</label>
                        <input type="text" name="unidad_entrada" required placeholder="Ej: m3, ton, lt">
                    </div>
                    <div class="form-group">
                        <label>Unidad de Salida *</label>
                        <input type="text" name="unidad_salida" required placeholder="Ej: kg, lt, ml">
                    </div>
                    <div class="form-group">
                        <label>Densidad (kg/m³)</label>
                        <input type="number" step="0.001" name="densidad" placeholder="Opcional">
                    </div>
                    <div class="form-group">
                        <label>Costo Unitario ($) *</label>
                        <input type="number" step="0.001" name="costo_unitario" required placeholder="Ej: 150.00">
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <div style="margin-top: 10px;">
                            <label style="display: inline-flex; align-items: center; gap: 8px;">
                                <input type="checkbox" name="estado" checked> Material activo
                            </label>
                        </div>
                    </div>
                </div>
                <button type="submit"><i class="fas fa-save"></i> Guardar Material</button>
            </form>
        </div>
    </div>

    <!-- Pestaña: Estadísticas -->
    <div id="estadisticas" class="tab-content">
        <div class="card">
            <h3 class="card-title">Estadísticas de Materiales</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <h4>Distribución por Tipo</h4>
                    <canvas id="typeChart" width="400" height="300"></canvas>
                </div>
                <div>
                    <h4>Costo por Tipo de Material</h4>
                    <canvas id="costChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ... (funciones existentes: showTab, searchMaterials) ...

    // Función para cargar gráficos
    function loadCharts() {
        // Datos para los gráficos (usando las estadísticas de PHP)
        const typeData = {
            labels: [
                'Agregados (<?php echo $stats['por_tipo']['agregado']; ?>)', 
                'Cemento (<?php echo $stats['por_tipo']['cemento']; ?>)', 
                'Aditivos Líquidos (<?php echo $stats['por_tipo']['aditivo_liquido']; ?>)', 
                'Aditivos Sólidos (<?php echo $stats['por_tipo']['aditivo_solido']; ?>)', 
                'Agua (<?php echo $stats['por_tipo']['agua']; ?>)'
            ],
            datasets: [{
                data: [
                    <?php echo $stats['por_tipo']['agregado']; ?>,
                    <?php echo $stats['por_tipo']['cemento']; ?>,
                    <?php echo $stats['por_tipo']['aditivo_liquido']; ?>,
                    <?php echo $stats['por_tipo']['aditivo_solido']; ?>,
                    <?php echo $stats['por_tipo']['agua']; ?>
                ],
                backgroundColor: ['#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#9b59b6']
            }]
        };

        const costData = {
            labels: ['Agregados', 'Cemento', 'Aditivos Líquidos', 'Aditivos Sólidos', 'Agua'],
            datasets: [{
                label: 'Costo Promedio por Tipo',
                data: [150, 2500, 45, 32, 0.05], // Valores de ejemplo, deberían calcularse
                backgroundColor: '#3498db'
            }]
        };

        // Crear gráfico de tipos
        new Chart(document.getElementById('typeChart'), {
            type: 'pie',
            data: typeData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Crear gráfico de costos
        new Chart(document.getElementById('costChart'), {
            type: 'bar',
            data: costData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
</script>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
