<?php
try {
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_bio",$con);
    if(empty ($_POST['search']))
    {
        $result=  mysql_query("SELECT COUNT(*) AS RecordCount FROM pac_arab10;");
        $row=  mysql_fetch_array($result);
        $recordCount=$row['RecordCount'];

        $result=  mysql_query("SELECT * FROM pac_arab10 ORDER BY ".$_GET['jtSorting']." LIMIT ".$_GET['jtStartIndex'].",".$_GET['jtPageSize'].";");
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
