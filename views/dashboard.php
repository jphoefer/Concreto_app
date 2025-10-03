<?php include_once 'layouts/header.php'; ?>

<div class="container">
    <h2>Dashboard - Planta de Concreto Premezclado</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Materiales</h5>
                    <p class="card-text">Gestionar materiales (agregados, cemento, aditivos, agua).</p>
                    <a href="index.php?action=materiales" class="btn btn-primary">Ir a Materiales</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Resistencias</h5>
                    <p class="card-text">Gestionar fórmulas de resistencia.</p>
                    <a href="index.php?action=resistencias" class="btn btn-primary">Ir a Resistencias</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Entradas</h5>
                    <p class="card-text">Registrar entradas de materiales.</p>
                    <a href="index.php?action=entradas" class="btn btn-primary">Ir a Entradas</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Producción</h5>
                    <p class="card-text">Registrar producción de concreto.</p>
                    <a href="index.php?action=producciones" class="btn btn-primary">Ir a Producción</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Inventario</h5>
                    <p class="card-text">Consultar niveles de inventario.</p>
                    <a href="index.php?action=inventario" class="btn btn-primary">Ir a Inventario</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'layouts/footer.php'; ?>
