<?php

namespace App\Controllers;

use App\Models\AlbumModel;


class AlbumController
{
    public function testeAlbum()
    {
        echo json_encode([
            "status" => "ok",
            "mensagem" => "funcionando"
        ]);
    }

    public function create($vars)
    {
        $body = json_decode(file_get_contents("php://input"), true);
        $albumModel = new AlbumModel();

        if (
            !isset($body['user_id']) ||
            !isset($body['titulo']) ||
            trim($body['titulo']) === ""
        ) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Campos obrigatórios: user_id e titulo."
            ]);
            return;
        }

        $userId = (int)$body['user_id'];
        $titulo = trim($body['titulo']);
        $descricao = $body['descricao'] ?? null;

        $album = $albumModel->createAlbum($userId, $titulo, $descricao);
        if (!$album) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Erro ao criar album"
            ]);
            return;
        }
        $this->jsonResponse([
            "sucess" => true,
            "message" => "Álbum criado com sucesso.",
            "Album_id" => $album
        ]);
    }


    public function show($vars)
    {
        $albumModel = new AlbumModel();

        $id = $vars['id'] ?? null;
        if (!is_numeric($id)) {
            $this->jsonResponse([
                "error" => true,
                "message" => "ID inválido."
            ]);
            return;
        }

        $album = $albumModel->findById($id);
        if (!$album) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Álbum não encontrado."
            ]);
            return;
        }

        $this->jsonResponse([
            "success" => true,
            "data" => $album
        ]);
    }
    public function showByUser($vars)
    {
        $userId = $vars["id"] ?? null;

        if (!$userId) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "ID do usuário é obrigatório."
            ]);
        }

        $albumModel = new AlbumModel();
        $albuns = $albumModel->findByUser($userId);

        if ($albuns == false) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Usuário sem albuns."
            ]);
        } else {
        return $this->jsonResponse([
            "success" => true,
            "data" => $albuns
        ]);
        }
    }

    public function UpdateByUser($vars)
    {
        $id = $vars['id'] ?? null;
        $body = json_decode(file_get_contents("php://input"), true);

        if (!is_numeric($id)) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "ID inválido."
            ]);
        }

        if (!isset($body['titulo']) || trim($body['titulo']) === "") {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Título é obrigatório."
            ]);
        }

        $titulo = trim($body['titulo']);
        $descricao = $body['descricao'] ?? null;
        $albumModel = new AlbumModel();


        $album = $albumModel->findById($id);
        if (!$album) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Álbum não encontrado."
            ]);
        }

        $ok = $albumModel->updateAlbum($id, $titulo, $descricao);

        if (!$ok) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Nenhuma alteração foi feita."
            ]);
        }

        return $this->jsonResponse([
            "success" => true,
            "message" => "Álbum atualizado com sucesso."
        ]);
    }

    public function DeleteAlbum($vars)
    {
        $id = $vars['id'] ?? null;

        if (!$id) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "ID é obrigatório."
            ]);
        }

        $albumModel = new AlbumModel();
        $album = $albumModel->findById($id);

        if (!$album) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Álbum não encontrado."
            ]);
        }

        $albumDeletado = $albumModel->deleteAlbum($id);

        if (!$albumDeletado) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Falha ao deletar o álbum."
            ]);
        }

        return $this->jsonResponse([
            "success" => true,
            "message" => "Álbum deletado com sucesso."
        ]);
    }



    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        return;
    }
}
