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
        <h2><i class="fas fa-industry"></i> Módulo de Producción</h2>
        <a href="index.php?action=producciones_create" class="btn-add">
            <i class="fas fa-plus"></i> Nueva Orden
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Órdenes</div>
            <div class="stat-number"><?php echo $stats['total_ordenes'] ?? 0; ?></div>
            <div class="stat-desc">Creadas en el sistema</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Total m³ Producidos</div>
            <div class="stat-number"><?php echo number_format($stats['total_m3_producidos'] ?? 0, 1); ?></div>
            <div class="stat-desc">Volumen total</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Órdenes Completadas</div>
            <div class="stat-number"><?php echo $stats['ordenes_completadas'] ?? 0; ?></div>
            <div class="stat-desc">Finalizadas</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Órdenes Pendientes</div>
            <div class="stat-number"><?php echo $stats['ordenes_pendientes'] ?? 0; ?></div>
            <div class="stat-desc">En proceso</div>
        </div>
    </div>

    <?php if (isset($message) && $message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (isset($error) && $error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <h3 class="card-title">Órdenes de Producción</h3>
        
        <?php if (isset($ordenes) && count($ordenes) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Resistencia</th>
                    <th>Cantidad (m³)</th>
                    <th>Fecha</th>
                    <th>Lote</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordenes as $orden): ?>
                <tr>
                    <td><?php echo $orden['id']; ?></td>
                    <td><?php echo $orden['cliente'] ?? 'N/A'; ?></td>
                    <td><?php echo $orden['resistencia_nombre'] ?? 'N/A'; ?></td>
                    <td><?php echo number_format($orden['cantidad'] ?? 0, 2); ?></td>
                    <td><?php echo $orden['fecha'] ?? 'N/A'; ?></td>
                    <td><?php echo $orden['lote'] ?? 'N/A'; ?></td>
                    <td><?php echo $orden['usuario'] ?? 'N/A'; ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="index.php?action=producciones_view&id=<?php echo $orden['id']; ?>" class="btn-edit">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="index.php?action=producciones_edit&id=<?php echo $orden['id']; ?>" class="btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="index.php?action=producciones_delete&id=<?php echo $orden['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('¿Está seguro de eliminar esta orden de producción?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No hay órdenes de producción registradas.</p>
        <?php endif; ?>
    </div>
</div>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
