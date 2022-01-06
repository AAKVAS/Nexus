<?php
function connect(){
    $cn = pg_connect("host=localhost port=5432 dbname=Nexus user=postgres           password=1");
    return $cn;
}
