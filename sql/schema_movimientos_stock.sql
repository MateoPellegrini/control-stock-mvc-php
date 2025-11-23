USE `control_stock`;

CREATE TABLE IF NOT EXISTS movimientos_stock (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto_id INT NOT NULL,
  user_id INT NOT NULL,
  tipo ENUM('compra','venta','ajuste_manual','correccion') NOT NULL,
  cantidad INT NOT NULL,
  stock_anterior INT NOT NULL,
  stock_nuevo INT NOT NULL,
  motivo VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_mov_productos FOREIGN KEY (producto_id)
    REFERENCES productos(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,

  CONSTRAINT fk_mov_usuarios FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);
