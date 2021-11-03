<?php
namespace Firebase\JWT;
include_once 'src/BeforeValidException.php';
include_once 'src/ExpiredException.php';
include_once 'src/SignatureInvalidException.php';
include_once 'src/JWT.php';

function makeToken($query){
    $key = "ArtyomIsLatent";
    $payload = array(
        "id" => $query->user_id,
        "email" => $query->email,
        "password" => $query->password
    );
    $jwt = JWT::encode($payload, $key);
    setcookie("key", $jwt, 0, "/");
}
