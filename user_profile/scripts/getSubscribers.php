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
        $query =  pg_query("SELECT * FROM subscriber WHERE user_id='". $_POST["user_id"] . "';");
        $result = array();
        while ($line = pg_fetch_array($query, null, PGSQL_ASSOC)){
            $array = array();
            $name = pg_fetch_object(pg_query("SELECT firstname, lastname FROM users WHERE user_id='" . $line["subscriber"] ."';"));
            $array["id"]=$line["subscriber"];
            $array["name"]=$name->firstname . " " . $name->lastname;
            $result[]=$array;
        }
        $json = json_encode($result);
        echo $json;
    }
}