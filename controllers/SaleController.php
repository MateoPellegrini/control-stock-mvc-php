<?php
// controllers/SaleController.php
class SaleController extends Controller {
    private $productModel;
    private $saleModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel  = new Product();
        $this->saleModel     = new Sale();
        $this->categoryModel = new Category();
    }

    public function create() {
        $this->requireLogin();
        $this->requireRole(['admin', 'ventas']);

        $productos = $this->productModel->getAllWithCategory();

        $this->view('sales/form', [
            'productos' => $productos,
        ]);
    }

    public function store() {
        $this->requireLogin();
        $this->requireRole(['admin', 'ventas']);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=sales&action=create');
        }

        $productoId     = (int)($_POST['producto_id'] ?? 0);
        $cantidad       = (int)($_POST['cantidad'] ?? 0);
        $precioUnitario = (float)($_POST['precio_unitario'] ?? 0);
        $motivo         = trim($_POST['motivo'] ?? '');

        if ($productoId <= 0 || $cantidad <= 0) {
            $_SESSION['error'] = 'Debe seleccionar un producto y una cantidad mayor a cero.';
            $this->redirect('index.php?controller=sales&action=create');
        }

        // 1) Registrar venta
        $this->saleModel->create([
            'producto_id'    => $productoId,
            'user_id'        => $_SESSION['user_id'],
            'cantidad'       => $cantidad,
            'precio_unitario'=> $precioUnitario,
            'motivo'         => $motivo,
        ]);

        // 2) Ajustar stock (resta)
        $this->productModel->adjustStock(
            $productoId,
            -$cantidad,
            'venta',
            $motivo !== '' ? $motivo : 'Venta simple',
            $_SESSION['user_id']
        );

        $_SESSION['success'] = 'Venta registrada y stock actualizado.';
        $this->redirect('index.php?controller=products&action=index');
    }
}
