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
else{
    $decoded = (array)JWT::decode($_COOKIE["key"], "ArtyomIsLatent", array('HS256'));
    $user_id = $decoded["id"];
    $post_id = $_POST["post_id"];
    $owner_user = $_POST["owner_user"];
    if(!empty($user_id)&&!empty($post_id)){
        $query =  pg_fetch_object(pg_query("SELECT * FROM post_likes WHERE post_id='" . $post_id . "' AND user_id='" . $user_id ."';"));
        if(empty($query)){
            pg_query("INSERT INTO post_likes (post_id, user_id, owner_user) VALUES ('" . $post_id . "', '" . $user_id . "', '" . $owner_user . "');");
        }
        else{
            pg_query("DELETE  FROM post_likes WHERE post_id='" . $post_id . "' AND user_id='" . $user_id . "' AND owner_user='" . $owner_user . "';");

        }
        pg_query("UPDATE posts SET likes = (SELECT COUNT(*) FROM post_likes WHERE post_id='". $post_id . "') WHERE post_id='" .  $post_id . "'");
    }


}


