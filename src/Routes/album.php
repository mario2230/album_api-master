<?php 

use App\Controllers\AlbumController;

return function($r) {
    $r->addRoute('GET', '/testeAlbum', [AlbumController::class, 'testeAlbum']);
    $r->addRoute('POST', '/albuns', [AlbumController::class, 'create']);
    $r->addRoute('GET', '/albuns/{id}', [AlbumController::class, 'show']);
    $r->addRoute('GET', '/users/{id}/albuns', [AlbumController::class, 'showByUser']);
    $r->addRoute('PUT', '/albuns/{id}', [AlbumController::class, 'UpdateByUser']);
    $r->addRoute('DELETE', '/albuns/{id}', [AlbumController::class, 'DeleteAlbum']);
};

?>