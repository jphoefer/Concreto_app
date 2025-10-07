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
        <h2><i class="fas fa-plus"></i> Nuevo Cliente</h2>
        <a href="index.php?action=clientes" class="back-button"><i class="fas fa-arrow-left"></i> Volver a Clientes</a>
    </div>

    <div class="card">
        <h3 class="card-title">Información del Cliente</h3>
        <form method="POST" action="index.php?action=clientes_create">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" name="nombre" required placeholder="Nombre o razón social">
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
                    <label>Estado</label>
                    <div style="display: flex; align-items: center; gap: 10px; margin-top: 8px;">
                        <input type="checkbox" name="estado" id="estado" checked>
                        <label for="estado" style="margin: 0;">Cliente activo</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Dirección</label>
                    <textarea name="direccion" rows="3" placeholder="Dirección completa"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label>Notas</label>
                <textarea name="notas" rows="3" placeholder="Información adicional, observaciones..."></textarea>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" style="background: #2ecc71;">
                    <i class="fas fa-save"></i> Registrar Cliente
                </button>
                <a href="index.php?action=clientes" class="back-button" style="display: inline-flex; align-items: center;">
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
