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
        <h2><i class="fas fa-tachometer-alt"></i> Resistencias de Concreto</h2>
        <a href="index.php" class="back-button"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Resistencias</div>
            <div class="stat-number"><?php echo $stats['total_resistencias']; ?></div>
            <div class="stat-desc">Configuradas en el sistema</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Costo Promedio</div>
            <div class="stat-number">$<?php echo number_format($stats['costo_promedio'], 2); ?></div>
            <div class="stat-desc">Por m³ de concreto</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Precio Venta Promedio</div>
            <div class="stat-number">$<?php echo number_format($stats['precio_venta_promedio'], 2); ?></div>
            <div class="stat-desc">Por m³ de concreto</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Margen Promedio</div>
            <div class="stat-number"><?php echo number_format($stats['margen_promedio'], 1); ?>%</div>
            <div class="stat-desc">De rentabilidad</div>
        </div>
    </div>

    <a href="index.php?action=resistencias_create" class="btn-add">
        <i class="fas fa-plus"></i> Nueva Resistencia
    </a>

    <div class="card">
        <h3 class="card-title">Lista de Resistencias</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Costo m³</th>
                    <th>Precio Venta m³</th>
                    <th>Margen</th>
                    <th>% Margen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resistencias_con_analisis as $resistencia): 
                    $margen = $resistencia['analisis']['margen_bruto'];
                    $porcentaje_margen = $resistencia['analisis']['porcentaje_margen'];
                    $clase_margen = $porcentaje_margen >= 30 ? 'status-active' : ($porcentaje_margen >= 15 ? 'status-warning' : 'status-inactive');
                ?>
                <tr>
                    <td><?php echo $resistencia['id']; ?></td>
                    <td><?php echo $resistencia['nombre']; ?></td>
                    <td><?php echo $resistencia['descripcion']; ?></td>
                    <td>$<?php echo number_format($resistencia['analisis']['costo_total'], 2); ?></td>
                    <td>$<?php echo number_format($resistencia['analisis']['precio_venta'], 2); ?></td>
                    <td>$<?php echo number_format($margen, 2); ?></td>
                    <td>
                        <span class="status-badge <?php echo $clase_margen; ?>">
                            <?php echo number_format($porcentaje_margen, 1); ?>%
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="index.php?action=resistencias_view&id=<?php echo $resistencia['id']; ?>" class="btn-edit">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="index.php?action=resistencias_edit&id=<?php echo $resistencia['id']; ?>" class="btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="index.php?action=resistencias_delete&id=<?php echo $resistencia['id']; ?>" class="btn-delete" onclick="return confirm('¿Está seguro de eliminar esta resistencia?')">
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

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
