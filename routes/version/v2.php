<?php 
$router->group(['prefix' => 'v2'], function () use ($router) {
    $router->POST('createencode','v2\MediaImgController@createbase64');
    $router->POST('requestdelete','v2\MediaImgController@RequestDelete');
    $router->GET('actiondelete','v2\MediaImgController@DeleteQueue');
});
?>
