<?php 
// Verifica si los archivos existen antes de incluirlos
$headerPath = __DIR__ . '/../../layouts/header.php';
$footerPath = __DIR__ . '/../../layouts/footer.php';

if (file_exists($headerPath)) {
    include_once $headerPath;
} else {
    die('Error: No se puede encontrar el archivo header.php');
}
?>

<div class="container">
    <h2>Producciones de Concreto</h2>
    <p>Aquí se mostrarán las producciones de concreto.</p>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Resistencia ID</th>
                <th>Cantidad (m3)</th>
                <th>Fecha</th>
                <th>Cliente</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['resistencia_id']; ?></td>
                <td><?php echo $row['cantidad']; ?></td>
                <td><?php echo $row['fecha']; ?></td>
                <td><?php echo $row['cliente']; ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php 
if (file_exists($footerPath)) {
    include_once $footerPath;
} else {
    die('Error: No se puede encontrar el archivo footer.php');
}
?>
