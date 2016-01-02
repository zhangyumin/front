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
        <script src="./src/jquery-1.10.1.min.js"></script>
    </head>
    <body>
        <?php include './navbar.php'; ?>
        <div class="ym-wrapper">
          <div class="ym-wbox">
            <div class="box info" id="introduction">
              <h2>PlantAPA: a portal for visualization and analysis of alternative polyadenylation in plants</h2>
              <div id="text">
                  <h5>●&nbsp;  Motivation</h5>
                  <p class="elegant"></p>
                  <p class="elegant" style="color: #000;text-transform: none;">Alternative polyadenylation (APA) is an important layer of gene regulation that produces mRNAs with different 3’ end and/or encoding variable protein isoforms. Up to 70% of annotated genes in Arabidopsis Thaliana and rice undergo APA. The increasing amounts of poly(A) sites in various plant species is placing new demands on the methods and tools applied for data access and mining.</p>
                  <br>
                  <h5>●&nbsp;  What is PlantAPA?</h5>
                  <p class="elegant"></p>
                  <p class="elegant" style="color: #000;text-transform: none;">PlantAPA is a web server for query, visualization, and analysis of poly(A) sites in plants, which can profile heterogeneous cleavage sites and quantify expression pattern of poly(A) sites across different conditions. To date, PlantAPA provides the largest database of APA in plants, including rice, Arabidopsis, Medicago truncatula, and Chlamydomonas reinhardtii (see data sets).</p>
                  <br>
                  <h5>●&nbsp;  Getting started</h5>
                  <h6>(1) Extracting poly(A) sites from uploaded sequences</h6>
                  <p class="elegant"></p>
                  <p class="elegant" style="color: #000;text-transform: none;">You can upload short reads, ESTs, or poly(A) site coordinates for poly(A) site extraction. Upon the complete of the pipeline, the extracted poly(A) sites can be visualized in PlantAPA, or pooled with PlantAPA stored poly(A) sites for APA analysis.</p>
                  <p class="elegant" style="color: #000;text-transform: none;">To start a task for poly(A) site extraction, click here. A task ID will be assigned for each task. You can retrieve the result of your task at our Data Retrieval Page. You can also view all tasks you started at the Data Retrieval Page. Click here for a step by step guide. You can browse a demo task here.</p>
                  <br>
                  <h6>(2) Searching PlantAPA and exporting sequences</h6>
                  <p class="elegant"></p>
                  <p class="elegant" style="color: #000;text-transform: none;">Search by a variety of keywords, such as gene ID, chromosome fragment, gene functions, GO term, and GO ID. Examples: XXX (gene ID, Arabidopsis), also XXX (GO ID), XXX (Medicago), Chr1~100..10000 (Chlamy).</p>
                  <p class="elegant" style="color: #000;text-transform: none;">After searching, the result page provides a list of poly(A) sites and a tool bar for exporting various kinds of sequences onto local computers for other analysis purpose. Particularly, poly(A) sites in previously overlooked genomic regions such as extended 3’ UTR regions, ambiguous regions, and intergenic regions are also listed.</p>
                  <br>
                  <h6>(3) Browsing PACs in the PAC browser</h6>
                  <p class="elegant"></p>
                  <p class="elegant" style="color: #000;text-transform: none;">You can have a quick access to the PAC browser by clicking the “PAC browse” tab in the main menu or the “View” link in a PAC list. One or more data sets from each plant species can be quickly loaded and graphically browsed online, by selecting the checkboxes of data sets in the ‘Tracks’ panel. Examples: XXX (Arabidopsis), also XXX (Medicago).</p>
                  <br>
                  <h6>(4) Quantification and visualization of PACs across different conditions</h6>
                  <p class="elegant"></p>
                  <p class="elegant" style="color: #000;text-transform: none;">By following the web link on a particular PAC or gene, a user can inspect various graphics and detailed information of the PACs in a gene or in a intergenic region, such as gene/PAC sequence, poly(A) signals, and PAT distributions across diverse conditions in the PAC viewer module.</p>
                  <br>
                  <h6>(5) Analysis of APA switching between two conditions</h6>
                  <p class="elegant"></p>
                  <p class="elegant" style="color: #000;text-transform: none;">Following the “PAC analysis” tab in the main menu, users can choose to generate lists of differentially expressed genes, PACs with differential usage, genes with 3’ UTR lengthening or shortening, and APA-site switching genes, using PlantAPA stored PACs together with the user uploaded PACs (if any). Click here to see a result page of APA-site switching genes.</p>
                  <br>
                  <h5>●&nbsp;  Finding out more</h5>
                  <p class="elegant"></p>
                  <p class="elegant" style="color: #000;text-transform: none;">The Help section provides more information, and the links at the top of the page will let you launch different modules. The Download page lets you get links to data set(s) of interest.
                  <br><br><br>PASPA was developed by the bioinformatics group at Xiamen University. If you have any questions or comments, please email to xhuister@xmu.edu.cn (Dr. Xiaohui Wu).</p>
              </div>
            </div>
          </div>
        </div>
        <?php
            include"./footer.php";
        ?>
    </body>
</html>
