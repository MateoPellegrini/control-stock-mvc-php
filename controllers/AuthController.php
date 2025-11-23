<?php
// controllers/AuthController.php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        $this->view('auth/login');
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?controller=auth&action=login');
        }

        $identifier = trim($_POST['identifier'] ?? '');
        $password   = trim($_POST['password'] ?? '');

        if ($identifier === '' || $password === '') {
            $_SESSION['error'] = 'Debe completar usuario/email y contraseña.';
            $this->redirect('index.php?controller=auth&action=login');
        }

        $user = $this->userModel->findByUsernameOrEmail($identifier);

        if ($user && $this->userModel->verifyPassword($user, $password) && (int)$user['is_active'] === 1) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['username']   = $user['username'];
            $_SESSION['role_id']    = $user['role_id'];
            $_SESSION['role_name']  = $user['role_name'];

            $this->redirect('index.php?controller=auth&action=dashboard');
        } else {
            $_SESSION['error'] = 'Credenciales inválidas o usuario inactivo.';
            $this->redirect('index.php?controller=auth&action=login');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('index.php?controller=auth&action=login');
    }

    public function dashboard() {
        $this->requireLogin();

        $username  = $_SESSION['username']  ?? 'Usuario';
        $roleName  = $_SESSION['role_name'] ?? 'desconocido';

        $dashboardModel = new Dashboard();

        // Datos comunes a varios roles
        $productCounts = $dashboardModel->getProductCounts();
        $lowStock      = $dashboardModel->getLowStockProducts(5);

        switch ($roleName) {
            case 'admin':
                $view = 'dashboard/admin';
                $data = [
                    'username'        => $username,
                    'roleName'        => $roleName,
                    'productCounts'   => $productCounts,
                    'lowStock'        => $lowStock,
                    'salesToday'      => $dashboardModel->getSalesSummary(),
                    'purchasesToday'  => $dashboardModel->getPurchasesSummary(),
                    'lastMovements'   => $dashboardModel->getLastStockMovements(10),
                    'topSelling'      => $dashboardModel->getTopSellingProducts(5, 30),
                ];
                break;

            case 'ventas':
                $view = 'dashboard/ventas';
                $data = [
                    'username'      => $username,
                    'roleName'      => $roleName,
                    'productCounts' => $productCounts,
                    'salesToday'    => $dashboardModel->getSalesSummary(),
                    'topSelling'    => $dashboardModel->getTopSellingProducts(5, 30),
                ];
                break;

            case 'compras':
                $view = 'dashboard/compras';
                $data = [
                    'username'        => $username,
                    'roleName'        => $roleName,
                    'productCounts'   => $productCounts,
                    'lowStock'        => $lowStock,
                    'purchasesToday'  => $dashboardModel->getPurchasesSummary(),
                    'lastMovements'   => $dashboardModel->getLastStockMovements(10),
                ];
                break;

            case 'deposito':
                $view = 'dashboard/deposito';
                $data = [
                    'username'      => $username,
                    'roleName'      => $roleName,
                    'productCounts' => $productCounts,
                    'lowStock'      => $lowStock,
                    'lastMovements' => $dashboardModel->getLastStockMovements(10),
                ];
                break;

            default:
                $view = 'auth/dashboard';
                $data = [
                    'username'  => $username,
                    'roleName'  => $roleName,
                ];
                break;
        }

        $this->view($view, $data);
    }
}
