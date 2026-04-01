<?php

namespace App\Models;

use App\Database\Connection;
use PDOException;

class UserModel
{
    public function createUser($nome, $email, $senhaHash)
    {
        try {
            $db = Connection::get();

            $sql = "INSERT INTO user (nome, email, senha) VALUES (:nome, :email, :senha)";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', $senhaHash);
            $stmt->execute();

            return $db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar User" . $e->getMessage());
            return false;
        }
    }

    public function findByEmail($email)
    {
        try {
            $db = Connection::get();

            $stmt = $db->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
            $stmt->bindValue(":email", $email);
            $stmt->execute();

            return $stmt->fetch();
        } catch (PDOException $e) {
            "Erro ao criar User " . $e->getMessage();

            return false;
        }
    }

    public function findById($id)
    {
        $db = Connection::get();
        $sql = "SELECT id, nome, email FROM user WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function updateUser($id, $nome, $email)
    {
        try {
            $db = Connection::get();

            $stmt = $db->prepare("SELECT id FROM user WHERE email = :email LIMIT 1");
            $stmt->bindValue(":email", $email);
            $stmt->execute();

            $user = $stmt->fetch();

       
            if ($user && $user["id"] != $id) {
                return false;
            }

            $sql = "UPDATE user 
                SET nome = :nome, email = :email 
                WHERE id = :id";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->bindValue(":nome", $nome);
            $stmt->bindValue(":email", $email);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar user: " . $e->getMessage());
            return false;
        }
    }
}
