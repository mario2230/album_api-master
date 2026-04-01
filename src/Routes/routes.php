<?php

return function($r) {
    (require __DIR__.'/user.php')($r);
    (require __DIR__.'/album.php')($r);
    (require __DIR__.'/media.php')($r);
};
