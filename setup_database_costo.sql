-- Agregar columna de costo a la tabla de materiales
ALTER TABLE materiales ADD COLUMN costo_unitario DECIMAL(15,3) DEFAULT 0;

-- Agregar columna de costo total a la tabla de entradas
ALTER TABLE entradas ADD COLUMN costo_total DECIMAL(15,3) DEFAULT 0;

-- Actualizar los materiales existentes con costos de ejemplo
UPDATE materiales SET costo_unitario = 150.00 WHERE nombre = 'Grava';
UPDATE materiales SET costo_unitario = 120.00 WHERE nombre = 'Arena';
UPDATE materiales SET costo_unitario = 2500.00 WHERE nombre = 'Cemento CPC-40';
UPDATE materiales SET costo_unitario = 0.05 WHERE nombre = 'Agua';
UPDATE materiales SET costo_unitario = 45.00 WHERE nombre = 'Aditivo líquido A';
UPDATE materiales SET costo_unitario = 32.00 WHERE nombre = 'Aditivo sólido B';

-- Actualizar las entradas existentes con costos
UPDATE entradas SET costo_total = cantidad * (
    SELECT costo_unitario FROM materiales WHERE materiales.id = entradas.material_id
);

-- Crear vista para costo de resistencias
CREATE OR REPLACE VIEW costo_resistencias AS
SELECT 
    r.id as resistencia_id,
    r.nombre as resistencia_nombre,
    SUM(rd.cantidad * m.costo_unitario) as costo_total,
    COUNT(rd.id) as numero_materiales
FROM resistencias r
JOIN resistencia_detalles rd ON r.id = rd.resistencia_id
JOIN materiales m ON rd.material_id = m.id
GROUP BY r.id, r.nombre;
