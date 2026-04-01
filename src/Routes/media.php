<?php 

use App\Controllers\MediaController;

return function($r) {
    $r->addRoute('POST', '/albuns/{id}/midia', [MediaController::class, 'upload']);
    $r->addRoute('GET', '/albuns/{id}/midia', [MediaController::class, 'listByAlbum']);
    $r->addRoute('DELETE', '/albuns/{id}/midia', [MediaController::class, 'delete']);
};

?>