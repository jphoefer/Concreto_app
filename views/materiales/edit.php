<?php 
$headerPath = __DIR__ . '/../../layouts/header.php';
$footerPath = __DIR__ . '/../../layouts/footer.php';

if (file_exists($headerPath)) {
    include_once $headerPath;
} else {
    die('Error: No se puede encontrar el archivo header.php');
}

// Verificar que $material_actual existe
if (!isset($material_actual)) {
    echo "<div class='alert alert-danger'>Error: No se recibieron datos del material.</div>";
    include_once $footerPath;
    exit();
}
?>

<div class="container">
    <div class="header">
        <h2><i class="fas fa-edit"></i> Editar Material</h2>
        <a href="index.php?action=materiales" class="back-button"><i class="fas fa-arrow-left"></i> Volver a Materiales</a>
    </div>

    <div class="card">
        <h3 class="card-title">Editar Información del Material</h3>
        
        <form method="POST" action="index.php?action=materiales_edit&id=<?php echo $material_actual['id']; ?>">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($material_actual['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Tipo *</label>
                    <select name="tipo" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="agregado" <?php echo ($material_actual['tipo'] == 'agregado') ? 'selected' : ''; ?>>Agregado</option>
                        <option value="cemento" <?php echo ($material_actual['tipo'] == 'cemento') ? 'selected' : ''; ?>>Cemento</option>
                        <option value="aditivo_liquido" <?php echo ($material_actual['tipo'] == 'aditivo_liquido') ? 'selected' : ''; ?>>Aditivo Líquido</option>
                        <option value="aditivo_solido" <?php echo ($material_actual['tipo'] == 'aditivo_solido') ? 'selected' : ''; ?>>Aditivo Sólido</option>
                        <option value="agua" <?php echo ($material_actual['tipo'] == 'agua') ? 'selected' : ''; ?>>Agua</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Unidad de Entrada *</label>
                    <input type="text" name="unidad_entrada" value="<?php echo htmlspecialchars($material_actual['unidad_entrada']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Unidad de Salida *</label>
                    <input type="text" name="unidad_salida" value="<?php echo htmlspecialchars($material_actual['unidad_salida']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Densidad (kg/m³)</label>
                    <input type="number" step="0.001" name="densidad" value="<?php echo htmlspecialchars($material_actual['densidad']); ?>">
                </div>
                <div class="form-group">
                    <label>Costo Unitario ($) *</label>
                    <input type="number" step="0.001" name="costo_unitario" value="<?php echo htmlspecialchars($material_actual['costo_unitario']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <div style="margin-top: 10px;">
                        <label style="display: inline-flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="estado" <?php echo $material_actual['estado'] ? 'checked' : ''; ?>> Material activo
                        </label>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" style="background: #2ecc71;">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="index.php?action=materiales" class="back-button" style="display: inline-flex; align-items: center;">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
