<?php
namespace Firebase\JWT;
include_once '../../src/BeforeValidException.php';
include_once '../../src/ExpiredException.php';
include_once '../../src/SignatureInvalidException.php';
include_once '../../src/JWT.php';
include_once '../../connect.php';

if (!connect()) {
    echo "<H3>Соединение не установленно</H3><br>";
} else {
    $decoded = (array)JWT::decode($_COOKIE["key"], "ArtyomIsLatent", array('HS256'));
    $user_id = $decoded["id"];
    $content = $_POST["content"];
    $post_id = $_POST["post_id"];
    $comment_id = $_POST["comment_id"];
    if (!empty($user_id) && !empty($comment_id) && !empty($post_id)) {

        $nospaces = str_replace("&nbsp;", '', $content);
        $nospaces = str_replace(" ", '', $nospaces);

        if ($nospaces != "") {
            pg_query("UPDATE comments SET content='" . $content . "' WHERE comment_id='" . $comment_id . "' AND user_id='" . $user_id ."'");
        }
    }
}
