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
        <h2><i class="fas fa-arrow-down"></i> Entradas de Materiales</h2>
        <a href="index.php" class="back-button"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a>
    </div>

    <!-- Estadísticas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Entradas</div>
            <div class="stat-number"><?php echo $stats['total_entradas']; ?></div>
            <div class="stat-desc">Registradas en el sistema</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Cantidad Total</div>
            <div class="stat-number"><?php echo number_format($stats['total_cantidad'] ?? 0); ?></div>
            <div class="stat-desc">Unidades ingresadas</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Inversión Total</div>
            <div class="stat-number">$<?php echo number_format($stats['total_costo'] ?? 0, 2); ?></div>
            <div class="stat-desc">En compras de materiales</div>
        </div>
        <div class="stat-card">
            <div class="stat-title">Costo Promedio</div>
            <div class="stat-number">$<?php echo number_format($stats['costo_promedio'] ?? 0, 2); ?></div>
            <div class="stat-desc">Por entrada</div>
        </div>
    </div>

    <!-- Navegación -->
    <div class="navigation">
        <a href="index.php?action=entradas_create" class="nav-button"><i class="fas fa-plus"></i> Nueva Entrada</a>
        <a href="index.php?action=proveedores" class="nav-button"><i class="fas fa-truck"></i> Proveedores</a>
        <a href="#analisis-compras" class="nav-button" onclick="showTab('analisis-compras')"><i class="fas fa-chart-bar"></i> Análisis de Compras</a>
    </div>

    <!-- Pestañas -->
    <div class="tabs">
        <div class="tab active" onclick="showTab('historial-entradas')"><i class="fas fa-list"></i> Historial de Entradas</div>
        <div class="tab" onclick="showTab('analisis-compras')"><i class="fas fa-chart-bar"></i> Análisis de Compras</div>
    </div>

    <!-- Pestaña: Historial de Entradas -->
    <div id="historial-entradas" class="tab-content active">
        <div class="card">
            <h3 class="card-title">Historial de Entradas de Materiales</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Material</th>
                        <th>Cantidad</th>
                        <th>Proveedor</th>
                        <th>Factura</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['fecha']; ?></td>
                        <td>
                            <?php echo $row['material_nombre']; ?>
                            <br>
                            <small class="text-muted">
                                <a href="index.php?action=entradas_historial_precios&material_id=<?php echo $row['material_id']; ?>" 
                                   style="color: #6c757d; text-decoration: none;">
                                    <i class="fas fa-chart-line"></i> Ver histórico
                                </a>
                            </small>
                        </td>
                        <td><?php echo $row['cantidad']; ?> <?php echo $row['unidad_entrada']; ?></td>
                        <td><?php echo $row['proveedor_nombre'] ?? $row['proveedor']; ?></td>
                        <td><?php echo $row['factura']; ?></td>
                        <td>$<?php echo number_format($row['precio_unitario'], 2); ?></td>
                        <td>$<?php echo number_format($row['costo_total'], 2); ?></td>
                        <td>
                            <div class="action-buttons">
                                <!-- En un futuro, se pueden agregar acciones como editar o eliminar -->
                                <a href="#" class="btn-edit" onclick="alert('Funcionalidad en desarrollo')">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pestaña: Análisis de Compras -->
    <div id="analisis-compras" class="tab-content">
        <div class="card">
            <h3 class="card-title">Análisis de Compras por Proveedor</h3>
            <table>
                <thead>
                    <tr>
                        <th>Proveedor</th>
                        <th>Total Compras</th>
                        <th>Cantidad Total</th>
                        <th>Monto Total</th>
                        <th>Precio Promedio</th>
                        <th>Última Compra</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($analisis = $analisis_compras->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $analisis['proveedor_nombre']; ?></td>
                        <td><?php echo $analisis['total_compras']; ?></td>
                        <td><?php echo number_format($analisis['total_cantidad'] ?? 0); ?></td>
                        <td>$<?php echo number_format($analisis['total_comprado'] ?? 0, 2); ?></td>
                        <td>$<?php echo number_format($analisis['precio_promedio'] ?? 0, 2); ?></td>
                        <td><?php echo $analisis['ultima_compra']; ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="index.php?action=proveedores_view&id=<?php echo $analisis['proveedor_id']; ?>" class="btn-edit">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function showTab(tabName) {
        // Ocultar todos los contenidos de pestañas
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });

        // Mostrar la pestaña seleccionada
        document.getElementById(tabName).classList.add('active');

        // Actualizar pestañas activas
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Encontrar y activar la pestaña clickeada
        const tabs = document.querySelectorAll('.tab');
        for (let tab of tabs) {
            if (tab.textContent.includes(tabName.replace('-', ' '))) {
                tab.classList.add('active');
                break;
            }
        }
    }
</script>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
