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
        <h2><i class="fas fa-plus"></i> Nuevo Proveedor</h2>
        <a href="index.php?action=proveedores" class="back-button"><i class="fas fa-arrow-left"></i> Volver a Proveedores</a>
    </div>

    <div class="card">
        <h3 class="card-title">Información del Proveedor</h3>
        <form method="POST" action="index.php?action=proveedores_create">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required placeholder="Nombre del proveedor">
                </div>
                <div class="form-group">
                    <label>Contacto</label>
                    <input type="text" name="contacto" placeholder="Persona de contacto">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" placeholder="Número de teléfono">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Correo electrónico">
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <textarea name="direccion" rows="2" placeholder="Dirección completa"></textarea>
                </div>
                <div class="form-group">
                    <label>Notas</label>
                    <textarea name="notas" rows="2" placeholder="Información adicional..."></textarea>
                </div>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" style="background: #2ecc71;">
                    <i class="fas fa-save"></i> Registrar Proveedor
                </button>
                <a href="index.php?action=proveedores" class="back-button" style="display: inline-flex; align-items: center;">
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
