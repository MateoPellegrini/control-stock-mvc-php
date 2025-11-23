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
  (1, 'Insumos agrícolas', NULL),
  (2, 'Fertilizantes', 1),
  (3, 'Herbicidas', 1),
  (4, 'Equipos', NULL),
  (5, 'Repuestos', 4)
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- Ejemplo de productos
INSERT INTO productos (nombre, sku, categoria_id, precio_compra, precio_venta, stock_actual, stock_minimo, estado)
VALUES
  ('Fertilizante NPK 20-20-20', 'NPK-202020', 2, 10000, 14500, 50, 10, 'activo'),
  ('Herbicida Selectivo X', 'HERB-SELX', 3, 8000, 12000, 30, 5, 'activo'),
  ('Pulverizadora 2000L', 'PULV-2000', 4, 500000, 650000, 2, 1, 'activo')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);
