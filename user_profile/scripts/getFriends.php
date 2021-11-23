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
    if(isset($_POST["user_id"])){
        $query =  pg_query("SELECT * FROM friend WHERE user_id='". $_POST["user_id"] . "' OR friend_id='". $_POST["user_id"] . "';");
        $result = array();
        while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)){
            $name = null;
            $array = array();
            if($line["user_id"]==$_POST["user_id"]){
                $name = pg_fetch_object(pg_query("SELECT firstname, lastname FROM users WHERE user_id='" . $line["friend_id"] ."';"));
                $array["id"]=$line["friend_id"];
            }
            else{
                $name = pg_fetch_object(pg_query("SELECT firstname, lastname FROM users WHERE user_id='" . $line["user_id"] ."';"));
                $array["id"]=$line["user_id"];
            }
            $array["name"]=$name->firstname . " " . $name->lastname;
            $result[]=$array;
        }
        $json = json_encode($result);
        echo $json;
    }
}