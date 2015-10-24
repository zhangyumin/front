<?php
	session_start();
try {
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_server",$con);
    if(empty ($_POST['search']))
    {
        $result=  mysql_query("SELECT COUNT(*) AS RecordCount FROM t_".$_GET['species']."_pac;");
        $row=  mysql_fetch_array($result);
        $recordCount=$row['RecordCount'];

        $result=  mysql_query("SELECT * FROM t_".$_GET['species']."_pac ORDER BY ".$_GET['jtSorting']." LIMIT ".$_GET['jtStartIndex'].",".$_GET['jtPageSize'].";");
    }
    else{
        $search=$_POST['search'];
        $result=  mysql_query("SELECT COUNT(*) AS RecordCount FROM pac_arab10 WHERE gene LIKE '%".$search."%';");
        $row=  mysql_fetch_array($result);
        $recordCount=$row['RecordCount'];
        
        $result=  mysql_query("SELECT * FROM pac_arab10	 WHERE gene LIKE '%".$search."%' ORDER BY ".$_GET['jtSorting']." LIMIT ".$_GET['jtStartIndex'].",".$_GET['jtPageSize'].";");
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
