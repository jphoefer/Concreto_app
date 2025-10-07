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
        <h2><i class="fas fa-users"></i> Clientes</h2>
        <a href="index.php" class="back-button"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Clientes</div>
            <div class="stat-number"><?php echo $stats['total_clientes'] ?? 0; ?></div>
            <div class="stat-desc">Registrados en el sistema</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Clientes Activos</div>
            <div class="stat-number" style="color: #2ecc71;"><?php echo $stats['clientes_activos'] ?? 0; ?></div>
            <div class="stat-desc">Actualmente activos</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Clientes Inactivos</div>
            <div class="stat-number" style="color: #e74c3c;"><?php echo $stats['clientes_inactivos'] ?? 0; ?></div>
            <div class="stat-desc">No disponibles</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Estado</div>
            <div class="stat-number" style="color: #3498db;">
                <?php 
                $total = $stats['total_clientes'] ?? 1;
                $activos = $stats['clientes_activos'] ?? 0;
                echo $total > 0 ? round(($activos / $total) * 100) . '%' : '0%';
                ?>
            </div>
            <div class="stat-desc">Tasa de actividad</div>
        </div>
    </div>

    <!-- Navegación -->
    <div class="navigation">
        <a href="index.php?action=clientes_create" class="nav-button"><i class="fas fa-plus"></i> Nuevo Cliente</a>
    </div>

    <div class="card">
        <h3 class="card-title">Lista de Clientes</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clientes)): ?>
                    <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente['id']; ?></td>
                        <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['contacto'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($cliente['telefono'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($cliente['email'] ?? 'N/A'); ?></td>
                        <td>
                            <span style="padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; background: <?php echo $cliente['estado'] ? '#d4edda' : '#f8d7da'; ?>; color: <?php echo $cliente['estado'] ? '#155724' : '#721c24'; ?>;">
                                <?php echo $cliente['estado'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </td>
                        <td>
                            <?php 
                            if (isset($cliente['created_at']) && !empty($cliente['created_at'])) {
                                echo date('d/m/Y', strtotime($cliente['created_at']));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="index.php?action=clientes_view&id=<?php echo $cliente['id']; ?>" class="btn-edit">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="index.php?action=clientes_edit&id=<?php echo $cliente['id']; ?>" class="btn-edit">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="index.php?action=clientes_delete&id=<?php echo $cliente['id']; ?>" class="btn-delete" 
                                   onclick="return confirm('¿Estás seguro de eliminar este cliente?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px;">
                            No hay clientes registrados. 
                            <a href="index.php?action=clientes_create" style="color: #3498db; text-decoration: none;">
                                Crear el primer cliente
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
