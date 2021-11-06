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
    $content = htmlentities($_POST["content"]);
    $post_id = $_POST["post_id"];
    $comment_id = $_POST["comment_id"];
    if(!empty($user_id) && !empty($content) && !empty($post_id) && !empty($comment_id)){
        $nospaces = str_replace("&nbsp;", '', $content);
        $nospaces = str_replace(" ", '', $nospaces);


        if($nospaces!=""){
            pg_query("INSERT INTO comments (user_id, content, post_id) VALUES ('". $user_id . "', '" . $content . "', '" . $post_id ."')");
            $com_id = pg_fetch_object(pg_query("SELECT comment_id from comments WHERE user_id='" . $user_id ."' AND content='" . $content ."' AND post_id='" . $post_id ."'")) ;
            pg_query("INSERT INTO replies_to_comments (comment_id, answered_comment) VALUES ('" . $com_id->comment_id . "', '" . $comment_id ."')");
        }
    }
}
