<?php 
$headerPath = __DIR__ . '/../../layouts/header.php';
$footerPath = __DIR__ . '/../../layouts/footer.php';

if (file_exists($headerPath)) {
    include_once $headerPath;
} else {
    die('Error: No se puede encontrar el archivo header.php');
}

// Obtener el detalle del material
$detalle = $this->model->getDetalle($_GET['detalle_id']);
?>

<div class="container">
    <div class="header">
        <h2><i class="fas fa-edit"></i> Editar Material en Resistencia</h2>
        <a href="index.php?action=resistencias_view&id=<?php echo $detalle['resistencia_id']; ?>" class="back-button">
            <i class="fas fa-arrow-left"></i> Volver a la Resistencia
        </a>
    </div>

    <div class="card">
        <h3 class="card-title">Editar: <?php echo $detalle['material_nombre']; ?></h3>
        
        <form method="POST" action="index.php?action=resistencias_editar_material&detalle_id=<?php echo $detalle['id']; ?>">
            <input type="hidden" name="resistencia_id" value="<?php echo $detalle['resistencia_id']; ?>">
            
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
                <a href="index.php?action=resistencias_view&id=<?php echo $detalle['resistencia_id']; ?>" class="back-button" style="display: inline-flex; align-items: center;">
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
