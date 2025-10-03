-- Tabla de materiales (agregados, cemento, aditivos)
CREATE TABLE materiales (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) CHECK (tipo IN ('agregado', 'cemento', 'aditivo_liquido', 'aditivo_solido', 'agua')),
    unidad_entrada VARCHAR(10) NOT NULL,
    unidad_salida VARCHAR(10) NOT NULL,
    densidad DECIMAL(10,3),
    estado BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de fórmulas de resistencia
CREATE TABLE resistencias (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de detalles de fórmula (materiales por m3)
CREATE TABLE resistencia_detalles (
    id SERIAL PRIMARY KEY,
    resistencia_id INTEGER REFERENCES resistencias(id) ON DELETE CASCADE,
    material_id INTEGER REFERENCES materiales(id),
    cantidad DECIMAL(10,3) NOT NULL,
    unidad VARCHAR(10) NOT NULL
);

-- Tabla de entradas de materiales
CREATE TABLE entradas (
    id SERIAL PRIMARY KEY,
    material_id INTEGER REFERENCES materiales(id),
    cantidad DECIMAL(15,3) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lote VARCHAR(50),
    proveedor VARCHAR(100),
    usuario VARCHAR(100)
);

-- Tabla de producciones (salidas)
CREATE TABLE producciones (
    id SERIAL PRIMARY KEY,
    resistencia_id INTEGER REFERENCES resistencias(id),
    cantidad DECIMAL(10,3) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    cliente VARCHAR(100),
    lote VARCHAR(50),
    usuario VARCHAR(100)
);

-- Tabla de detalles de producción (materiales utilizados)
CREATE TABLE produccion_detalles (
    id SERIAL PRIMARY KEY,
    produccion_id INTEGER REFERENCES producciones(id) ON DELETE CASCADE,
    material_id INTEGER REFERENCES materiales(id),
    cantidad DECIMAL(15,3) NOT NULL,
    unidad VARCHAR(10) NOT NULL
);

-- Insertar datos básicos de materiales (CORREGIDO: se usa "densidad" en lugar de "density")
INSERT INTO materiales (nombre, tipo, unidad_entrada, unidad_salida, densidad) VALUES
('Grava', 'agregado', 'm3', 'kg', 1600),
('Arena', 'agregado', 'm3', 'kg', 1500),
('Cemento CPC-40', 'cemento', 'ton', 'kg', 1000),
('Agua', 'agua', 'lt', 'lt', 1),
('Aditivo líquido A', 'aditivo_liquido', 'lt', 'ml', 1.2),
('Aditivo sólido B', 'aditivo_solido', 'kg', 'g', 1.5);

-- Insertar algunas resistencias básicas
INSERT INTO resistencias (nombre, descripcion) VALUES
('2500 psi', 'Concreto estándar para elementos no estructurales'),
('3000 psi', 'Concreto para losas y pavimentos'),
('3500 psi', 'Concreto para columnas y losas estructurales');

-- Insertar detalles para resistencia 2500 psi
INSERT INTO resistencia_detalles (resistencia_id, material_id, cantidad, unidad) VALUES
(1, 1, 1080, 'kg'),  -- Grava
(1, 2, 720, 'kg'),   -- Arena
(1, 3, 300, 'kg'),   -- Cemento
(1, 4, 180, 'lt'),   -- Agua
(1, 5, 500, 'ml');   -- Aditivo líquido

-- Insertar detalles para resistencia 3000 psi
INSERT INTO resistencia_detalles (resistencia_id, material_id, cantidad, unidad) VALUES
(2, 1, 1100, 'kg'),  -- Grava
(2, 2, 700, 'kg'),   -- Arena
(2, 3, 350, 'kg'),   -- Cemento
(2, 4, 160, 'lt'),   -- Agua
(2, 5, 600, 'ml');   -- Aditivo líquido

-- Insertar detalles para resistencia 3500 psi
INSERT INTO resistencia_detalles (resistencia_id, material_id, cantidad, unidad) VALUES
(3, 1, 1150, 'kg'),  -- Grava
(3, 2, 650, 'kg'),   -- Arena
(3, 3, 400, 'kg'),   -- Cemento
(3, 4, 150, 'lt'),   -- Agua
(3, 5, 700, 'ml');   -- Aditivo líquido

-- Crear vista para el inventario actual
CREATE OR REPLACE VIEW inventario_actual AS
SELECT 
    m.id,
    m.nombre,
    m.tipo,
    m.unidad_entrada,
    m.unidad_salida,
    m.densidad,
    COALESCE(SUM(e.cantidad), 0) - COALESCE(SUM(pd.cantidad), 0) as cantidad_actual
FROM materiales m
LEFT JOIN entradas e ON m.id = e.material_id
LEFT JOIN produccion_detalles pd ON m.id = pd.material_id
GROUP BY m.id, m.nombre, m.tipo, m.unidad_entrada, m.unidad_salida, m.densidad;

-- Crear índices para mejorar el rendimiento
CREATE INDEX idx_entradas_material_id ON entradas(material_id);
CREATE INDEX idx_produccion_detalles_material_id ON produccion_detalles(material_id);
CREATE INDEX idx_resistencia_detalles_resistencia_id ON resistencia_detalles(resistencia_id);
CREATE INDEX idx_resistencia_detalles_material_id ON resistencia_detalles(material_id);
