<?php
namespace App\Models;
use App\Lib\DataBase;
use PDO;
use Firebase\JWT\JWT;
use Tuupola\Base62;
use DateTime;
class AuthModel
{
    private $db;
    private $table = 'users';
    private $response;

    public function __CONSTRUCT()
    {
        $db = new DataBase();
        $this->db = $db->connect();
    }

    public function login($data) // tiene usuario y password
    {
        $stm = $this->db->prepare("SELECT * FROM $this->table WHERE 
                                    email =? and  password =?"
                                );
        $stm->execute(array(
            $data['email'],
            $data['password']
        ));
        $result = $stm->fetch(PDO::FETCH_ASSOC);

        if(!$result){
            return false;
        }else{
            $now = new DateTime();
            $future = new DateTime("+30 minutes");
            $jti = (new Base62)->encode(random_bytes(16));
            $payload = [
            'iat' => $now->getTimeStamp(),
            'exp' => $future->getTimeStamp(),
            'jti' => $jti,
            'sub' => [
                        "id" => $result['id'],
                        "email" => $result['email'],
                        "password" => $result['password']
                     ]
            ];
            $secret = "123456789helo_secret";
            $token = JWT::encode($payload, $secret, 'HS256');
            $resultData['token'] = $token;
            $resultData['expires'] = $future->getTimeStamp();    
        }
        return $resultData;
    }
}