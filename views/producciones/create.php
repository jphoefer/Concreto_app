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
        <h2><i class="fas fa-plus"></i> Nueva Orden de Producción</h2>
        <a href="index.php?action=producciones" class="back-button">
            <i class="fas fa-arrow-left"></i> Volver a Producción
        </a>
    </div>

    <?php if (isset($error) && $error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <h3 class="card-title">Información de la Orden</h3>
        <form method="POST" action="index.php?action=producciones_create">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Cliente *</label>
                    <select name="cliente" required id="clienteSelect">
                        <option value="">Seleccionar cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?php echo htmlspecialchars($cliente['nombre']); ?>" 
                                <?php echo isset($_POST['cliente']) && $_POST['cliente'] == $cliente['nombre'] ? 'selected' : ''; ?>>
                                <?php echo $cliente['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small>O escribir un nuevo cliente:</small>
                    <input type="text" name="cliente_nuevo" id="clienteNuevo" 
                           placeholder="Escribir nuevo cliente" 
                           style="margin-top: 5px; display: none;">
                </div>
                
                <div class="form-group">
                    <label>Resistencia *</label>
                    <select name="resistencia_id" required id="resistenciaSelect">
                        <option value="">Seleccionar resistencia</option>
                        <?php foreach ($resistencias as $resistencia): ?>
                            <option value="<?php echo $resistencia['id']; ?>" 
                                data-precio="<?php echo $resistencia['precio_venta']; ?>">
                                <?php echo $resistencia['nombre']; ?> - $<?php echo number_format($resistencia['precio_venta'], 2); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Cantidad (m³) *</label>
                    <input type="number" step="0.001" name="cantidad" required 
                           value="<?php echo $_POST['cantidad'] ?? ''; ?>" 
                           id="cantidadInput" 
                           placeholder="Ej: 10.5">
                </div>
                
                <div class="form-group">
                    <label>Precio Total Estimado</label>
                    <input type="text" id="precioTotal" readonly 
                           style="background-color: #e9ecef; font-weight: bold; color: #2c3e50;">
                </div>
                
                <div class="form-group">
                    <label>Número de Lote</label>
                    <input type="text" name="lote" 
                           value="<?php echo $_POST['lote'] ?? ''; ?>" 
                           placeholder="Número de lote o referencia">
                </div>

                <div class="form-group">
                    <label>Fecha de Entrega</label>
                    <input type="date" name="fecha_entrega" 
                           value="<?php echo $_POST['fecha_entrega'] ?? ''; ?>"
                           min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label>Dirección de la Obra</label>
                    <textarea name="direccion_obra" rows="2" placeholder="Dirección completa de la obra"><?php echo $_POST['direccion_obra'] ?? ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Contacto en Obra</label>
                    <input type="text" name="contacto_obra" 
                           value="<?php echo $_POST['contacto_obra'] ?? ''; ?>" 
                           placeholder="Nombre del contacto">
                </div>
                
                <div class="form-group">
                    <label>Teléfono en Obra</label>
                    <input type="text" name="telefono_obra" 
                           value="<?php echo $_POST['telefono_obra'] ?? ''; ?>" 
                           placeholder="Teléfono de contacto">
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" style="background: #2ecc71;">
                    <i class="fas fa-save"></i> Crear Orden
                </button>
                <a href="index.php?action=producciones" class="back-button" style="display: inline-flex; align-items: center;">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resistenciaSelect = document.getElementById('resistenciaSelect');
    const cantidadInput = document.getElementById('cantidadInput');
    const precioTotal = document.getElementById('precioTotal');
    const clienteSelect = document.getElementById('clienteSelect');
    const clienteNuevo = document.getElementById('clienteNuevo');
    
    // Calcular precio total
    function calcularPrecioTotal() {
        const selectedOption = resistenciaSelect.options[resistenciaSelect.selectedIndex];
        const precioUnitario = selectedOption.getAttribute('data-precio');
        const cantidad = cantidadInput.value;
        
        if (precioUnitario && cantidad) {
            const total = (parseFloat(precioUnitario) * parseFloat(cantidad)).toFixed(2);
            precioTotal.value = '$' + total;
        } else {
            precioTotal.value = '';
        }
    }
    
    // Manejar selección de cliente
    function manejarCliente() {
        if (clienteSelect.value === '') {
            clienteNuevo.style.display = 'block';
            clienteNuevo.setAttribute('required', 'required');
        } else {
            clienteNuevo.style.display = 'none';
            clienteNuevo.removeAttribute('required');
        }
    }
    
    clienteSelect.addEventListener('change', manejarCliente);
    resistenciaSelect.addEventListener('change', calcularPrecioTotal);
    cantidadInput.addEventListener('input', calcularPrecioTotal);
    
    // Inicializar
    manejarCliente();
});
</script>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
