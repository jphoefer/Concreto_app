    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Funcionalidad para las pestañas
        function showTab(tabName) {
            // Ocultar todos los contenidos de pestañas
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Mostrar la pestaña seleccionada
            document.getElementById(tabName).classList.add('active');

            // Actualizar pestañas activas
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Encontrar la pestaña clickeada y activarla
            event.currentTarget.classList.add('active');
        }

        // Funcionalidad para confirmar eliminaciones
        function confirmAction(message) {
            return confirm(message);
        }
    </script>
</body>
</html>
