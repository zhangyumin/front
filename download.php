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
        <link href="./src/navbar.css" rel="stylesheet"/>
        <style>
            td{
                padding: 20px !important;
                font-family: 'Latos',sans-serif;
            }
        </style>
    </head>
    <body>
    <script src="./src/jquery-2.0.0.min.js"></script>
        <?php
            include"./navbar.php";
        ?>
        <br>
        <div class="table-tools" style="border-style: dotted;border-color: #366fa5;width:80%;margin: auto;">
        <fieldset class="download" style="text-align: center;min-width: 100%;">
            <legend>
                <span style="margin:auto;">
                            <font color="#224055" size="18px;"><b>Tools</b></font>
                </span>
            </legend>
            <table style="margin:auto;border-color: #87cefa;border:2px;" border rules='rows'>
                <tr>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Size</td>
                    <td>Release date</td>
                    <td>Download</td>
                </tr>
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
            </table><br>
        </fieldset>
        </div><br>
        <div class="table-dataset" style="border-style: dotted;border-color: #366fa5;width:80%;margin: auto;">
        <fieldset class="download" style="text-align: center;min-width: 100%;">
            <legend>
                <span style="margin:auto;">
                            <font color="#224055" size="18px;"><b>Datasets</b></font>
                </span>
            </legend>
            <table style="margin:auto;border-color: #87cefa;border:2px;" border rules='rows'>
                <tr>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Size</td>
                    <td>File type</td>
                    <td>Download</td>
                </tr>
                <tr>
                    <td>Arabidopsis thaliana</td>
                    <td>sequence</td>
                    <td>333MB</td>
                    <td>GFF3</td>
                    <td><a href="./download_data.php?type=1&name=Arabidopsis_thaliana.TAIR10.23.gff3"/>Click here</td>
                </tr>
            </table><br>
        </fieldset>
        </div><br>
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
            include"./wheelmenu.php";
        ?>
        <?php
            include"./footer.php";
          ?>
    </body>
</html>
