<?php
namespace Firebase\JWT;
include_once '../../connect.php';
include_once '../../makeToken.php';

if(isset($_POST["email"])&&isset($_POST["password"])){

    if (!connect()) {
        echo "<H3>Соединение не установленно</H3><br>";
    }
    else{
        $query =  pg_fetch_object(pg_query("select * from users where email='" . $_POST["email"] . "';"));
        if($query->password!=$_POST["password"]||empty($_POST["password"])){
            //неверный пароль
            header('Location:http://localhost:9092/authentication_page/index.html');
            exit;
        }
        else{
            makeToken($query);
            header("Location:http://localhost:9092/user_profile/index.php?id=" . $query->user_id);
            exit;
        }
    }
}