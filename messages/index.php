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

if (!empty($_GET["id"])){
    $partner = pg_fetch_object(pg_query("SELECT * FROM users WHERE user_id='" . $_GET["id"] ."';"));
    if(empty($partner)){
        header('Location:http://localhost:9092/user_profile/index.php?id=' . $id);
        exit;
    }
}
else{
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
            <div id="partner_info" name="<?= $partner->user_id ?>">
                <a class="partner_name" href="../user_profile/index.php?id=<?= $partner->user_id ?>"><?=
                    $partner->firstname . " " . $partner->lastname;
                    ?>
                </a>
                <img src="../resources/points.svg" class="points">
            </div>
            <hr>
            <div class="message_canvas">

            </div>
            <hr>
            <div class="send_block">
                    <div contenteditable="true" id="message_text"></div>
                    <input type="submit" class="nexus_button" id="send_message_button" value="Отправить">
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="sidebar">сайдбар</div>
    </div>
</div>
<script src="../jquery-latest.js"></script>
<script src="scripts/main.js"></script>
</body>
</html>

