<?php

use App\Models\UserModel;
$app->group('/user/', function () {
     
    $this->get('loged',function($req,$res,$args){ // obtiene un recurso por su id
        $user = new UserModel();
        return $res
        ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $user->get($this->jwt->id)
            )
        );
    });
    $this->get('',function($req,$res,$args) { // obtiene todos los recursos
        $user = new UserModel();
        return $res
        ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $user->getAll()
            )
        );
    });
    $this->get('{id}',function($req,$res,$args){ // obtiene un recurso por su id
        $user = new UserModel();
        return $res
        ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $user->get($args['id'])
            )
        );
    });
    $this->post('',function($req,$res,$args){ // crea un recurso
        $user = new UserModel();  
        $data = $req->getBody();
        $da = json_decode($data,true);
        $user->create($da);
    });
    $this->delete('{id}',function($req,$res,$args){ // elimina un recurso
        $user = new UserModel();
        return $res
        ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $user->delete($args["id"])
            )
        );
    });
    $this->put('{id}',function($req,$res,$args){ // ACTUALIZA SI EXISTE 
        $user = new UserModel();  
        $data = $req->getBody();
        $data = json_decode($data,true);
        $user->update($data, $args['id']);
    });
    $this->patch('{id}',function($req,$res,$args){ // actualiza parcialmente Un recurso si existe
        $user = new UserModel();  
        $data = $req->getBody();
        $data = json_decode($data,true);
        $user->replace($data, $args['id']);
    });


    /* $this->get('{esp}',function($req,$res,$args){ // obtiene un recurso por su id
        $user = new UserModel();
        return $res
        ->withHeader('Content-type', 'application/json')
           ->getBody()
           ->write(
            json_encode(
                $user->get($args['esp'])
            )
        );
    }); */

});
