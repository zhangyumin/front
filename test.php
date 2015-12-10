<?php
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_server",$con);
    $result=mysql_query("select coord,gene,wt_leaf_1,wt_leaf_2,wt_leaf_3 from t_arab_pac");
    while($row=mysql_fetch_row($result)){
        $median = array();
        //取中位数
        array_push($median, $row[2]);
        array_push($median, $row[3]);
        array_push($median, $row[4]);
        sort($median);//升序排列
        $n = count($median);
        if($n%2==1){
            $middle = $median[($n-1)/2];
        }
        else{
            $middle = ($median[$n/2]+$median[$n/2-1])/2;
            $middle = number_format($middle,1);
        }
        mysql_query("update t_arab_pac set wt_leaf_med = $middle where coord=$row[0] and gene='$row[1]'");
//        echo $middle;
   }
?>