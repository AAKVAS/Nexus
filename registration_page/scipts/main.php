<?php
namespace Firebase\JWT;
include_once '../../connect.php';
include_once '../../makeToken.php';

if(isset($_POST["email"])&&isset($_POST["password"])&&isset($_POST["repeat_password"])&&isset($_POST["firstname"])&&isset($_POST["lastname"])){
    if(!empty($_POST["email"])&&!empty($_POST["password"])&&!empty($_POST["repeat_password"])&&!empty($_POST["firstname"])&&!empty($_POST["lastname"])){
        if ($_POST["password"]!=$_POST["repeat_password"]){
            //неверный пароль
            header('Location:http://localhost:9092/registration_page/index.html');
            exit;
        }
        else {
            if (!connect()) {
                echo "<H3>Соединение не установленно</H3><br>";
            }
            else{
                $query =  pg_fetch_object(pg_query("select * from users where email='" . $_POST["email"] . "';"));
                if($query->email==$_POST["email"]){
                    //почта занята
                    header('Location:http://localhost:9092/registration_page/index.html');
                    exit;
                }
                else{
                $query = pg_query("insert into users (email, password, firstname, lastname) values ('" . $_POST["email"] . "', '" . $_POST["password"] . "', '" . $_POST["firstname"] . "', '" . $_POST["lastname"] . "');");

                $query = pg_fetch_object(pg_query("select * from users where email='" . $_POST["email"] . "';"));
                makeToken($query);
                header("Location:http://localhost:9092/user_profile/index.php?id=" . $query->user_id);
                exit;
                }
            }
        }
    }
    else {
        header('Location:http://localhost:9092/registration_page/index.html');
        exit;
    }
}