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
        <h2><i class="fas fa-edit"></i> Editar Orden de Producción #<?php echo $orden['id']; ?></h2>
        <a href="index.php?action=producciones" class="back-button">
            <i class="fas fa-arrow-left"></i> Volver a Producción
        </a>
    </div>

    <?php if (isset($error) && $error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card">
        <h3 class="card-title">Información de la Orden</h3>
        <form method="POST" action="index.php?action=producciones_edit&id=<?php echo $orden['id']; ?>">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Cliente *</label>
                    <select name="cliente" required id="clienteSelect">
                        <option value="">Seleccionar cliente</option>
                        <?php foreach ($clientes as $cliente_item): ?>
                            <option value="<?php echo htmlspecialchars($cliente_item['nombre']); ?>" 
                                <?php echo ($orden['cliente'] == $cliente_item['nombre'] || (isset($_POST['cliente']) && $_POST['cliente'] == $cliente_item['nombre'])) ? 'selected' : ''; ?>>
                                <?php echo $cliente_item['nombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small>O escribir un nuevo cliente:</small>
                    <input type="text" name="cliente_nuevo" id="clienteNuevo" 
                           value="<?php echo (isset($_POST['cliente_nuevo']) ? $_POST['cliente_nuevo'] : (in_array($orden['cliente'], array_column($clientes, 'nombre')) ? '' : $orden['cliente'])); ?>"
                           placeholder="Escribir nuevo cliente" 
                           style="margin-top: 5px; display: none;">
                </div>
                
                <div class="form-group">
                    <label>Resistencia *</label>
                    <select name="resistencia_id" required>
                        <option value="">Seleccionar resistencia</option>
                        <?php foreach ($resistencias as $resistencia): ?>
                            <option value="<?php echo $resistencia['id']; ?>" 
                                <?php echo ($orden['resistencia_id'] == $resistencia['id'] || (isset($_POST['resistencia_id']) && $_POST['resistencia_id'] == $resistencia['id'])) ? 'selected' : ''; ?>>
                                <?php echo $resistencia['nombre']; ?> - $<?php echo number_format($resistencia['precio_venta'], 2); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Cantidad (m³) *</label>
                    <input type="number" step="0.001" name="cantidad" required 
                           value="<?php echo $_POST['cantidad'] ?? $orden['cantidad']; ?>">
                </div>
                
                <div class="form-group">
                    <label>Número de Lote</label>
                    <input type="text" name="lote" 
                           value="<?php echo $_POST['lote'] ?? $orden['lote']; ?>" 
                           placeholder="Número de lote o referencia">
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; margin-top: 20px;">
                <button type="submit" style="background: #3498db;">
                    <i class="fas fa-save"></i> Actualizar Orden
                </button>
                <a href="index.php?action=producciones_view&id=<?php echo $orden['id']; ?>" class="back-button" style="display: inline-flex; align-items: center;">
                    <i class="fas fa-eye"></i> Ver Orden
                </a>
                <a href="index.php?action=producciones" class="back-button" style="display: inline-flex; align-items: center;">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>

    <!-- Gestión de Estado -->
    <div class="card">
        <h3 class="card-title">Gestión de Estado</h3>
        <form method="POST" action="index.php?action=producciones_cambiar_estado&id=<?php echo $orden['id']; ?>" style="display: flex; gap: 10px; align-items: center;">
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clienteSelect = document.getElementById('clienteSelect');
    const clienteNuevo = document.getElementById('clienteNuevo');
    
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
    
    // Actualizar campo cliente según selección
    clienteSelect.addEventListener('change', function() {
        manejarCliente();
        // Si se selecciona un cliente existente, actualizar el campo oculto
        if (this.value !== '') {
            clienteNuevo.value = this.value;
        }
    });
    
    // Si se escribe un cliente nuevo, actualizar el select
    clienteNuevo.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            clienteSelect.value = '';
        }
    });
    
    // Inicializar
    manejarCliente();
});

// Manejar envío del formulario para asegurar que el cliente se envíe correctamente
document.querySelector('form').addEventListener('submit', function(e) {
    const clienteSelect = document.getElementById('clienteSelect');
    const clienteNuevo = document.getElementById('clienteNuevo');
    
    // Si se está escribiendo un cliente nuevo, usar ese valor
    if (clienteNuevo.value.trim() !== '') {
        clienteSelect.value = clienteNuevo.value;
    }
});
</script>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
