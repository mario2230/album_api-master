<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    private static ?PDO $instance = null;

    public static function get(): PDO
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $host = $_ENV["DB_HOST"] ?? null;
        $db   = $_ENV["DB_NAME"] ?? null;
        $user = $_ENV["DB_USER"] ?? null;
        $pass = $_ENV["DB_PASS"] ?? null;


        if (!$host || !$db || !$user) {
            header('Content-Type: application/json');
            echo json_encode([
                "error"   => true,
                "type"    => "env_error",
                "message" => "Variáveis de ambiente do banco estão ausentes."
            ]);
            exit;
        }

        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        try {
            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            return self::$instance;
        } catch (PDOException $e) {
            error_log("Erro PDO: " . $e->getMessage());

            header('Content-Type: application/json');
            echo json_encode([
                "error"   => true,
                "type"    => "database_connection_error",
                "message" => "Não foi possível conectar ao banco de dados."
            ]);
            exit;
        }
    }
}
