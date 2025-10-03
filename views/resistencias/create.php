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
        <h2><i class="fas fa-plus"></i> Crear Nueva Resistencia</h2>
        <a href="index.php?action=resistencias" class="back-button"><i class="fas fa-arrow-left"></i> Volver a Resistencias</a>
    </div>

    <div class="card">
        <h3 class="card-title">Información de la Nueva Resistencia</h3>
        <form method="POST" action="index.php?action=resistencias_create">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required placeholder="Ej: 2500 psi, 3000 psi">
                </div>
                <div class="form-group">
                    <label>Precio de Venta por m³ ($) *</label>
                    <input type="number" step="0.01" name="precio_venta" required placeholder="Ej: 3500.00">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="3" placeholder="Descripción de la resistencia y usos recomendados"></textarea>
                </div>
            </div>
            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" style="background: #2ecc71;">
                    <i class="fas fa-save"></i> Crear Resistencia
                </button>
                <a href="index.php?action=resistencias" class="back-button" style="display: inline-flex; align-items: center;">
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
