<?php
// models/Product.php
class Product {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllWithCategory() {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                ORDER BY p.nombre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM productos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        // 1) Insertar sin SKU (o ignorando el que venga del form)
        $sql = "INSERT INTO productos
                (nombre, sku, codigo_barras, categoria_id, precio_compra, precio_venta, stock_actual, stock_minimo, estado)
                VALUES
                (:nombre, NULL, :codigo_barras, :categoria_id, :precio_compra, :precio_venta, :stock_actual, :stock_minimo, :estado)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre'         => $data['nombre'],
            'codigo_barras'  => $data['codigo_barras'] ?? null,
            'categoria_id'   => $data['categoria_id'] ?: null,
            'precio_compra'  => $data['precio_compra'],
            'precio_venta'   => $data['precio_venta'],
            'stock_actual'   => $data['stock_actual'],
            'stock_minimo'   => $data['stock_minimo'],
            'estado'         => $data['estado'] ?? 'activo',
        ]);

        $id = $this->db->lastInsertId();

        // 2) Generar SKU automático: P000001, P000002, etc.
        $sku = 'P' . str_pad($id, 6, '0', STR_PAD_LEFT);

        $sqlSku = "UPDATE productos SET sku = :sku WHERE id = :id";
        $stmtSku = $this->db->prepare($sqlSku);
        $stmtSku->execute([
            'sku' => $sku,
            'id'  => $id,
        ]);

        return $id;
    }

    public function update($id, $data) {
        $sql = "UPDATE productos SET
                    nombre = :nombre,
                    sku = :sku,
                    codigo_barras = :codigo_barras,
                    categoria_id = :categoria_id,
                    precio_compra = :precio_compra,
                    precio_venta = :precio_venta,
                    stock_actual = :stock_actual,
                    stock_minimo = :stock_minimo,
                    estado = :estado
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'             => $id,
            'nombre'         => $data['nombre'],
            'sku'            => $data['sku'] ?? null,
            'codigo_barras'  => $data['codigo_barras'] ?? null,
            'categoria_id'   => $data['categoria_id'] ?: null,
            'precio_compra'  => $data['precio_compra'],
            'precio_venta'   => $data['precio_venta'],
            'stock_actual'   => $data['stock_actual'],
            'stock_minimo'   => $data['stock_minimo'],
            'estado'         => $data['estado'] ?? 'activo',
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM productos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function countByCategory() {
        $sql = "SELECT categoria_id, COUNT(*) AS total
                FROM productos
                GROUP BY categoria_id";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();
        $map = [];
        foreach ($rows as $row) {
            $map[$row['categoria_id']] = (int)$row['total'];
        }
        return $map;
    }

    public function adjustStock($productoId, $delta, $tipo, $motivo = null, $userId = null) {
        // $delta puede ser +5, -3, etc.
        if ($delta == 0) {
            return; // nada que hacer
        }

        $db = $this->db;
        $db->beginTransaction();

        try {
            // 1) Traer producto actual
            $sql = "SELECT stock_actual FROM productos WHERE id = :id FOR UPDATE";
            $stmt = $db->prepare($sql);
            $stmt->execute(['id' => $productoId]);
            $row = $stmt->fetch();

            if (!$row) {
                throw new Exception("Producto no encontrado");
            }

            $stockAnterior = (int)$row['stock_actual'];
            $stockNuevo    = $stockAnterior + (int)$delta;
            if ($stockNuevo < 0) {
                $stockNuevo = 0; // o podés tirar error
            }

            // 2) Actualizar stock en productos
            $sqlUpd = "UPDATE productos SET stock_actual = :stock WHERE id = :id";
            $stmtUpd = $db->prepare($sqlUpd);
            $stmtUpd->execute([
                'stock' => $stockNuevo,
                'id'    => $productoId,
            ]);

            // 3) Registrar movimiento
            $movementModel = new StockMovement();
            $movementModel->logMovement(
                $productoId,
                $userId ?? 0,
                $tipo,
                $delta,
                $stockAnterior,
                $stockNuevo,
                $motivo
            );

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            if (APP_DEBUG) {
                throw $e;
            }
        }
    }

}
