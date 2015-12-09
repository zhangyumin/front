<?php
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_server",$con);
    $result=mysql_query("select wt_leaf_1,wt_leaf_2,wt_leaf_3 from t_arab_pac");
    while($row=mysql_fetch_row($result)){
        //取总和
        $sum = 0;
        foreach ($row as $key => $value) {
            $sum = $sum + $value;
            echo $value;
        }
        //取平均数
        $average=$sum/3;
        $average= number_format($average, 1);
        //取中位数
        sort($row);//升序排列
        $n = count($row);
        if($n%2==1){
            $middle = $row[($n-1)/2];
        }
        else{
            $middle = ($row[$n/2]+$row[$n/2-1])/2;
            $middle = number_format($middle,1);
        }
        mysql_query("update t_arab_pac set wt_leaf_sum = $sum , wt_leaf_avg = $average , wt_leaf_med = $middle where wt_leaf_1 = $row[0] and wt_leaf_2 = $row[1] and wt_leaf_3 = $row[2]");
//        echo "---------------------------------\n";
//        echo $sum."\n";
//        echo $average."\n";
//        echo $middle,"\n";
//        echo "----------------------------------\n";
   }
?>