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
        <h2><i class="fas fa-user"></i> Cliente: <?php echo htmlspecialchars($cliente['nombre']); ?></h2>
        <a href="index.php?action=clientes" class="back-button"><i class="fas fa-arrow-left"></i> Volver a Clientes</a>
    </div>

    <div class="card">
        <h3 class="card-title">Información del Cliente</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label><strong>Nombre</strong></label>
                <p><?php echo htmlspecialchars($cliente['nombre']); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Contacto</strong></label>
                <p><?php echo htmlspecialchars($cliente['contacto'] ?? 'No especificado'); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Teléfono</strong></label>
                <p><?php echo htmlspecialchars($cliente['telefono'] ?? 'No especificado'); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Email</strong></label>
                <p><?php echo htmlspecialchars($cliente['email'] ?? 'No especificado'); ?></p>
            </div>
            <div class="form-group">
                <label><strong>Estado</strong></label>
                <p>
                    <span style="padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; background: <?php echo $cliente['estado'] ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $cliente['estado'] ? '#155724' : '#721c24'; ?>;">
                        <?php echo $cliente['estado'] ? 'Activo' : 'Inactivo'; ?>
                    </span>
                </p>
            </div>
            <div class="form-group">
                <label><strong>Fecha de Registro</strong></label>
                <p>
                    <?php 
                    if (isset($cliente['created_at']) && !empty($cliente['created_at'])) {
                        echo date('d/m/Y H:i', strtotime($cliente['created_at']));
                    } else {
                        echo 'No especificado';
                    }
                    ?>
                </p>
            </div>
        </div>
        
        <?php if (!empty($cliente['direccion'])): ?>
        <div class="form-group">
            <label><strong>Dirección</strong></label>
            <p><?php echo nl2br(htmlspecialchars($cliente['direccion'])); ?></p>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($cliente['notas'])): ?>
        <div class="form-group">
            <label><strong>Notas</strong></label>
            <p><?php echo nl2br(htmlspecialchars($cliente['notas'])); ?></p>
        </div>
        <?php endif; ?>
    </div>

    <div style="display: flex; gap: 15px; margin-top: 20px;">
        <a href="index.php?action=clientes_edit&id=<?php echo $cliente['id']; ?>" class="nav-button">
            <i class="fas fa-edit"></i> Editar Cliente
        </a>
        <a href="index.php?action=clientes" class="back-button">
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
