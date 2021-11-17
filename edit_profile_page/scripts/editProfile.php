<?php
namespace Firebase\JWT;
include_once '../../src/BeforeValidException.php';
include_once '../../src/ExpiredException.php';
include_once '../../src/SignatureInvalidException.php';
include_once '../../src/JWT.php';
include_once '../../connect.php';

if(!connect()){
    echo "<H3>Соединение не установленно</H3><br>";
}
else{
    $decoded = (array)JWT::decode($_COOKIE["key"], "ArtyomIsLatent", array('HS256'));
    $user_id = $decoded["id"];
    $buttonType = $_POST["buttonType"];
    $newValue = $_POST["newValue"];
    switch ($buttonType){
        case 'edit_name':
            $field = 'firstname';
            break;
        case 'edit_lastname':
            $field = 'lastname';
            break;
        case 'edit_email':
            $field = 'email';
            break;
        case 'edit_password':
            $field = 'password';
    }
    pg_query("UPDATE users SET " . $field . "='" . $newValue ."' WHERE user_id='" . $user_id ."';");
}

