<?php
// models/Category.php
class Category {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $sql = "SELECT * FROM categorias ORDER BY parent_id IS NULL DESC, parent_id, nombre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM categorias WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getChildren($parentId) {
        $sql = "SELECT * FROM categorias WHERE parent_id " . ($parentId === null ? "IS NULL" : "= :id") . " ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        if ($parentId === null) {
            $stmt->execute();
        } else {
            $stmt->execute(['id' => $parentId]);
        }
        return $stmt->fetchAll();
    }

    public function getTree() {
        $all = $this->getAll();
        $byParent = [];
        foreach ($all as $cat) {
            $pid = $cat['parent_id'] ?? 0;
            if (!isset($byParent[$pid])) {
                $byParent[$pid] = [];
            }
            $byParent[$pid][] = $cat;
        }

        $build = function($parentId) use (&$build, $byParent) {
            $pid = $parentId ?? 0;
            $nodes = $byParent[$pid] ?? [];
            foreach ($nodes as &$n) {
                $n['children'] = $build($n['id']);
            }
            return $nodes;
        };

        return $build(null);
    }
}
