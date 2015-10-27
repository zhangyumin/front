<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Search</title>
        <script src="./src/jquery-2.0.0.min.js"></script>
        <style>
            fieldset{
                border-color: #5499c9 !important;
                border-style: solid !important;
                border-width: 2px !important;
                padding: 5px 10px !important;
            }
            .style1{
                font-size: 14px;
                text-align: center;
            }
            .theme{
                font-size: 16px;
                text-align: center;
            }
/*            .bottom{
                position: fixed;
                bottom: 0;
                width:100%;
            }*/
        </style>
    </head>
    <body onload="getchr()">
        <?php
            $con=  mysql_connect("localhost","root","root");
            mysql_select_db("db_server",$con);
            include"navbar.php";
        ?>
        <script type="text/javascript">
        $(document).ready(function(){
            $(".more1").click(function(){
                $(".more1_content").slideToggle("slow");
                if($(".more1").text()=='More')
                    $(".more1").html("Close");
                else
                    $(".more1").html("More");
            });
            $(".more2").click(function(){
                $(".more2_content").slideToggle("slow");
                if($(".more2").text()=='More')
                    $(".more2").html("Close");
                else
                    $(".more2").html("More");
            });
        });
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
       <fieldset style="margin: 50px auto 0 auto ;width: 95%;">
                    <legend>
                        <span class="h3_italic" >
                            <font color="#224055" ><b>Search</b>:Search and view the system samples</font>
                        </span>
                    </legend>
           <div style="width:60%;margin:0 auto;">
               <form name="search" method="post" id="getback" action="search_result.php">
                   <label for="species" style="margin-right:2%;">Species:</label>
                   <select id="species" name="species" style="width:25%" onclick="getchr()">
                        <option value="arab" selected="selected">Arabidopsis thaliana</option>
                         <option value="japonica">Japonica rice</option>
                        <option value="mtr">Medicago truncatula</option>
                        <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
                    </select>
                   <label for="chr" style="margin: 0 1%">in</label>
                        <select id="chr" name="chr" style="width:6%">
                            <option value="all" selected="selected">All</option>
                        </select>
                   <label for="start" style="margin:0 1%;"> from</label>
                        <input type="text" name="start" id="start" style="width:14%">
                   <label for="end" style="margin:0 1%;"> to</label>
                        <input type="text" name="end" id="end" style="width:14%"><br>
<!--                        &nbsp;for
                        <input type="text" name="keyword" style="width:20%;">-->
                        <br><label for="gene_id">Gene ID:(use ',' to split different gene id)</label><br><textarea style="width:100%" name="gene_id"></textarea><br>
                        <br><label for="go_accession">Go term accession:(use ',' to split different gene id)</label><br><textarea style="width:100%" name='go_accession' id="go_accession"></textarea><br>
                        <br><label for="go_name" style="padding-right:2%;">Go term name:</label><input type='text' name='go_name' style="width: 40%"/><br>
                        <br><label for="function" style="padding-right:6.7%;">Function:</label><input type='text' name='function' style="width:40%;"/><br>
                        <div style="text-align:center;">
                            <button type="submit">submit</button>
                            <button type="reset">reset</button>
                            <input type="button" onclick="search_example()" value="Example">
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
                        </div>
                    </form>
           </div>
           
<!--           <div class="tips" style="font-size: 10px;">
               Dataset description: detailed description on the searching dataset can be referred by unfolding more description in the dataset table.
               <br>
               Tips for searching: search using a ID, gene name, symbols or description variable in publications and databases.
     
           </div>-->
        </fieldset>
        <br>
        <div class="table-tools" style="margin: 30px auto 20px auto;width:95%;;">
            <legend>
                <span style="margin:auto;">
                    <font color="#224055"><b>Datasets list:</b>all species documented in browser</font>
                </span>
            </legend>
            <table cellspacing="1" cellpadding="0" border="0" style="border:1px solid #5499c9;width:100%">
                <tbody>
                    <tr class="theme">
                        <td class="theme" bgcolor="#e1e1e1" align="center" height="24">Species</td>
                        <td class="theme" bgcolor="#e1e1e1" align="center" height="24">Samples</td>
                        <td class="theme" bgcolor="#e1e1e1" align="center" height="24">PAC</td>
                        <td class="theme" bgcolor="#e1e1e1" align="center" height="24">PAT</td>
                        <td class="theme" bgcolor="#e1e1e1" align="center" height="24">Description</td>
                        <td class="theme" bgcolor="#e1e1e1" align="center" height="24">Reference</td>
                        <td class="theme" bgcolor="#e1e1e1" align="center" height="24">Jbrowse</td>
                        <td class="theme" bgcolor="#e1e1e1" align="center" height="24">Example</td>
                        <td class="theme" bgcolor="#e1e1e1" align="center" height="24"></td>
                    </tr>
                    <tr>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">Arabidopsis thanliana</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">14</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">62834</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">68388227</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">poly(A) sites from leaf, seed, and root tissues of WT and Oxt6 mutant by poly(A) tag sequencing</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">Wu et al. PNAS, 2011; Thomas et al. Plant Cell, 2012; Liu et al. PloS One, 2014</td>                     
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <a target="_blank" href="../jbrowse/?data=data/arabidopsis">
                                <span title="Browse polyA sites in Jbrowse" style="background-color:#0066cc;color: #FFFFFF">Chr</span>
                            </a>
                        </td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <a target="_blank" href="./search_result.php?keyword=at1g0070">
                                <span title="Browse search result for 'at1g0070'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                            </a>
                        </td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <span class="more1" title="View more information about arabidopsis thanliana" style="cursor: pointer;background-color:#0066cc;color: #FFFFFF">More</span>
                        </td>
                    </tr>
                    <tr class="more1_content" style="display:none;">
                        <td colspan="6" style="text-align:center;"><div class="sss">this is the content<br>this is the content<br>this is the content<br>this is the content<br>this is the content<br></div></td>
                    </tr>
                    <tr>
                       <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">Oryza sativa</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">1</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">32657</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">57852</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">poly(A) sites extracted from ESTs</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">Shen et al. Nucleic Acids Res, 2008</td>                     
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <a target="_blank" href="../jbrowse/?data=data/arabidopsis">
                                <span title="Browse polyA sites in Jbrowse" style="background-color:#0066cc;color: #FFFFFF">Chr</span>
                            </a>
                        </td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <a target="_blank" href="./search_result.php?keyword=at1g0070">
                                <span title="Browse search result for 'at1g0070'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                            </a>
                        </td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <span class="more2" title="Browse search result for 'at1g0070'" style="cursor: pointer;background-color:#0066cc;color: #FFFFFF">More</span>
                        </td>
                    </tr>
                    <tr class="more2_content" style="display:none;">
                        <td colspan="6" style="text-align:center;"><div class="sss">this is the content<br>this is the content<br>this is the content<br>this is the content<br>this is the content<br></div></td>
                    </tr>
                    <tr>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">Medicago truncatula</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">1</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">42691</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">2747920</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">poly(A) sites from leaf tissue by poly(A) tag sequencing</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">Wu et al. BMC Genomics, 2014</td>                     
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <a target="_blank" href="../jbrowse/?data=data/arabidopsis">
                                <span title="Browse polyA sites in Jbrowse" style="background-color:#0066cc;color: #FFFFFF">Chr</span>
                            </a>
                        </td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <a target="_blank" href="./search_result.php?keyword=at1g0070">
                                <span title="Browse search result for 'at1g0070'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                            </a>
                        </td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <span title="Browse search result for 'at1g0070'" style="background-color:#0066cc;color: #FFFFFF">More</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">Chlamydomonas reinhardtii</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">3</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">39295</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">1002767</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">poly(A) sites extracted from ESTs, 454, and Illumina sequence reads</td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">Zhao et al. G3:Genes|Genomes|Genetics, 2014</td>                     
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <a target="_blank" href="../jbrowse/?data=data/arabidopsis">
                                <span title="Browse polyA sites in Jbrowse" style="background-color:#0066cc;color: #FFFFFF">Chr</span>
                            </a>
                        </td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <a target="_blank" href="./search_result.php?keyword=at1g0070">
                                <span title="Browse search result for 'at1g0070'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                            </a>
                        </td>
                        <td class="style1" style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                            <span title="Browse search result for 'at1g0070'" style="background-color:#0066cc;color: #FFFFFF">More</span>
                        </td>
                    </tr>
                    
                </tbody>
            </table><br>
        </div>
        <div class="bottom">
        <?php
            include"footer.php";
            ?>
        </div>
    </body>
</html>