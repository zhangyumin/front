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
<!--        <div class="download">
            <h1 class="center">Tools</h1>
            <table  class="bordertable">
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
        <br>-->
        <div class="download">
            <h2 style="border-bottom: 2px #5499c9 solid;padding: 15px 0px 0px 0px;margin-bottom: 0px;text-align: left">
                <font color="#224055" ><b>Datasets</b></font>
            </h2>
            <table  class="bordertable">
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
                    <td>Japonica rice</td>
                    <td>sequence</td>
                    <td>81.5MB</td>
                    <td>GFF3</td>
                    <td><a href="./download_data.php?type=1&name=japonica.gff3"/>Click here</td>
                </tr>
                <tr>
                    <td>Arabidopsis thaliana</td>
                    <td>sequence</td>
                    <td>66.2MB</td>
                    <td>GFF3</td>
                    <td><a href="./download_data.php?type=1&name=arab.gff3"/>Click here</td>
                </tr>
                <tr>
                    <td>Medicago truncatula</td>
                    <td>sequence</td>
                    <td>63.7MB</td>
                    <td>GFF3</td>
                    <td><a href="./download_data.php?type=1&name=mtr.gff3"/>Click here</td>
                </tr>
                <tr>
                    <td>Chlamydomonas reinhardtii (Green alga)</td>
                    <td>sequence</td>
                    <td>33.5MB</td>
                    <td>GFF3</td>
                    <td><a href="./download_data.php?type=1&name=chlamy.gff3"/>Click here</td>
                </tr>
            </table><br>
        </div>
        <div class="download">
            <h2 style="border-bottom: 2px #5499c9 solid;padding: 15px 0px 0px 0px;margin-bottom: 0px;text-align: left">
                <font color="#224055" ><b>Demos</b></font>
            </h2>
            <table  class="bordertable">
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
                    <td>arab</td>
                    <td>poly(A) site file for PAC Trap(Arabidopsis thaliana)</td>
                    <td>145.8KB</td>
                    <td>PA</td>
                    <td><a href="./download_data.php?type=1&name=arab.PA"/>Click here</td>
                </tr>
                <tr>
                    <td>arab_sample_100k</td>
                    <td>short read for PAC Trap(Arabidopsis thaliana)</td>
                    <td>25.5MB</td>
                    <td>fastq</td>
                    <td><a href="./download_data.php?type=1&name=arab_sample_100k.fastq"/>Click here</td>
                </tr>
                <tr>
                    <td>AtEST.demo</td>
                    <td>EST file for PAC Trap(Arabidopsis thaliana)</td>
                    <td>6.2MB</td>
                    <td>fa</td>
                    <td><a href="./download_data.php?type=1&name=AtEST.demo.fa"/>Click here</td>
                </tr>
                <tr>
                    <td>rice_japonica1</td>
                    <td>short read for PAC Trap(Japonica rice)</td>
                    <td>636.3KB</td>
                    <td>fa</td>
                    <td><a href="./download_data.php?type=1&name=rice_japonica1.fa"/>Click here</td>
                </tr>
                <tr>
                    <td>rice_japonica2</td>
                    <td>short read for PAC Trap(Japonica rice)</td>
                    <td>638.8KB</td>
                    <td>fa</td>
                    <td><a href="./download_data.php?type=1&name=rice_japonica2.fa"/>Click here</td>
                </tr>
                <tr>
                    <td>rice_japonica_all</td>
                    <td>short read for PAC Trap(Japonica rice)</td>
                    <td>6.4MB</td>
                    <td>fa</td>
                    <td><a href="./download_data.php?type=1&name=rice_japonica_all.fa"/>Click here</td>
                </tr>
            </table><br>
        </div>
        <br>
        <?php
            session_start();
            if($_GET['data']!=NULL){
                $hexamer=  glob("./result/".$_SESSION['file']."/db_user.PAC_".$_SESSION['file'].".SQL.400nt.".$_SESSION['file'].".PAT*sort_once")[0];
                $hex=explode("/", $hexamer);
//                var_dump($hex);
                $hexamer = $hex[3];
                $snc=  glob("./result/".$_SESSION['file']."/db_user.PAC_".$_SESSION['file'].".SQL.400nt.".$_SESSION['file'].".PAT*.cnt")[0];
                $snc_array= explode("/", $snc);
                $snc=$snc_array[3];
                echo "<div class=\"download\">"
                    . "<h2 style=\"border-bottom: 2px #5499c9 solid;padding: 15px 0px 0px 0px;margin-bottom: 0px;text-align: left\"><font color=\"#224055\" ><b>Results</b></font></h2>"
                    . "<table class='bordertable'>"
                    . "<thead>"
                    . "<tr>"
                    . "<th>Name</th>"
                    . "<th>Description</th>"
                    . "<th>File Type</th>"
                    . "<th>Download</th>"
                    . "</tr>"
                    . "</thead>"
                    . "<tbody>"
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
                    echo " </tbody></table><br>"
                    . "</div><br>";
            }
        ?>
    </div>
    </div>
        <?php
            include"./footer.php";
          ?>
    </body>
</html>
