<?php
// models/Sale.php
class Sale {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $cantidad       = (int)$data['cantidad'];
        $precioUnitario = (float)$data['precio_unitario'];
        $total          = $cantidad * $precioUnitario;

        $sql = "INSERT INTO ventas
                (producto_id, user_id, cantidad, precio_unitario, total, motivo)
                VALUES
                (:producto_id, :user_id, :cantidad, :precio_unitario, :total, :motivo)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'producto_id'    => $data['producto_id'],
            'user_id'        => $data['user_id'],
            'cantidad'       => $cantidad,
            'precio_unitario'=> $precioUnitario,
            'total'          => $total,
            'motivo'         => $data['motivo'] ?? null,
        ]);
        return $this->db->lastInsertId();
    }
}
