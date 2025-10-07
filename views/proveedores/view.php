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
        <h2><i class="fas fa-truck"></i> Proveedor: <?php echo htmlspecialchars($proveedor['nombre']); ?></h2>
        <a href="index.php?action=proveedores" class="back-button"><i class="fas fa-arrow-left"></i> Volver a Proveedores</a>
    </div>

    <div class="card">
        <h3 class="card-title">Información del Proveedor</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label><strong>Nombre</strong></label>
                <p><?php echo htmlspecialchars($proveedor['nombre']); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Contacto</strong></label>
                <p><?php echo htmlspecialchars($proveedor['contacto'] ?? 'No especificado'); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Teléfono</strong></label>
                <p><?php echo htmlspecialchars($proveedor['telefono'] ?? 'No especificado'); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Email</strong></label>
                <p><?php echo htmlspecialchars($proveedor['email'] ?? 'No especificado'); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Dirección</strong></label>
                <p><?php echo htmlspecialchars($proveedor['direccion'] ?? 'No especificado'); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Fecha de Registro</strong></label>
                <p><?php echo date('d/m/Y H:i', strtotime($proveedor['created_at'])); ?></p>
            </div>
        </div>
        
        <?php if (!empty($proveedor['notas'])): ?>
        <div class="form-group">
            <label><strong>Notas</strong></label>
            <p><?php echo nl2br(htmlspecialchars($proveedor['notas'])); ?></p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Estadísticas de Compras -->
    <?php if (isset($compras_stats) && $compras_stats['total_compras'] > 0): ?>
    <div class="card" style="margin-top: 20px;">
        <h3 class="card-title">Estadísticas de Compras</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label><strong>Total de Compras</strong></label>
                <p><?php echo $compras_stats['total_compras']; ?></p>
            </div>
            <div class="form-group">
                <label><strong>Materiales Comprados</strong></label>
                <p><?php echo number_format($compras_stats['total_materiales']); ?> unidades</p>
            </div>
            <div class="form-group">
                <label><strong>Monto Total</strong></label>
                <p>$<?php echo number_format($compras_stats['monto_total'], 2); ?></p>
            </div>
            <?php if ($compras_stats['ultima_compra']): ?>
            <div class="form-group">
                <label><strong>Última Compra</strong></label>
                <p><?php echo date('d/m/Y', strtotime($compras_stats['ultima_compra'])); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="card" style="margin-top: 20px; background: #f8f9fa;">
        <h3 class="card-title">Estadísticas de Compras</h3>
        <p style="text-align: center; padding: 20px; color: #6c757d;">
            No se han registrado compras para este proveedor.
        </p>
    </div>
    <?php endif; ?>

    <div style="display: flex; gap: 15px; margin-top: 20px;">
        <a href="index.php?action=proveedores_edit&id=<?php echo $proveedor['id']; ?>" class="nav-button">
            <i class="fas fa-edit"></i> Editar Proveedor
        </a>
        <a href="index.php?action=entradas_create&proveedor_id=<?php echo $proveedor['id']; ?>" class="nav-button">
            <i class="fas fa-plus"></i> Nueva Compra
        </a>
        <a href="index.php?action=proveedores" class="back-button">
            <i class="fas fa-list"></i> Volver a la Lista
        </a>
    </div>
</div>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
