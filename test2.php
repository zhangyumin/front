<?php
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_server",$con);
    mysql_query("update t_arab_pa2 set oxt6_root_med = oxt6_root_1 where oxt6_root_1 >= oxt6_root_2 and oxt6_root_1 <= oxt6_root_3");
    mysql_query("update t_arab_pa2 set oxt6_root_med = oxt6_root_1 where oxt6_root_1 <= oxt6_root_2 and oxt6_root_1 >= oxt6_root_3");
    
    mysql_query("update t_arab_pa2 set oxt6_root_med = oxt6_root_2 where oxt6_root_2 >= oxt6_root_1 and oxt6_root_2 <= oxt6_root_3");
    mysql_query("update t_arab_pa2 set oxt6_root_med = oxt6_root_2 where oxt6_root_2 <= oxt6_root_1 and oxt6_root_2 >= oxt6_root_3");
    
    mysql_query("update t_arab_pa2 set oxt6_root_med = oxt6_root_3 where oxt6_root_3 >= oxt6_root_2 and oxt6_root_3 <= oxt6_root_1");
    mysql_query("update t_arab_pa2 set oxt6_root_med = oxt6_root_3 where oxt6_root_3 <= oxt6_root_2 and oxt6_root_3 >= oxt6_root_1");
?>