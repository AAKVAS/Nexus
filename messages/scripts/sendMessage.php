<?php
namespace Firebase\JWT;
include_once '../../src/BeforeValidException.php';
include_once '../../src/ExpiredException.php';
include_once '../../src/SignatureInvalidException.php';
include_once '../../src/JWT.php';
include_once '../../connect.php';

if (!connect()) {
    echo "<H3>Соединение не установленно</H3><br>";
}
else {
    $decoded = (array)JWT::decode($_COOKIE["key"], "ArtyomIsLatent", array('HS256'));
    $user_id = $decoded["id"];
    if (isset($_POST["text"])){
            $nospaces = str_replace("&nbsp;", '', htmlentities($_POST["text"]));
            $nospaces = str_replace(" ", '', $nospaces);


            if($nospaces!=""){

                $newPost = pg_query("insert into messages");

            }
        }
}
