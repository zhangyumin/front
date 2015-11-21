<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Search</title>
        <script src="./src/jquery-1.10.1.min.js"></script>
        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <!--[if lte IE 7]>
        <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <!--[if lt IE 9]>
        <script src="./js/html5shiv/html5shiv.js"></script>
        <![endif]-->
    </head>
    <style type="text/css">
        .STYLE1 {font-size: 12px}
        a:link {
        color: #FFFFFF;
        text-decoration: none;
        }
        a:visited {
        text-decoration: none;
        }
        a:hover {
        text-decoration: none;
        }
        a:active {
        text-decoration: none;
        }
    </style>
    <body>
         
        <?php
            include"navbar.php";
        ?>
            <?php
                session_start();
                $con=  mysql_connect("localhost","root","root");
                mysql_select_db("db_server",$con);
                if(!isset($_SESSION['search'])){
                    $_SESSION['search']=$_POST['species'].date("Y").date("m").date("d").date("h").date("i").date("s");
                }
                else{
                    mysql_query("drop table db_user.Search_".$_SESSION['search']."");
                    $_SESSION['search']=$_POST['species'].substr($_SESSION['search'], strpos($_SESSION['search'], "201"));
                }
//                echo $_SESSION['search'];
                //模糊搜索
                if($_GET['method']=='fuzzy'){
                    $go_array_key=array();
                    $key=$_POST['key'];
                    if($_GET['keyword']!=NULL)
                        $key=$_GET['keyword'];
                    $go_qry=  mysql_query("select gene from t_arab_go where gene like '%$key%' or goid like '%$key%' or goterm like '%$key%' or genefunction like '%$key%'");
                    while($go_result=  mysql_fetch_row($go_qry)){
                        array_push($go_array_key, $go_result[0]);
                    }
                    $pac_qry= "create table db_user.Search_".$_SESSION['search']." select * from t_arab_pac where ";
                    $pac_qry.="gene in ('";
                    $pac_qry.=implode("','", $go_array_key);
                    $pac_qry.="') or chr like '%$key%'; ";
//                    echo $pac_qry;
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
                    $gene_array=array();
                    $go_array=array();
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
                        if($_POST['gene_id']!=NULL){
                            $gene_array=  explode(",", $gene_id);
                            $gene_array=  array_unique($gene_array);
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
                             if($_POST['function']!=NULL)
                             {
                                 $go_sysQry.=" and genefunction like '%$function%'";
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
                                       $sysQry.=";";
                               $query_result=mysql_query($sysQry);
                             }
                }
        //            echo $_SESSION['file'];
        //            echo $go_sysQry;
        //            echo $go_insert;
        //            echo $go_array;
//            //        var_dump($sysQry_result);
//                         echo '<script>window.location.href="show_sequence_searched.php?chr=1&gene=31185&strand=-1";</script>';
//                            echo $go_sysQry;
//                             echo $sysQry;

            ?>
        <script type="text/javascript">
            function locking(){   
               document.all.ly.style.display="block";   
               document.all.ly.style.width=document.body.clientWidth;   
               document.all.ly.style.height=document.body.offsetHeight;   
               document.all.Layer2.style.display='block';  
               }   
            function Lock_CheckForm(theForm){   
                document.all.ly.style.display='none';document.all.Layer2.style.display='none';
                return   false;   
             }   
//            function set(){
//                $("#species").val("<?php echo $_POST['species'];?>");
//                $("#chr").val("<?php echo $_POST['chr'];?>");
//                document.getElementById("gene_id").value="<?php echo $gene_id;?>";
//                document.getElementById("go_accession").value="<?php echo $go_accession;?>";
//                document.getElementById("start").value="<?php echo $start;?>";
//                document.getElementById("end").value="<?php echo $end;?>";
//                document.getElementById("go_name").value="<?php echo $go_name;?>";
//                document.getElementById("function").value="<?php echo $function;?>";
//            }
            function ChgMtd(){
                document.getElementById("pacs").style.display='none';
                document.getElementById("pacs-region").style.display='none';
                document.getElementById("seq").style.display='none';
                if(document.getElementById("method").value=='choose'){
                    document.getElementById("sub").disabled=true;
                    document.getElementById("can").disabled=true;
                }
                else{
                    document.getElementById("sub").disabled=false;
                    document.getElementById("can").disabled=false;
                    document.getElementById(document.getElementById("method").value).style.display='block';
//                console.log(document.getElementById("method").value);
                }
            }
             <?php
//                $arr_arab=array();
//                $arr_japonica=array();
//                $arr_mtr=array();
//                $arr_chlamy=array();
//                echo "var chr=[";
//                //arabidopsis
//                $arab_sql=mysql_query("select distinct chr from t_arab_gff;");
//                $i=0;
//                while($arab_row=  mysql_fetch_row($arab_sql)){
//                    array_push($arr_arab, $arab_row[0]);
//                }
//                echo "[\"";
//                foreach ($arr_arab as $key => $value) {
//                    if($key!=  count($arr_arab)-1)
//                        echo $value."\",\"";
//                    else
//                        echo $value;
//                }
//                echo "\"],";
//                //japonica
//                $arab_sql=mysql_query("select distinct chr from t_japonica_gff;");
//                $i=0;
//                while($arab_row=  mysql_fetch_row($arab_sql)){
//                    array_push($arr_japonica, $arab_row[0]);
//                }
//                echo "[\"";
//                foreach ($arr_japonica as $key => $value) {
//                    if($key!=  count($arr_japonica)-1)
//                        echo $value."\",\"";
//                    else
//                        echo $value;
//                }
//                echo "\"],";
//                //mtr
//                $arab_sql=mysql_query("select distinct chr from t_mtr_gff;");
//                $i=0;
//                while($arab_row=  mysql_fetch_row($arab_sql)){
//                    array_push($arr_mtr, $arab_row[0]);
//                }
//                echo "[\"";
//                foreach ($arr_mtr as $key => $value) {
//                    if($key!=  count($arr_mtr)-1)
//                        echo $value."\",\"";
//                    else
//                        echo $value;
//                }
//                echo "\"],";
//                //chlamy
//                $arab_sql=mysql_query("select distinct chr from t_chlamy_gff;");
//                $i=0;
//                while($arab_row=  mysql_fetch_row($arab_sql)){
//                    array_push($arr_chlamy, $arab_row[0]);
//                }
//                echo "[\"";
//                foreach ($arr_chlamy as $key => $value) {
//                    if($key!=  count($arr_chlamy)-1)
//                        echo $value."\",\"";
//                    else
//                        echo $value;
//                }
//                echo "\"]";
//                echo "];";
          ?>//// 
//                function getchr(){
//                    var sltSpecies=document.search.species;
//                    var sltChr=document.search.chr;
//                    var speciesChr=chr[sltSpecies.selectedIndex];
//                    sltChr.length=1;
//                    for(var i=0;i<speciesChr.length;i++){
//                        sltChr[i+1]=new Option(speciesChr[i],speciesChr[i]);
//                    }
//                }
        </script>
        <div id="ly" style="position: absolute; top: 0px; opacity:0.4; background-color: #777;z-index: 2; left: 0px; display: none;">
        </div>
        <!--          浮层框架开始         -->
        <div id="Layer2" align="center" style="border: 1px solid;position: absolute; z-index: 3; left: 560; top: 50%;background-color: #fff; display: none;" >
            <table width="540" height="300" border="0" cellpadding="0" cellspacing="0" style="border: 0    solid    #e7e3e7;border-collapse: collapse ;" >
                <tr>
                    <td style="background-color: #73A2d6; color: #fff; padding-left: 4px; padding-top: 2px;font-weight: bold; font-size: 12px;" height="10" valign="middle">
                         <div align="right">
                             <a href=JavaScript:; class="STYLE1" onclick="Lock_CheckForm(this);">[Close]
                             </a> &nbsp;&nbsp;&nbsp;&nbsp;
                         </div>
                    </td>
                </tr>
                <tr>
                    <td height="130" align="center">
                        <form name="pac_export" method="post" action="export_seq.php?source=Search" target="_blank">
                            method<select id="method" name="method" onchange="ChgMtd()">
                                <option value="choose">Please choose</option>
                                <option value="pacs">export sequences of PACs</option>
                                <option value="pacs-region">export sequences of regions of  PACs</option>
                                <option value="seq">export gene sequences</option>
                            </select><br>
                            <div id="pacs" style="display:none">
                                upstream (nt) <input type="text" value="200" name='upstream'></input><br>
                                downstream (nt) <input type="text" value="200" name='downstream'></input><br>
                                PAC in region <select name='pac_region'>
                                    <option value="all">all</option>
                                    <option value="genomic-region">genomic region</option>
                                    <option value="3TUR">3'UTR</option>
                                    <option value="5UTR">5‘UTR</option>
                                    <option value="CDS">CDS</option>
                                    <option value="intron">intron</option>
                                    <option value="intergenic.igt">intergenic</option>
                                    <option value="intergenic.pm">promoter</option>
                                </select>
                            </div>
                            <div id="pacs-region" style="display:none">
                                region of PACs <select name='pacs_region'>
                                    <option value="all">all</option>
                                    <option value="genomic-region">genomic region</option>
                                    <option value="3TUR">3'UTR</option>
                                    <option value="5UTR">5‘UTR</option>
                                    <option value="CDS">CDS</option>
                                    <option value="intron">intron</option>
                                    <option value="intergenic.igt">intergenic</option>
                                    <option value="intergenic.pm">promoter</option>
                                </select>
                            </div>
                            <div id="seq" style="display:none">
                                annotation version <select name='anno_version'>
                                    <option value="raw-annotation">raw annotation</option>
                                    <option value="3utr-extended-annotation">3' UTR extended annotation</option>
                                </select><br>
                                export <select name='export'>
                                    <option value="whole-gene">whole gene</option>
                                    <option value="joined-cds">joined CDS</option>
                                    <option value="3utr-only">3' UTR only</option>
                                </select>
                            </div>
                            <button id="sub" type="submit" disabled="true">Submit</button>
                            <button id="can" type="reset" disabled="true">Reset</button>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
        <div class="ym-wrapper">
            <script>
                <?php
                    echo "var species='".$_POST['species']."'";
                ?>
            </script>
<!--            <fieldset >
                <legend>
                    <h4>
                        <font color="#224055" ><b>Search</b>:Search and view the system samples</font>
                    </h4>
                </legend>
               <div class="box info ym-form">
                   <form method="post" name="search" id="getback" action="search_result.php">
                    <div class="ym-grid ym-fbox">
                        <div class="ym-g33 ym-gl">
                           <label for="species" style="margin-right:2%;">Species:</label>
                           <select id="species" name="species" style="width:80%" onclick="getchr()">
                                <option value="arab" selected="selected">Arabidopsis thaliana</option>
                                 <option value="japonica">Japonica rice</option>
                                <option value="mtr">Medicago truncatula</option>
                                <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
                            </select>
                        </div>
                        <div class="ym-g33 ym-gl">
                            <label for="chr" style="margin: 0 1%">in</label>
                            <select id="chr" name="chr" style="width:80%">
                                <option value="all">All</option>
                            </select>
                        </div>
                        <div class="ym-g33 ym-gl">
                            <label for="start" style="margin:0 1%;"> from</label>
                            <input type="text" name="start" id="start" style="width:40%">
                            <label for="end" style="margin:0 1%;"> to</label>
                            <input type="text" name="end" id="end" style="width:40%"><br>
                        </div>
                    </div>
                    <div class="ym-grid ym-fbox">
                            <br><label for="gene_id">Gene ID:(use ',' to split different gene id)</label><br><textarea style="width:100%" name="gene_id" id="gene_id"></textarea><br>
                            <br><label for="go_accession">Go term accession:(use ',' to split different gene id)</label><br><textarea style="width:100%" name='go_accession' id="go_accession"></textarea><br>
                            <br><label for="go_name" >Go term name:</label><input type='text' name='go_name' id="go_name" class="ym-gr" style="width:89%;"/><br>
                            <br><label for="function" >Function:</label><input type='text' name='function' id="function" class="ym-gr" style="width:89%;"/><br>
                    </div>
                    <div class="ym-grid ym-fbox">
                                <button type="submit">submit</button>
                                <button type="reset">reset</button>
                                <input type="button" onclick="search_example()" value="Example">
                    </div>
                                <script type="text/javascript">
                                    function search_example(){
                                        if(document.getElementById("species").value=='arab'){
                                            document.getElementById("go_accession").value="GO:0006888";
                                            document.getElementById("start").value="10000";
                                            document.getElementById("end").value="1000000";
                                        }
                                        else if(document.getElementById("species").value=='japonica'){
                                            document.getElementById("go_accession").value="GO:0009987";
                                            document.getElementById("start").value="10000";
                                            document.getElementById("end").value="50000";
                                        }
                                        else if(document.getElementById("species").value=='mtr'){
                                            document.getElementById("go_accession").value="GO:0003899";
                                            document.getElementById("start").value="10000";
                                            document.getElementById("end").value="1000000";
                                        }
                                        else if(document.getElementById("species").value=='chlamy'){
                                            document.getElementById("go_accession").value="GO:0008131";
                                            document.getElementById("start").value="10000";
                                            document.getElementById("end").value="100000";
                                        }
                                    }
                                </script>
                            
                        </form>
               </div>
            </fieldset>
            <br><br>-->
            <div class="filter" id="filter">
                    <button onclick="locking()">export sequences</button>
                    <form>
                        <input type="text" name="search" id="search" />
                        <button type="submit" id="search_button">search</button>
                        <button type="reset" id="reset_button">reset</button>
                    </form>
                    <div style="clear:both;"></div>
            </div>
            <div id="jtable" style="clear: both;width: 100%;"></div>
            <link href="src/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
            <link href="src/jtable.css" rel="stylesheet" type="text/css" />
            <script src="src/jquery-ui-1.8.16.custom.min.js" type="text/javascript" ></script>
            <script src="src/jquery.jtable.js" type="text/javascript" ></script>
             <script type="text/javascript">
                    $(document).ready(function (){
                        $('#jtable').jtable({
                            title:'PAC',
                            paging:true,
                            pageSize:5,
                            sorting:true,
                            defaultSorting:'gene ASC',
                            actions:{
                                listAction:'Search_PAClist.php'
                            },
                            fields:{
                                view:{
                                    title:'View',
                                    display: function (data) {
                                        return "<a target=\"_blank\" href=\"../jbrowse/?data=data/arabidopsis&amp;loc="+data.record.chr+":"+data.record.coord+"\">"+"<span title=\"View the sequence in Jbrowse\" style=\"background-color:#0066cc;color:#FFFFFF;\">View</span></a>";
                                    }
                                },
                                gene:{
                                    key:true,
                                    edit:false,
                                    create:false,
                                    columnResizable:false,
                                    title:'gene',
                                    edit:false
                                },
                                chr:{
                                    title:'chr',
                                    edit:false
                                },
                                ftr_start:{
                                    title:'ftr_start',
                                    edit:false
                                },
                                ftr_end:{
                                    title:'ftr_end',
                                    edit:false
                                },
                                strand:{
                                    title:'strand',
                                    edit:false
                                },
                                ftr:{
                                    title:'ftr',
                                    edit:false
                                },
                                gene_type:{
                                    title:'gene_type',
                                    edit:false
                                },
                                detail:{
                                    title:'Detail',
                                    display: function (data) {
                                        if(data.record.ftr=='intergenic.igt' || data.record.ftr=='intergenic.pm'){
                                            if(data.record.strand=='-'){
                                                return "<td><a target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"&strand=-1&flag=intergenic&coord="+data.record.coord+"\"><span title=\"Get more information about this sequence\" style=\"background-color:#0066cc;color:#FFFFFF;\">Detail</span></a></td>";
                                            }
                                            else
                                                return "<td><a target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"&strand=1&flag=intergenic&coord="+data.record.coord+"\"><span title=\"Get more information about this sequence\" style=\"background-color:#0066cc;color:#FFFFFF;\">Detail</span></a></td>";
                                        }
                                        else{
                                            return "<td><a target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"\"><span title=\"Get more information about this sequence\" style=\"background-color:#0066cc;color:#FFFFFF;\">Detail</span></a></td>";
                                        }
                                    }
                                }
                            }
                        });

                        $('#jtable').jtable('load');
                        $('#filter').appendTo(".jtable-title").addClass('filter_class');
                        $('#search_button').click(function (e){
                            e.preventDefault();
                                    $('#jtable').jtable('load',{
                                        search: $('#search').val()
                                    });
                                });
                        $('#reset_button').click(function(e){
                            e.preventDefault();
                                    $('#jtable').jtable('load');
                                });
                    });
                </script>
        </div>
        <div class="bottom">
        <?php
            include"footer.php";
            ?>
        </div>
    </body>
</html>