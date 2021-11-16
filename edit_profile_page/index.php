<?php
namespace Firebase\JWT;
include_once '../src/BeforeValidException.php';
include_once '../src/ExpiredException.php';
include_once '../src/SignatureInvalidException.php';
include_once '../src/JWT.php';
include_once '../connect.php';

$decoded = (array)JWT::decode($_COOKIE["key"], "ArtyomIsLatent", array('HS256'));
$id = $decoded["id"];

if (!connect()) {
    echo "<H3>Соединение не установленно</H3><br>";
}

$user = pg_fetch_object(pg_query("SELECT * FROM users WHERE user_id='" . $id ."';"));
if(empty($user)){
    header('Location:http://localhost:9092/user_profile/index.php?id=' . $id);
    exit;
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
<header class="page_header"><img class="page_header_logo logo" src="../resources/Nexus_1_3.png" ></header>
<div class="page_layout">
    <div class="page_body">
            <div class="narrow_column">
                    <div class="user_login">
                        <div class="user_login_wrap">
                            <div class="user_data_wrap">
                                <div class="userdata" id="firstname"><div class="prompt">Firstname:</div> <?= $user->firstname ?></div>
                            </div>
                            <img class="pencil_button" id="edit_name" src="../resources/pencil.svg">
                        </div>
                        <br>
                        <div class="user_login_wrap">
                            <div class="user_data_wrap">
                                <div class="userdata" id="lastname"><div class="prompt">Lastname:</div><?= $user->lastname?></div>
                            </div>
                            <img class="pencil_button" id="edit_lastname" src="../resources/pencil.svg">
                        </div>
                        <br>
                        <div class="user_login_wrap">
                            <div class="user_data_wrap">
                                <div class="userdata" id="email"><div class="prompt">Email:</div><?= $user->email?></div>
                            </div>
                            <img class="pencil_button" id="edit_email" src="../resources/pencil.svg">
                        </div>
                    </div>
            </div>
    </div>
    <div class="row ">
        <div class="sidebar">сайдбар</div>
    </div>
</div>
<script src="scripts/main.js"></script>
</body>
</html>
