<?php 
$headerPath = __DIR__ . '/../../layouts/header.php';
$footerPath = __DIR__ . '/../../layouts/footer.php';

if (file_exists($headerPath)) {
    include_once $headerPath;
} else {
    die('Error: No se puede encontrar el archivo header.php');
}

// Calcular precio total de venta
$precio_total_venta = (isset($orden['precio_venta']) ? $orden['precio_venta'] : 0) * (isset($orden['cantidad']) ? $orden['cantidad'] : 0);
?>

<div class="container">
    <div class="header">
        <h2><i class="fas fa-eye"></i> Orden de Producción #<?php echo isset($orden['id']) ? $orden['id'] : ''; ?></h2>
        <div style="display: flex; gap: 10px;">
            <a href="index.php?action=producciones_edit&id=<?php echo isset($orden['id']) ? $orden['id'] : ''; ?>" class="btn-edit">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="index.php?action=producciones" class="back-button">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <?php if (isset($message) && $message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (isset($error) && $error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Estadísticas de la orden -->
    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <div class="stat-title">Cantidad Total</div>
            <div class="stat-number"><?php echo number_format(isset($orden['cantidad']) ? $orden['cantidad'] : 0, 2); ?> m³</div>
            <div class="stat-desc">Volumen de concreto</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
            <div class="stat-title">Precio Venta Total</div>
            <div class="stat-number">$<?php echo number_format($precio_total_venta, 2); ?></div>
            <div class="stat-desc">Valor de la orden</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
            <div class="stat-title">Costo Total Estimado</div>
            <div class="stat-number">$<?php echo number_format(isset($costo_total) ? $costo_total : 0, 2); ?></div>
            <div class="stat-desc">Costo de materiales</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="stat-title">Margen Estimado</div>
            <div class="stat-number">$<?php echo number_format($precio_total_venta - (isset($costo_total) ? $costo_total : 0), 2); ?></div>
            <div class="stat-desc">Ganancia estimada</div>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Información General</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <p><strong>Cliente:</strong> <?php echo isset($orden['cliente']) ? $orden['cliente'] : 'N/A'; ?></p>
                <p><strong>Resistencia:</strong> <?php echo isset($orden['resistencia_nombre']) ? $orden['resistencia_nombre'] : 'N/A'; ?></p>
                <p><strong>Precio por m³:</strong> $<?php echo number_format(isset($orden['precio_venta']) ? $orden['precio_venta'] : 0, 2); ?></p>
            </div>
            <div>
                <p><strong>Fecha:</strong> <?php echo isset($orden['fecha']) ? $orden['fecha'] : 'N/A'; ?></p>
                <p><strong>Lote:</strong> <?php echo isset($orden['lote']) ? $orden['lote'] : 'N/A'; ?></p>
                <p><strong>Usuario:</strong> <?php echo isset($orden['usuario']) ? $orden['usuario'] : 'N/A'; ?></p>
                <p><strong>Estado:</strong> 
                    <span class="status-badge <?php 
                        $estado = isset($orden['estado']) ? $orden['estado'] : 'pendiente';
                        echo $estado == 'completada' ? 'status-active' : 
                             ($estado == 'produccion' ? 'status-warning' : 'status-inactive'); 
                    ?>">
                        <?php echo ucfirst($estado); ?>
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Gestión de Estado -->
    <div class="card">
        <h3 class="card-title">Gestión de Estado</h3>
        <form method="POST" action="index.php?action=producciones_cambiar_estado&id=<?php echo isset($orden['id']) ? $orden['id'] : ''; ?>" style="display: flex; gap: 10px; align-items: center;">
            <select name="nuevo_estado" style="flex: 1;">
                <option value="pendiente" <?php echo (isset($orden['estado']) && $orden['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                <option value="produccion" <?php echo (isset($orden['estado']) && $orden['estado'] == 'produccion') ? 'selected' : ''; ?>>En Producción</option>
                <option value="completada" <?php echo (isset($orden['estado']) && $orden['estado'] == 'completada') ? 'selected' : ''; ?>>Completada</option>
            </select>
            <button type="submit" style="background: #3498db;">
                <i class="fas fa-sync-alt"></i> Cambiar Estado
            </button>
        </form>
    </div>

    <!-- Materiales Requeridos -->
    <div class="card">
        <h3 class="card-title">Materiales Requeridos</h3>
        <?php if (isset($materiales_detalle) && count($materiales_detalle) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Tipo</th>
                    <th>Cantidad Requerida</th>
                    <th>Unidad</th>
                    <th>Costo Unitario</th>
                    <th>Costo Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $costo_calculado = 0;
                foreach ($materiales_detalle as $material): 
                    $costo_material = $material['cantidad'] * $material['costo_unitario'];
                    $costo_calculado += $costo_material;
                ?>
                <tr>
                    <td><?php echo $material['material_nombre']; ?></td>
                    <td><?php echo ucfirst(str_replace('_', ' ', $material['tipo'])); ?></td>
                    <td><?php echo number_format($material['cantidad'], 2); ?></td>
                    <td><?php echo $material['unidad']; ?></td>
                    <td>$<?php echo number_format($material['costo_unitario'], 2); ?></td>
                    <td>$<?php echo number_format($costo_material, 2); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr style="background-color: #2c3e50; color: white; font-weight: bold;">
                    <td colspan="5" style="text-align: right;">TOTAL COSTO MATERIALES:</td>
                    <td>$<?php echo number_format($costo_calculado, 2); ?></td>
                </tr>
            </tbody>
        </table>
        <?php else: ?>
        <p>No se han calculado los materiales requeridos para esta orden.</p>
        <?php endif; ?>
    </div>
</div>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
