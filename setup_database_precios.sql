-- Agregar columna de precio_venta a la tabla de resistencias
ALTER TABLE resistencias ADD COLUMN precio_venta DECIMAL(15,3) DEFAULT 0;

-- Actualizar resistencias existentes con precios de ejemplo
UPDATE resistencias SET precio_venta = 3500.00 WHERE nombre = '2500 psi';
UPDATE resistencias SET precio_venta = 4200.00 WHERE nombre = '3000 psi';
UPDATE resistencias SET precio_venta = 4800.00 WHERE nombre = '3500 psi';

-- Crear vista para análisis de rentabilidad
CREATE OR REPLACE VIEW analisis_rentabilidad AS
SELECT 
    r.id,
    r.nombre,
    r.descripcion,
    r.precio_venta,
    COALESCE(SUM(rd.cantidad * m.costo_unitario), 0) as costo_total,
    (r.precio_venta - COALESCE(SUM(rd.cantidad * m.costo_unitario), 0)) as margen_bruto,
    CASE 
        WHEN COALESCE(SUM(rd.cantidad * m.costo_unitario), 0) > 0 
        THEN ((r.precio_venta - COALESCE(SUM(rd.cantidad * m.costo_unitario), 0)) / COALESCE(SUM(rd.cantidad * m.costo_unitario), 1)) * 100
        ELSE 0 
    END as porcentaje_margen
FROM resistencias r
LEFT JOIN resistencia_detalles rd ON r.id = rd.resistencia_id
LEFT JOIN materiales m ON rd.material_id = m.id
GROUP BY r.id, r.nombre, r.descripcion, r.precio_venta;
