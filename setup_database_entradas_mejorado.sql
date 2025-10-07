-- Crear tabla de proveedores
CREATE TABLE IF NOT EXISTS proveedores (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    contacto VARCHAR(200),
    telefono VARCHAR(20),
    email VARCHAR(100),
    direccion TEXT,
    rfc VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Modificar la tabla entradas para agregar nuevos campos
ALTER TABLE entradas 
ADD COLUMN IF NOT EXISTS proveedor_id INTEGER REFERENCES proveedores(id),
ADD COLUMN IF NOT EXISTS factura VARCHAR(100),
ADD COLUMN IF NOT EXISTS fecha_factura DATE,
ADD COLUMN IF NOT EXISTS precio_unitario DECIMAL(15,3) DEFAULT 0,
ADD COLUMN IF NOT EXISTS iva DECIMAL(15,3) DEFAULT 0,
ADD COLUMN IF NOT EXISTS total_factura DECIMAL(15,3) DEFAULT 0,
ADD COLUMN IF NOT EXISTS observaciones TEXT;

-- Actualizar las entradas existentes para tener un proveedor por defecto (opcional)
INSERT INTO proveedores (nombre, contacto) 
VALUES ('Proveedor General', 'Contacto General')
ON CONFLICT DO NOTHING;

-- Asignar el proveedor por defecto a las entradas existentes
UPDATE entradas 
SET proveedor_id = (SELECT id FROM proveedores WHERE nombre = 'Proveedor General')
WHERE proveedor_id IS NULL;

-- Crear vista para el historial de precios de materiales
CREATE OR REPLACE VIEW historial_precios AS
SELECT 
    e.id as entrada_id,
    e.material_id,
    m.nombre as material_nombre,
    e.precio_unitario,
    e.fecha_factura as fecha_compra,
    e.proveedor_id,
    p.nombre as proveedor_nombre,
    e.factura
FROM entradas e
JOIN materiales m ON e.material_id = m.id
JOIN proveedores p ON e.proveedor_id = p.id
WHERE e.precio_unitario > 0
ORDER BY e.fecha_factura DESC;

-- Crear vista para análisis de compras por proveedor
CREATE OR REPLACE VIEW analisis_compras AS
SELECT 
    p.id as proveedor_id,
    p.nombre as proveedor_nombre,
    COUNT(e.id) as total_compras,
    SUM(e.cantidad) as total_cantidad,
    SUM(e.total_factura) as total_comprado,
    AVG(e.precio_unitario) as precio_promedio,
    MAX(e.fecha_factura) as ultima_compra
FROM proveedores p
LEFT JOIN entradas e ON p.id = e.proveedor_id
GROUP BY p.id, p.nombre
ORDER BY total_comprado DESC;

-- Otorgar permisos en las nuevas tablas y vistas
GRANT ALL PRIVILEGES ON TABLE proveedores TO concreto_user;
GRANT ALL PRIVILEGES ON SEQUENCE proveedores_id_seq TO concreto_user;
GRANT SELECT ON historial_precios TO concreto_user;
GRANT SELECT ON analisis_compras TO concreto_user;
