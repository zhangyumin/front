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
//        $sql="";
        $search_chr=array();
        $search_start=array();
        $search_end=array();
        $search_geneid=array();
        $search_goaccession=array();
        $go_id=array();
        $search_goname=array();
        $search_function=array();
        $condition_array=array();
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
        //mysql_query("create table db_user.sys_pac_$file as(select chr,strand,coord,gff_id,ftr,ftr_start,ftr_end,transcript,gene,gene_type,ftrs,trspt_cnt,UPA_start,UPA_end,tot_PAnum,tot_ftrs,ref_coord,ref_tagnum$sys_sql from db_bio.PAC_sys_arab10);");
        if($_POST['chr']!=0)
        {
            array_push($condition_array,'$search_chr');
            $result_chr=mysql_query("select gene from db_user.sys_pac_$file where chr=$chr;");
            while($row_chr=mysql_fetch_row($result_chr))
            {
                array_push($search_chr, $row_chr[0]);
            }
        }
        if($_POST['start']!=NULL)
        {
            array_push($condition_array,'$search_start');
            $result_start=mysql_query("select gene from db_user.sys_pac_$file where ftr_start>=$start;");
            while($row_start=mysql_fetch_row($result_start))
            {
                array_push($search_start, $row_start[0]);
            }
        }
        if($_POST['end']!=NULL)
        {
            array_push($condition_array,'$search_end');
            $result_end=mysql_query("select gene from db_user.sys_pac_$file where ftr_end>=$end;");
            while($row_end=mysql_fetch_row($result_end))
            {
                array_push($search_end, $row_end[0]);
            }
        }
        if($_POST['gene_id']!=NULL)
        {
            array_push($condition_array,'$search_geneid');
            $search_geneid=explode(';',$gene_id);
        }
        if($_POST['go_accession']!=NULL)
        {
            array_push($condition_array,'$search_goaccession');
            $go_id=explode(';',$go_accession);
            foreach ($go_id as $key => $value) {
                $result_goaccession=mysql_query("select gene from db_bio.go_arab10 where goid='$value';");
                while($row_goaccession=mysql_fetch_row($result_goaccession))
                {
                    array_push($search_goaccession, $row_goaccession[0]);
                }
            }
        }
        if($_POST['go_name']!=NULL)
        {
            array_push($condition_array,'$search_goname');
            $result_goname=mysql_query("select gene from db_bio.go_arab10 where goterm like '%$go_name%';");
            while($row_goname=mysql_fetch_row($result_goname))
            {
                array_push($search_goname, $row_goname[0]);
            }
        }
        if($_POST['function']!=NULL)
        {
            array_push($condition_array,'$search_function');
            $result_function=mysql_query("select gene from db_bio.go_arab10 where goterm like '%$function%';");
            while($row_function=mysql_fetch_row($result_function))
            {
                array_push($search_function, $row_function[0]);
            }
        }
        //以上将符合各种搜索条件的gene保存在数组，通过对比取出相同gene，即为搜索结果
        $condition=  implode(',', $condition_array);
        $search_result=  array_intersect(...$condition_array);
        var_dump($search_result);
     
       foreach ($_SESSION['file_real'] as $key => $value) {
           if($_POST[$value]=='on')
            $usr_sql.=','.$value;
       }
       $sql=$usr_sql.$sys_sql;

//       if(strlen($usr_sql)>0)//勾选了user的sample
//       {     // echo $usr_sql;
//            mysql_query("create table db_user.usr_pac_$file as(select chr,strand,coord,gff_id,ftr,ftr_start,ftr_end,transcript,gene,gene_type,ftrs,trspt_cnt,UPA_start,UPA_end,tot_PAnum,tot_ftrs,ref_coord,ref_tagnum$usr_sql from db_user.PAC_$file);");
//       }

    }
    else if($_GET['action']=='degene')
        echo "degene";
    else if($_GET['action']=='depac')
        echo "depac";
    ?>