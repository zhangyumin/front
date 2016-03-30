<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>polyA browser</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="./src/index-slide/animate.min.css">
        <script src="./src/jquery-1.10.1.min.js"></script>
        <script src="./src/index-slide/jquery.aniview.js"></script>
        <script>
        $(document).ready(function(){
            $('.aniview').AniView();
        });
        </script>
    </head>
    <style>
        .row{
            border: #faebcc 1px solid;
            border-radius: 5px;
            font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif;
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
            font-size: 14px;
            letter-spacing: 0.5px;
            text-align: justify;
            word-spacing: 0px;
        }
    </style>
    <body>
        <?php include './navbar.php'; ?>
        <div class="ym-wrapper">
          <div class="ym-wbox">
              <div>
                <div class="row aniview" av-animation="slideInRight">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading" id="summaryHeading">
                                <h3 class="panel-title">●&nbsp;  Motivation</h3>
                            </div>
                            <div class="panel-body">
                                <p>Alternative polyadenylation (APA) is an important layer of gene regulation that produces mRNAs with different 3’ end and/or encoding variable protein isoforms. Up to 70% of annotated genes in Arabidopsis Thaliana and rice undergo APA. The increasing amounts of poly(A) sites in various plant species is placing new demands on the methods and tools applied for data access and mining.</p>
                            </div>
                        </div>
                    </div>
                </div><br>
                  <div class="row aniview" av-animation="slideInLeft">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading" id="summaryHeading">
                                <h3 class="panel-title">●&nbsp;  What is PlantAPA?</h3>
                            </div>
                            <div class="panel-body">
                                <p>PlantAPA is a web server for query, visualization, and analysis of poly(A) sites in plants, which can profile heterogeneous cleavage sites and quantify expression pattern of poly(A) sites across different conditions. To date, PlantAPA provides the largest database of APA in plants, including rice, Arabidopsis, <i>Medicago truncatula</i>, and <i>Chlamydomonas reinhardtii</i> (see <a href="./search.php">datasets</a>).</p>
                            </div>
                        </div>
                    </div>
                </div><br>
                  <div class="row aniview" av-animation="fadeInUp">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading" id="summaryHeading">
                                <h3 class="panel-title">●&nbsp;  Getting started</h3>
                            </div>
                            <div class="panel-body">
                                <h6>(1) Extracting poly(A) sites from uploaded sequences</h6>
                                <p>You can upload short reads, ESTs, or poly(A) site coordinates for poly(A) site extraction. Upon the complete of the pipeline, the extracted poly(A) sites can be visualized in PlantAPA, or pooled with PlantAPA stored poly(A) sites for APA analysis.</p>
                                <p>To start a task for poly(A) site extraction, click <a href="./upload_option.php">here</a>. A task ID will be assigned for each task. You can retrieve the result of your task at our <a href="./task.php">Data Retrieval Page</a>. You can also view all tasks you started at the <a href="./task.php">Data Retrieval Page</a>. Click <a href="./help.php">here</a> for a step by step guide. You can browse a demo task <a href="./demo.php?method=trap">here</a>.</p>
                                <h6>(2) Searching PlantAPA and exporting sequences</h6>
                                <p>Search by a variety of keywords, such as gene ID, chromosome fragment, gene functions, GO term, and GO ID. Examples: <a href="./sequence_detail.php?species=arab&seq=AT1G02130">AT1G02130</a> (gene ID, Arabidopsis), also <a href="./search_result.php?method=fuzzy&keyword=floral&species=arab">floral</a> (any key word in the search box of menu bar), <a href="./sequence_detail.php?species=mtr&seq=Medtr2g105570">Medtr2g105570</a> (Medicago).</p>
                                <p>After searching, the <a href="./search_result.php?method=fuzzy&keyword=floral&species=arab">result</a> page provides a list of poly(A) sites and a tool bar for exporting various kinds of sequences onto local computers for other analysis purpose. Particularly, poly(A) sites in previously overlooked genomic regions such as extended 3’ UTR regions, ambiguous regions, and intergenic regions are also listed.</p>
                                <h6>(3) Browsing PACs in the PAC browser</h6>
                                <p>You can have a quick access to the PAC browser by clicking the "<a href="./browse.php">PAC browse</a>" tab in the main menu or the “View” link in a <a href="./search_result.php?method=fuzzy&keyword=floral&species=arab">PAC list</a>. One or more data sets from each plant species can be quickly loaded and graphically browsed online, by selecting the checkboxes of data sets in the ‘Tracks’ panel. Examples: <a href="../jbrowse/?data=data/arab&loc=Chr5:16395507..16399467&tracks=Gene annotation,DNA,APAplant stored PAC">AT5G40910</a> (Arabidopsis), also <a href="../jbrowse/?data=data/mtr&loc=chr2:45520733..45526107&tracks=Gene annotation,DNA,APAplant stored PAC">Medtr2g105570</a> (Medicago).</p>
                                <h6>(4) Quantification and visualization of PACs across different conditions</h6>
                                <p>By following the web link on a particular PAC or gene, a user can inspect various graphics and detailed information of the PACs in a gene or in a intergenic region, such as gene/PAC sequence, poly(A) signals, and PAT distributions across diverse conditions in the PAC viewer module.</p>
                                <h6>(5) Analysis of APA switching between two conditions</h6>
                                <p>Following the “<a href="./analysis.php">PAC analysis</a>” tab in the main menu, users can choose to generate lists of differentially expressed genes, PACs with differential usage, genes with 3’ UTR lengthening or shortening, and APA-site switching genes, using PlantAPA stored PACs together with the user uploaded PACs (if any). Click here to see a result page of APA-site switching genes.</p>
                            </div>
                        </div>
                    </div>
                </div><br>
                  <div class="row aniview" av-animation="slideInUp">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading" id="summaryHeading">
                                <h3 class="panel-title">●&nbsp;  Finding out more</h3>
                            </div>
                            <div class="panel-body">
                                <p>The <a href="./help.php">Help section</a> provides more information, and the links at the top of the page will let you launch different modules. The <a href=./download.php>Download page</a> lets you get links to data set(s) of interest.
                                <br><br><br>PlantAPA was developed by the bioinformatics group at Xiamen University. If you have any questions or comments, please email to xhuister@xmu.edu.cn (Dr. Xiaohui Wu).</p>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
          </div>
        </div>
        <?php
            include"./footer.php";
        ?>
    </body>
</html>
