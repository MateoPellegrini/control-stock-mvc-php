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

    public function getAllRoles() {
        $sql = "SELECT id, nombre FROM roles ORDER BY id ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function createUser(array $data) {
        // Generar un salt random
        $salt = bin2hex(random_bytes(16)); // 32 chars hex

        // Generar hash igual que en verifyPassword:
        // hash('sha256', $salt . $password)
        $passwordHash = hash('sha256', $salt . $data['password']);

        $sql = "INSERT INTO users
                (username, email, password_hash, salt, role_id, is_active, created_at)
                VALUES
                (:username, :email, :password_hash, :salt, :role_id, :is_active, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'username'      => $data['username'],
            'email'         => $data['email'],
            'password_hash' => $passwordHash,
            'salt'          => $salt,
            'role_id'       => $data['role_id'],
            'is_active'     => $data['is_active'] ? 1 : 0,
        ]);

        return $this->db->lastInsertId();
    }
}
