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
                  <p class="elegant" style="color: #000;text-transform: none;">PAlternative polyadenylation (APA) is an important layer of gene regulation that produces mRNAs with different 3’ end and/or encoding variable protein isoforms. Up to 70% of annotated genes in Arabidopsis Thaliana and rice undergo APA. The increasing amounts of poly(A) sites in various plant species is placing new demands on the methods and tools applied for data access and mining.</p>
                  <br>
                  <h5>●&nbsp;  What is PlantAPA?</h5>
                  <p class="elegant"></p>
                  <p class="elegant" style="color: #000;text-transform: none;">PlantAPA is a web server for query, visualization, and analysis of poly(A) sites in plants, which can profile heterogeneous cleavage sites and quantify expression pattern of poly(A) sites across different conditions. To date, PlantAPA provides the largest database of APA in plants, including rice, Arabidopsis, Medicago truncatula, and Chlamydomonas reinhardtii (see data sets).</p>
                  <br>
                  <h5>●&nbsp;  Getting started</h5>
                  <p class="elegant"></p>
                  <p class="elegant" style="color: #000;text-transform: none;">PlantAPA is a web server for query, visualization, and analysis of poly(A) sites in plants, which can profile heterogeneous cleavage sites and quantify expression pattern of poly(A) sites across different conditions. To date, PlantAPA provides the largest database of APA in plants, including rice, Arabidopsis, Medicago truncatula, and Chlamydomonas reinhardtii (see data sets).</p>
                  <br>
              </div>
            </div>
          </div>
        </div>
        <?php
            include"./footer.php";
        ?>
    </body>
</html>
