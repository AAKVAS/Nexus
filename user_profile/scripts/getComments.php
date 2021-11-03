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
    if(isset($_POST["post_id"])){
        $query = pg_query("select comments.comment_id, users.user_id, users.firstname, users.lastname, comments.post_id, comments.content
from users, comments WHERE users.user_id=comments.user_id AND post_id='" . $_POST["post_id"] . "';");
       // $query =  pg_query("SELECT * FROM comments WHERE post_id='" . $_POST["post_id"] . "' ORDER BY comment_id ASC;");
        $result = array();
        $decoded = (array)JWT::decode($_COOKIE["key"], "ArtyomIsLatent", array('HS256'));
        $user_id = $decoded["id"];
        $result[]=$user_id;
        while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)){
            $array = array();
            $array["post_id"] = $line["post_id"];
            $array["comment_id"] = $line["comment_id"];
            $array["firstname"] = $line["firstname"];
            $array["lastname"] = $line["lastname"];


            $array["user_id"] = $line["user_id"];
            $array["content"] = $line["content"];
            $result[]=$array;
        }
        $json = json_encode($result);
        echo $json;
    }
}