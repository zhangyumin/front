<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Search</title>
        <script src="./src/jquery-2.0.0.min.js"></script>
        <script src="./src/jquery.dataTables.min.js"type="text/javascript" ></script>
        <link href="./src/jquery.dataTables.css"type="text/css" rel="stylesheet"></link>
        <style>
             fieldset{
                border-color: #5499c9 !important;
                border-style: solid !important;
                border-width: 2px !important;
                padding: 5px 10px !important;
            }
        </style>
    </head>
    <body onload="set()">
         
        <?php
            include"navbar.php";
        ?>
            <?php
                session_start();
                $con=  mysql_connect("localhost","root","root");
                mysql_select_db("db_bio",$con);
               if($_POST['species']=='arab'){
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
                        $sysQry="select * from db_bio.gff_arab10_all where 1=1";
                        if($_POST['chr']!='all'){
                            $sysQry.=" and chr=".$_POST['chr']."";
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
                        $go_sysQry="select gene from db_bio.go_arab10 where 1=1";
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
                                   $sysQry="select * from db_bio.gff_arab10_all where 1=1";
                                   if($_POST['chr']!='all'){
                                       $sysQry.=" and chr=".$_POST['chr']."";
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
            //            echo $_SESSION['file'];
            //            echo $go_sysQry;
            //            echo $go_insert;
            //            echo $go_array;
//            //        var_dump($sysQry_result);
//                         echo '<script>window.location.href="show_sequence_searched.php?chr=1&gene=31185&strand=-1";</script>';
//                            echo $go_sysQry;
//                             echo $sysQry;
                }
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
        </script>
        <fieldset style="margin: 10px auto 0 auto ;width: 95%;">
                    <legend>
                        <span class="h3_italic" >
                            <font color="#224055" ><b>Search</b>:Search and view the system samples</font>
                        </span>
                    </legend>
           <div style="width:60%;margin:0 auto;">
               <form method="post" id="getback" action="#">
                   <label for="species" style="margin-right:2%;">Species:</label>
                   <select id="species" name="species" style="width:25%">
                        <option value="arab">Arabidopsis thaliana</option>
                         <option value="rice">Oryza sativa (Rice)</option>
                        <option value="mtr">Medicago truncatula</option>
                        <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
                    </select>
                   <label for="chr" style="margin: 0 1%">in</label>
                        <select id="chr" name="chr" style="width:8%">
                            <option value="all">All</option>
                            <option value="1">Chr1</option>
                            <option value="2">Chr2</option>
                            <option value="3">Chr3</option>
                            <option value="4">Chr4</option>
                            <option value="5">Chr5</option>
                            <option value="chloroplast">chloroplast</option>
                            <option value="mitochondria">mitochondria</option>
                        </select>
                   <label for="start" style="margin:0 1%;"> from</label>
                        <input type="text" name="start" id="start" style="width:14%">
                   <label for="end" style="margin:0 1%;"> to</label>
                        <input type="text" name="end" id="end" style="width:14%"><br>
<!--                        &nbsp;for
                        <input type="text" name="keyword" style="width:20%;">-->
                        <br><label for="gene_id">Gene ID:(use ',' to split different gene id)</label><br><textarea style="width:100%" name="gene_id" id="gene_id"></textarea><br>
                        <br><label for="go_accession">Go term accession:(use ',' to split different gene id)</label><br><textarea style="width:100%" name='go_accession' id="go_accession"></textarea><br>
                        <br><label for="go_name" style="padding-right:2%;">Go term name:</label><input type='text' name='go_name' id="go_name" style="width: 40%"/><br>
                        <br><label for="function" style="padding-right:6.7%;">Function:</label><input type='text' name='function' id="function" style="width:40%;"/><br>
                        <div style="text-align:center;">
                            <button type="submit">submit</button>
                            <button type="reset">reset</button>
                            <button>Example</button>
                        </div>
                    </form>
           </div>
<!--           <div class="tips" style="font-size: 10px;">
               Dataset description: detailed description on the searching dataset can be referred by unfolding more description in the dataset table.
               <br>
               Tips for searching: search using a ID, gene name, symbols or description variable in publications and databases.
     
           </div>-->
        </fieldset>
        <br><br>
        <div id="table"  style="width: 90%;overflow-x: auto;background-color: #fff;margin:auto;">
            <table id="result" class="display dataTable" cellspacing="0" role="grid" aria-describedby="example_infox" style="text-align: center;">
            <thead>
                <tr>
                    <th>View</th>
                    <th>Gene</th>
                    <th>Chr</th>
                    <th>Strand</th>
                    <th>Gene Type</th>
                    <th>ftr_start</th>
                    <th>ftr_end</th>
                    <th>pac</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php
                            while($query_row=  mysql_fetch_row($query_result)){
                                echo "<tr>";
                                    echo "<td><a target=\"_blank\" href=\"../jbrowse/?data=data/arabidopsis&loc=$query_row[1]:$query_row[4]\"><span title=\"View the sequence in Jbrowse\" style=\"background-color:#0066cc;color:#FFFFFF;\">View</span></a></td>";
                                    echo "<td>$query_row[0]</td>";
                                    echo "<td>$query_row[1]</td>";
                                    echo "<td>$query_row[2]</td>";
                                    echo "<td>$query_row[3]</td>";
                                    echo "<td>$query_row[4]</td>";
                                    echo "<td>$query_row[5]</td>";
                                    $coord=($query_row[4]+$query_row[5])/2;
                                    if($query_row[2]=='+'){
                                        echo "<td><a target=\"_blank\" href=\"./show_pacviewer.php?species=".$_POST['species']."&gene=$coord&chr=$query_row[1]&strand=1\"><span title=\"Get pac information about this sequence\" style=\"background-color:#0066cc;color:#FFFFFF;\">PAC</span></a></td>";
                                        echo "<td><a target=\"_blank\" href=\"./sequence_detail.php?species=".$_POST['species']."&seq=$query_row[0]&chr=$query_row[1]&ftr_start=$query_row[4]&ftr_end=$query_row[5]&strand=1\"><span title=\"Get more information about this sequence\" style=\"background-color:#0066cc;color:#FFFFFF;\">Detail</span></a></td>";
                                    }
                                    if($query_row[2]=='-'){
                                        echo "<td><a target=\"_blank\" href=\"./show_pacviewer.php?species=".$_POST['species']."&gene=$coord&chr=$query_row[1]&strand=-1\"><span title=\"Get pac information about this sequence\" style=\"background-color:#0066cc;color:#FFFFFF;\">PAC</span></a></td>";
                                         echo "<td><a target=\"_blank\" href=\"./sequence_detail.php?species=".$_POST['species']."&seq=$query_row[0]&chr=$query_row[1]&ftr_start=$query_row[4]&ftr_end=$query_row[5]&strand=$query_row[2]1\"><span title=\"Get more information about this sequence\" style=\"background-color:#0066cc;color:#FFFFFF;\">Detail</span></a></td>";
                                    }
                                echo "</tr>";
                            }
                ?>
            </tbody>
            </table>
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