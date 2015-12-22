<?php
    session_start();
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_server",$con);
    function getIP() { 
        if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) 
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
        else if (@$_SERVER["HTTP_CLIENT_IP"]) 
            $ip = $_SERVER["HTTP_CLIENT_IP"]; 
        else if (@$_SERVER["REMOTE_ADDR"]) 
            $ip = $_SERVER["REMOTE_ADDR"]; 
        else if (@getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR"); 
        else if (@getenv("HTTP_CLIENT_IP")) 
            $ip = getenv("HTTP_CLIENT_IP"); 
        else if (@getenv("REMOTE_ADDR")) 
            $ip = getenv("REMOTE_ADDR"); 
        else 
            $ip = "Unknown"; 
        return $ip; 
    }
    $uip = getIP();
    $mysqltime=date('Y-m-d H:i:s',  time());
//    mysql_query("INSERT INTO `User_Task`(`id`, `ip`, `time`) VALUES (".$_SESSION['file'].",$uip,$mysqltime)");
    echo "INSERT INTO `User_Task`(`id`, `ip`, `time`) VALUES ('".$_SESSION['file']."','$uip','$mysqltime')";
?>