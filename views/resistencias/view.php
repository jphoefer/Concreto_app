<?php 
$headerPath = __DIR__ . '/../../layouts/header.php';
$footerPath = __DIR__ . '/../../layouts/footer.php';

if (file_exists($headerPath)) {
    include_once $headerPath;
} else {
    die('Error: No se puede encontrar el archivo header.php');
}

// Reiniciar el cursor para poder leer los detalles nuevamente
$detalles_data = $detalles->fetchAll(PDO::FETCH_ASSOC);

// Calcular costo total de la resistencia
$costo_total_resistencia = 0;
foreach ($detalles_data as $detalle) {
    $costo_total_resistencia += $detalle['costo_total_material'];
}

// Calcular márgenes
$margen_bruto = $resistencia_actual['precio_venta'] - $costo_total_resistencia;
$porcentaje_margen = $costo_total_resistencia > 0 ? ($margen_bruto / $costo_total_resistencia) * 100 : 0;
?>

<div class="container">
    <div class="header">
        <h2><i class="fas fa-tachometer-alt"></i> Resistencia: <?php echo $resistencia_actual['nombre']; ?></h2>
        <a href="index.php?action=resistencias" class="back-button"><i class="fas fa-arrow-left"></i> Volver a Resistencias</a>
    </div>

    <!-- Tarjetas de Resumen Financiero -->
    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
            <div class="stat-title">Costo Total por m³</div>
            <div class="stat-number">$<?php echo number_format($costo_total_resistencia, 2); ?></div>
            <div class="stat-desc">Insumos y materiales</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
            <div class="stat-title">Precio de Venta por m³</div>
            <div class="stat-number">$<?php echo number_format($resistencia_actual['precio_venta'], 2); ?></div>
            <div class="stat-desc">Precio al cliente</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
            <div class="stat-title">Margen Bruto</div>
            <div class="stat-number">$<?php echo number_format($margen_bruto, 2); ?></div>
            <div class="stat-desc">Ganancia por m³</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #3498db, #2980b9);">
            <div class="stat-title">% de Margen</div>
            <div class="stat-number"><?php echo number_format($porcentaje_margen, 1); ?>%</div>
            <div class="stat-desc">Rentabilidad</div>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Información de la Resistencia</h3>
        <p><strong>Descripción:</strong> <?php echo $resistencia_actual['descripcion']; ?></p>
        <?php if (isset($resistencia_actual['fecha_creacion']) && !empty($resistencia_actual['fecha_creacion'])): ?>
        <p><strong>Fecha de creación:</strong> <?php echo $resistencia_actual['fecha_creacion']; ?></p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3 class="card-title">Análisis de Costos por Material</h3>
        <?php if (count($detalles_data) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Costo Unitario</th>
                    <th>Costo Total</th>
                    <th>% del Costo Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles_data as $detalle): ?>
                <tr>
                    <td><?php echo $detalle['material_nombre']; ?></td>
                    <td><?php echo ucfirst(str_replace('_', ' ', $detalle['tipo'])); ?></td>
                    <td><?php echo $detalle['cantidad']; ?></td>
                    <td><?php echo $detalle['unidad']; ?></td>
                    <td>$<?php echo number_format($detalle['costo_unitario'], 2); ?></td>
                    <td>$<?php echo number_format($detalle['costo_total_material'], 2); ?></td>
                    <td>
                        <div class="progress" style="height: 20px; background: #ecf0f1; border-radius: 10px; margin-bottom: 5px;">
                            <div class="progress-bar" style="height: 100%; width: <?php echo $detalle['porcentaje_costo']; ?>%; background: #3498db; border-radius: 10px; text-align: center; color: white; font-weight: bold; line-height: 20px;">
                                <?php echo number_format($detalle['porcentaje_costo'], 1); ?>%
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="index.php?action=resistencias_edit&id=<?php echo $resistencia_actual['id']; ?>&modo=material&detalle_id=<?php echo $detalle['id']; ?>" class="btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="index.php?action=resistencias_eliminar_material&resistencia_id=<?php echo $resistencia_actual['id']; ?>&detalle_id=<?php echo $detalle['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('¿Está seguro de eliminar este material de la resistencia?')">
                                <i class="fas fa-trash"></i> Eliminar
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr style="background-color: #2c3e50; color: white; font-weight: bold;">
                    <td colspan="5" style="text-align: right;">TOTAL POR m³:</td>
                    <td>$<?php echo number_format($costo_total_resistencia, 2); ?></td>
                    <td>100%</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <?php else: ?>
        <p>No se han agregado materiales a esta resistencia.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3 class="card-title">Agregar Material a la Resistencia</h3>
        <form method="POST" action="index.php?action=resistencias_agregar_material">
            <input type="hidden" name="resistencia_id" value="<?php echo $resistencia_actual['id']; ?>">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Material *</label>
                    <select name="material_id" required id="materialSelect">
                        <option value="">Seleccionar material</option>
                        <?php 
                        // Reiniciar el cursor de materiales
                        $materiales->execute();
                        while ($material = $materiales->fetch(PDO::FETCH_ASSOC)): 
                        ?>
                            <option value="<?php echo $material['id']; ?>" data-costo="<?php echo $material['costo_unitario']; ?>">
                                <?php echo $material['nombre']; ?> (<?php echo ucfirst(str_replace('_', ' ', $material['tipo'])); ?>) - $<?php echo number_format($material['costo_unitario'], 2); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Cantidad *</label>
                    <input type="number" step="0.001" name="cantidad" required id="cantidadMaterial">
                </div>
                <div class="form-group">
                    <label>Unidad *</label>
                    <input type="text" name="unidad" required placeholder="Ej: kg, lt, ml">
                </div>
                <div class="form-group">
                    <label>Costo Estimado</label>
                    <input type="text" id="costoEstimado" readonly style="background-color: #e9ecef; font-weight: bold;">
                </div>
                <div class="form-group" style="grid-column: span 4;">
                    <button type="submit" class="btn-add" style="display: inline-flex; align-items: center; margin-top: 8px;">
                        <i class="fas fa-plus"></i> Agregar Material
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Calcular costo estimado al cambiar material o cantidad
    document.addEventListener('DOMContentLoaded', function() {
        const materialSelect = document.getElementById('materialSelect');
        const cantidadInput = document.getElementById('cantidadMaterial');
        const costoEstimado = document.getElementById('costoEstimado');

        function calcularCosto() {
            const selectedOption = materialSelect.options[materialSelect.selectedIndex];
            const costoUnitario = selectedOption.getAttribute('data-costo');
            const cantidad = cantidadInput.value;

            if (costoUnitario && cantidad) {
                const costoTotal = (parseFloat(costoUnitario) * parseFloat(cantidad)).toFixed(2);
                costoEstimado.value = '$' + costoTotal;
            } else {
                costoEstimado.value = '';
            }
        }

        materialSelect.addEventListener('change', calcularCosto);
        cantidadInput.addEventListener('input', calcularCosto);
    });
</script>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
