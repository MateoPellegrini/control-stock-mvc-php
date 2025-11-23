<?php
// models/User.php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByUsernameOrEmail($identifier) {
        $sql = "SELECT u.*, r.nombre AS role_name
                FROM users u
                INNER JOIN roles r ON u.role_id = r.id
                WHERE u.username = :id OR u.email = :id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $identifier]);
        return $stmt->fetch();
    }

    /**
     * Verifica la contraseña usando salt + hash SHA256
     */
    public function verifyPassword($user, $password) {
        if (!$user || !isset($user['salt']) || !isset($user['password_hash'])) {
            return false;
        }
        $salt = $user['salt'];
        $hashInput = hash('sha256', $salt . $password);
        return hash_equals($user['password_hash'], $hashInput);
    }
}
