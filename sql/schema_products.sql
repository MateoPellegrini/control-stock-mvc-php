-- Extensión de esquema: categorías anidadas y productos
USE `control_stock`;

-- Tabla de categorías (anidadas)
CREATE TABLE IF NOT EXISTS categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  parent_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_categorias_parent FOREIGN KEY (parent_id)
    REFERENCES categorias(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB;

-- Tabla de productos
CREATE TABLE IF NOT EXISTS productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  sku VARCHAR(50) NULL UNIQUE,
  codigo_barras VARCHAR(20) NULL,
  categoria_id INT NULL,
  precio_compra DECIMAL(10,2) NOT NULL DEFAULT 0,
  precio_venta DECIMAL(10,2) NOT NULL DEFAULT 0,
  stock_actual INT NOT NULL DEFAULT 0,
  stock_minimo INT NOT NULL DEFAULT 0,
  estado ENUM('activo','inactivo') NOT NULL DEFAULT 'activo',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_productos_categorias FOREIGN KEY (categoria_id)
    REFERENCES categorias(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB;

-- Ejemplo de categorías padre / hijas
INSERT INTO categorias (id, nombre, parent_id) VALUES
  (1, 'Almacén', NULL),
  (2, 'Fideos', 1),
  (3, 'Arroz', 1),
  (4, 'Galletitas', 1),

  (5, 'Bebidas', NULL),
  (6, 'Gaseosas', 5),
  (7, 'Aguas', 5),
  (8, 'Jugos', 5),

  (9, 'Limpieza', NULL),
  (10, 'Lavandina', 9),
  (11, 'Detergente', 9),

  (12, 'Perfumería', NULL),
  (13, 'Jabón de tocador', 12),
  (14, 'Shampoo', 12)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- Ejemplo de productos
INSERT INTO productos (nombre, sku, codigo_barras, categoria_id, precio_compra, precio_venta, stock_actual, stock_minimo, estado)
VALUES
  ('Fideos Tirabuzón 500g Marolio', 'FID-TIRA-500-MAR', '7791234567890', 2, 600, 900, 40, 10, 'activo'),
  ('Arroz Largo Fino 1kg Molinos', 'ARR-LF-1KG-MOL', '7790987654321', 3, 850, 1200, 30, 8, 'activo'),
  ('Galletitas Chocolinas 250g', 'GAL-CHOC-250', '7790001112223', 4, 700, 1100, 25, 5, 'activo'),

  ('Gaseosa Cola 2.25L Coca-Cola', 'GAS-COLA-225', '7791112223334', 6, 1800, 2500, 20, 5, 'activo'),
  ('Agua Mineral 2L Villavicencio', 'AGUA-VILLA-2L', '7792223334445', 7, 900, 1300, 35, 10, 'activo'),
  ('Jugo en polvo Naranja x25g', 'JUG-NAR-25', '7793334445556', 8, 150, 300, 100, 20, 'activo'),

  ('Lavandina 1L Ayudín', 'LAV-AYU-1L', '7794445556667', 10, 700, 1100, 18, 5, 'activo'),
  ('Detergente 750ml Magistral', 'DET-MAG-750', '7795556667778', 11, 800, 1250, 15, 4, 'activo'),

  ('Jabón de tocador Dove 90g', 'JAB-DOVE-90', '7796667778889', 13, 500, 800, 25, 6, 'activo'),
  ('Shampoo Sedal 400ml', 'SHA-SED-400', '7797778889990', 14, 1200, 1800, 12, 3, 'activo')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);
