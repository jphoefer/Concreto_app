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
        <h2><i class="fas fa-plus"></i> Registrar Nueva Entrada</h2>
        <a href="index.php?action=entradas" class="back-button"><i class="fas fa-arrow-left"></i> Volver a Entradas</a>
    </div>

    <div class="card">
        <h3 class="card-title">Información de la Entrada</h3>
        <form method="POST" action="index.php?action=entradas_create">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Material *</label>
                    <select name="material_id" required id="materialSelect">
                        <option value="">Seleccionar material</option>
                        <?php while ($material = $materiales->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $material['id']; ?>" data-costo="<?php echo $material['costo_unitario']; ?>">
                                <?php echo $material['nombre']; ?> (<?php echo $material['unidad_entrada']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Proveedor *</label>
                    <select name="proveedor_id" required>
                        <option value="">Seleccionar proveedor</option>
                        <?php while ($proveedor = $proveedores->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?php echo $proveedor['id']; ?>">
                                <?php echo $proveedor['nombre']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Cantidad *</label>
                    <input type="number" step="0.001" name="cantidad" required id="cantidadEntrada">
                </div>
                <div class="form-group">
                    <label>Precio Unitario ($) *</label>
                    <input type="number" step="0.001" name="precio_unitario" required id="precioUnitario">
                </div>
                <div class="form-group">
                    <label>Fecha de Entrada *</label>
                    <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label>Fecha de Factura</label>
                    <input type="date" name="fecha_factura">
                </div>
                <div class="form-group">
                    <label>Número de Factura</label>
                    <input type="text" name="factura" placeholder="Número de factura">
                </div>
                <div class="form-group">
                    <label>IVA ($)</label>
                    <input type="number" step="0.001" name="iva" value="0" id="ivaInput">
                </div>
                <div class="form-group">
                    <label>Total Factura ($)</label>
                    <input type="number" step="0.001" name="total_factura" value="0" id="totalFactura">
                </div>
                <div class="form-group">
                    <label>Lote</label>
                    <input type="text" name="lote" placeholder="Número de lote">
                </div>
                <div class="form-group">
                    <label>Proveedor (texto libre)</label>
                    <input type="text" name="proveedor" placeholder="Nombre del proveedor (opcional)">
                </div>
            </div>
            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
            </div>

            <!-- Resumen de costos -->
            <div class="card" style="background: #f8f9fa; margin-top: 20px;">
                <h4>Resumen de Costos</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div>
                        <strong>Subtotal:</strong> 
                        <span id="subtotalCalculado">$0.00</span>
                    </div>
                    <div>
                        <strong>IVA:</strong> 
                        <span id="ivaCalculado">$0.00</span>
                    </div>
                    <div>
                        <strong>Total:</strong> 
                        <span id="totalCalculado" style="font-weight: bold; color: #2ecc71;">$0.00</span>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" style="background: #2ecc71;">
                    <i class="fas fa-save"></i> Registrar Entrada
                </button>
                <a href="index.php?action=entradas" class="back-button" style="display: inline-flex; align-items: center;">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cantidadInput = document.getElementById('cantidadEntrada');
        const precioInput = document.getElementById('precioUnitario');
        const ivaInput = document.getElementById('ivaInput');
        const totalFacturaInput = document.getElementById('totalFactura');
        const subtotalSpan = document.getElementById('subtotalCalculado');
        const ivaSpan = document.getElementById('ivaCalculado');
        const totalSpan = document.getElementById('totalCalculado');

        function calcularCostos() {
            const cantidad = parseFloat(cantidadInput.value) || 0;
            const precio = parseFloat(precioInput.value) || 0;
            const iva = parseFloat(ivaInput.value) || 0;

            const subtotal = cantidad * precio;
            const total = subtotal + iva;

            subtotalSpan.textContent = '$' + subtotal.toFixed(2);
            ivaSpan.textContent = '$' + iva.toFixed(2);
            totalSpan.textContent = '$' + total.toFixed(2);

            // Actualizar el campo de total factura
            totalFacturaInput.value = total.toFixed(2);
        }

        cantidadInput.addEventListener('input', calcularCostos);
        precioInput.addEventListener('input', calcularCostos);
        ivaInput.addEventListener('input', calcularCostos);

        // Calcular costos iniciales
        calcularCostos();
    });
</script>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
