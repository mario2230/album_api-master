<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController
{
    public function register()
    {
        $body = json_decode(file_get_contents("php://input"), true);
        $userModel = new UserModel();

        if (!isset($body['nome']) || !isset($body['email']) || !isset($body['senha'])) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Campos obrigatórios faltando."
            ]);
            return;
        }

        $nome = trim($body['nome']);
        $email = trim($body['email']);
        $senha = trim($body['senha']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Formato do email inválido"
            ]);
            return;
        }


        $userExists = $userModel->findByEmail($email);

        if ($userExists) {
            $this->jsonResponse([
                "error" => true,
                "message" => "E-mail já cadastrado."
            ]);
            return;
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $userId = $userModel->createUser($nome, $email, $senhaHash);

        if (!$userId) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Falha ao criar usuário."
            ]);
            return;
        }

        $this->jsonResponse([
            "success" => true,
            "message" => "Usuário criado com sucesso!",
            "user_id" => $userId
        ]);
    }


    public function login()
    {
        $body = json_decode(file_get_contents("php://input"), true);

        if (empty($body['email']) || empty($body['senha'])) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Email e senha são obrigatórios."
            ]);
            return;
        }

        $email = trim($body['email']);
        $senha = trim($body['senha']);

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Usuário não encontrado."
            ]);
            return;
        }

        if (!password_verify($senha, $user['senha'])) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Senha incorreta."
            ]);
            return;
        }

        $this->jsonResponse([
            "success" => true,
            "message" => "Login realizado com sucesso!",
            "user" => [
                "id" => $user["id"],
                "email" => $user["email"],
                "nome" => $user["nome"]
            ]
        ]);
    }

    public function getUser($vars)
    {
        $id = $vars['id'] ?? null;

        if (!$id) {
            $this->jsonResponse([
                "error" => true,
                "message" => "ID é obrigatório."
            ]);
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->findById($id);

        if (!$user) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Usuário não encontrado."
            ]);
            return;
        }

        $this->jsonResponse([
            "success" => true,
            "data" => $user
        ]);
    }

    public function updateUser($vars)
    {
        $body = json_decode(file_get_contents("php://input"), true);
        $id = $vars['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            $this->jsonResponse([
                "error" => true,
                "message" => "ID inválido."
            ]);
            return;
        }

        if (!isset($body['nome']) || !isset($body['email'])) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Campos obrigatórios faltando."
            ]);
            return;
        }

        $nome = trim($body['nome']);
        $email = trim($body['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse([
                "error" => true,
                "message" => "E-mail inválido."
            ]);
            return;
        }

        $userModel = new UserModel();

     
        $userAtual = $userModel->findById($id);

        if (!$userAtual) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Usuário não encontrado."
            ]);
            return;
        }

   
        $emailExistente = $userModel->findByEmail($email);

        if ($emailExistente && $emailExistente["id"] != $id) {
            $this->jsonResponse([
                "error" => true,
                "message" => "E-mail já está sendo usado por outro usuário."
            ]);
            return;
        }

    
        $sucesso = $userModel->updateUser($id, $nome, $email);

        if (!$sucesso) {
            $this->jsonResponse([
                "error" => true,
                "message" => "Erro ao atualizar usuário."
            ]);
            return;
        }

        $this->jsonResponse([
            "success" => true,
            "message" => "Usuário atualizado com sucesso.",
            "data" => [
                "id" => $id,
                "nome" => $nome,
                "email" => $email
            ]
        ]);
    }


    private function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        return;
    }
}
