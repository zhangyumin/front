<?php
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_server",$con);
    session_start();
    $chr=$_POST['chr'];
    $start=$_POST['start'];
    $end=$_POST['end'];
    $gene_id=$_POST['gene_id'];
    $go_accession=$_POST['go_accession'];
    $go_name=$_POST['go_name'];
    $function=$_POST['function'];
    $gene_array=array();
    $go_array=array();
    //如果没有进行分析,则只对系统数据进行搜索过滤
    if(!isset($_SESSION['file'])){
        $_SESSION['file']=$_POST['species'].date("Y").date("m").date("d").date("h").date("i").date("s");
        //若go搜索无输入
        if($go_accession==NULL&&$go_name==NULL&&$function==NULL){
            $sysQry="create table db_user.SearchedPAC_".$_SESSION['file']." as(select * from db_server.t_".$_SESSION['species'].".pac where 1=1";
            if($_POST['chr']!='all'){
                $sysQry.=" and chr=".$_POST['chr']."";
            }
            if($_POST['start']!=NULL){
                $sysQry.=" and coord>=".$_POST['start']."";
            }
            if($_POST['end']!=NULL){
                $sysQry.=" and coord<=".$_POST['end']."";
            }
            if($_POST['gene_id']!=NULL){
                $gene_array=  explode(",", $gene_id);
                $gene_array=  array_unique($gene_array);
                $sysQry.=" and gene in ('";
                $sysQry.=implode("','", $gene_array);
                $sysQry.="')";
            }
            $sysQry.=");";
            $query_result=mysql_query($sysQry);
        }
        else
        {
            $go_sysQry="select gene from db_server.t_".$_SESSION['species'].".go where 1=1";
            if($_POST['go_name']!=NULL)
            {
                $go_sysQry.=" and goterm like '%$go_name%'";

                 }
                 if($_POST['function']!=NULL)
                 {
                     $go_sysQry.=" and genefunction like '%$function%'";
                 } 
                 if($_POST['go_accession']!=NULL)
                 {
                     $go_id=explode(',',$go_accession);
                     $go_id=  array_unique($go_id);
                     $go_sysQry.=" and";
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
                   $sysQry="create table db_user.SearchedPAC_".$_SESSION['file']." as(select * from db_server.t_".$_SESSION['species']."._pac where 1=1";
                   if($_POST['chr']!='all'){
                       $sysQry.=" and chr=".$_POST['chr']."";
                   }
                   if($_POST['start']!=NULL){
                       $sysQry.=" and coord>=".$_POST['start']."";
                   }
                   if($_POST['end']!=NULL){
                       $sysQry.=" and coord<=".$_POST['end']."";
                   }
                   if($_POST['gene_id']!=NULL){
                        $gene_array=  explode(",", $gene_id);
                        $gene_array=  array_unique($gene_array);
                        $sysQry.=" and gene in ('";
                        $sysQry.=implode("','", $gene_array);
                        $sysQry.="')";
                   }
                    if(count($go_array)>0){
                       $sysQry.=" and gene in ('";
                       $sysQry.=implode("','", $go_array);
                       $sysQry.="')";
                   }
                       $sysQry.=");";
               $query_result=mysql_query($sysQry);
             }
    }
    //如果有$_session['file'],则1.之前搜索过,2.上传处理过数据.
    else{
        //测试是否存在用户数据,若存在,证明为情况2.
        $user_data_test=  mysql_query("select * from db_user.PAC_".$_SESSION['file'].";");
        if(mysql_num_rows($user_data_test)>0){
            //删除已经搜索过的数据
            mysql_query("drop table db_user.sysQryPAC_".$_SESSION['file'].";");
            mysql_query("drop table db_user.usrQryPAC_".$_SESSION['file'].";");
            //系统及用户数据过滤筛选
           if($go_accession==NULL&&$go_name==NULL&&$function==NULL){
            $sysQry="create table db_user.sysQryPAC_".$_SESSION['file']." as(select * from db_server.t_".$_SESSION['species']."_pac where 1=1";
            $usrQry="create table db_user.usrQryPAC_".$_SESSION['file']." as(select * from db_user.PAC_".$_SESSION['file']." where 1=1";
            if($_POST['chr']!='all'){
                $sysQry.=" and chr=".$_POST['chr']."";
                $usrQry.=" and chr=".$_POST['chr']."";
            }
            if($_POST['start']!=NULL){
                $sysQry.=" and coord>=".$_POST['start']."";
                $usrQry.=" and coord>=".$_POST['start']."";
            }
            if($_POST['end']!=NULL){
                $sysQry.=" and coord<=".$_POST['end']."";
                $usrQry.=" and coord<=".$_POST['end']."";
            }
            if($_POST['gene_id']!=NULL){
                $gene_array=  explode(",", $gene_id);
                $gene_array=  array_unique($gene_array);
                $sysQry.=" and gene in ('";
                $sysQry.=implode("','", $gene_array);
                $sysQry.="')";
                $usrQry.=" and gene in ('";
                $usrQry.=implode("','", $gene_array);
                $usrQry.="')";
            }
            $sysQry.=");";
            $usrQry.=");";
            $sys_query_result=mysql_query($sysQry);
            $usr_query_result=mysql_query($usrQry);
        }
        else
        {
            $go_sysQry="select gene from db_server.t_".$_SESSION['species']."_go where 1=1";
            if($_POST['go_name']!=NULL)
            {
                $go_sysQry.=" and goterm like '%$go_name%'";

                 }
                 if($_POST['function']!=NULL)
                 {
                     $go_sysQry.=" and genefunction like '%$function%'";
                 } 
                 if($_POST['go_accession']!=NULL)
                 {
                     $go_id=explode(',',$go_accession);
                     $go_id=  array_unique($go_id);
                     $go_sysQry.=" and";
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
                   $sysQry="create table db_user.sysQryPAC_".$_SESSION['file']." as(select * from db_server.t_".$_SESSION['species']."_pac where 1=1";
                   $usrQry="create table db_user.usrQryPAC_".$_SESSION['file']." as(select * from db_user.PAC_".$_SESSION['file']." where 1=1";
                   if($_POST['chr']!='all'){
                       $sysQry.=" and chr=".$_POST['chr']."";
                       $usrQry.=" and chr=".$_POST['chr']."";
                   }
                   if($_POST['start']!=NULL){
                       $sysQry.=" and coord>=".$_POST['start']."";
                       $usrQry.=" and coord>=".$_POST['start']."";
                   }
                   if($_POST['end']!=NULL){
                       $sysQry.=" and coord<=".$_POST['end']."";
                       $usrQry.=" and coord<=".$_POST['end']."";
                   }
                   if($_POST['gene_id']!=NULL){
                        $gene_array=  explode(",", $gene_id);
                        $gene_array=  array_unique($gene_array);
                        $sysQry.=" and gene in ('";
                        $sysQry.=implode("','", $gene_array);
                        $sysQry.="')";
                        $usrQry.=" and gene in ('";
                        $usrQry.=implode("','", $gene_array);
                        $usrQry.="')";
                   }
                    if(count($go_array)>0){
                       $sysQry.=" and gene in ('";
                       $sysQry.=implode("','", $go_array);
                       $sysQry.="')";
                       $usrQry.=" and gene in ('";
                       $usrQry.=implode("','", $go_array);
                       $usrQry.="')";
                   }
                       $sysQry.=");";
                       $usrQry.=");";
               $sys_query_result=mysql_query($sysQry);
               $usr_query_result=mysql_query($usrQry);
             }
             $merge="./src/perl/PAT_mergePAC.pl -smptbls 'usrQryPAC_".$_SESSION['file'].";sysQryPAC_".$_SESSION['file']."' -reftbl usrQryPAC_".$_SESSION['file']." -smpcols '".implode(':',$_SESSION['file_real'] ).";".implode(':', $_SESSION['sys_real'])."' -smplbls '".implode(':',$_SESSION['file_real'] ).";".implode(':', $_SESSION['sys_real'])."' -otbl SearchedPAC_".$_SESSION['file']." -udist 24 -conf ./src/r/db_2.xml";
//             echo $merge;
             shell_exec($merge);
        }
        else{
            //删除之前搜索的系统数据
            mysql_query("drop table db_user.SearchedPAC_".$_SESSION['file'].";");
            if($go_accession==NULL&&$go_name==NULL&&$function==NULL){
                $sysQry="create table db_user.SearchedPAC_".$_SESSION['file']." as(select * from db_server.t_".$_SESSION['species']."_pac where 1=1";
                if($_POST['chr']!='all'){
                    $sysQry.=" and chr=".$_POST['chr']."";
                }
                if($_POST['start']!=NULL){
                    $sysQry.=" and coord>=".$_POST['start']."";
                }
                if($_POST['end']!=NULL){
                    $sysQry.=" and coord<=".$_POST['end']."";
                }
                if($_POST['gene_id']!=NULL){
                    $gene_array=  explode(",", $gene_id);
                    $gene_array=  array_unique($gene_array);
                    $sysQry.=" and gene in ('";
                    $sysQry.=implode("','", $gene_array);
                    $sysQry.="')";
                }
                $sysQry.=");";
                $query_result=mysql_query($sysQry);
            }
            else
            {
                $go_sysQry="select gene from db_server.t_".$_SESSION['species']."_go where 1=1";
                if($_POST['go_name']!=NULL)
                {
                    $go_sysQry.=" and goterm like '%$go_name%'";
                }
                if($_POST['function']!=NULL)
                {
                    $go_sysQry.=" and genefunction like '%$function%'";
                } 
                if($_POST['go_accession']!=NULL)
                {
                    $go_id=explode(',',$go_accession);
                    $go_id=  array_unique($go_id);
                    $go_sysQry.=" and";
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
                  $sysQry="create table db_user.SearchedPAC_".$_SESSION['file']." as(select * from db_server.t_".$_SESSION['species']."_pac where 1=1";
                  if($_POST['chr']!='all'){
                      $sysQry.=" and chr=".$_POST['chr']."";
                  }
                  if($_POST['start']!=NULL){
                      $sysQry.=" and coord>=".$_POST['start']."";
                  }
                  if($_POST['end']!=NULL){
                      $sysQry.=" and coord<=".$_POST['end']."";
                  }
                  if($_POST['gene_id']!=NULL){
                       $gene_array=  explode(",", $gene_id);
                       $gene_array=  array_unique($gene_array);
                       $sysQry.=" and gene in ('";
                       $sysQry.=implode("','", $gene_array);
                       $sysQry.="')";
                  }
                   if(count($go_array)>0){
                      $sysQry.=" and gene in ('";
                      $sysQry.=implode("','", $go_array);
                      $sysQry.="')";
                  }
                      $sysQry.=");";
              $query_result=mysql_query($sysQry);
            }
        }
    }
    if($_GET['method']=='degene'){
//            echo "<script>alert('in it')</script>";
        if($_POST['degene_nor_method']=='none')
            $donorm=NULL;
        else
            $donorm=$_POST['degene_nor_method'];
//        echo $_POST['nor_method'];
        $minpat=$_POST['degene_min_pat'];
        $method=$_POST['degene_method'];
        $sig=$_POST['degene_sig'];
        if($_POST['degene_multi_test']=='Bonferroni')
            $Adj=1;
        else
            $Adj=0;
        $_SESSION['sample']=  array_merge($_POST['sample1'],$_POST['sample2']);
        $degene_cmd="Rscript /var/www/front/src/r/R_DEgene.r ofile='degene.".$_SESSION['file']."' method=$method adj=$Adj sig=$sig minpat=$minpat donorm=$donorm path='/var/www/front/searched/' intbl=SearchedPAC_".$_SESSION['file']." cols='".implode(':',$_POST['sample1'] ).";".implode(':', $_POST['sample2'])."' groups=column1:column2 conf=/var/www/front/src/r/db_2.xml >/dev/null &";
        //$degene_cmd="Rscript /var/www/html/front/src/r/R_pairDEgene.r minrep=1 minpat=5 donorm=0 path='/home/zym/data/' intbl=PAC_sys_arab10 cols='oxt6_leaf_1:oxt6_leaf_2;wt_leaf_1:wt_leaf_2' groups=sys:user conf=/var/www/html/front/db.xml 2>&1";
//        echo $degene_cmd;
        if(count($_POST['sample1'])>=1&&count($_POST['sampe2']>=1))
        {
           shell_exec($degene_cmd);
           print_r(json_encode($_POST));
//            echo "<br><br>$degene_cmd<br><br>";
//            echo '<script>window.location.href="aftertreatment_result_test.php?result=degene";</script>';
        }
        else
            echo "<script>alert('choose two sample');history.go(-1);</script>";
    }
    else if($_GET['method']=='depac'){
        if($_POST['depac_normethod']=='none')
            $donorm=NULL;
        else
            $donorm=$_POST['depac_normethod'];
        $method=$_POST['depac_method'];
        $minpat=$_POST['depac_min_pat'];
        $sig=$_POST['depac_sig'];
        if($_POST['depac_multi_test']=='Bonferroni')
            $Adj=1;
        else
            $Adj=0;
        $_SESSION['sample']=  array_merge($_POST['sample1'],$_POST['sample2']);
        $depac_cmd="Rscript /var/www/front/src/r/R_DEPAC.r ofile='depac.".$_SESSION['file']."' method=$method adj=$Adj sig=$sig minpat=$minpat donorm=$donorm path='/var/www/front/searched/' intbl=SearchedPAC_".$_SESSION['file']." cols='".implode(':',$_POST['sample1']).";".implode(':', $_POST['sample2'])."' groups=column1:column2 conf=/var/www/front/src/r/db_2.xml >/dev/null &";
//        echo "<br><br>$depac_cmd<br><br>";
        shell_exec($depac_cmd);
//        echo '<script>window.location.href="aftertreatment_result_test.php?result=depac";</script>';
        print_r(json_encode($_POST));
    }
    else if($_GET['method']=='only3utr'){
            if($_POST['sgminpat']>0)
                $avgpat=$_POST['sgminpat'];
            else
                $avgpat=0;
            $sig=$_POST['only3utr_sig'];
            $_SESSION['sample']=  array_merge($_POST['sample1'],$_POST['sample2']);
            $sg_ocmd="Rscript /var/www/front/src/r/R_switch3UTR.r ofile='only3utr.".$_SESSION['file']."' adj=0 sig=$sig avgPAT=$avgpat path='/var/www/front/searched/' intbl=SearchedPAC_".$_SESSION['file']." cols='".implode(':',$_POST['sample1']).";".implode(':', $_POST['sample2'])."' groups=column1:column2 conf=/var/www/front/src/r/db_2.xml >/dev/null &";
//            echo $sg_ocmd;
            shell_exec($sg_ocmd);
            print_r(json_encode($_POST));
//            echo '<script>window.location.href="aftertreatment_result_test.php?result=switchinggene_o";</script>';
    }
    else if($_GET['method']=='none3utr'){
        if($_POST['uttp']=='on')
            $minpat1='T';
        else
            $minpat1='F';
        $minpat2=$_POST['minpat2'];
        $minpat3=$_POST['minpat3'];
        $minpat4=$_POST['minpat4'];
        $minpat5=$_POST['minpat5'];
        $minpat6=$_POST['minpat6'];
        $_SESSION['sample']=  array_merge($_POST['sample1'],$_POST['sample2']);
        $sg_ncmd="Rscript /var/www/front/src/r/R_switchNon3UTR.r ofile='none3utr.".$_SESSION['file']."' path='/var/www/front/searched/' intbl=SearchedPAC_".$_SESSION['file']." switch=$minpat1:$minpat2:$minpat3:$minpat4:$minpat5:$minpat6 cond='' cols='".implode(':',$_POST['sample1']).";".implode(':', $_POST['sample2'])."' groups=column1:column2 conf=/var/www/front/src/r/db_2.xml >/dev/null &";
        //echo $sg_ncmd;
        shell_exec($sg_ncmd);
        print_r(json_encode($_POST));
//        echo '<script>window.location.href="aftertreatment_result_test.php?result=switchinggene_n";</script>';
    }