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
    $subscriber =  pg_fetch_object(pg_query("SELECT user_id FROM subscriber WHERE user_id='" . $user_id ."' AND subscriber='" . $_POST["id"] . "';"));
    if(!empty($subscriber->user_id)){
        pg_query("INSERT INTO friend (user_id, friend_id) VALUES ('" . $user_id ."', '" . $_POST["id"] ."');");
        pg_query("DELETE FROM subscriber WHERE user_id='" . $user_id ."' AND subscriber='" . $_POST["id"] ."';");
        pg_query("DELETE FROM subscriber WHERE user_id='" . $_POST["id"] ."' AND subscriber='" . $user_id ."';");
    }
    else{
        $isFriend = pg_fetch_object(pg_query("SELECT * FROM friend WHERE (user_id='" . $user_id ."' AND friend_id='" . $_POST["id"] . "') OR (user_id='" . $_POST["id"] ."' AND friend_id='" . $user_id ."');"));
        if (!empty($isFriend->user_id)){
            pg_query("DELETE FROM friend WHERE (user_id='" . $user_id ."' AND friend_id='" . $_POST["id"] . "') OR (user_id='" . $_POST["id"] ."' AND friend_id='" . $user_id ."')");
            pg_query("INSERT INTO subscriber (user_id, subscriber) VALUES ('". $user_id ."', '" . $_POST["id"] ."');");
        }
        else{
            $isSubscriber = pg_fetch_object(pg_query("SELECT * FROM subscriber WHERE user_id='" . $_POST["id"] ."' AND subscriber='" . $user_id ."';"));
            if(!empty($isSubscriber->subscriber)){
                pg_query("DELETE FROM subscriber WHERE user_id='" . $_POST["id"] ."' AND subscriber='" . $user_id ."';");
            }
            else{
                pg_query("INSERT INTO subscriber (user_id, subscriber) VALUES ('". $_POST["id"] ."', '" . $user_id ."');");
            }
        }


    }
}
