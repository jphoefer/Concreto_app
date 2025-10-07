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
        <h2><i class="fas fa-truck"></i> Proveedores</h2>
        <a href="index.php?action=entradas" class="back-button"><i class="fas fa-arrow-left"></i> Volver a Entradas</a>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Proveedores</div>
            <div class="stat-number"><?php echo $stats['total_proveedores'] ?? 0; ?></div>
            <div class="stat-desc">Registrados en el sistema</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Con Compras</div>
            <div class="stat-number"><?php echo $stats['proveedores_con_compras'] ?? 0; ?></div>
            <div class="stat-desc">Proveedores activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Total Compras</div>
            <div class="stat-number"><?php echo $stats['total_compras'] ?? 0; ?></div>
            <div class="stat-desc">Transacciones realizadas</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Inversión Total</div>
            <div class="stat-number">$<?php echo number_format($stats['monto_total_compras'] ?? 0, 2); ?></div>
            <div class="stat-desc">En compras a proveedores</div>
        </div>
    </div>

    <!-- Navegación -->
    <div class="navigation">
        <a href="index.php?action=proveedores_create" class="nav-button"><i class="fas fa-plus"></i> Nuevo Proveedor</a>
    </div>

    <div class="card">
        <h3 class="card-title">Lista de Proveedores</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($proveedores)): ?>
                    <?php foreach ($proveedores as $proveedor): ?>
                    <tr>
                        <td><?php echo $proveedor['id']; ?></td>
                        <td><?php echo htmlspecialchars($proveedor['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($proveedor['contacto'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($proveedor['telefono'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($proveedor['email'] ?? 'N/A'); ?></td>
                        <td>
                            <?php 
                            // Manejar el caso cuando created_at no existe o es null
                            if (isset($proveedor['created_at']) && !empty($proveedor['created_at'])) {
                                echo date('d/m/Y', strtotime($proveedor['created_at']));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="index.php?action=proveedores_view&id=<?php echo $proveedor['id']; ?>" class="btn-edit">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="index.php?action=proveedores_edit&id=<?php echo $proveedor['id']; ?>" class="btn-edit">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="index.php?action=proveedores_delete&id=<?php echo $proveedor['id']; ?>" class="btn-delete" 
                                   onclick="return confirm('¿Estás seguro de eliminar este proveedor?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px;">
                            No hay proveedores registrados. 
                            <a href="index.php?action=proveedores_create" style="color: #3498db; text-decoration: none;">
                                Crear el primer proveedor
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
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
