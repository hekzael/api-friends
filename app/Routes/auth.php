<?php

use App\Models\AuthModel;

$app->group('/auth/', function () {
     
    $this->post('login',function($req,$res,$args){
        $user = new AuthModel();
        $data = $req->getBody();
        $data = json_decode($data,true);
        return $res
        ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $user->login($data)
            )
        );
    });
    $this->post('logout',function($req,$res,$args) {
        
    });
});