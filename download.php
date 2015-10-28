<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Download</title>
        <link href="./src/index.css" rel="stylesheet" type="text/css" />
        <script src="./src/jquery-2.0.0.min.js"></script>
        <script src="./src/jquery.slides.min.js"></script>
        <!-- Mobile viewport optimisation -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="js/wowslider/style.css" />
        <script type="text/javascript" src="js/wowslider/jquery.js"></script>

        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <!--[if lte IE 7]>
        <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <!--[if lt IE 9]>
        <script src="./js/html5shiv/html5shiv.js"></script>
        <![endif]-->

        <!--<link rel="stylesheet" href="./src/font-awesome.min.css">-->
        <!--<link rel="stylesheet" href="./src/example.css">-->
 
    </head>
    <body>
        <?php
            include"./navbar.php";
        ?>
    <div class="ym-wrapper">
        <div class="ym-wbox">
        <div class="download">
            <h1 class="center">Tools</h1>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Size</th>
                    <th>Release date</th>
                    <th>Download</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Perl</td>
                    <td>alterPA,pa2pac,switchgene and so on</td>
                    <td>90.8KB</td>
                    <td>2015-7-31</td>
                    <td><a href="./download_data.php?type=1&name=Perl.zip"/>Click here</td>
                </tr>
                <tr>
                    <td>R</td>
                    <td>DeGene,DePAC,SwitchFU and so on</td>
                    <td>773KB</td>
                    <td>2015-7-31</td>
                    <td><a href="./download_data.php?type=1&name=R.zip"/>Click here</td>
                </tr>
                </tbody>
            </table><br>
        
        </div>
        <br>
        <div class="download">
            <h1 class="center">Datasets</h1>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Size</th>
                    <th>File type</th>
                    <th>Download</th>
                </tr>
                </thead>
                <tr>
                    <td>Arabidopsis thaliana</td>
                    <td>sequence</td>
                    <td>333MB</td>
                    <td>GFF3</td>
                    <td><a href="./download_data.php?type=1&name=Arabidopsis_thaliana.TAIR10.23.gff3"/>Click here</td>
                </tr>
            </table><br>
        </div>
    </div>
    </div>
        <br>
        <?php
            session_start();
            if($_GET['data']!=NULL){
                $hexamer=  glob("./result/".$_SESSION['file']."/db_user.PAC_".$_SESSION['file'].".SQL.400nt.".$_SESSION['file'].".PAT*sort_once")[0];
                $hexamer = substr($hexamer, 28);
                $snc=  glob("./result/".$_SESSION['file']."/db_user.PAC_".$_SESSION['file'].".SQL.400nt.".$_SESSION['file'].".PAT*.cnt")[0];
                $snc = substr($snc, 28);
                echo "<div class=\"table-result\" style=\"border-style: dotted;border-color: #366fa5;width:80%;margin: auto;\">"
                . "<fieldset class=\"download\" style=\"text-align: center;min-width: 100%;\">"
                    . " <legend>"
                    . "<span style=\"margin:auto;\">"
                    . "<font color=\"#224055\" size=\"18px;\"><b>Results</b></font>"
                    . "</span>"
                    . "</legend>"
                    . "<table style=\"margin:auto;border-color: #87cefa;border:2px;\" border rules='rows'>"
                    . "<tr>"
                    . "<td>Name</td>"
                    . "<td>Description</td>"
                    . "<td>File Type</td>"
                    . "<td>Download</td>"
                    . "</tr>"
                    . "<tr>";
                echo "<td>hexamer</td>"
                    . "<td>hexamer statistics</td>"
                    . "<td>txt</td>"
                    . "<td><a href=\"./download_data.php?type=3&name=$hexamer\"/>Click here</td>"
                    . "</tr>";
                echo "<td>Single Nucleotide</td>"
                    . "<td>Single Nucleotide Compositions</td>"
                    . "<td>cnt</td>"
                    . "<td><a href=\"./download_data.php?type=3&name=$snc\"/>Click here</td>"
                    . "</tr>";
                foreach ($_SESSION['file_real'] as $key =>$value){
                    echo "<td>$value</td>"
                    . "<td>polyA site file</td>"
                    . "<td>PA</td>"
                    . "<td><a href=\"./download_data.php?type=2&name=$value.qc.fa.noT.fa.sam.M30S10.PA\"/>Click here</td>"
                    . "</tr>";
                    echo "<td>$value</td>"
                    . "<td>PAT file</td>"
                    . "<td>PAT</td>"
                    . "<td><a href=\"./download_data.php?type=2&name=$value.qc.fa.noT.fa.sam.M30S10.PA_PAT\"/>Click here</td>"
                    . "</tr>";
                }
                    echo " </table><br>"
                    . "</fieldset>"
                    . "</div><br>";
            }
        ?>
        <?php
            include"./footer.php";
          ?>
    </body>
</html>
