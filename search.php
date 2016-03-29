
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
        <!-- webui popover -->
        <script src="./src/jquery.webui-popover.js"></script>
        <link href="./src/jquery.webui-popover.css" rel="stylesheet" type="text/css"/>
        <style>
            .col-md-12{
                border:1px solid #faebcc;
            }
            .panel-title{
                color:#8a6b3d;
                padding-left: 10px;
                margin-top: 0;
                margin-bottom: 0;
                font-size: 18px;
            }
            .panel-heading{
                background-color: #fcf8a1;
                padding: 10px 15px;
            }
            .panel-body{
                padding: 15px;
                font-size: 16px;
                letter-spacing: 0.5px;
                text-align: justify;
                word-spacing: 1px;
            }
            .margin5px{
                margin-right: 5px;
            }
            .demo:hover{
                background-color: #fff;
                cursor: pointer;
            }
            a:hover{
                cursor:pointer;
            }
        </style>
    </head>
    <script type="text/javascript">

//            $(document).ready(function(){
//                
//                $(".more1").click(function(){
//                    $(".more1_content div,.more1_content").slideToggle("slow");
//                    if($(".more1").text()=='More')
//                        $(".more1").html("Close");
//                    else
//                        $(".more1").html("More");
//                });
//                $(".more2").click(function(){
//                    $(".more2_content div,.more2_content").slideToggle("slow");
//                    if($(".more2").text()=='More')
//                        $(".more2").html("Close");
//                    else
//                        $(".more2").html("More");
//                });
//                $(".more3").click(function(){
//                    $(".more3_content div,.more3_content").slideToggle("slow");
//                    if($(".more3").text()=='More')
//                        $(".more3").html("Close");
//                    else
//                        $(".more3").html("More");
//                });
//                $(".more4").click(function(){
//                    $(".more4_content div,.more4_content").slideToggle("slow");
//                    if($(".more4").text()=='More')
//                        $(".more4").html("Close");
//                    else
//                        $(".more4").html("More");
//                });
//            });
                
                //<?php
//                //读取mysql生成js数组信息
//                    $arr_arab=array();
//                    $arr_japonica=array();
//                    $arr_mtr=array();
//                    $arr_chlamy=array();
//                    echo "var ftr=[";
//                    //arabidopsis
//                    $arab_sql=mysql_query("select distinct ftr from t_japonica_pac;");
//                    $i=0;
//                    while($arab_row=  mysql_fetch_row($arab_sql)){
//                        array_push($arr_arab, $arab_row[0]);
//                    }
//                    echo "[\"";
//                    foreach ($arr_arab as $key => $value) {
//                        if($key!=  count($arr_arab)-1)
//                            echo $value."\",\"";
//                        else
//                            echo $value;
//                    }
//                    echo "\"],";
//                    //japonica
//                    $arab_sql=mysql_query("select distinct ftr from t_arab_pac;");
//                    $i=0;
//                    while($arab_row=  mysql_fetch_row($arab_sql)){
//                        array_push($arr_japonica, $arab_row[0]);
//                    }
//                    echo "[\"";
//                    foreach ($arr_japonica as $key => $value) {
//                        if($key!=  count($arr_japonica)-1)
//                            echo $value."\",\"";
//                        else
//                            echo $value;
//                    }
//                    echo "\"],";
//                    //mtr
//                    $arab_sql=mysql_query("select distinct ftr from t_mtr_pac;");
//                    $i=0;
//                    while($arab_row=  mysql_fetch_row($arab_sql)){
//                        array_push($arr_mtr, $arab_row[0]);
//                    }
//                    echo "[\"";
//                    foreach ($arr_mtr as $key => $value) {
//                        if($key!=  count($arr_mtr)-1)
//                            echo $value."\",\"";
//                        else
//                            echo $value;
//                    }
//                    echo "\"],";
//                    //chlamy
//                    $arab_sql=mysql_query("select distinct ftr from t_chlamy_pac;");
//                    $i=0;
//                    while($arab_row=  mysql_fetch_row($arab_sql)){
//                        array_push($arr_chlamy, $arab_row[0]);
//                    }
//                    echo "[\"";
//                    foreach ($arr_chlamy as $key => $value) {
//                        if($key!=  count($arr_chlamy)-1)
//                            echo $value."\",\"";
//                        else
//                            echo $value;
//                    }
//                    echo "\"]";
//                    echo "];";
//                ?>
    
                    var chr=[["Chr1","Chr10","Chr11","Chr12","Chr2","Chr3","Chr4","Chr5","Chr6","Chr7","Chr8","Chr9"],["chloroplast","Chr1","Chr2","Chr3","Chr4","Chr5","mitochondria"],["chr1","chr2","chr3","chr4","chr5","chr6","chr7","chr8","scaffold0001","scaffold0002","scaffold0003","scaffold0004","scaffold0005","scaffold0006","scaffold0007","scaffold0008","scaffold0009","scaffold0010","scaffold0011","scaffold0012","scaffold0013","scaffold0014","scaffold0015","scaffold0016","scaffold0018","scaffold0019","scaffold0020","scaffold0022","scaffold0024","scaffold0025","scaffold0026","scaffold0027","scaffold0028","scaffold0034","scaffold0035","scaffold0036","scaffold0040","scaffold0041","scaffold0042","scaffold0043","scaffold0044","scaffold0045","scaffold0047","scaffold0048","scaffold0049","scaffold0050","scaffold0053","scaffold0054","scaffold0057","scaffold0058","scaffold0059","scaffold0062","scaffold0064","scaffold0066","scaffold0068","scaffold0069","scaffold0072","scaffold0076","scaffold0078","scaffold0082","scaffold0086","scaffold0088","scaffold0091","scaffold0092","scaffold0097","scaffold0100","scaffold0101","scaffold0103","scaffold0110","scaffold0113","scaffold0118","scaffold0121","scaffold0122","scaffold0123","scaffold0133","scaffold0136","scaffold0140","scaffold0147","scaffold0148","scaffold0151","scaffold0154","scaffold0167","scaffold0168","scaffold0170","scaffold0172","scaffold0173","scaffold0175","scaffold0180","scaffold0189","scaffold0194","scaffold0196","scaffold0197","scaffold0199","scaffold0202","scaffold0204","scaffold0212","scaffold0216","scaffold0219","scaffold0221","scaffold0227","scaffold0236","scaffold0242","scaffold0246","scaffold0249","scaffold0251","scaffold0256","scaffold0262","scaffold0263","scaffold0268","scaffold0274","scaffold0275","scaffold0276","scaffold0279","scaffold0280","scaffold0288","scaffold0291","scaffold0294","scaffold0308","scaffold0314","scaffold0316","scaffold0325","scaffold0332","scaffold0334","scaffold0340","scaffold0349","scaffold0361","scaffold0374","scaffold0380","scaffold0388","scaffold0392","scaffold0393","scaffold0417","scaffold0419","scaffold0428","scaffold0429","scaffold0443","scaffold0468","scaffold0493","scaffold0504","scaffold0508","scaffold0536","scaffold0591","scaffold0603","scaffold0650","scaffold0658","scaffold0683","scaffold0693","scaffold0699","scaffold0783","scaffold0795","scaffold0874","scaffold0927","scaffold1051","scaffold1099","scaffold1457","scaffold1514","scaffold2053"],["chromosome_1","chromosome_10","chromosome_11","chromosome_12","chromosome_13","chromosome_14","chromosome_15","chromosome_16","chromosome_17","chromosome_2","chromosome_3","chromosome_4","chromosome_5","chromosome_6","chromosome_7","chromosome_8","chromosome_9","scaffold_18","scaffold_19","scaffold_20","scaffold_21","scaffold_22","scaffold_23","scaffold_24","scaffold_25","scaffold_26","scaffold_27","scaffold_28","scaffold_31","scaffold_33","scaffold_34","scaffold_35","scaffold_36","scaffold_37","scaffold_39","scaffold_40","scaffold_42","scaffold_44","scaffold_46","scaffold_47","scaffold_48","scaffold_49","scaffold_50"]];
                    function getchr(){
                        var sltSpecies=document.search.species;
                        var sltChr=document.search.chr;
                        var speciesChr=chr[sltSpecies.selectedIndex];
                        sltChr.length=1;
                        for(var i=0;i<speciesChr.length;i++){
                            sltChr[i+1]=new Option(speciesChr[i],speciesChr[i]);
                        }
                    }
                    var ftr=[["3'UTR","CDS","intergenic","intron","5'UTR","promoter"],["intergenic","5'UTR","3'UTR","CDS","AMB","intron","exon","pseudogenic exon","promoter"],["intergenic","3'UTR","CDS","intron","AMB","5'UTR","promoter"],["intergenic","3'UTR","AMB","intron","CDS","5'UTR"]];
                    var ftr_value=[["3UTR","CDS","intergenic.igt","intron","5UTR","intergenic.pm"],["intergenic.igt","5UTR","3UTR","CDS","AMB","intron","exon","pseudogenic_exon","intergenic.pm"],["intergenic.igt","3UTR","CDS","intron","AMB","5UTR","intergenic.pm"],["intergenic","3UTR","AMB","intron","CDS","5UTR"]];
                    function getftr(){
                        var sltSpecies=document.search.species;
                        var sltFtr=document.search.ftr;
                        var speciesFtr=ftr[sltSpecies.selectedIndex];
                        var speciesFtrValue = ftr_value[sltSpecies.selectedIndex]
                        sltFtr.length=1;
                        for(var i=0;i<speciesFtr.length;i++){
                            sltFtr[i+1]=new Option(speciesFtr[i],speciesFtrValue[i]);
                        }
                    }
                    function gettip(){
                        if(document.getElementById("species").value=='arab'){
                            $("#tip1").text("ARC2,GIF2,AT1G01020,JAM2,AT1G02130");
                            $("#tip2").text("GO:0006888,GO:0006355");
                        }
                        else if(document.getElementById("species").value=='japonica'){
                            $("#tip1").text("LOC_Os01g01070,LOC_Os01g03070,LOC_Os01g01307");
                            $("#tip2").text("GO:0009987,GO:0009987");
                        }
                        else if(document.getElementById("species").value=='mtr'){
                            $("#tip1").text("Medtr0236s0040,Medtr0246s0020,Medtr0251s0050");
                            $("#tip2").text("GO:0003899,GO:0006412");
                        }
                        else if(document.getElementById("species").value=='chlamy'){
                            $("#tip1").text("Cre04.g225950,Cre04.g226050,Cre04.g226150");
                            $("#tip2").text("GO:0005507,GO:0003743");
                        }
                    }
    </script>
    <body onload="getchr();getftr();gettip()">        
        <?php include './navbar.php'; ?>
        <div class="ym-wrapper">
                <h2 style="border-bottom: 2px #5db95b solid;padding: 15px 0px 0px 0px;margin-bottom: 0px;text-align: left">
                    <font color="#224055" ><b>Search:</b> search and view the system samples</font>
                </h2>
               <div class=" info ym-form">
                   <form name="search" method="post" id="getback" action="search_result.php">
                       <div class="ym-grid ym-fbox">
                            <div class="ym-g20 ym-gl">
                                <label for="species" style="margin-right:2%;">Species:</label>
                                <select id="species" name="species" style="width:70%" onclick="getchr();getftr();gettip()">
                                     <option value="japonica">Japonica rice</option>
                                    <option value="arab" selected="selected">Arabidopsis thaliana</option>
                                    <option value="mtr">Medicago truncatula</option>
                                    <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
                                </select>
                            </div>
                            <div class="ym-g20 ym-gl">
                               <label for="chr" style="margin-right: 2%;margin-left: 16%">In</label>
                                    <select id="chr" name="chr" style="width:70%">
                                        <option value="all" selected="selected">All</option>
                                    </select>
                            </div>
                            <div class="ym-g20 ym-gl">    
                               <label for="start" style="margin-right: 2%;margin-left: 8%"> From</label>
                               <input type="text" name="start" id="start" style="width:70%">
                            </div>
                            <div class="ym-g20 ym-gl">
                               <label for="end" style="margin-right: 2%;margin-left: 14%"> To</label>
                               <input type="text" name="end" id="end" style="width:70%">
                            </div>
                           <div class="ym-g20 ym-gl">
                               <label for="ftr" style="margin-right: 2%;margin-left: 15%"> For</label>
                               <select id="ftr" name="ftr" style="width:70%">
                                    <option value="all" selected="selected">All</option>
                                </select>
                            </div><div style="clear:both"></div>
                        </div>
                        <div class="ym-grid ym-fbox">    
                            <label for="gene_id" class="margin5px">Gene symbol or locus ID: (use ',' to split different ID)</label><a id="nameexp" class="demo"><img src="./pic/exp.png" style="width:15px;height: 15px;display: inline-block;vertical-align: bottom;padding-bottom: 1px;"></a>
                            <textarea id="gene_id" style="width:100%;height: 50px" name="gene_id"></textarea>
                        </div>
                        <div class="ym-grid ym-fbox">
                            <label for="go_accession" class="margin5px">GO term accession: (use ',' to split different GO ID)</label><a id="goexp" class="demo"><img src="./pic/exp.png" style="width:15px;height: 15px;display: inline-block;vertical-align: bottom;padding-bottom: 1px;"></a>
                            <textarea style="width:100%;height: 50px" name='go_accession' id="go_accession"></textarea>
                        </div>
                        <div class="ym-grid ym-fbox">
                            <label for="go_name" class="margin5px">GO term name:</label><a id="termexp" class="demo"><img src="./pic/exp.png" style="width:15px;height: 15px;display: inline-block;vertical-align: bottom;padding-bottom: 1px;"></a>
                            <textarea type='text' name='go_name' id='go_name' style="width:100%;height: 25px"></textarea>
                        </div>
                        <div class="ym-grid ym-fbox">
                            <label for="function" class="margin5px">Function:</label><a id="functionexp" class="demo"><img src="./pic/exp.png" style="width:15px;height: 15px;display: inline-block;vertical-align: bottom;padding-bottom: 1px;"></a>
                            <textarea type='text' name='function' id='function' style="width:100%;height: 25px"></textarea>
                        </div>
                        <div class="ym-grid ym-fbox">
                                <button type="submit">Submit</button>
                                <button type="reset">Reset</button>
                                <button type="button" onclick="search_example()">Example</button>
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
                                    function name_demo(){
                                        if(document.getElementById("species").value=='arab'){
                                                document.getElementById("gene_id").value="ARC2,GIF2,AT1G01020,JAM2,AT1G02130";
                                            }
                                            else if(document.getElementById("species").value=='japonica'){
                                                document.getElementById("gene_id").value="LOC_Os01g01070,LOC_Os01g03070,LOC_Os01g01307";
                                            }
                                            else if(document.getElementById("species").value=='mtr'){
                                                document.getElementById("gene_id").value="Medtr0236s0040,Medtr0246s0020,Medtr0251s0050";
                                            }
                                            else if(document.getElementById("species").value=='chlamy'){
                                                document.getElementById("gene_id").value="Cre04.g225950,Cre04.g226050,Cre04.g226150";
                                        }
                                    }
                                    function go_demo(){
                                        if(document.getElementById("species").value=='arab'){
                                                document.getElementById("go_accession").value="GO:0006888,GO:0006355";
                                            }
                                            else if(document.getElementById("species").value=='japonica'){
                                                document.getElementById("go_accession").value="GO:0009987,GO:0009987";
                                            }
                                            else if(document.getElementById("species").value=='mtr'){
                                                document.getElementById("go_accession").value="GO:0003899,GO:0006412";
                                            }
                                            else if(document.getElementById("species").value=='chlamy'){
                                                document.getElementById("go_accession").value="GO:0005507,GO:0003743";
                                        }
                                    }
                                    function generate_term(){
                                        
                                    }
                                     $('#nameexp').webuiPopover({
                                        placement:'right',//值: auto,top,right,bottom,left,top-right,top-left,bottom-right,bottom-left
                                        title:'Example',
                                        content:'<a id="tip1" onclick="javascript:name_demo()">ARC2,GIF2,AT1G01020,JAM2,AT1G02130</a>',
                                        trigger:'hover',
                                        type:'html'
                                    });
                                    $('#goexp').webuiPopover({
                                        placement:'right',//值: auto,top,right,bottom,left,top-right,top-left,bottom-right,bottom-left
                                        title:'Example',
                                        content:'<a id="tip2" onclick="javascript:go_demo()">GO:0006888,GO:0006355</a>',
                                        trigger:'hover',
                                        type:'html'
                                    });
                                    $('#termexp').webuiPopover({
                                        placement:'right',//值: auto,top,right,bottom,left,top-right,top-left,bottom-right,bottom-left
                                        title:'Example',
                                        content:'<a onclick=\"javascript:document.getElementById(\'go_name\').value=\'plasma\';\">plasma</a>',
                                        trigger:'hover',
                                        type:'html'
                                    });
                                    $('#functionexp').webuiPopover({
                                        placement:'right',//值: auto,top,right,bottom,left,top-right,top-left,bottom-right,bottom-left
                                        title:'Example',
                                        content:'<a onclick=\"javascript:document.getElementById(\'function\').value=\'transport\';\">transport</a>',
                                        trigger:'hover',
                                        type:'html'
                                    });
                                </script>
                        </div>
                        </form>
               </div>
<!--                <legend>
                    <h4>
                        <font color="#224055"><b>Datasets list: </b>all species documented in browser</font>
                    </h4>
                </legend>
                <hr style="border-bottom: 2px #5499c9 solid">
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
                                <a target="_blank" href="./sequence_detail.php?species=arab&seq=AT1G01020">
                                    <span title="Browse gene detail for 'AT1G01020'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                                </a>
                            </td>
                            <td class="style1"  width='50' style="white-space: nowrap;">
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
                                        <div class="ym-grid " style="clear: both;" >
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
                                <a target="_blank" href="./sequence_detail.php?seq=LOC_Os01g01080&species=japonica">
                                    <span title="Browse gene detail for 'LOC_Os01g01080'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                                </a>
                            </td>
                            <td class="style1" width='50' style="white-space: nowrap;">
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
                                    <h5 style="clear: both" class="center">Distributions of poly(A) site clusters (PACs) and poly(A) tags (PATs)</h5>
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
                                <a target="_blank" href="./sequence_detail.php?seq=Medtr0019s0160&species=mtr">
                                    <span title="Browse gene detail for 'Medtr0019s0160'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                                </a>
                            </td>
                            <td class="style1" width='50' style="white-space: nowrap;">
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
                                    <div class="ym-grid" style="clear:both">   
                                        <h5 class="center">Distributions of poly(A) site clusters (PACs) and poly(A) tags (PATs)</h5>
                                    </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Chlamydomonas reinhardtii</td>
                            <td>4</td>
                            <td>45372</td>
                            <td>13536005</td>
                            <td>poly(A) sites extracted from ESTs, 454, Illumina, and  PAT-seq sequence reads</td>
                            <td>Zhao et al. G3:Genes|Genomes|Genetics, 2014; Umen et al. PloS one, 2016</td>                     
                            <td>
                                <a target="_blank" href="../jbrowse/?data=data/chlamy">
                                    <span title="Browse polyA sites in Jbrowse" style="background-color:#0066cc;color: #FFFFFF">Chr</span>
                                </a>
                            </td>
                            <td>
                                <a target="_blank" href="./sequence_detail.php?seq=Cre01.g000650&species=chlamy">
                                    <span title="Browse gene detail for 'Cre01.g000650'" style="background-color:#0066cc;color: #FFFFFF">View</span>
                                </a>
                            </td>
                            <td class="style1" width='50' style="white-space: nowrap;">
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
                                            <tr>
                                                <td>Chlamydomonas reinhardtii</td>
                                                <td>from PAT-seq</td>
                                                <td>mix</td>
                                                <td>mix</td>
                                                <td>Umen et al. PloS one, 2016</td>
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
                                    <div class="ym-grid" style="clear:both">
                                        <h5 class="center">Distributions of poly(A) site clusters (PACs) and poly(A) tags (PATs)</h5>
                                    </div>        
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>-->
            <div class="col-md-12" style="margin-top:20px">
                <div class="panel panel-info">
                    <div class="panel-heading" id="summaryHeading">
                        <h3 class="panel-title">About search</h3>
                    </div>
                    <div class="panel-body">
                        <p>
                        The search module allows users to query genes or PACs in the data sets of interest by a variety of keywords, such as gene ID, chromosome fragment, gene functions, GO term, and GO ID. 
                        </p>
                        <p>
                        Example queries:<br>
                        1) Enter genome coordinate: <a href='search_result.php?method=fuzzy&keyword=Chr1&species=arab'>Species:Arabidopsis thaliana in Chr1</a><br>
                        2) Enter one gene ID: gene: <a href='search_result.php?method=fuzzy&keyword=AT1G02130&species=arab'>AT1G02130</a> or <a href='search_result.php?method=fuzzy&keyword=Medtr2g105570&species=mtr'>Medtr2g105570</a><br>
                        3) Enter one Go ID: <a href='search_result.php?method=fuzzy&keyword=0006888&species=arab'>GO:0006888</a><br>
                        4) Enter one Go function: <a href='search_result.php?method=fuzzy&keyword=transport&species=arab'>transport</a><br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bottom">
            <?php
                include "footer.php";
            ?>
        </div>
    </body>
</html>