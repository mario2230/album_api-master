<?php

namespace App\Controllers;

use App\Models\AlbumModel;
use App\Models\MediaModel;

class MediaController
{
    private function salvarArquivo($file, $userId, $albumId)
    {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nome = uniqid() . '.' . $ext;

        $basePath = __DIR__ . '/../../storage/users';
        $destinoDir = "$basePath/$userId/albuns/$albumId";
        if (!is_dir($destinoDir)) {
            mkdir($destinoDir, 0777, true);
        }

        $destino = "$destinoDir/$nome";
        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            return false;
        }

        return "users/$userId/albuns/$albumId/$nome";
    }

    public function upload($vars)
    {
        $albumId = $vars['id'] ?? null;
        $userId = $_POST['user_id'] ?? null;

        if (!$albumId || !$userId || !isset($_FILES['media'])) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Dados obrigatórios faltando."
            ]);
        }

        $albumModel = new AlbumModel();
        $album = $albumModel->findById($albumId);

        if (!$album || $album['user_id'] != $userId) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Álbum inválido."
            ]);
        }

        $file = $_FILES['media'];

        if ($file['error'] !== 0) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Erro no upload."
            ]);
        }

        $mime = mime_content_type($file['tmp_name']);

        if (!str_starts_with($mime, 'image') && !str_starts_with($mime, 'video')) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Tipo de arquivo não permitido."
            ]);
        }

        $tipo = str_starts_with($mime, 'image') ? 'image' : 'video';
        $caminho = $this->salvarArquivo($file, $userId, $albumId);

        if (!$caminho) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Falha ao salvar arquivo."
            ]);
        }

        $midiaModel = new MediaModel();
        $ok = $midiaModel->criar($albumId, $userId, $tipo, $caminho);

        if (!$ok) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Erro ao salvar mídia no banco."
            ]);
        }

        return $this->jsonResponse([
            "success" => true,
            "message" => "Upload realizado com sucesso."
        ]);
    }

    public function listByAlbum($vars)
    {
        $albumId = $vars['id'] ?? null;

        if (!is_numeric($albumId)) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "ID do álbum inválido."
            ]);
        }

        $albumModel = new AlbumModel();
        $album = $albumModel->findById($albumId);

        if (!$album) {
            return $this->jsonResponse([
                "error" => true,
                "message" => "Álbum não encontrado."
            ]);
        }

        $mediaModel =  new MediaModel();
        $medias = $mediaModel->findByAlbum($albumId);

        $this->jsonResponse([
            "sucess" => true,
            "album" => [
                "id" => $album['id'],
                "titulo" => $album['titulo']
            ],
            "midias" => $medias
        ]);
    }

    public function delete($vars)
    {
        $id = $vars['id'] ?? null;

        if (!is_numeric($id)) {
            return $this->jsonResponse([
                'error' => true,
                'message' => 'ID inválido.'
            ]);
        }

        $mediaModel = new MediaModel();
        $media = $mediaModel->findById((int)$id);

        if (!$media) {
            return $this->jsonResponse([
                'error' => true,
                'message' => 'Mídia não encontrada.'
            ]);
        }


        $this->deletarArquivoEPasta($media['caminho']);


        $ok = $mediaModel->delete((int)$id);

        if (!$ok) {
            return $this->jsonResponse([
                'error' => true,
                'message' => 'Erro ao deletar mídia.'
            ]);
        }

        return $this->jsonResponse([
            'success' => true,
            'message' => 'Mídia deletada com sucesso.'
        ]);
    }


    private function deletarArquivoEPasta(string $caminhoRelativo): void
    {
        $basePath = __DIR__ . '/../../storage/';
        $arquivo = $basePath . $caminhoRelativo;


        if (file_exists($arquivo) && is_file($arquivo)) {
            unlink($arquivo);
        }


        $pastaAlbum = dirname($arquivo);


        if (is_dir($pastaAlbum)) {
            $arquivos = array_diff(scandir($pastaAlbum), ['.', '..']);
            if (count($arquivos) === 0) {
                rmdir($pastaAlbum);
            }
        }
    }


    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        return;
    }
}
