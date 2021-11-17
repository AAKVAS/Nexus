<?php
namespace Firebase\JWT;
include_once '../src/BeforeValidException.php';
include_once '../src/ExpiredException.php';
include_once '../src/SignatureInvalidException.php';
include_once '../src/JWT.php';
include_once '../connect.php';

$decoded = (array)JWT::decode($_COOKIE["key"], "ArtyomIsLatent", array('HS256'));
$tokenId = $decoded["id"];
$id = $tokenId;
if(isset($_GET["id"])){
    if(!empty($_GET["id"])){
        $id = $_GET["id"];
    }
}

if (!connect()) {
    echo "<H3>Соединение не установленно</H3><br>";
}

$query =  pg_fetch_object(pg_query("select * from users where user_id=' " . $id . "';"));
if(empty($query)){
    header('Location:http://localhost:9092/authentication_page/index.html');
    exit;
}

$getpost = pg_query("select posts.post_id, posts.user_id, posts.likes, posts.content, posts.send_time, repost.repost, users.firstname, users.lastname from  users, posts LEFT JOIN repost on posts.post_id = repost.post_id WHERE posts.user_id=users.user_id AND posts.user_id='" . $query->user_id . "' ORDER BY post_id DESC;");
$likes = pg_query("SELECT * FROM post_likes WHERE user_id ='" . $tokenId . "' AND owner_user='" .  $query->user_id . "' ORDER BY post_id DESC;");
$liked_posts = array();
if(!empty($likes)){
    while($likes_line = pg_fetch_array($likes, null, PGSQL_ASSOC)){
        $liked_posts[$likes_line["post_id"]]=true;
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nexus</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet"></head>
<body>
<header id="page_header" class=""><img class="logo" src="../resources/Nexus_1_3.png" ></header>
<div class="page_layout">
    <div class="page_body">
        <div class="narrow_column">
            <div class="profile_wrap">
                <div id="owner_photo_wrap"><img id="photo" src="../resources/avatar.jpg">
                </div>
                <?php
                    if($tokenId==$id){
                        echo '<input id="edit_profile" class="nexus_button" type="submit" value="редактировать профиль">';
                    }
                    else{
                        echo '<input id="send_message" class="nexus_button" type="submit" value="Сообщения"><br>';
                        echo '<input id="become_friends" class="nexus_button" type="submit" value="Добавить в друзья">';
                    }
                ?>


            </div>
            <div id="friends_wrap" >
                <div class="friend_list">тут список друзьев</div></div>
        </div>
        <div class="wide_column">
            <div id="page_info_wrap" >
                <div id="page_info">
                    <div class="username"><?= $query->firstname . " " . $query->lastname?></div>
                    <div class="user_status">online</div>
                </div>
            </div>
            <div id="wall_wrap">
                <?php
                if($id==$tokenId){
                    echo '<form method="post" >
                <div id="submit_post" >
                    <img class="wall_avatar" src="../resources/avatar.jpg">
                    <div contenteditable="true" id="postText" class="post_area" placeholder="Поделитесь новостями"></div>
                    <input type="submit" class="nexus_button" id="share_post_button" value="Поделиться">
                </div> 
                </form>';
                }
                ?>
                    <?php

                        while ($line = pg_fetch_array($getpost, null, PGSQL_ASSOC)){
                            $post_id = $line["post_id"];
                            $user_id = null;
                            $name = null;
                            $likes = null;
                            if($line["repost"]!=null){

                                $user_id = $line["user_id"];
                                $name = $line["firstname"] . " " .$line["lastname"];
                                $likes = $line["likes"];
                                $content = $line["content"];
                            }
                            while($line["repost"]!=null)
                            {
                               $line=pg_fetch_array(pg_query("select posts.post_id, posts.user_id, posts.likes, posts.content, posts.send_time, repost.repost, users.firstname, users.lastname from  users, posts LEFT JOIN repost on posts.post_id = repost.post_id WHERE posts.user_id=users.user_id AND posts.post_id='" . $line["repost"] . "' ORDER BY post_id DESC;"));

                            }

                            echo '<div name="' . $id . " " . $post_id .'" class="profile_wall">';
                            if(!empty($user_id)){
                                echo '<div name="' . $id . " " . $line["post_id"] .'" class="repost_block"><a href="http://localhost:9092/user_profile/index.php?id="' . $user_id . '">' . $name . '</a>';
                                if($id==$tokenId){
                                    echo '<img src="resources/points.svg" class="post_points" height="20">'.
                                        '<div class="points_menu">'.
                                        '<div class="post_edit_button">Редактировать</div><hr>'.
                                        '<div class="delete_post_button">Удалить</div></div>
                                        </div>';
                                }
                                echo  "<div>" . htmlentities($content) . "</div><br>";
                                echo '<div class="post_content moved_right">';

                            }
                            else{
                                echo '<div class="post_content">';
                            }
                            echo '<a href="http://localhost:9092/user_profile/index.php?id="' . $line["user_id"] . '">' . $line["firstname"] . ' ' . $line["lastname"] .
                                '</a>';
                            if($id==$tokenId && empty($user_id)){
                                echo '<img src="resources/points.svg" class="post_points" height="20">'.
                                    '<div class="points_menu">'.
                                    '<div class="post_edit_button">Редактировать</div><hr>'.
                                    '<div class="delete_post_button">Удалить</div></div>';
                            }
                            echo  "<br><div>" . htmlentities($line["content"]) . "</div></div><br>";

                            if(!empty($likes)){
                                echo '<div class="like" id="like_' . $post_id . '">' . $likes . '</div>';
                            }
                            else{
                                echo '<div class="like" id="like_' . $post_id . '"></div>';
                            }
                            if(array_key_exists($post_id, $liked_posts)){
                                echo '<img class="like_button action_on_post" src="resources/like.svg" height="20" >';

                            }
                            else{
                                echo '<img class="like_button action_on_post" src="resources/default_like.svg" height="20" >';
                            }
                            echo
                                '<img class="comment_button action_on_post" src="resources/comment.svg" height="24">' .
                                '<img class="repost_button action_on_post" src="resources/repost.svg" height="20">' .
                                '<div class="comments"  id="comment_' . $post_id . '">
                                <div class="send_comment_block">
                                <div contenteditable="true" class="comment_area" placeholder="Оставить комментарий"></div>
                                <input type="submit" class="send_comment nexus_button"  value="Отправить">
                                </div>
                                </div>
                                 </div>';
                        }
                    ?>
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="sidebar">сайдбар</div>
    </div>
</div>
<div id="modal">
    <div id="edit_text" name="">
        Редактировать текст:
        <img src="resources/cross.svg" id="cross_img">
        <textarea id="editor"></textarea>
        <input type="submit" class="nexus_button" id="submit_edit" value="Подтвердить">

    </div>
</div>

<script src="../jquery-latest.js"></script>
<script src="scripts/main.js"></script>
</body>
</html>
