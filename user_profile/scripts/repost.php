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
    if (isset($_POST["post_id"])){
        date_default_timezone_set('UTC');
        $now = getdate();
        pg_query("INSERT INTO posts (user_id, send_time) VALUES ('" . $user_id . "', '" . $now[0] ." ')");
        $query = pg_fetch_object(pg_query("SELECT post_id FROM posts WHERE user_id='" . $user_id ."' AND send_time='". $now[0] ."';"));
        pg_query("INSERT INTO repost (post_id, repost) VALUES ('" . $query->post_id ."', '" . $_POST["post_id"] . "')");

    }
}
