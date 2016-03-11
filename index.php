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
    </head>
    <body>
        <?php include './navbar.php'; ?>
        <div class="ym-wrapper">
            <div class="ym-wbox">
                <div id="floor1">
                    <div id="info" style="float:left;background-color: #eee;width: 70%;border-radius: 20px">
                        <div style="padding:20px">
                            <img src="./pic/logo.jpg" style="float:left"> 
                            <h1 style="padding:20px 0px 0px 0px;">Welcome to PlantAPA!</h1>
                            <hr style="margin:10px 0px;border-top: 1px solid #d5d5d5;clear: both">
                            <p style="font-size:19px;word-spacing:1px; letter-spacing: 1px;color: #000">PlantAPA is a web server for query, visualization, and analysis of poly(A) sites in plants, which can profile heterogeneous cleavage sites and quantify expression pattern of poly(A) sites across different conditions. To date, PlantAPA provides the largest database of APA in plants, including rice, Arabidopsis, Medicago truncatula, and Chlamydomonas reinhardtii.</p>
                            <p style="float:right;"><a href="./info.php" style="background-color:#5db95b;color:#fff;padding: 6px 12px;font-size: 12px;text-align: center;vertical-align: middle;border-radius: 4px;">More details</a></p>
                        </div>  
                    </div>
                    <div id="news" style="float: right;width: 30%">
                        <div style="padding:20px;font-size: 17px">
                            <h2 style="border-bottom:1px solid #5db95b;">What's new</h2>
                            <div style="background-color:#eee;border-radius: 4px">
                                Version 1.2.0&emsp;&emsp;&emsp;&emsp;&emsp;2016-03-12
                            <ul>
                                <li>Adjust the layout of the page</li>
                                <li>Add new sample("from PAT-seq") in chlamy</li>
                            </ul><br>
                                Version 1.1.0&emsp;&emsp;&emsp;&emsp;&emsp;2015-10-12
                            <ul>
                                <li>Add function of <a href="analysis.php">data analysis</a> </li>
                            </ul><br>
                                Version 1.0.0&emsp;&emsp;&emsp;&emsp;&emsp;2015-06-01
                            <ul>
                                <li>Release first version of PlantAPA</li>
                            </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="clear: both"></div>
            </div>
        </div>
        <?php
            include"./footer.php";
        ?>
    </body>
</html>
