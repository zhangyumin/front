<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>PlantAPA-Download</title>
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
            <h2 style="border-bottom: 2px #5db95b solid;padding: 15px 0px 0px 0px;margin-bottom: 0px;text-align: left">
                <font color="#224055" ><b>Datasets</b></font>
            </h2>
            <table  class="bordertable">
                <thead>
                    <tr>
                        <th>Species</th>
                        <th>Label</th>
                        <th>Cell Line</th>
                        <th>Tissue</th>
                        <th>Reference</th>
                        <th>Genome Annotation</th>
                        <th>PATs</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>wt leaf 1</td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_leaf_1%20PAT%20minus%20strand%2Cwt_leaf_1%20PAT%20plus%20strand&highlight=' target='_blanck'>1110951</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>wt leaf 2</td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1073%2Fpnas.1019732108">Wu et al. PNAS, 2011</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_leaf_2%20PAT%20minus%20strand%2Cwt_leaf_2%20PAT%20plus%20strand&highlight=' target='_blanck'>695258</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>wt leaf 3</td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_leaf_3%20PAT%20minus%20strand%2Cwt_leaf_3%20PAT%20plus%20strand&highlight=' target='_blanck'>22176</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>wt seed 1</td>
                        <td>WT</td>
                        <td>seed</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_seed_1%20PAT%20minus%20strand%2Cwt_seed_1%20PAT%20plus%20strand&highlight=' target='_blanck'>21416</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>wt seed 2</td>
                        <td>WT</td>
                        <td>seed</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1073%2Fpnas.1019732108">Wu et al. PNAS, 2011</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_seed_2%20PAT%20minus%20strand%2Cwt_seed_2%20PAT%20plus%20strand&highlight=' target='_blanck'>1314335</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>wt root 1</td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_root_1%20PAT%20minus%20strand%2Cwt_root_1%20PAT%20plus%20strand&highlight=' target='_blanck'>16229894</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>wt root 2</td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_root_2%20PAT%20minus%20strand%2Cwt_root_2%20PAT%20plus%20strand&highlight=' target='_blanck'>10703765</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>wt root 3</td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_root_3%20PAT%20minus%20strand%2Cwt_root_3%20PAT%20plus%20strand&highlight=' target='_blanck'>12541261</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>oxt6 root 1</td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_root_1%20PAT%20minus%20strand%2Coxt6_root_1%20PAT%20plus%20strand&highlight=' target='_blanck'>977997</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>oxt6 root 2</td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_root_2%20PAT%20minus%20strand%2Coxt6_root_2%20PAT%20plus%20strand&highlight=' target='_blanck'>76768</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>oxt6 root 3</td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_root_3%20PAT%20minus%20strand%2Coxt6_root_3%20PAT%20plus%20strand&highlight=' target='_blanck'>2176288</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>oxt6 leaf 1</td>
                        <td>Oxt6</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_leaf_1%20PAT%20minus%20strand%2Coxt6_leaf_1%20PAT%20plus%20strand&highlight=' target='_blanck'>6092063</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>oxt6 leaf 2</td>
                        <td>Oxt6</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_leaf_2%20PAT%20minus%20strand%2Coxt6_leaf_2%20PAT%20plus%20strand&highlight=' target='_blanck'>10589959</a></td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td>oxt6 leaf 3</td>
                        <td>Oxt6</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_leaf_3%20PAT%20minus%20strand%2Coxt6_leaf_3%20PAT%20plus%20strand&highlight=' target='_blanck'>5875054</a></td>
                    </tr>
                    <tr>
                        <td>Oryza sativa</td>
                        <td>from EST</td>
                        <td>mix</td>
                        <td>mix</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1093%2Fnar%2Fgkn158">Shen et al. Plant Cell, 2012</a></td>
                        <td><a href='http://rice.plantbiology.msu.edu/' target='_blanck'>MSU v7</a></td>
                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cfrom_EST%20PAT%20minus%20strand%2Cfrom_EST%20PAT%20plus%20strand&highlight=' target='_blanck'>57852</a></td>
                    </tr>
                    <tr>
                        <td>Medicago truncatula</td>
                        <td>wt leaf</td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1186%2F1471-2164-15-615">Wu et al. BMC Genomics, 2014</a></td>
                        <td><a href='http://medicago.jcvi.org/medicago/index.php#' target="_blank">JCVI Medtr v4</a></td>
                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cwt_leaf_1%20PAT%20minus%20strand%2Cwt_leaf_1%20PAT%20plus%20strand&highlight=' target="_blank">2747920</a></td>
                    </tr>
                    <tr>
                        <td>Chlamydomonas reinhardtii</td>
                        <td>from illumina</td>
                        <td>mix</td>
                        <td>mix</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                        <td><a href='https://phytozome.jgi.doe.gov/pz/portal.html#!info?alias=Org_Creinhardtii' target="_blank">Creinhardtii 281 v55</a></td>
                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2Cfrom_illumina%20PAT%20plus%20strand%2Cfrom_illumina%20%20PAT%20minus%20strand&highlight=' target="_blank">622248</a></td>
                    </tr>
                    <tr>
                        <td>Chlamydomonas reinhardtii</td>
                        <td>from 454</td>
                        <td>mix</td>
                        <td>mix</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                        <td><a href='https://phytozome.jgi.doe.gov/pz/portal.html#!info?alias=Org_Creinhardtii' target="_blank">Creinhardtii 281 v55</a></td>
                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2CGene%20annotation%2CFrom_454%20PAT%20minus%20strand%2CFrom_454%20PAT%20plus%20strand&highlight=' target="_blank">324305</a></td>
                    </tr>
                    <tr>
                        <td>Chlamydomonas reinhardtii</td>
                        <td>from EST</td>
                        <td>mix</td>
                        <td>mix</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                        <td><a href='https://phytozome.jgi.doe.gov/pz/portal.html#!info?alias=Org_Creinhardtii' target="_blank">Creinhardtii 281 v55</a></td>
                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2CGene%20annotation%2CFrom_EST%20PAT%20minus%20strand%2CFrom_EST%20PAT%20plus%20strand&highlight=' target="_blank">56754</a></td>
                    </tr>
                    <tr>
                        <td>Chlamydomonas reinhardtii</td>
                        <td>from PAT-seq</td>
                        <td>mix</td>
                        <td>mix</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0146107">Bell et al. PloS one, 2016</a></td>
                        <td><a href='https://phytozome.jgi.doe.gov/pz/portal.html#!info?alias=Org_Creinhardtii' target="_blank">Creinhardtii 281 v55</a></td>
                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2CGene%20annotation%2CFrom_PATseq%20PAT%20plus%20strand%2CFrom_PATseq%20%20PAT%20minus%20strand&highlight=' target="_blank">12532698</a></td>
                    </tr>
                </tbody>
            </table><br>
        </div>
        <div class="download">
            <h2 style="border-bottom: 2px #5db95b solid;padding: 15px 0px 0px 0px;margin-bottom: 0px;text-align: left">
                <font color="#224055" ><b>Demos</b></font>
            </h2>
            <table  class="bordertable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Size</th>
                    <th>Download</th>
                </tr>
                </thead>
                <tr>
                    <td>Ath.demo.PA</td>
                    <td>poly(A) site file for PAC Trap (Arabidopsis thaliana)</td>
                    <td>145.8KB</td>
                    <td><a href="./download_data.php?type=1&name=arab.PA"/>Click here</td>
                </tr>
                <tr>
                    <td>Ath.demo.NGS.fq</td>
                    <td>short read for PAC Trap (Arabidopsis thaliana)</td>
                    <td>25.5MB</td>
                    <td><a href="./download_data.php?type=1&name=arab_sample_100k.fastq"/>Click here</td>
                </tr>
                <tr>
                    <td>Ath.demo.EST.fa</td>
                    <td>EST file for PAC Trap (Arabidopsis thaliana)</td>
                    <td>6.2MB</td>
                    <td><a href="./download_data.php?type=1&name=AtEST.demo.fa"/>Click here</td>
                </tr>
                <tr>
                    <td>Rice_japonica1.demo.fa</td>
                    <td>short read for PAC Trap (Japonica rice)</td>
                    <td>636.3KB</td>
                    <td><a href="./download_data.php?type=1&name=rice_japonica1.fa"/>Click here</td>
                </tr>
                <tr>
                    <td>Rice_japonica2.demo.fa</td>
                    <td>short read for PAC Trap (Japonica rice)</td>
                    <td>638.8KB</td>
                    <td><a href="./download_data.php?type=1&name=rice_japonica2.fa"/>Click here</td>
                </tr>
                <tr>
                    <td>Rice_japonica_3.demo.fa</td>
                    <td>short read for PAC Trap (Japonica rice)</td>
                    <td>6.4MB</td>
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
                    . "<h2 style=\"border-bottom: 2px #5db95b solid;padding: 15px 0px 0px 0px;margin-bottom: 0px;text-align: left\"><font color=\"#224055\" ><b>Results</b></font></h2>"
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
