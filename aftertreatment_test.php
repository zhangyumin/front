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
        $search_usr_array=array();
        $search_usr_result=array();
        $search_go_usr_array=array();
        $sql_search="select coord from db_user.sys_pac_$file where 1=1";
        $go_sql_search="select coord from db_user.sys_pac_$file left join db_bio.go_arab10 on PAC_$file.gene=go_arab10.gene where 1=1";
        $sql_usr_search="select coord from db_user.PAC_$file where 1=1";
        $go_sql_usr_search="select coord from db_user.PAC_$file left join db_bio.go_arab10 on PAC_$file.gene=go_arab10.gene where 1=1";
        $out1=mysql_query("select distinct _group from sample_arab10;");
        while($row= mysql_fetch_row($out1))
        {
            if($_POST['sl_'.$row[0]]!=NULL)
            {
                $sys_sql.=$_POST["sl_$row[0]"];//系统pac数据表应当搜索的列名
            }
        }
        $_SESSION['sys_checked']=  explode(',', substr($sys_sql, 1));
        foreach ($_SESSION['file_real'] as $key => $value) {
           if($_POST[$value]=='on')
            $usr_sql.=','.$value;
       }
       $_SESSION['usr_checked']= explode(',', substr($usr_sql, 1));
       $sql=$usr_sql.$sys_sql;
       if(strlen($sql)==0)
           echo "<script type='text/javascript'>alert('at least select one sample');history.back();</script>";
        //echo $sys_sql;
        //
        if(strlen($sys_sql)>0)
        {
            mysql_query("create table db_user.sys_pac_$file as(select chr,strand,coord,ftr,ftr_start,ftr_end,gene$sys_sql from db_bio.PAC_sys_arab10);");
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
             //echo $sql_search;
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
                     $go_sql_search.=" and";
                     if($_POST['go_name']==NULL&&$_POST['function']==NULL){
                         $go_sql_search="select coord from db_user.sys_pac_$file left join db_bio.go_arab10 on sys_pac_$file.gene=go_arab10.gene where (";
                     }
//                     foreach ($go_id as $key => $value) {
//                         if($key==0)
//                         {
//                             $go_sql_search.=" goid='$value'";
//                         }
//                         else
//                             $go_sql_search.=" or goid='$value'";
//                     }
//                     $go_sql_search.=")";
                        $go_sql_search.=" goid in ('";
                        $go_sql_search.=implode("','", $go_id);
                 }
                 $go_sql_usr_search.="');";
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
             mysql_query("create table db_user.sysQryPAC_$file like db_user.sys_pac_$file");
             $sys_insert="insert into db_user.sysQryPAC_$file (chr,strand,coord,ftr,ftr_start,ftr_end,gene$sys_sql) select chr,strand,coord,ftr,ftr_start,ftr_end,gene$sys_sql from db_bio.PAC_sys_arab10 where 1=0";
//            foreach ($search_result as $key => $value) {
//                //mysql_query("insert into db_user.sysQryPAC_$file (chr,strand,coord,ftr,ftr_start,ftr_end,gene$sys_sql) select chr,strand,coord,ftr,ftr_start,ftr_end,gene$sys_sql from db_bio.PAC_sys_arab10 where coord=$value;");
//                $sys_insert.=" or coord=$value";
//            }
//            $sys_insert.=";";
             if(count($search_result)>0){
                 $sys_insert.=" or coord in (";
                 $sys_insert.=implode(",", $search_result);
                 $sys_insert.=");";
             }
            mysql_query($sys_insert);
        }
        //以上将符合各种搜索条件的coord保存在数组，通过对比取出相同gene，即为搜索结果

//        var_dump($search_array);echo "<br><br><br><br><br>";
//        var_dump($search_go_array);echo "<br>";
//        var_dump($search_result);echo "<br>";
     

       if(strlen($usr_sql)>0)//勾选了user的sample
       {     // echo $usr_sql;
            mysql_query("create table db_user.usr_pac_$file as(select chr,strand,coord,ftr,ftr_start,ftr_end,gene$usr_sql from db_user.PAC_$file);");
            if($_POST['chr']!=0)
             {
     //            array_push($condition_array,'$search_chr');
     //            $search_num+=1;
                 $sql_usr_search.=" and chr=$chr";
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
                 $sql_usr_search.=" and ftr_start>=$start";
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
                 $sql_usr_search.=" and ftr_end<=$end";
     //            $result_end=mysql_query("select coord from db_user.sys_pac_$file where ftr_end<=$end;");
     //            while($row_end=mysql_fetch_row($result_end))
     //            {
     //                array_push($search_array, $row_end[0]);
     //            }
             }
             if($_POST['gene_id']!=NULL)
             {
     //            array_push($condition_array,'$search_geneid');
                 $search_usr_geneid=explode(',',$gene_id);
                 $search_usr_geneid=array_unique($search_usr_geneid);
//                 foreach ($search_usr_geneid as $key => $value) {
//                     $sql_usr_search.=" or gene='$value'";
//                 }
                 $sql_usr_search.=" or gene in (";
                 $sql_usr_search.=implode(",", $search_usr_geneid);
                 $sql_usr_search.=")";
             }
             $sql_usr_search.=";";
             $search_usr_part1=mysql_query($sql_usr_search);
             while($row_usr_part1=  mysql_fetch_row($search_usr_part1))
             {
                 array_push($search_usr_array, $row_usr_part1[0]);
             }
     //       以上从sys_pac_session中搜索
             if($_POST['go_name']==NULL&&$_POST['function']==NULL&&$_POST['go_accession']==NULL)
             {
                 $go_sql_usr_search="";
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
                     $go_sql_usr_search.=" and goterm like '%$go_name%'";

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
                     $go_sql_usr_search.=" and genefunction like '%$function%'";
                 } 
                 if($_POST['go_accession']!=NULL)
                 {
         //            array_push($condition_array,'$search_goaccession');
         //            $search_num+=1;
                     $go_usr_id=explode(',',$go_accession);
         //            foreach ($go_id as $key => $value) {
         ////                $result_goaccession=mysql_query("select gene from db_bio.go_arab10 where goid='$value';");
         ////                $result_goaccession=  mysql_query("select coord from db_user.sys_pac_$file r,db_bio.go_arab10 u where r.gene=u.gene and u.goid='$value';");
         //                $result_goaccession=  mysql_query("select coord from db_user.sys_pac_$file left join db_bio.go_arab10 on sys_pac_$file.gene=go_arab10.gene where goid='$value';");
         //                while($row_goaccession=mysql_fetch_row($result_goaccession))
         //                {
         //                    array_push($search_array, $row_goaccession[0]);
         //                }
         //            }
                     $go_usr_id=  array_unique($go_usr_id);
                     $go_sql_usr_search.=" and";
                     if($_POST['go_name']==NULL&&$_POST['function']==NULL){
                         $go_sql_usr_search="select coord from db_user.PAC_$file left join db_bio.go_arab10 on PAC_$file.gene=go_arab10.gene where";
                     }
//                     foreach ($go_usr_id as $key => $value) {
//                         if($key==0)
//                         {
//                             $go_sql_usr_search.=" goid='$value'";
//                         }
//                         else
//                             $go_sql_usr_search.=" or goid='$value'";
                        $go_sql_usr_search.=" goid in ('";
                        $go_sql_usr_search.=implode("','", $go_usr_id);
                 }
                 $go_sql_usr_search.="');";
                 $search_usr_part2=mysql_query($go_sql_usr_search);
                 while($row_usr_part2=  mysql_fetch_row($search_usr_part2))
                 {
                     array_push($search_go_usr_array, $row_usr_part2[0]);
                 }

             }
             if(count($search_go_usr_array)>0)
                     $search_usr_result=array_intersect($search_usr_array,$search_go_usr_array);
             else
                     $search_usr_result=$search_usr_array;
            mysql_query("create table db_user.usrQryPAC_$file like db_user.usr_pac_$file");
            $usr_insert="insert into db_user.usrQryPAC_$file (chr,strand,coord,ftr,ftr_start,ftr_end,gene$usr_sql) select chr,strand,coord,ftr,ftr_start,ftr_end,gene$usr_sql from db_user.usr_pac_$file where 1=0";
//            foreach ($search_usr_result as $key => $value) {
//               // mysql_query("insert into db_user.usrQryPAC_$file (chr,strand,coord,ftr,ftr_start,ftr_end,gene$usr_sql) select chr,strand,coord,ftr,ftr_start,ftr_end,gene$usr_sql from db_user.PAC_$file where coord=$value;");
//                $usr_insert.=" or coord=$value";
//            }
//            $usr_insert.=";";
            $usr_insert.=" or coord in (";
            $usr_insert.=implode(",", $search_usr_result);
            $usr_insert.=");";
            mysql_query($usr_insert);
       }
        var_dump($go_sql_usr_search);echo "<br><br><br><br><br>";
//        var_dump($search_usr_result);echo "<br>";
//        var_dump($search_usr_result);echo "<br>";
       $sys_num=  mysql_num_rows(mysql_query("select chr from db_user.usrQryPAC_$file;"));
       $usr_num= mysql_num_rows(mysql_query("select chr from db_user.sysQryPAC_$file;"));
       if($sys_num==0&&$usr_num==0&&$sql!=0)
           echo "<script type='text/javascript'>alert('no result');history.back();</script>";
       $merge="./src/perl/PAT_mergePAC.pl -smptbls 'usrQryPAC_$file;sysQryPAC_$file' -reftbl usrQryPAC_$file -smpcols '".implode(':',$_SESSION['usr_checked'] ).";".implode(':', $_SESSION['sys_checked'])."' -smplbls '".implode(':',$_SESSION['usr_checked'] ).";".implode(':', $_SESSION['sys_checked'])."' -otbl SearchedPAC_$file -udist 24 -gff gff_arab10 -conf /var/www/html/front/src/r/db_2.xml";
       shell_exec($merge);
       echo '<script>window.location.href="show_sequence_searched.php?chr=1&gene=31185&strand=-1";</script>';
           

    }
    else if($_GET['action']=='degene')
    {
        if($_POST['nor_method']=='none')
            $donorm=0;
        else
            $donorm=1;
        if($_POST['min_pat']>0)
        {
            $minpat=$_POST['min_pat'];
            $minrep=1;
        }
        else
        {
            $minpat=0;
            $minrep=0;
        }
        
            $column1=$_POST['column1'];
            $column2=$_POST['column2'];
        $degene_cmd="Rscript /var/www/html/front/src/r/R_pairDEgene.r minrep=$minrep minpat=$minpat donorm=$donorm path='/var/www/html/front/searched/' intbl=SearchedPAC_$file cols='$column1;$column2' groups=column1:column2 conf=/var/www/html/front/src/r/db_2.xml";
        //$degene_cmd="Rscript /var/www/html/front/src/r/R_pairDEgene.r minrep=1 minpat=5 donorm=0 path='/home/zym/data/' intbl=PAC_sys_arab10 cols='oxt6_leaf_1:oxt6_leaf_2;wt_leaf_1:wt_leaf_2' groups=sys:user conf=/var/www/html/front/db.xml 2>&1";
        if(count($_SESSION['usr_checked'])>1&&count($_SESSION['usr_checked']>1))
        {
            //echo $degene_cmd;
            shell_exec($degene_cmd);
            echo '<script>window.location.href="aftertreatment_result_test.php?result=degene&chr=1&gene=31185&strand=-1";</script>';
        }
        else
            echo "<script>alert('choose two sample');history.go(-1);</script>";
    }
    else if($_GET['action']=='depac')
    {
        if($_POST['depac_normethod']=='none')
            $donorm=0;
        else
            $donorm=1;
        if($_POST['depacmin_pat']>0)
        {
            $minpat=$_POST['depacmin_pat'];
            $minrep=1;
        }
        else
        {
            $minpat=0;
            $minrep=0;
        }
        $column3=$_POST['column3'];
        $column4=$_POST['column4'];
        $depac_cmd="Rscript /var/www/html/front/src/r/R_pairDEPAC.r minrep=$minrep minpat=$minpat donorm=$donorm path='/var/www/html/front/searched/' intbl=SearchedPAC_$file cols='$column3;$column4' groups=column1:column2 conf=/var/www/html/front/src/r/db_2.xml 2>&1";
        echo "<br><br>$depac_cmd<br><br>";
        echo shell_exec($depac_cmd);
        echo '<script>window.location.href="aftertreatment_result_test.php?result=depac&chr=1&gene=31185&strand=-1";</script>';
    }
    else if($_GET['action']=='switchinggene')
    {
        if($_POST['3utr']=='only3utr'){
            if($_POST['sgminpat']>0)
                $avgpat=$_POST['sgminpat'];
            else
                $avgpat=0;
            $column5=$_POST['column5'];
            $column6=$_POST['column6'];
            $sg_ocmd="Rscript /var/www/html/front/src/r/R_switchFU.r ogene=F avgPAT=$avgpat suffix=xx path='/var/www/html/front/searched/' intbl=SearchedPAC_$file cols='$column5;$column6' groups=user:sys conf=/var/www/html/front/src/r/db_2.xml 2>&1";
            //echo $sg_ocmd;
            echo shell_exec($sg_ocmd);
            echo '<script>window.location.href="aftertreatment_result_test.php?result=switchinggene_o&chr=1&gene=31185&strand=-1";</script>';
        }
        else if($_POST['3utr']=='none3utr'){
            if($_POST['uttp']=='on')
                $minpat1='T';
            else
                $minpat1='F';
            $minpat2=$_POST['minpat2'];
            $minpat3=$_POST['minpat3'];
            $minpat4=$_POST['minpat4'];
            $minpat5=$_POST['minpat5'];
            $minpat6=$_POST['minpat6'];
            $column7=$_POST['column7'];
            $column8=$_POST['column8'];
            $sg_ncmd="Rscript /var/www/html/front/src/r/R_switchMangone.r path='/var/www/html/front/searched/' intbl=SearchedPAC_$file switch=$minpat1:$minpat2:$minpat3:$minpat4:$minpat5:$minpat6 cond='' suffix=xx cols='$column7;$column8' groups=user:sys conf=/var/www/html/front/src/r/db_2.xml 2>&1";
            //echo $sg_ncmd;
            echo shell_exec($sg_ncmd);
            echo '<script>window.location.href="aftertreatment_result_test.php?result=switchinggene_n&chr=1&gene=31185&strand=-1";</script>';
        }
    }
    else{
        $out2=mysql_query("select distinct _group from sample_arab10;");
        while($row1= mysql_fetch_row($out2))
        {
            if($_POST['sl_'.$row1[0]]!=NULL)
            {
                $sys_sql.=$_POST["sl_$row1[0]"];//系统pac数据表应当搜索的列名
            }
        }
        $_SESSION['sys_checked']=  explode(',', substr($sys_sql, 1));
        foreach ($_SESSION['file_real'] as $key => $value) {
           if($_POST[$value]=='on')
            $usr_sql.=','.$value;
       }
       $_SESSION['usr_checked']= explode(',', substr($usr_sql, 1));
       $sql=$usr_sql.$sys_sql;
       var_dump($_SESSION['sys_checked']);
       var_dump($_SESSION['usr_checked']);
       if(strlen($sql)==0)
           echo "<script type='text/javascript'>alert('at least select one sample');history.back();</script>";
        if(strlen($sys_sql)>0)
            mysql_query("create table db_user.sys_pac_$file as(select chr,strand,coord,gff_id,ftr,ftr_start,ftr_end,transcript,gene,gene_type,ftrs,trspt_cnt,UPA_start,UPA_end,tot_PAnum,tot_ftrs,ref_coord,ref_tagnum$sys_sql from db_bio.PAC_sys_arab10);");
       $merge="./src/perl/PAT_mergePAC.pl -smptbls 'PAC_$file;sys_pac_$file' -reftbl PAC_$file -smpcols '".implode(':',$_SESSION['usr_checked'] ).";".implode(':', $_SESSION['sys_checked'])."' -smplbls '".implode(':',$_SESSION['usr_checked'] ).";".implode(':', $_SESSION['sys_checked'])."' -otbl SearchedPAC_$file -udist 24 -gff gff_arab10 -conf /var/www/html/front/src/r/db_2.xml 2>&1";
       echo shell_exec($merge);
            if($_GET['action']=='o_degene'){
                if($_POST['nor_method']=='none')
                    $donorm=0;
                else
                    $donorm=1;
                if($_POST['min_pat']>0)
                {
                    $minpat=$_POST['min_pat'];
                    $minrep=1;
                }
                else
                {
                    $minpat=0;
                    $minrep=0;
                }
                $degene_cmd="Rscript /var/www/html/front/src/r/R_pairDEgene.r minrep=$minrep minpat=$minpat donorm=$donorm path='/var/www/html/front/searched/' intbl=SearchedPAC_$file cols='".implode(':',$_SESSION['usr_checked'] ).";".implode(':', $_SESSION['sys_checked'])."' groups=user:sys conf=/var/www/html/front/src/r/db_2.xml 2>&1";
                //$degene_cmd="Rscript /var/www/html/front/src/r/R_pairDEgene.r minrep=1 minpat=5 donorm=0 path='/home/zym/data/' intbl=PAC_sys_arab10 cols='oxt6_leaf_1:oxt6_leaf_2;wt_leaf_1:wt_leaf_2' groups=sys:user conf=/var/www/html/front/db.xml 2>&1";
                if(count($_SESSION['usr_checked'])>1&&count($_SESSION['usr_checked']>1))
                {
                    shell_exec($degene_cmd);
                    //echo "<br><br>$degene_cmd<br><br>";
                    echo '<script>window.location.href="aftertreatment_result_test.php?result=degene&chr=1&gene=31185&strand=-1";</script>';
                }
                else
                    echo "<script>alert('choose two sample');history.go(-1);</script>";
            }
            else if($_GET['action']=='o_depac'){
                if($_POST['depac_normethod']=='none')
                    $donorm=0;
                else
                    $donorm=1;
                if($_POST['depacmin_pat']>0)
                {
                    $minpat=$_POST['depacmin_pat'];
                    $minrep=1;
                }
                else
                {
                    $minpat=0;
                    $minrep=0;
                }
                $depac_cmd="Rscript /var/www/html/front/src/r/R_pairDEPAC.r minrep=$minrep minpat=$minpat donorm=$donorm path='/var/www/html/front/searched/' intbl=SearchedPAC_$file cols='".implode(':',$_SESSION['usr_checked'] ).";".implode(':', $_SESSION['sys_checked'])."' groups=user:sys conf=/var/www/html/front/src/r/db_2.xml 2>&1";
                echo "<br><br>$depac_cmd<br><br>";
                echo shell_exec($depac_cmd);
                echo '<script>window.location.href="aftertreatment_result_test.php?result=depac&chr=1&gene=31185&strand=-1";</script>';
            }
            else if($_GET['action']=='o_switchinggene'){
                if($_POST['3utr']=='only3utr'){
                    if($_POST['sgminpat']>0)
                        $avgpat=$_POST['sgminpat'];
                    else
                        $avgpat=0;
                    $sg_ocmd="Rscript /var/www/html/front/src/r/R_switchFU.r ogene=F avgPAT=$avgpat suffix=xx path='/var/www/html/front/searched/' intbl=SearchedPAC_$file cols='".implode(':',$_SESSION['usr_checked'] ).";".implode(':', $_SESSION['sys_checked'])."' groups=user:sys conf=/var/www/html/front/src/r/db_2.xml 2>&1";
                    //echo $sg_ocmd;
                    echo shell_exec($sg_ocmd);
                    echo '<script>window.location.href="aftertreatment_result_test.php?result=switchinggene_o&chr=1&gene=31185&strand=-1";</script>';
                }
                else if($_POST['3utr']=='none3utr'){
                    if($_POST['uttp']=='on')
                        $minpat1='T';
                    else
                        $minpat1='F';
                    $minpat2=$_POST['minpat2'];
                    $minpat3=$_POST['minpat3'];
                    $minpat4=$_POST['minpat4'];
                    $minpat5=$_POST['minpat5'];
                    $minpat6=$_POST['minpat6'];
                    $sg_ncmd="Rscript /var/www/html/front/src/r/R_switchMangone.r path='/var/www/html/front/searched/' intbl=SearchedPAC_$file switch=$minpat1:$minpat2:$minpat3:$minpat4:$minpat5:$minpat6 cond='' suffix=xx cols='".implode(':',$_SESSION['usr_checked'] ).";".implode(':', $_SESSION['sys_checked'])."' groups=user:sys conf=/var/www/html/front/src/r/db_2.xml 2>&1";
                    //echo $sg_ncmd;
                    echo shell_exec($sg_ncmd);
                    echo '<script>window.location.href="aftertreatment_result_test.php?result=switchinggene_n&chr=1&gene=31185&strand=-1";</script>';
                }
            }
    }
    ?>