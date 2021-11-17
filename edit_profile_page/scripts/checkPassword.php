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
    $password =  pg_fetch_object(pg_query("SELECT password FROM users WHERE user_id='" . $user_id ."';"));
    $res =  $password->password==$_POST["password"];
    echo $res;
}

