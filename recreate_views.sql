-- Eliminar vistas existentes si las hay
DROP VIEW IF EXISTS analisis_rentabilidad;
DROP VIEW IF EXISTS costo_resistencias;
DROP VIEW IF EXISTS inventario_actual;

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

-- Crear vista para el inventario actual
CREATE OR REPLACE VIEW inventario_actual AS
SELECT 
    m.id,
    m.nombre,
    m.tipo,
    m.unidad_entrada,
    m.unidad_salida,
    m.densidad,
    m.costo_unitario,
    COALESCE(SUM(e.cantidad), 0) - COALESCE(SUM(pd.cantidad), 0) as cantidad_actual,
    (COALESCE(SUM(e.cantidad), 0) - COALESCE(SUM(pd.cantidad), 0)) * m.costo_unitario as costo_total_inventario
FROM materiales m
LEFT JOIN entradas e ON m.id = e.material_id
LEFT JOIN produccion_detalles pd ON m.id = pd.material_id
GROUP BY m.id, m.nombre, m.tipo, m.unidad_entrada, m.unidad_salida, m.densidad, m.costo_unitario;

-- Otorgar permisos en las vistas
GRANT SELECT ON analisis_rentabilidad TO concreto_user;
GRANT SELECT ON costo_resistencias TO concreto_user;
GRANT SELECT ON inventario_actual TO concreto_user;
