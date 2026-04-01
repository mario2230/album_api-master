<?php

namespace App\Models;

use App\Database\Connection;
use PDOException;

class MediaModel
{
    public function criar($album_id, $user_id, $tipo, $caminho)
    {
        $db = Connection::get();

        $sql = "INSERT INTO albuns_midias (album_id, user_id, tipo, caminho)
            VALUES (:album_id, :user_id, :tipo, :caminho)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':album_id' => $album_id,
            ':user_id' => $user_id,
            ':tipo' => $tipo,
            ':caminho' => $caminho
        ]);

        return $db->lastInsertId();
    }

    public function findByAlbum(int $albumId): array
    {
        $db = Connection::get();
        $sql = "SELECT id, tipo, caminho, criado_em
            FROM albuns_midias
            WHERE album_id = :album_id
            ORDER BY criado_em DESC";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':album_id', $albumId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById(int $id)
    {
        $db = Connection::get();

        $sql = "SELECT * FROM albuns_midias WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }


    public function delete(int $id)
    {
        $db = Connection::get();
        $sql = "DELETE FROM albuns_midias WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }
}
