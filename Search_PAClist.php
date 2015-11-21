<?php
	session_start();

try {
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_user",$con);
    if(empty ($_POST['search']))
    {
        $result=  mysql_query("SELECT COUNT(*) AS RecordCount FROM Search_".$_SESSION['search'].";");
        $row=  mysql_fetch_array($result);
        $recordCount=$row['RecordCount'];

        $result=  mysql_query("SELECT * FROM Search_".$_SESSION['search']." ORDER BY ".$_GET['jtSorting']." LIMIT ".$_GET['jtStartIndex'].",".$_GET['jtPageSize'].";");
    }
    else{
        $search=$_POST['search'];
        $result=  mysql_query("SELECT COUNT(*) AS RecordCount FROM Search_".$_SESSION['search']." WHERE gene LIKE '%".$search."%' or coord LIKE '%".$search."%' or chr LIKE '%".$search."%' or ftr_start LIKE '%".$search."%' or ftr_end LIKE '%".$search."%' or ftr LIKE '%".$search."%' or gene_type LIKE '%".$search."%';");
        $row=  mysql_fetch_array($result);
        $recordCount=$row['RecordCount'];
        
        $result=  mysql_query("SELECT * FROM Search_".$_SESSION['search']." WHERE gene LIKE '%".$search."%' or coord LIKE '%".$search."%' or chr LIKE '%".$search."%' or ftr_start LIKE '%".$search."%' or ftr_end LIKE '%".$search."%' or ftr LIKE '%".$search."%' or gene_type LIKE '%".$search."%' ORDER BY ".$_GET['jtSorting']." LIMIT ".$_GET['jtStartIndex'].",".$_GET['jtPageSize'].";");
    }
    $rows=array();
    while($row=  mysql_fetch_array($result))
    {
        $rows[]=$row;
    }
    
    $jTableResult=array();
    $jTableResult['Result']="OK";
    $jTableResult['Records']=$rows;
    $jTableResult['TotalRecordCount']=$recordCount;
    print json_encode($jTableResult);
} 
catch (Exception $ex) {
    $jTableResult=array();
    $jTableResult['Result']="ERROR";
    $jTableResult['Message']=$ex->getMessage();
    print json_encode($jTableResult);
}

?>
