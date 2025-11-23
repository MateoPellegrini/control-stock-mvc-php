<?php
// models/Dashboard.php

class Dashboard
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Cantidad de productos activos / inactivos
    public function getProductCounts()
    {
        $sql = "SELECT estado, COUNT(*) AS total
                FROM productos
                GROUP BY estado";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();

        $data = [
            'activo'   => 0,
            'inactivo' => 0,
        ];

        foreach ($rows as $row) {
            $estado = $row['estado'];
            if (isset($data[$estado])) {
                $data[$estado] = (int)$row['total'];
            }
        }

        return $data;
    }

    // Productos con stock por debajo del mínimo
    public function getLowStockProducts($limit = 10)
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.estado = 'activo'
                  AND p.stock_actual <= p.stock_minimo
                ORDER BY p.stock_actual ASC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Resumen de ventas (por defecto hoy)
    public function getSalesSummary($fechaDesde = null, $fechaHasta = null)
    {
        if (!$fechaDesde || !$fechaHasta) {
            $hoy = date('Y-m-d');
            $fechaDesde = $hoy . ' 00:00:00';
            $fechaHasta = $hoy . ' 23:59:59';
        }

        $sql = "SELECT
                    COUNT(*) AS cantidad_ventas,
                    SUM(total) AS total_vendido,
                    SUM(cantidad) AS unidades_vendidas
                FROM ventas
                WHERE created_at BETWEEN :desde AND :hasta";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desde' => $fechaDesde,
            'hasta' => $fechaHasta,
        ]);

        $row = $stmt->fetch() ?: [];

        return [
            'cantidad_ventas' => (int)($row['cantidad_ventas'] ?? 0),
            'total_vendido'   => (float)($row['total_vendido'] ?? 0),
            'unidades'        => (int)($row['unidades_vendidas'] ?? 0),
        ];
    }

    // Resumen de compras (por defecto hoy)
    public function getPurchasesSummary($fechaDesde = null, $fechaHasta = null)
    {
        if (!$fechaDesde || !$fechaHasta) {
            $hoy = date('Y-m-d');
            $fechaDesde = $hoy . ' 00:00:00';
            $fechaHasta = $hoy . ' 23:59:59';
        }

        $sql = "SELECT
                    COUNT(*) AS cantidad_compras,
                    SUM(total) AS total_comprado,
                    SUM(cantidad) AS unidades_compradas
                FROM compras
                WHERE created_at BETWEEN :desde AND :hasta";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'desde' => $fechaDesde,
            'hasta' => $fechaHasta,
        ]);

        $row = $stmt->fetch() ?: [];

        return [
            'cantidad_compras' => (int)($row['cantidad_compras'] ?? 0),
            'total_comprado'   => (float)($row['total_comprado'] ?? 0),
            'unidades'         => (int)($row['unidades_compradas'] ?? 0),
        ];
    }

    // Últimos movimientos de stock
    public function getLastStockMovements($limit = 10)
    {
        $sql = "SELECT m.*, p.nombre AS producto_nombre, u.username
                FROM movimientos_stock m
                INNER JOIN productos p ON m.producto_id = p.id
                INNER JOIN users u ON m.user_id = u.id
                ORDER BY m.created_at DESC, m.id DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Top productos vendidos en últimos N días (default 30)
    public function getTopSellingProducts($limit = 5, $dias = 30)
    {
        $fechaDesde = date('Y-m-d 00:00:00', strtotime('-' . (int)$dias . ' days'));
        $fechaHasta = date('Y-m-d 23:59:59');

        $sql = "SELECT
                    v.producto_id,
                    p.nombre AS producto_nombre,
                    SUM(v.cantidad) AS unidades_vendidas,
                    SUM(v.total) AS total_vendido
                FROM ventas v
                INNER JOIN productos p ON v.producto_id = p.id
                WHERE v.created_at BETWEEN :desde AND :hasta
                GROUP BY v.producto_id, p.nombre
                ORDER BY unidades_vendidas DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':desde', $fechaDesde);
        $stmt->bindValue(':hasta', $fechaHasta);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
