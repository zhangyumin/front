<?php
    //接收数据
    $coord = $_GET['coord'];
    $strand = $_GET['strand'];
    $species = $_GET['species'];
    $chr = $_GET['chr'];
    //链接数据库
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_server",$con);
//    echo "select gene from t_".$species."_gff_genes where chr='$chr' and ftr_start <= $coord and ftr_end >= $coord;";
    $gene=mysql_query("select gene from t_".$species."_gff_genes where chr='$chr' and ftr_start <= $coord and ftr_end >= $coord;");
    $seq = mysql_fetch_row($gene)[0];
    if($seq == NULL){
        echo "<script>alert(\"no data in this area\");window.close();</script>";
    }
    else{
        header("location: ./sequence_detail.php?species=$species&seq=$seq");
    }
?>

