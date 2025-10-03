<?php 
$headerPath = __DIR__ . '/../../layouts/header.php';
$footerPath = __DIR__ . '/../../layouts/footer.php';

if (file_exists($headerPath)) {
    include_once $headerPath;
} else {
    die('Error: No se puede encontrar el archivo header.php');
}

// Determinar el modo de operación
$modo_edicion_material = isset($detalle) && isset($detalle['id']);
?>

<div class="container">
    <div class="header">
        <h2><i class="fas fa-edit"></i> 
            <?php echo $modo_edicion_material ? 'Editar Material en Resistencia' : 'Editar Resistencia: ' . $resistencia_actual['nombre']; ?>
        </h2>
        <a href="index.php?action=resistencias_view&id=<?php echo $resistencia_actual['id']; ?>" class="back-button">
            <i class="fas fa-arrow-left"></i> Volver a la Resistencia
        </a>
    </div>

    <?php if ($modo_edicion_material): ?>
    <!-- MODO: Edición de material específico -->
    <div class="card">
        <h3 class="card-title">Editar: <?php echo $detalle['material_nombre']; ?></h3>
        
        <form method="POST" action="index.php?action=resistencias_edit&id=<?php echo $resistencia_actual['id']; ?>&modo=material&detalle_id=<?php echo $detalle['id']; ?>">
            <input type="hidden" name="resistencia_id" value="<?php echo $resistencia_actual['id']; ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Material</label>
                    <input type="text" value="<?php echo $detalle['material_nombre']; ?>" disabled>
                    <small class="text-muted">Tipo: <?php echo ucfirst(str_replace('_', ' ', $detalle['tipo'])); ?></small>
                </div>
                
                <div class="form-group">
                    <label>Cantidad *</label>
                    <input type="number" step="0.001" name="cantidad" value="<?php echo $detalle['cantidad']; ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Unidad *</label>
                    <input type="text" name="unidad" value="<?php echo $detalle['unidad']; ?>" required>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" style="background: #2ecc71;">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="index.php?action=resistencias_edit&id=<?php echo $resistencia_actual['id']; ?>" class="back-button" style="display: inline-flex; align-items: center;">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <?php else: ?>
    <!-- MODO: Edición de resistencia y gestión de materiales -->
    <div class="card">
        <h3 class="card-title">Editar Información de la Resistencia</h3>
        
        <form method="POST" action="index.php?action=resistencias_edit&id=<?php echo $resistencia_actual['id']; ?>">
            <input type="hidden" name="editar_resistencia" value="1">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($resistencia_actual['nombre']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Precio de Venta por m³ ($) *</label>
                    <input type="number" step="0.01" name="precio_venta" value="<?php echo htmlspecialchars($resistencia_actual['precio_venta']); ?>" required>
                </div>
                
                <div class="form-group" style="grid-column: span 2;">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="3"><?php echo htmlspecialchars($resistencia_actual['descripcion']); ?></textarea>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" style="background: #2ecc71;">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    <!-- Sección de gestión de materiales -->
    <div class="card">
        <h3 class="card-title">Materiales de la Resistencia</h3>
        
        <?php 
        // Reiniciar el cursor de detalles
        $detalles_data = $detalles->fetchAll(PDO::FETCH_ASSOC);
        if (count($detalles_data) > 0): 
        ?>
        <table>
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Costo Unitario</th>
                    <th>Costo Total</th>
                    <th>% del Costo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles_data as $material): ?>
                <tr>
                    <td><?php echo $material['material_nombre']; ?></td>
                    <td><?php echo ucfirst(str_replace('_', ' ', $material['tipo'])); ?></td>
                    <td><?php echo $material['cantidad']; ?></td>
                    <td><?php echo $material['unidad']; ?></td>
                    <td>$<?php echo number_format($material['costo_unitario'], 2); ?></td>
                    <td>$<?php echo number_format($material['costo_total_material'], 2); ?></td>
                    <td><?php echo number_format($material['porcentaje_costo'], 1); ?>%</td>
                    <td>
                        <div class="action-buttons">
                            <a href="index.php?action=resistencias_edit&id=<?php echo $resistencia_actual['id']; ?>&modo=material&detalle_id=<?php echo $material['id']; ?>" class="btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="index.php?action=resistencias_eliminar_material&resistencia_id=<?php echo $resistencia_actual['id']; ?>&detalle_id=<?php echo $material['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('¿Está seguro de eliminar este material de la resistencia?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No se han agregado materiales a esta resistencia.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3 class="card-title">Agregar Material a la Resistencia</h3>
        <form method="POST" action="index.php?action=resistencias_edit&id=<?php echo $resistencia_actual['id']; ?>">
            <input type="hidden" name="agregar_material" value="1">
            <input type="hidden" name="resistencia_id" value="<?php echo $resistencia_actual['id']; ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Material *</label>
                    <select name="material_id" required>
                        <option value="">Seleccionar material</option>
                        <?php while ($material = $materiales->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $material['id']; ?>">
                                <?php echo $material['nombre']; ?> (<?php echo ucfirst(str_replace('_', ' ', $material['tipo'])); ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Cantidad *</label>
                    <input type="number" step="0.001" name="cantidad" required>
                </div>
                <div class="form-group">
                    <label>Unidad *</label>
                    <input type="text" name="unidad" required placeholder="Ej: kg, lt, ml">
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" style="margin-top: 8px;"><i class="fas fa-plus"></i> Agregar</button>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
