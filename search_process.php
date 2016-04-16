<?php
                session_start();
                $con=  mysql_connect("localhost","root","root");
                mysql_select_db("db_server",$con);
                    $_SESSION['search_species'] = $_POST['species'];
                if(!isset($_SESSION['search'])){
                    $_SESSION['search']=$_POST['species'].date("Y").date("m").date("d").date("h").date("i").date("s");
                }
                else{
                    mysql_query("drop table db_user.Search_".$_SESSION['search']."");
                    $_SESSION['search']=$_POST['species'].substr($_SESSION['search'], strpos($_SESSION['search'], "201"));
                }
                //geneid不属于一个表，先从数据库中读取所有的geneid
                $gene_array=array();
                if($_POST['gene_id'] != NULL){
                    $gene_array=  explode(",", $_POST['gene_id']);
                    $gene_array=  array_unique($gene_array);
                    $sql_geneid = "select gene from t_".$_POST['species']."_genedesc where gene in ('".implode("','", $gene_array)."') or alias in ('".implode("','", $gene_array)."');";
                    $geneid_result = mysql_query($sql_geneid);
//                    var_dump($gene_array);
                    while($geneid_result_row=  mysql_fetch_row($geneid_result)){
                        array_push($gene_array, $geneid_result_row[0]);
//                        var_dump($geneid_result_row[0]);
                    }
                    $gene_array=  array_unique($gene_array);
//                    var_dump($sql_geneid);
//                    var_dump($gene_array);
                }
//                echo $_SESSION['search'];
                //模糊搜索
                if($_GET['method']=='fuzzy'){
                    $go_array_key=array();
                    $key=$_POST['key'];
                    $species=$_POST['species'];
                    if($_GET['species']!=NULL)
                        $species=$_GET['species'];
                    if($_GET['keyword']!=NULL)
                        $key=$_GET['keyword'];
                    $go_qry=  mysql_query("select gene from t_".$species."_go where gene like '%$key%' or goid like '%$key%' or goterm like '%$key%'");
                    while($go_result=  mysql_fetch_row($go_qry)){
                        array_push($go_array_key, $go_result[0]);
                    }
                    $detail_query = mysql_query("select gene from t_".$species."_genedesc where alias like '%$key%' or description like '%$key%' or description_full like '%$key%'");
                    while($detail_result = mysql_fetch_row($detail_query)){
                        array_push($go_array_key, $detail_result[0]);
                    }
                    $pac_qry= "create table db_user.Search_".$_SESSION['search']." select * from t_".$species."_pac where ";
                    $pac_qry.="gene in ('";
                    $pac_qry.=implode("','", $go_array_key);
                    $pac_qry.="') or chr like '%$key%' or gene like '%$key%' or ftr like '%$key%'; ";
                    $query_result=  mysql_query($pac_qry);
                }
                else{
                    $chr=$_POST['chr'];
                    $start=$_POST['start'];
                    $end=$_POST['end'];
                    $gene_id=$_POST['gene_id'];
                    $go_accession=$_POST['go_accession'];
                    $go_name=$_POST['go_name'];
                    $function=$_POST['function'];
                    $go_array=array();
                    $species=$_POST['species'];
                    $ftr = $_POST['ftr'];
                   //若go搜索无输入
                    if($go_accession==NULL&&$go_name==NULL&&$function==NULL){
                        $sysQry="create table db_user.Search_".$_SESSION['search']." select * from db_server.t_".$_POST['species']."_pac where 1=1";
                        if($_POST['chr']!='all'){
                            $sysQry.=" and chr='$chr'";
                        }
                        if($_POST['start']!=NULL){
                            $sysQry.=" and ftr_start>=".$_POST['start']."";
                        }
                        if($_POST['end']!=NULL){
                            $sysQry.=" and ftr_end<=".$_POST['end']."";
                        }
                        if($_POST['ftr']!='all'){
                            $sysQry.=" and ftr='$ftr'";
                        }
                        if($_POST['gene_id']!=NULL){
                            $sysQry.=" and gene in ('";
                            $sysQry.=implode("','", $gene_array);
                            $sysQry.="')";
                        }
                        $sysQry.=";";
                        $query_result=mysql_query($sysQry);
                    }
                    else
                    {
                        $go_sysQry="select gene from db_server.t_".$_POST['species']."_go where 1=1";
                        if($_POST['go_name']!=NULL)
                        {
                            $go_sysQry.=" and goterm like '%$go_name%'";

                             }
                             if($_POST['go_accession']!=NULL)
                             {
                                 $go_id=explode(',',$go_accession);
                                 $go_id=  array_unique($go_id);
                                 $go_sysQry.=" and";
            //                     foreach ($go_id as $key => $value) {
            //                         if($key==0)
            //                         {
            //                             $go_sql_search.=" goid='$value'";
            //                         }
            //                         else
            //                             $go_sql_search.=" or goid='$value'";
            //                     }
            //                     $go_sql_search.=")";
                                    $go_sysQry.=" goid in ('";
                                    $go_sysQry.=implode("','", $go_id);
                                    $go_sysQry.="')";
                             }
                                    $go_sysQry.=";";
                                    $go_sysQry_result=mysql_query($go_sysQry);
                                    while($go_sysQry_row=  mysql_fetch_row($go_sysQry_result))
                                    {
                                        array_push($go_array,$go_sysQry_row[0]);
                                    }
                                    $go_array=array_unique($go_array);
                                    if($_POST['function']!=NULL){
                                        $detail_query = mysql_query("select gene from t_".$_POST['species']."_genedesc where description like '%$function%' or description_full like '%$function%'");
                                        while($detail_result = mysql_fetch_row($detail_query)){
                                            array_push($go_array, $detail_result[0]);
                                        }
                                    }
                                   $sysQry="create table db_user.Search_".$_SESSION['search']." select * from db_server.t_".$_POST['species']."_pac where 1=1";
                                   if($_POST['chr']!='all'){
                                       $sysQry.=" and chr='$chr'";
                                   }
                                   if($_POST['start']!=NULL){
                                       $sysQry.=" and ftr_start>=".$_POST['start']."";
                                   }
                                   if($_POST['end']!=NULL){
                                       $sysQry.=" and ftr_end<=".$_POST['end']."";
                                   }
                                   if($_POST['ftr']!='all'){
                                        $sysQry.=" and ftr='$ftr'";
                                    }
                                   if($_POST['gene_id']!=NULL){
                                        $sysQry.=" and gene in ('";
                                        $sysQry.=implode("','", $gene_array);
                                        $sysQry.="')";
                                   }
                                    if(count($go_array)>0){
                                       $sysQry.=" and gene in ('";
                                       $sysQry.=implode("','", $go_array);
                                       $sysQry.="')";
                                   }
                                       $sysQry.=";";
                               $query_result=mysql_query($sysQry);
                             }  
                }
//                echo "select * from Search_".$_SESSION['search']." into outfile '/var/www/front/searched/Search_".$_SESSION['search'].".txt';";
                mysql_query("select * from db_user.Search_".$_SESSION['search']." into outfile '/var/www/front/searched/Search_".$_SESSION['search'].".txt';");
        //            echo $_SESSION['file'];
        //            echo $go_sysQry;
        //            echo $go_insert;
        //            echo $go_array;
//            //        var_dump($sysQry_result);
//                         echo '<script>window.location.href="show_sequence_searched.php?chr=1&gene=31185&strand=-1";</script>';
//                            echo $go_sysQry;
//                             echo $sysQry;
                header("Location:./search_result.php"); 
            ?>