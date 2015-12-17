<?php
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_server",$con);
    $sql = mysql_query("select * from t_arab_pa1 where coord = 352");
    while($array = mysql_fetch_array($sql)){
        var_dump($array);
    }
?>