-- SQL base para crear la BD, roles y usuarios
CREATE DATABASE IF NOT EXISTS `control_stock`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `control_stock`;

-- Tabla de roles de usuario
CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL UNIQUE,
  descripcion VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(64) NOT NULL,
  salt VARCHAR(64) NOT NULL,
  role_id INT NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_roles FOREIGN KEY (role_id)
    REFERENCES roles(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Roles por defecto
INSERT INTO roles (nombre, descripcion) VALUES
  ('admin', 'Administrador del sistema, acceso completo'),
  ('ventas', 'Usuario de ventas, puede registrar ventas y ver stock'),
  ('compras', 'Usuario de compras, puede registrar compras y ver proveedores'),
  ('deposito', 'Usuario de depósito, puede ver y actualizar stock físico')
ON DUPLICATE KEY UPDATE nombre = VALUES(nombre);

-- Usuario administrador por defecto
-- Login:
--   usuario: admin
--   email:   admin@admin.com
--   clave:   admin123

INSERT INTO users (username, email, password_hash, salt, role_id)
SELECT 'admin', 'admin@admin.com', '155c8ccdba9076d1d713bb3c47cd0fea37c681378695fb92be5ff66faf2d1195', 'd1afdfcfb79d8e7b4631075494238a61', r.id
FROM roles r
WHERE r.nombre = 'admin'
ON DUPLICATE KEY UPDATE email = VALUES(email);
