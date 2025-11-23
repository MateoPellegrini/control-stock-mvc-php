<?php
// controllers/ProductController.php
class ProductController extends Controller {
    private $categoryModel;
    private $productModel;

    public function __construct() {
        $this->categoryModel = new Category();
        $this->productModel  = new Product();
    }

    public function index() {
        $this->requireLogin();

        $tree          = $this->categoryModel->getTree();
        $productCounts = $this->productModel->countByCategory();
        $productos     = $this->productModel->getAllWithCategory();

        $this->view('products/index', [
            'tree'          => $tree,
            'productCounts' => $productCounts,
            'productos'     => $productos,
        ]);
    }

    public function showCategory() {
        $this->requireLogin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            $this->redirect('index.php?controller=products&action=index');
        }

        $category  = $this->categoryModel->getById($id);
        if (!$category) {
            $this->redirect('index.php?controller=products&action=index');
        }

        $children  = $this->categoryModel->getChildren($id);

        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.categoria_id = :cat
                ORDER BY p.nombre";
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute(['cat' => $id]);
        $productos = $stmt->fetchAll();

        $counts = $this->productModel->countByCategory();

        $this->view('products/category', [
            'category'      => $category,
            'children'      => $children,
            'productos'     => $productos,
            'counts'        => $counts,
        ]);
    }

    public function create() {
        $this->requireLogin();
        $this->requireRole(['admin', 'compras']); // Verificar el si el rol tiene permisos

        $tree = $this->categoryModel->getTree();

        $this->view('products/form', [
            'mode'    => 'create',
            'product' => null,
            'tree'    => $tree,
        ]);
    }

    public function store() {
        $this->requireLogin();
        $this->requireRole(['admin', 'compras']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=products&action=index');
        }

        $data = [
            'nombre'        => trim($_POST['nombre'] ?? ''),
            'sku'           => trim($_POST['sku'] ?? ''),
            'codigo_barras' => trim($_POST['codigo_barras'] ?? ''),
            'categoria_id'  => (int)($_POST['categoria_id'] ?? 0),
            'precio_compra' => (float)($_POST['precio_compra'] ?? 0),
            'precio_venta'  => (float)($_POST['precio_venta'] ?? 0),
            'stock_actual'  => (int)($_POST['stock_actual'] ?? 0),
            'stock_minimo'  => (int)($_POST['stock_minimo'] ?? 0),
            'estado'        => $_POST['estado'] ?? 'activo',
        ];

        if ($data['nombre'] === '') {
            $_SESSION['error'] = 'El nombre del producto es obligatorio.';
            $this->redirect('index.php?controller=products&action=create');
        }

        $this->productModel->create($data);

        $_SESSION['success'] = 'Producto creado correctamente.';
        $this->redirect('index.php?controller=products&action=index');
    }

    public function edit() {
        $this->requireLogin();
        $this->requireRole(['admin', 'compras']);

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            $this->redirect('index.php?controller=products&action=index');
        }

        $product = $this->productModel->getById($id);
        if (!$product) {
            $this->redirect('index.php?controller=products&action=index');
        }

        $tree = $this->categoryModel->getTree();

        $this->view('products/form', [
            'mode'    => 'edit',
            'product' => $product,
            'tree'    => $tree,
        ]);
    }

    public function update() {
        $this->requireLogin();
        $this->requireRole(['admin', 'compras']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=products&action=index');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('index.php?controller=products&action=index');
        }

        // 1) Traer producto actual
        $productActual = $this->productModel->getById($id);
        if (!$productActual) {
            $this->redirect('index.php?controller=products&action=index');
        }

        $nuevoStock = (int)($_POST['stock_actual'] ?? $productActual['stock_actual']);

        $data = [
            'nombre'        => trim($_POST['nombre'] ?? ''),
            'sku'           => $productActual['sku'], // si se genera solo
            'codigo_barras' => trim($_POST['codigo_barras'] ?? ''),
            'categoria_id'  => (int)($_POST['categoria_id'] ?? 0),
            'precio_compra' => (float)($_POST['precio_compra'] ?? 0),
            'precio_venta'  => (float)($_POST['precio_venta'] ?? 0),
            'stock_minimo'  => (int)($_POST['stock_minimo'] ?? 0),
            'estado'        => $_POST['estado'] ?? 'activo',
        ];

        if ($data['nombre'] === '') {
            $_SESSION['error'] = 'El nombre del producto es obligatorio.';
            $this->redirect('index.php?controller=products&action=edit&id=' . $id);
        }

        // 2) Actualizar datos generales (sin tocar stock_actual acá)
        $sql = "UPDATE productos SET
                    nombre = :nombre,
                    codigo_barras = :codigo_barras,
                    categoria_id = :categoria_id,
                    precio_compra = :precio_compra,
                    precio_venta = :precio_venta,
                    stock_minimo = :stock_minimo,
                    estado = :estado
                WHERE id = :id";
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'id'            => $id,
            'nombre'        => $data['nombre'],
            'codigo_barras' => $data['codigo_barras'] ?: null,
            'categoria_id'  => $data['categoria_id'] ?: null,
            'precio_compra' => $data['precio_compra'],
            'precio_venta'  => $data['precio_venta'],
            'stock_minimo'  => $data['stock_minimo'],
            'estado'        => $data['estado'],
        ]);

        // 3) Si cambió el stock, registrar movimiento
        $stockAnterior = (int)$productActual['stock_actual'];
        $delta = $nuevoStock - $stockAnterior;

        if ($delta !== 0) {
            $this->productModel->adjustStock(
                $id,
                $delta,
                'ajuste_manual',
                'Edición desde ABM de productos',
                $_SESSION['user_id'] ?? null
            );
        }

        $_SESSION['success'] = 'Producto actualizado correctamente.';
        $this->redirect('index.php?controller=products&action=index');
    }


    public function delete() {
        $this->requireLogin();
        $this->requireRole(['admin', 'compras']);

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            $this->redirect('index.php?controller=products&action=index');
        }

        $this->productModel->delete($id);
        $_SESSION['success'] = 'Producto eliminado correctamente.';
        $this->redirect('index.php?controller=products&action=index');
    }

    public function history() {
        $this->requireLogin();
        $this->requireRole(['admin', 'compras', 'deposito']); // ventas si querés también

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            $this->redirect('index.php?controller=products&action=index');
        }

        $product = $this->productModel->getById($id);
        if (!$product) {
            $this->redirect('index.php?controller=products&action=index');
        }

        $movementModel = new StockMovement();
        $movimientos = $movementModel->getByProduct($id);

        $this->view('products/history', [
            'product'    => $product,
            'movimientos'=> $movimientos,
        ]);
    }

}
