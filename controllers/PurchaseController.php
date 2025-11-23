<?php
// controllers/PurchaseController.php
class PurchaseController extends Controller {
    private $productModel;
    private $purchaseModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel  = new Product();
        $this->purchaseModel = new Purchase();
        $this->categoryModel = new Category();
    }

    public function create() {
        $this->requireLogin();
        $this->requireRole(['admin', 'compras']);

        $productos = $this->productModel->getAllWithCategory();
        $tree      = $this->categoryModel->getTree();

        $this->view('purchases/form', [
            'productos' => $productos,
            'tree'      => $tree,
        ]);
    }

    public function store() {
        $this->requireLogin();
        $this->requireRole(['admin', 'compras']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=purchases&action=create');
        }

        $productoId     = (int)($_POST['producto_id'] ?? 0);
        $cantidad       = (int)($_POST['cantidad'] ?? 0);
        $precioUnitario = (float)($_POST['precio_unitario'] ?? 0);
        $motivo         = trim($_POST['motivo'] ?? '');

        if ($productoId <= 0 || $cantidad <= 0) {
            $_SESSION['error'] = 'Debe seleccionar un producto y una cantidad mayor a cero.';
            $this->redirect('index.php?controller=purchases&action=create');
        }

        // 1) Registrar compra
        $this->purchaseModel->create([
            'producto_id'    => $productoId,
            'user_id'        => $_SESSION['user_id'],
            'cantidad'       => $cantidad,
            'precio_unitario'=> $precioUnitario,
            'motivo'         => $motivo,
        ]);

        // 2) Ajustar stock (suma)
        $this->productModel->adjustStock(
            $productoId,
            +$cantidad,
            'compra',
            $motivo !== '' ? $motivo : 'Compra simple',
            $_SESSION['user_id']
        );

        $_SESSION['success'] = 'Compra registrada y stock actualizado.';
        $this->redirect('index.php?controller=products&action=index');
    }
}
