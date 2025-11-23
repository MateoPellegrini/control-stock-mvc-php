<?php
// core/Database.php

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Cargar config
        $config = require __DIR__ . '/../config/config.php';

        $host = $config['db_host'];
        $dbname = $config['db_name'];
        $user = $config['db_user'];
        $pass = $config['db_pass'];

        try {
            $this->connection = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            if (!empty($config['app_debug'])) {
                die("Error de conexión: " . $e->getMessage());
            } else {
                die("Error de conexión a la base de datos.");
            }
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
