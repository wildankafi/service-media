<?php 
$router->group(['prefix' => 'v1'], function () use ($router) {
    $router->POST('media','v1\MediaImgController@create');
    $router->GET('file/{param}','v1\MediaImgController@read');
    $router->GET('file/{param}/view','v1\MediaImgController@view');
    $router->POST('createencode','v1\MediaImgController@createbase64');
});
?>
