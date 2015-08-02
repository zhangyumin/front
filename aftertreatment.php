<?php
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_bio",$con);
    session_start();
    $file=$_SESSION['file'];
    if($_GET['action']=='search')
    {
        $chr=$_POST['chr'];
        $start=$_POST['start'];
        $end=$_POST['end'];
        $gene_id=$_POST['gene_id'];
        $go_accession=$_POST['go_accession'];
        $go_name=$_POST['go_name'];
        $function=$_POST['function'];
        $search_array=array();
        $search_result=array();
        $search_go_array=array();
        $search_num=0;
        $sql_search="select coord from db_user.sys_pac_$file where 1=1";
        $go_sql_search="select coord from db_user.sys_pac_$file left join db_bio.go_arab10 on sys_pac_$file.gene=go_arab10.gene where 1=1";
        $out1=mysql_query("select distinct _group from sample_arab10;");
        while($row= mysql_fetch_row($out1))
        {
            if($_POST['sl_'.$row[0]]!=NULL)
            {
                $sys_sql.=$_POST["sl_$row[0]"];//系统pac数据表应当搜索的列名
            }
        }
        //echo $sys_sql;
        //
        if(strlen($sys_sql)>0)
        {
            mysql_query("create table db_user.sys_pac_$file as(select chr,strand,coord,gff_id,ftr,ftr_start,ftr_end,transcript,gene,gene_type,ftrs,trspt_cnt,UPA_start,UPA_end,tot_PAnum,tot_ftrs,ref_coord,ref_tagnum$sys_sql from db_bio.PAC_sys_arab10);");
             if($_POST['chr']!=0)
             {
     //            array_push($condition_array,'$search_chr');
     //            $search_num+=1;
                 $sql_search.=" and chr=$chr";
     //            $result_chr=mysql_query("select coord from db_user.sys_pac_$file where chr=$chr;");
     //            while($row_chr=mysql_fetch_row($result_chr))
     //            {
     //                array_push($search_array, $row_chr[0]);
     //            }
             }
             if($_POST['start']!=NULL)
             {
     //            array_push($condition_array,'$search_start');
     //            $search_num+=1;
                 $sql_search.=" and ftr_start>=$start";
     //            $result_start=mysql_query("select coord from db_user.sys_pac_$file where ftr_start>=$start;");
     //            while($row_start=mysql_fetch_row($result_start))
     //            {
     //                array_push($search_start, $row_start[0]);
     //            }
             }
             if($_POST['end']!=NULL)
             {
     //            array_push($condition_array,'$search_end');
     //            $search_num+=1;
                 $sql_search.=" and ftr_end<=$end";
     //            $result_end=mysql_query("select coord from db_user.sys_pac_$file where ftr_end<=$end;");
     //            while($row_end=mysql_fetch_row($result_end))
     //            {
     //                array_push($search_array, $row_end[0]);
     //            }
             }
             if($_POST['gene_id']!=NULL)
             {
     //            array_push($condition_array,'$search_geneid');
                 $search_geneid=explode(',',$gene_id);
                 $search_geneid=array_unique($search_geneid);
                 $sql_search.=" and (1=0";
                 foreach ($search_geneid as $key => $value) {
     //                $result_geneid=  mysql_query("select coord from db_user.sys_pac_$file where gene='$value';");
     //                while($row_geneid=  mysql_fetch_row($result_geneid))
     //                {
     //                    array_push($search_array,$row_geneid[0]);
     //                }
                     $sql_search.=" or gene='$value'";
                 }
                 $sql_search.=")";
             }
             $sql_search.=";";
             $search_part1=mysql_query($sql_search);
             while($row_part1=  mysql_fetch_row($search_part1))
             {
                 array_push($search_array, $row_part1[0]);
             }
     //       以上从sys_pac_session中搜索
             if($_POST['go_name']==NULL&&$_POST['function']==NULL&&$_POST['go_accession']==NULL)
             {
                 $go_sql_search="";
             }
             else{
                 if($_POST['go_name']!=NULL)
                 {
         //            array_push($condition_array,'$search_goname');
         //            $search_num+=1;
         //            $result_goname=mysql_query("select coord from db_user.sys_pac_$file left join db_bio.go_arab10 on sys_pac_$file.gene=go_arab10.gene where goterm like '%$go_name%';");
         //            while($row_goname=mysql_fetch_row($result_goname))
         //            {
         //                array_push($search_array, $row_goname[0]);
         //            }
                     $go_sql_search.=" and goterm like '%$go_name%'";

                 }
                 if($_POST['function']!=NULL)
                 {
         //            array_push($condition_array,'$search_function');
         //            $search_num+=1;
         //            $result_function=mysql_query("select coord from db_user.sys_pac_$file left join db_bio.go_arab10 on sys_pac_$file.gene=go_arab10.gene where genefunction like '%$function%';");
         //            while($row_function=mysql_fetch_row($result_function))
         //            {
         //                array_push($search_array, $row_function[0]);
         //            }
                     $go_sql_search.=" and genefunction like '%$function%'";
                 } 
                 if($_POST['go_accession']!=NULL)
                 {
         //            array_push($condition_array,'$search_goaccession');
         //            $search_num+=1;
                     $go_id=explode(',',$go_accession);
         //            foreach ($go_id as $key => $value) {
         ////                $result_goaccession=mysql_query("select gene from db_bio.go_arab10 where goid='$value';");
         ////                $result_goaccession=  mysql_query("select coord from db_user.sys_pac_$file r,db_bio.go_arab10 u where r.gene=u.gene and u.goid='$value';");
         //                $result_goaccession=  mysql_query("select coord from db_user.sys_pac_$file left join db_bio.go_arab10 on sys_pac_$file.gene=go_arab10.gene where goid='$value';");
         //                while($row_goaccession=mysql_fetch_row($result_goaccession))
         //                {
         //                    array_push($search_array, $row_goaccession[0]);
         //                }
         //            }
                     $go_id=  array_unique($go_id);
                     $go_sql_search.=" and (";
                     if($_POST['go_name']==NULL&&$_POST['function']==NULL){
                         $go_sql_search="select coord from db_user.sys_pac_$file left join db_bio.go_arab10 on sys_pac_$file.gene=go_arab10.gene where (";
                     }
                     foreach ($go_id as $key => $value) {
                         if($key==0)
                         {
                             $go_sql_search.=" goid='$value'";
                         }
                         else
                             $go_sql_search.=" or goid='$value'";
                     }
                     $go_sql_search.=")";
                 }
                 $go_sql_search.=";";
                 $search_part2=mysql_query($go_sql_search);
                 while($row_part2=  mysql_fetch_row($search_part2))
                 {
                     array_push($search_go_array, $row_part2[0]);
                 }

             }
             if(count($search_go_array)>0)
                     $search_result=array_intersect($search_array,$search_go_array);
             else
                     $search_result=$search_array;
                 mysql_query("create table db_user.usrQryPAC_$file like db_user.sys_pac_$file");
            foreach ($search_result as $key => $value) {
                mysql_query("insert into db_user.usrQryPAC_$file (chr,strand,coord,gff_id,ftr,ftr_start,ftr_end,transcript,gene,gene_type,ftrs,trspt_cnt,UPA_start,UPA_end,tot_PAnum,tot_ftrs,ref_coord,ref_tagnum$sys_sql) select chr,strand,coord,gff_id,ftr,ftr_start,ftr_end,transcript,gene,gene_type,ftrs,trspt_cnt,UPA_start,UPA_end,tot_PAnum,tot_ftrs,ref_coord,ref_tagnum$sys_sql from db_bio.PAC_sys_arab10 where coord=$value;");
            }
        }
        //以上将符合各种搜索条件的coord保存在数组，通过对比取出相同gene，即为搜索结果

        var_dump($search_array);echo "<br><br><br><br><br>";
        var_dump($search_go_array);echo "<br>";
        var_dump($search_result);echo "<br>";
     
       foreach ($_SESSION['file_real'] as $key => $value) {
           if($_POST[$value]=='on')
            $usr_sql.=','.$value;
       }
       $sql=$usr_sql.$sys_sql;

       if(strlen($usr_sql)>0)//勾选了user的sample
       {     // echo $usr_sql;
            mysql_query("create table db_user.usr_pac_$file as(select chr,strand,coord,gff_id,ftr,ftr_start,ftr_end,transcript,gene,gene_type,ftrs,trspt_cnt,UPA_start,UPA_end,tot_PAnum,tot_ftrs,ref_coord,ref_tagnum$usr_sql from db_user.PAC_$file);");
            
            
       }

    }
    else if($_GET['action']=='degene')
        echo "degene";
    else if($_GET['action']=='depac')
        echo "depac";
    ?>