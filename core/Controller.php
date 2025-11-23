<?php
// core/Controller.php
class Controller {
    protected function view($view, $data = []) {
        extract($data);
        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    protected function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Por favor, iniciá sesión para continuar.';
            $this->redirect('index.php?controller=auth&action=login');
        }
    }

    protected function userRole() {
        return $_SESSION['role_name'] ?? null;
    }

    protected function requireRole(array $roles) {
        $role = $this->userRole();
        if ($role === null || !in_array($role, $roles, true)) {
            // Podés redirigir y mostrar mensaje en vez de este echo
            $_SESSION['error'] = 'No tenés permisos para acceder a esta sección.';
            $this->redirect('index.php?controller=auth&action=dashboard');
        }
    }

}
