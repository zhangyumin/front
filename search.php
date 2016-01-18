
<?php
    $con= mysql_connect("localhost","root","root");
    mysql_select_db("db_server",$con);        
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Search</title>
        <script src="./src/jquery-1.10.1.min.js"></script>

        <link href="./src/index.css" rel="stylesheet" type="text/css" />

        <script src="./src/jquery.slides.min.js"></script>
        <!-- Mobile viewport optimisation -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <!--[if lte IE 7]>
        <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <!--[if lt IE 9]>
        <script src="./js/html5shiv/html5shiv.js"></script>
        <![endif]-->

    </head>
    <script type="text/javascript">

            $(document).ready(function(){
                
                $(".more1").click(function(){
                    $(".more1_content div,.more1_content").slideToggle("slow");
                    if($(".more1").text()=='More')
                        $(".more1").html("Close");
                    else
                        $(".more1").html("More");
                });
                $(".more2").click(function(){
                    $(".more2_content div,.more2_content").slideToggle("slow");
                    if($(".more2").text()=='More')
                        $(".more2").html("Close");
                    else
                        $(".more2").html("More");
                });
                $(".more3").click(function(){
                    $(".more3_content div,.more3_content").slideToggle("slow");
                    if($(".more3").text()=='More')
                        $(".more3").html("Close");
                    else
                        $(".more3").html("More");
                });
                $(".more4").click(function(){
                    $(".more4_content div,.more4_content").slideToggle("slow");
                    if($(".more4").text()=='More')
                        $(".more4").html("Close");
                    else
                        $(".more4").html("More");
                });
            });
                <?php
                    $arr_arab=array();
                    $arr_japonica=array();
                    $arr_mtr=array();
                    $arr_chlamy=array();
                    echo "var chr=[";
                    //arabidopsis
                    $arab_sql=mysql_query("select distinct chr from t_japonica_gff;");
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
                    $arab_sql=mysql_query("select distinct chr from t_arab_gff;");
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
    <body onload="getchr()">        
        <?php include './navbar.php'; ?>
        <div class="ym-wrapper">
            <fieldset >
                <legend>
                    <h4>
                        <font color="#224055" ><b>Search:</b> search and view the system samples</font>
                    </h4>
                </legend>
               <div class="box info ym-form">
                   <form name="search" method="post" id="getback" action="search_result.php">
                       <div class="ym-grid ym-fbox">
                            <div class="ym-g33 ym-gl">
                                <label for="species" style="margin-right:2%;">Species:</label>
                                <select id="species" name="species" style="width:80%" onclick="getchr()">
                                     <option value="japonica" selected="selected">Japonica rice</option>
                                    <option value="arab">Arabidopsis thaliana</option>
                                    <option value="mtr">Medicago truncatula</option>
                                    <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
                                </select>
                            </div>
                            <div class="ym-g33 ym-gl">
                               <label for="chr" style="margin: 0 2%">in</label>
                                    <select id="chr" name="chr" style="width:80%">
                                        <option value="all" selected="selected">All</option>
                                    </select>
                            </div>
                            <div class="ym-g33 ym-gl">    
                               <label for="start" style="margin:0 1%;"> from</label>
                                <input type="text" name="start" id="start" style="width:39%">
                               <label for="end" style="margin:0 1%;"> to</label>
                               <input type="text" name="end" id="end" style="width:40.7%">
                            </div>
                        </div>
                        <div class="ym-grid ym-fbox">    
                            <label for="gene_id">Gene ID:(use ',' to split different gene id)</label>
                            <textarea style="width:100%" name="gene_id"></textarea>
                        </div>
                        <div class="ym-grid ym-fbox">
                            <label for="go_accession">Go term accession:(use ',' to split different gene id)</label>
                            <textarea style="width:100%" name='go_accession' id="go_accession"></textarea>
                        </div>
                        <div class="ym-grid ym-fbox">
                            <label for="go_name" >Go term name:</label>
                            <input type='text' name='go_name' class="ym-gr" style="width:89%;"/>
                        </div>
                        <div class="ym-grid ym-fbox">
                            <label for="function" >Function:</label>
                            <input type='text' name='function' class="ym-gr" style="width:89%;"/>
                        </div>
                        <div class="ym-grid ym-fbox">
                                <button type="submit">submit</button>
                                <button type="reset">reset</button>
                                <button type="button" onclick="search_example()">example</button>
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
            </fieldset>
            <br>
            <fieldset class="table-tools" >
                <legend>
                    <h4>
                        <font color="#224055"><b>Datasets list: </b>all species documented in browser</font>
                    </h4>
                </legend>
                <table cellspacing="1" cellpadding="0" border="0" style="border:1px solid #5499c9;">
                    <thead>
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
                    </thead>
                    <tbody>
                        <tr>
                            <td>Arabidopsis thanliana</td>
                            <td>14</td>
                            <td>62834</td>
                            <td>68388227</td>
                            <td>poly(A) sites from leaf, seed, and root tissues of WT and Oxt6 mutant by poly(A) tag sequencing</td>
                            <td>Wu et al. PNAS, 2011; Thomas et al. Plant Cell, 2012; Liu et al. PloS One, 2014</td>                     
                            <td>
                                <a target="_blank" href="../jbrowse/?data=data/arab">
                                    <span title="Browse polyA sites in Jbrowse" style="background-color:#0066cc;color: #FFFFFF">Chr</span>
                                </a>
                            </td>
                            <td>
                                <a target="_blank" href="./search_result.php?method=fuzzy&keyword=at1g01020&species=arab">
                                    <span title="Browse search result for 'AT1G01020'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                                </a>
                            </td>
                            <td class="style1"  width='50' style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                                <span class="more1" title="View more information about arabidopsis thanliana" style="cursor: pointer;background-color:#0066cc;color: #FFFFFF">More</span>
                            </td>
                        </tr>
                        <tr  >
                                
                                <td class="more1_content" colspan="9" >
                                    <div class="box info">
                                    
                                    <table cellspacing="1" cellpadding="0" border="0" style="border:1px dotted #5499c9;width:90%;margin: 10px 5%;">
                                        <thead>
                                            <tr class="theme" bgcolor="#5499c9">
                                                <td class="theme"  height="24">Species</td>
                                                <td class="theme"  height="24">Lable</td>
                                                <td class="theme"  height="24">Cell Line</td>
                                                <td class="theme"  height="24">Tissue</td>
                                                <td class="theme"  height="24">Reference</td>
                                                <td class="theme"  height="24">Genome Annotation</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Arabidopsis thanliana</td>
                                                <td>wt leaf 1</td>
                                                <td>WT</td>
                                                <td>leaf</td>
                                                <td>Thomas et al. Plant Cell, 2012</td>
                                                <td>TAIR 10</td>
                                            </tr>
                                            <tr>
                                                <td>Arabidopsis thanliana</td>
                                                <td>wt leaf 2</td>
                                                <td>WT</td>
                                                <td>leaf</td>
                                                <td>Wu et al. PNAS, 2012</td>
                                                <td>TAIR 10</td>
                                            </tr>
                                            <tr>
                                                <td>Arabidopsis thanliana</td>
                                                <td>wt leaf 3</td>
                                                <td>WT</td>
                                                <td>leaf</td>
                                                <td>Thomas et al. Plant Cell, 2012</td>
                                                <td>TAIR 10</td>
                                            </tr>
                                            <tr>
                                                <td>Arabidopsis thanliana</td>
                                                <td>wt seed 1</td>
                                                <td>WT</td>
                                                <td>seed</td>
                                                <td>Thomas et al. Plant Cell, 2012</td>
                                                <td>TAIR 10</td>
                                            </tr>
                                            <tr>
                                                <td>Arabidopsis thanliana</td>
                                                <td>wt seed 2</td>
                                                <td>WT</td>
                                                <td>seed</td>
                                                <td>Wu et al. PNAS, 2012</td>
                                                <td>TAIR 10</td>
                                            </tr>
                                            <tr>
                                                <td>Arabidopsis thanliana</td>
                                                <td>wt root 1</td>
                                                <td>WT</td>
                                                <td>root</td>
                                                <td>Liu et al. PloS, 2014</td>
                                                <td>TAIR 10</td>
                                            </tr>
                                            <tr>
                                                <td>Arabidopsis thanliana</td>
                                                <td>wt root 2</td>
                                                <td>WT</td>
                                                <td>root</td>
                                                <td>Liu et al. PloS, 2014</td>
                                                <td>TAIR 10</td>
                                            </tr>
                                            <tr>
                                                <td>Arabidopsis thanliana</td>
                                                <td>wt root 3</td>
                                                <td>WT</td>
                                                <td>root</td>
                                                <td>Liu et al. PloS, 2014</td>
                                                <td>TAIR 10</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="ym-grid "  style="text-align: center">
                                        <div class="ym-g50 ym-gl">
                                            <img src='./pic/arab_PACs.png' style="width:400px;height: 240px"/>
                                        </div>
                                        <div class="ym-g50 ym-gr" >
                                            <img src='./pic/arab_PATs.png' style="width:400px;height: 240px"/>
                                        </div>

                                    </div>
                                    <div class="ym-grid " >
                                        <h5 class="center">Distributions of poly(A) site clusters (PACs) and poly(A) tags (PATs)</h5>
                                    </div>
                                </div>
                                </td>
                                
                        </tr>
                        <tr>
                           <td>Oryza sativa</td>
                            <td>1</td>
                            <td>32657</td>
                            <td>57852</td>
                            <td>poly(A) sites extracted from ESTs</td>
                            <td>Shen et al. Nucleic Acids Res, 2008</td>                     
                            <td>
                                <a target="_blank" href="../jbrowse/?data=data/japonica">
                                    <span title="Browse polyA sites in Jbrowse" style="background-color:#0066cc;color: #FFFFFF">Chr</span>
                                </a>
                            </td>
                            <td>
                                <a target="_blank" href="./search_result.php?keyword=LOC_Os01g01080&species=japonica&method=fuzzy">
                                    <span title="Browse search result for 'LOC_Os01g01080'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                                </a>
                            </td>
                            <td class="style1" width='50' style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                                <span class="more2" title="Browse search result for 'LOC_Os01g01080'" style="cursor: pointer;background-color:#0066cc;color: #FFFFFF">More</span>
                            </td>
                        </tr>
                        <tr >
                            
                            <td class="more2_content" colspan="9" >
                                <div  class="box info">
                                    <table cellspacing="1" cellpadding="0" border="0" style="border:1px dotted #5499c9;width:100%;margin: 10px 2px;">
                                        <tbody>
                                            <tr class="theme">
                                                <td class="theme" bgcolor="#5499c9" align="center" height="24">Species</td>
                                                <td class="theme" bgcolor="#5499c9" align="center" height="24">Lable</td>
                                                <td class="theme" bgcolor="#5499c9" align="center" height="24">Cell Line</td>
                                                <td class="theme" bgcolor="#5499c9" align="center" height="24">Tissue</td>
                                                <td class="theme" bgcolor="#5499c9" align="center" height="24">Reference</td>
                                                <td class="theme" bgcolor="#5499c9" align="center" height="24">Genome Annotation</td>
                                            </tr>
                                            <tr>
                                                <td>Oryza sativa</td>
                                                <td>from EST</td>
                                                <td>mix</td>
                                                <td>mix</td>
                                                <td>shen et al. Plant Cell, 2012</td>
                                                <td>TAIR 10</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="ym-grid "  style="text-align: center">
                                        <div class="ym-g50 ym-gl">
                                            <img src='./pic/japonica_PACs.png' style="width:400px;height: 240px"/>
                                        </div>
                                        <div class="ym-g50 ym-gl">
                                            <img src='./pic/japonica_PATs.png' style="width:400px;height: 240px"/>
                                        </div>
                                    </div>
                                    <h5 class="center">Distributions of poly(A) site clusters (PACs) and poly(A) tags (PATs)</h5>
                                </div>
                        </td>
                        </tr>
                        <tr>
                            <td>Medicago truncatula</td>
                            <td>1</td>
                            <td>42691</td>
                            <td>2747920</td>
                            <td>poly(A) sites from leaf tissue by poly(A) tag sequencing</td>
                            <td>Wu et al. BMC Genomics, 2014</td>                     
                            <td>
                                <a target="_blank" href="../jbrowse/?data=data/mtr">
                                    <span title="Browse polyA sites in Jbrowse" style="background-color:#0066cc;color: #FFFFFF">Chr</span>
                                </a>
                            </td>
                            <td>
                                <a target="_blank" href="./search_result.php?keyword=Medtr0019s0160&species=mtr&method=fuzzy">
                                    <span title="Browse search result for 'Medtr0019s0160'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                                </a>
                            </td>
                            <td class="style1" width='50' style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                                <span class="more3" title="View more information about Medicago truncatula'" style="cursor: pointer;background-color:#0066cc;color: #FFFFFF">More</span>
                            </td>
                        </tr>
                        <tr class="more3_content">
                            
                            <td colspan="9" >
                                <div class="box info">
                                    <table cellspacing="1" cellpadding="0" border="0" style="border:1px dotted #5499c9;width:100%;margin: 10px 2px;">
                                        <tbody>
                                            <tr class="theme" bgcolor="#5499c9">
                                                <td class="theme"  height="24">Species</td>
                                                <td class="theme"  height="24">Lable</td>
                                                <td class="theme"  height="24">Cell Line</td>
                                                <td class="theme"  height="24">Tissue</td>
                                                <td class="theme"  height="24">Reference</td>
                                                <td class="theme"  height="24">Genome Annotation</td>
                                            </tr>
                                            <tr>
                                                <td>Medicago truncatula</td>
                                                <td>wt leaf</td>
                                                <td>WT</td>
                                                <td>leaf</td>
                                                <td>Wu et al. BMC Genomics, 2014</td>
                                                <td>JCVI Medtr v4</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="ym-grid "  style="text-align: center">
                                        <div class="ym-g50 ym-gl">
                                            <img src='./pic/mtr_PACs.png' style="width:400px;height: 240px"/>
                                        </div>
                                        <div class="ym-g50 ym-gl">
                                            <img src='./pic/mtr_PATs.png' style="width:400px;height: 240px"/>
                                        </div>
                                        
                                    </div> 
                                    <div class="ym-grid">   
                                        <h5 class="center">Distributions of poly(A) site clusters (PACs) and poly(A) tags (PATs)</h5>
                                    </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Chlamydomonas reinhardtii</td>
                            <td>3</td>
                            <td>39295</td>
                            <td>1002767</td>
                            <td>poly(A) sites extracted from ESTs, 454, and Illumina sequence reads</td>
                            <td>Zhao et al. G3:Genes|Genomes|Genetics, 2014</td>                     
                            <td>
                                <a target="_blank" href="../jbrowse/?data=data/chlamy">
                                    <span title="Browse polyA sites in Jbrowse" style="background-color:#0066cc;color: #FFFFFF">Chr</span>
                                </a>
                            </td>
                            <td>
                                <a target="_blank" href="./search_result.php?keyword=Cre01.g000650&species=chlamy&method=fuzzy">
                                    <span title="Browse search result for 'Cre01.g000650'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                                </a>
                            </td>
                            <td class="style1" width='50' style="white-space: nowrap;border-top: 1px dotted #c9bc9c;">
                                <span class="more4" title="View more information about Chlamydomonas reinhardtii'" style="cursor: pointer;background-color:#0066cc;color: #FFFFFF">More</span>
                            </td>
                        </tr>
                        <tr class="more4_content" >
                            <td colspan="9">
                                <div class="box info">
                                    <table cellspacing="1" cellpadding="0" border="0" style="border:1px dotted #5499c9;width:100%;margin: 10px 2px;">
                                        <tbody>
                                            <tr class="theme" bgcolor="#5499c9">
                                                <td class="theme"  height="24">Species</td>
                                                <td class="theme"  height="24">Lable</td>
                                                <td class="theme"  height="24">Cell Line</td>
                                                <td class="theme"  height="24">Tissue</td>
                                                <td class="theme"  height="24">Reference</td>
                                                <td class="theme"  height="24">Genome Annotation</td>
                                            </tr>
                                            <tr>
                                                <td>Chlamydomonas reinhardtii</td>
                                                <td>from illumina</td>
                                                <td>mix</td>
                                                <td>mix</td>
                                                <td>Zhao et al. G3:Genes|Genomes|Genetics, 2014</td>
                                                <td>Creinhardtii 281 v55</td>
                                            </tr>
                                            <tr>
                                                <td>Chlamydomonas reinhardtii</td>
                                                <td>from 454</td>
                                                <td>mix</td>
                                                <td>mix</td>
                                                <td>Zhao et al. G3:Genes|Genomes|Genetics, 2014</td>
                                                <td>Creinhardtii 281 v55</td>
                                            </tr>
                                            <tr>
                                                <td>Chlamydomonas reinhardtii</td>
                                                <td>from EST</td>
                                                <td>mix</td>
                                                <td>mix</td>
                                                <td>Zhao et al. G3:Genes|Genomes|Genetics, 2014</td>
                                                <td>Creinhardtii 281 v55</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="ym-grid" style="text-align: center">
                                        <div class="ym-g50 ym-gl">
                                            <img src='./pic/chlamy_PACs.png' style="width:400px;height: 240px"/>
                                        </div>
                                        <div class="ym-g50 ym-gl">
                                            <img src='./pic/chlamy_PATs.png' style="width:400px;height: 240px"/>
                                        </div>
                                    </div>
                                    <div class="ym-grid">
                                        <h5 class="center">Distributions of poly(A) site clusters (PACs) and poly(A) tags (PATs)</h3>
                                    </div>        
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table><br>
            </fieldset> 
        </div>
        <div class="bottom">
            <?php
                include "footer.php";
            ?>
        </div>
    </body>
</html>