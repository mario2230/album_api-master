<?php 

use App\Controllers\UserController;

return function($r)  {
    $r->addRoute('POST', '/register', [UserController::class, 'register']);
    $r->addRoute('GET', '/login', [UserController::class, 'login']);
    $r->addRoute('GET', '/user/{id}', [UserController::class, 'getUser']);
    $r->addRoute('PUT', '/user/{id}', [UserController::class, 'updateUser']);
}

?>