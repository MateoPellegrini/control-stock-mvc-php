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

        switch ($roleName) {
            case 'admin':
                $view = 'dashboard/admin';
                break;
            case 'ventas':
                $view = 'dashboard/ventas';
                break;
            case 'compras':
                $view = 'dashboard/compras';
                break;
            case 'deposito':
                $view = 'dashboard/deposito';
                break;
            default:
                $view = 'auth/dashboard';
                break;
        }

        $this->view($view, [
            'username' => $username,
            'roleName' => $roleName,
        ]);
    }
}
