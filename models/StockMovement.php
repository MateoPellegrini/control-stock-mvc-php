<?php
// models/StockMovement.php
class StockMovement {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function logMovement($productoId, $userId, $tipo, $cantidad, $stockAnterior, $stockNuevo, $motivo = null) {
        $sql = "INSERT INTO movimientos_stock
                (producto_id, user_id, tipo, cantidad, stock_anterior, stock_nuevo, motivo)
                VALUES
                (:producto_id, :user_id, :tipo, :cantidad, :stock_anterior, :stock_nuevo, :motivo)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'producto_id'    => $productoId,
            'user_id'        => $userId,
            'tipo'           => $tipo,
            'cantidad'       => $cantidad,
            'stock_anterior' => $stockAnterior,
            'stock_nuevo'    => $stockNuevo,
            'motivo'         => $motivo,
        ]);
    }

    public function getByProduct($productoId) {
        $sql = "SELECT m.*, u.username
                FROM movimientos_stock m
                INNER JOIN users u ON m.user_id = u.id
                WHERE m.producto_id = :prod
                ORDER BY m.created_at DESC, m.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['prod' => $productoId]);
        return $stmt->fetchAll();
    }
}
