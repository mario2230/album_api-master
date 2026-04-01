<?php

namespace App\Models;

use App\Database\Connection;
use PDOException;

class AlbumModel
{
    public function createAlbum($userId, $titulo, $descricao)
    {
        try {
            $db = Connection::get();
            $sql = "INSERT INTO albuns (user_id, titulo, descricao) VALUES (:user_id, :titulo, :descricao)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(":user_id", $userId);
            $stmt->bindValue(":titulo", $titulo);
            $stmt->bindValue(":descricao", $descricao);
            if ($stmt->execute()) {
                return $db->lastInsertId();
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function findById($id)
    {
        $db = Connection::get();
        $sql = "SELECT * FROM albuns WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        return $stmt->fetch();
    }



    public function findByUser($userId)
    {
        $db = Connection::get();
        $sql = "SELECT * FROM albuns WHERE user_id = :user_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":user_id", $userId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function updateAlbum($id, $titulo, $descricao)
    {
        try {
            $db = Connection::get();
            $sql = "UPDATE albuns SET titulo = :titulo, descricao = :descricao WHERE id = :id";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro updateAlbum: " . $e->getMessage());
            return false;
        }
    }


    public function deleteAlbum($id) {
        $db = Connection::get();
        $sql = "DELETE FROM albuns WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }
}
