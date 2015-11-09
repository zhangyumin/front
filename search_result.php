<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Search</title>
        <script src="./src/jquery-2.0.0.min.js"></script>
        <script src="./src/jquery.dataTables.min.js"type="text/javascript" ></script>
        <link href="./src/jquery.dataTables.css"type="text/css" rel="stylesheet"></link>
        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <!--[if lte IE 7]>
        <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <!--[if lt IE 9]>
        <script src="./js/html5shiv/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body onload="set();getchr()">
         
        <?php
            include"navbar.php";
        ?>
            <?php
                session_start();
                $con=  mysql_connect("localhost","root","root");
                mysql_select_db("db_server",$con);
                if($_GET['method']=='fuzzy'){
                    $go_array_key=array();
                    $key=$_POST['key'];
                    $go_qry=  mysql_query("select gene from t_arab_go where gene like '%$key%' or goid like '%$key%' or goterm like '%$key%' or genefunction like '%$key%'");
                    while($go_result=  mysql_fetch_row($go_qry)){
                        array_push($go_array_key, $go_result[0]);
                    }
                    $pac_qry= "select * from t_arab_pac where ";
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
                        $sysQry="select * from db_server.t_".$_POST['species']."_pac where 1=1";
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
                                   $sysQry="select * from db_server.t_".$_POST['species']."_pac where 1=1";
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
            function set(){
                $("#species").val("<?php echo $_POST['species'];?>");
                $("#chr").val("<?php echo $_POST['chr'];?>");
                document.getElementById("gene_id").value="<?php echo $gene_id;?>";
                document.getElementById("go_accession").value="<?php echo $go_accession;?>";
                document.getElementById("start").value="<?php echo $start;?>";
                document.getElementById("end").value="<?php echo $end;?>";
                document.getElementById("go_name").value="<?php echo $go_name;?>";
                document.getElementById("function").value="<?php echo $function;?>";
            }
             <?php
                $arr_arab=array();
                $arr_japonica=array();
                $arr_mtr=array();
                $arr_chlamy=array();
                echo "var chr=[";
                //arabidopsis
                $arab_sql=mysql_query("select distinct chr from t_arab_gff;");
                $i=0;
                while($arab_row=  mysql_fetch_row($arab_sql)){
                    array_push($arr_arab, $arab_row[0]);
                }
                echo "[\"";
                foreach ($arr_arab as $key => $value) {
                    if($key!=  count($arr_arab)-1)
                        echo $value."\",\"";
                    else
                        echo $value;
                }
                echo "\"],";
                //japonica
                $arab_sql=mysql_query("select distinct chr from t_japonica_gff;");
                $i=0;
                while($arab_row=  mysql_fetch_row($arab_sql)){
                    array_push($arr_japonica, $arab_row[0]);
                }
                echo "[\"";
                foreach ($arr_japonica as $key => $value) {
                    if($key!=  count($arr_japonica)-1)
                        echo $value."\",\"";
                    else
                        echo $value;
                }
                echo "\"],";
                //mtr
                $arab_sql=mysql_query("select distinct chr from t_mtr_gff;");
                $i=0;
                while($arab_row=  mysql_fetch_row($arab_sql)){
                    array_push($arr_mtr, $arab_row[0]);
                }
                echo "[\"";
                foreach ($arr_mtr as $key => $value) {
                    if($key!=  count($arr_mtr)-1)
                        echo $value."\",\"";
                    else
                        echo $value;
                }
                echo "\"],";
                //chlamy
                $arab_sql=mysql_query("select distinct chr from t_chlamy_gff;");
                $i=0;
                while($arab_row=  mysql_fetch_row($arab_sql)){
                    array_push($arr_chlamy, $arab_row[0]);
                }
                echo "[\"";
                foreach ($arr_chlamy as $key => $value) {
                    if($key!=  count($arr_chlamy)-1)
                        echo $value."\",\"";
                    else
                        echo $value;
                }
                echo "\"]";
                echo "];";
            ?> 
                function getchr(){
                    var sltSpecies=document.search.species;
                    var sltChr=document.search.chr;
                    var speciesChr=chr[sltSpecies.selectedIndex];
                    sltChr.length=1;
                    for(var i=0;i<speciesChr.length;i++){
                        sltChr[i+1]=new Option(speciesChr[i],speciesChr[i]);
                    }
                }
        </script>
        <div class="ym-wrapper">
            <fieldset >
                <legend>
                    <h4>
                        <font color="#224055" ><b>Search</b>:Search and view the system samples</font>
                    </h4>
                </legend>
               <div class="box info ym-form">
                   <form method="post" name="search" id="getback" action="#">
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
            <br><br>
            <div id="table"  style="overflow-x: auto;background-color: #fff;margin:auto;">
                <table id="result" class="display dataTable" cellspacing="0" role="grid" aria-describedby="example_infox" style="text-align: center;">
                <thead>
                    <tr>
                        <th>View</th>
                        <th>Gene</th>
                        <th>Chr</th>
                        <th>ftr_start</th>
                        <th>ftr_end</th>
                        <th>Strand</th>
                        <th>Ftr</th>
                        <th>Gene Type</th>
                        <!--<th>Pac</th>-->
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                                while($query_row=  mysql_fetch_row($query_result)){
                                    echo "<tr>";
                                        echo "<td><a target=\"_blank\" href=\"../jbrowse/?data=data/arabidopsis&loc=$query_row[0]:$query_row[5]\"><span title=\"View the sequence in Jbrowse\" style=\"background-color:#0066cc;color:#FFFFFF;\">View</span></a></td>";
                                        echo "<td>$query_row[8]</td>";
                                        echo "<td>$query_row[0]</td>";
                                        echo "<td>$query_row[5]</td>";
                                        echo "<td>$query_row[6]</td>";
                                        echo "<td>$query_row[1]</td>";
                                        echo "<td>$query_row[4]</td>";
                                        echo "<td>$query_row[9]</td>";
                                        if($query_row[4]=='intergenic.igt'||$query_row[4]=='intergenic.pm'){
                                            if($query_row[1]=='+')
                                                echo "<td><a target=\"_blank\" href=\"./sequence_detail.php?species=".$_POST['species']."&seq=$query_row[8]&strand=1&flag=intergenic\"><span title=\"Get more information about this sequence\" style=\"background-color:#0066cc;color:#FFFFFF;\">Detail</span></a></td>";
                                            else
                                                echo "<td><a target=\"_blank\" href=\"./sequence_detail.php?species=".$_POST['species']."&seq=$query_row[8]&strand=-1&flag=intergenic\"><span title=\"Get more information about this sequence\" style=\"background-color:#0066cc;color:#FFFFFF;\">Detail</span></a></td>";
                                        }
                                        else
                                            echo "<td><a target=\"_blank\" href=\"./sequence_detail.php?species=".$_POST['species']."&seq=$query_row[8]\"><span title=\"Get more information about this sequence\" style=\"background-color:#0066cc;color:#FFFFFF;\">Detail</span></a></td>";
                                    echo "</tr>";
                                }
                    ?>
                </tbody>
                </table>
            </div>
        </div>
            <script>
                $(document).ready(function(){
                    $('#result').dataTable({
                        "lengthMenu":[[10,25,50,-1],[10,25,50,"all"]],
                        "pagingType":"full_numbers"
                    });
                });
            </script>
        
        <div class="bottom">
        <?php
            include"footer.php";
            ?>
        </div>
    </body>
</html>