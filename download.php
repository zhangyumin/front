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
        <script type="text/javascript">
            function GetDetail(a){
                var id = a.id+"_detail";
                console.log(id);
                $('#'+id).slideToggle();
            }
        </script>
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
                <font color="#224055" ><b>Downloading datasets in PlantAPA</b></font>
                <a href="help.php#download" target="_blank">
                    <img title="Get help for downloading" style="width:20px;height: 20px;display: inline-block" src="./pic/help.png">
                </a>
            </h2>
            <table  class="bordertable">
                <thead>
                    <tr>
                        <th width="10%">Species</th>
                        <th>Label</th>
                        <th>Cell Line</th>
                        <th>Tissue</th>
                        <th>Reference</th>
                        <th>Genome Annotation</th>
                        <th>PATs</th>
                        <th>Download Data</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line1' onclick="GetDetail(this)" style="cursor:pointer;color:blue">wt leaf 1</a></td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Cwt_leaf_1%20PAT%20minus%20strand%2Cwt_leaf_1%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>1055461</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf1_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs">PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf1_PAC" title="File of poly(A) site clusters">PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf1_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)">Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf1_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)">PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line1_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Plants were grown in soil in a climate-controlled growth room under short-day (8-h daylight) conditions, or under sterile conditions by germinating seeds, to capture as broad a range of poly(A) sites in leaves as possible.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line2' onclick="GetDetail(this)" style="cursor:pointer;color:blue">wt leaf 2</a></td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1073%2Fpnas.1019732108">Wu et al. PNAS, 2011</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Cwt_leaf_2%20PAT%20minus%20strand%2Cwt_leaf_2%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>927476</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf2_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs">PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf2_PAC" title="File of poly(A) site clusters">PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf2_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)">Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf2_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)">PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line2_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Plants were grown in soil in a climate-controlled growth room under short-day (8-h daylight) conditions, or under sterile conditions by germinating seeds, to capture as broad a range of poly(A) sites in leaves as possible.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line3' onclick="GetDetail(this)" style="cursor:pointer;color:blue">wt leaf 3</a></td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Cwt_leaf_3%20PAT%20minus%20strand%2Cwt_leaf_3%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>24269</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf3_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs">PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf3_PAC" title="File of poly(A) site clusters">PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf3_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)">Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_leaf3_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)">PAT Track</a></td>
                    </tr>
                     <tr>
                        <td id='line3_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Plants were grown in soil in a climate-controlled growth room under short-day (8-h daylight) conditions, or under sterile conditions by germinating seeds, to capture as broad a range of poly(A) sites in leaves as possible.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line4' onclick="GetDetail(this)" style="cursor:pointer;color:blue">wt seed 1</a></td>
                        <td>WT</td>
                        <td>seed</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Cwt_seed_1%20PAT%20minus%20strand%2Cwt_seed_1%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>332010</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_WT_seed1_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_seed1_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_seed1_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_seed1_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line4_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: RNA was isolated from dried Arabidopsis seed.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line5' onclick="GetDetail(this)" style="cursor:pointer;color:blue">wt seed 2</a></td>
                        <td>WT</td>
                        <td>seed</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1073%2Fpnas.1019732108">Wu et al. PNAS, 2011</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Cwt_seed_2%20PAT%20minus%20strand%2Cwt_seed_2%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>2230409</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_WT_seed2_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_seed2_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_seed2_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_seed2_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line5_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: RNA was isolated from dried Arabidopsis seed.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line6' onclick="GetDetail(this)" style="cursor:pointer;color:blue">wt root 1</a></td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Cwt_root_1%20PAT%20minus%20strand%2Cwt_root_1%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>16232735</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root1_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root1_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root1_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root1_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line6_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line7' onclick="GetDetail(this)" style="cursor:pointer;color:blue">wt root 2</a></td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Cwt_root_2%20PAT%20minus%20strand%2Cwt_root_2%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>100700327</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root2_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root2_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root2_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root2_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line7_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line8' onclick="GetDetail(this)" style="cursor:pointer;color:blue">wt root 3</a></td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Cwt_root_3%20PAT%20minus%20strand%2Cwt_root_3%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>12547544</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root3_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root3_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root3_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_WT_root3_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line8_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line9' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6 root 1</a></td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Coxt6_root_1%20PAT%20minus%20strand%2Coxt6_root_1%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>5861620</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root1_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root1_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root1_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root1_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line9_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: A mutant deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line10' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6 root 2</a></td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Coxt6_root_2%20PAT%20minus%20strand%2Coxt6_root_2%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>10174020</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root2_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root2_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root2_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root2_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line10_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: A mutant deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line11' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6 root 3</a></td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Coxt6_root_3%20PAT%20minus%20strand%2Coxt6_root_3%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>5653903</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root3_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root3_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root3_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_root3_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line11_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: A mutant deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line12' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6 leaf 1</a></td>
                        <td>Oxt6</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Coxt6_leaf_1%20PAT%20minus%20strand%2Coxt6_leaf_1%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>1222315</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf1_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf1_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf1_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf1_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line12_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: A mutant deﬁcient in CPSF30 expression. Seedlings were germinated and grown in growth chambers set at 22 ℃ under continuous light.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line13' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6 leaf 2</a></td>
                        <td>Oxt6</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Coxt6_leaf_2%20PAT%20minus%20strand%2Coxt6_leaf_2%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>74991</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf2_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf2_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf2_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf2_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line13_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: A mutant deﬁcient in CPSF30 expression. Seedlings were germinated and grown in growth chambers set at 22 ℃ under continuous light.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line14' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6 leaf 3</a></td>
                        <td>Oxt6</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CPlantAPA%20stored%20PAC%2Coxt6_leaf_3%20PAT%20minus%20strand%2Coxt6_leaf_3%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>2569857</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf3_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf3_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf3_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_Oxt6_leaf3_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line14_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: A mutant deﬁcient in CPSF30 expression. Seedlings were germinated and grown in growth chambers set at 22 ℃ under continuous light.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line15' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6::C30G 1</a></td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cg1%20PAT%20plus%20strand%2Cg1%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>2277879</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_g1_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_g1_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_g1_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_g1_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line15_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Transgenes that encode the wild-type AtCPSF30 were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line16' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6::C30G 2</a></td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cg2%20PAT%20plus%20strand%2Cg2%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>4324508</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_g2_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_g2_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_g2_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_g2_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line16_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Transgenes that encode the wild-type AtCPSF30 were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line17' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6::C30G 3</a></td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cg3%20PAT%20plus%20strand%2Cg3%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>5510456</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_g3_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_g3_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_g3_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_g3_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line17_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Transgenes that encode the wild-type AtCPSF30 were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line18' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6::C30GM 1</a></td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cgm1%20PAT%20plus%20strand%2Cgm1%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>5360339</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_gm1_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_gm1_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_gm1_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_gm1_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line18_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: A mutant deficient in its interaction with calmodulin were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr>
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line19' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6::C30GM 2</a></td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cgm2%20PAT%20plus%20strand%2Cgm2%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>5204727</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_gm2_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_gm2_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_gm2_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_gm2_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line19_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: A mutant deficient in its interaction with calmodulin were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr style="border-bottom: 1px #ccc dashed">
                        <td>Arabidopsis thanliana</td>
                        <td><a id='line20' onclick="GetDetail(this)" style="cursor:pointer;color:blue">oxt6::C30GM 3</a></td>
                        <td>Oxt6</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                        <td><a href='http://www.arabidopsis.org/' target='_blanck'>TAIR 10</a></td>
                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cgm3%20PAT%20plus%20strand%2Cgm3%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>6886779</a></td>
                        <td><a href="./download_data.php?type=1&name=Ath_TAIR10_gm3_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_gm3_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_gm3_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Ath_TAIR10_gm3_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line20_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: A mutant deficient in its interaction with calmodulin were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                    </tr>
                    <tr>
                        <td>Oryza sativa</td>
                        <td><a id='line21' onclick="GetDetail(this)" style="cursor:pointer;color:blue">from EST</a></td>
                        <td>mix</td>
                        <td>mix</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1093%2Fnar%2Fgkn158">Shen et al. Plant Cell, 2012</a></td>
                        <td><a href='http://rice.plantbiology.msu.edu/' target='_blanck'>MSU v7</a></td>
                        <td><a href='../jbrowse/?data=data/japonica&tracks=PlantAPA%20stored%20PAC%2CGene%20annotation%2Cfrom_EST%20PAT%20minus%20strand%2Cfrom_EST%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target='_blanck'>57852</a></td>
                        <td><a href="./download_data.php?type=1&name=Rice_MSU7_From_EST_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_From_EST_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_From_EST_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_From_EST_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line21_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: ESTs and partial or complete cDNA sequences, were collected from GenBank.</td>
                    </tr>
                    <tr>
                        <td>Oryza sativa</td>
                        <td><a id='line22' onclick="GetDetail(this)" style="cursor:pointer;color:blue">from_RNAseq</a></td>
                        <td>mix</td>
                        <td>mix</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/22443345">Davidson et al. Plant J, 2012</a></td>
                        <td><a href='http://rice.plantbiology.msu.edu/' target='_blanck'>MSU v7</a></td>
                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cfrom_RNAseq%20PAT%20plus%20strand%2Cfrom_RNAseq%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>47180</a></td>
                        <td><a href="./download_data.php?type=1&name=Rice_MSU7_from_RNAseq_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_from_RNAseq_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_from_RNAseq_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_from_RNAseq_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line22_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Poly(A) sites collected from RNA-seq reads of leaf, endosperm, embryo, seed, pistil, anther, and inflorescence tissues.</td>
                    </tr>
                    <tr>
                        <td>Oryza sativa</td>
                        <td><a id='line23' onclick="GetDetail(this)" style="cursor:pointer;color:blue">flower_buds</a></td>
                        <td>WT</td>
                        <td>flower</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                        <td><a href='http://rice.plantbiology.msu.edu/' target='_blanck'>MSU v7</a></td>
                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cflower_buds%20PAT%20plus%20strand%2Cflower_buds%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>153823</a></td>
                        <td><a href="./download_data.php?type=1&name=Rice_MSU7_flower_buds_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_flower_buds_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_flower_buds_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_flower_buds_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line23_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Poly(A) sites collected from RNA-seq reads. Seeds from the cultivated rice subspecies Oryza sativa L. ssp. Japonica cultivar Nipponbare were grown in a greenhouse in Singapore under natural light conditions. Flower buds were collected before ﬂowering.</td>
                    </tr>
                    <tr>
                        <td>Oryza sativa</td>
                        <td><a id='line24' onclick="GetDetail(this)" style="cursor:pointer;color:blue">flowers</a></td>
                        <td>WT</td>
                        <td>flower</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                        <td><a href='http://rice.plantbiology.msu.edu/' target='_blanck'>MSU v7</a></td>
                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cflowers%20PAT%20plus%20strand%2Cflowers%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>124224</a></td>
                        <td><a href="./download_data.php?type=1&name=Rice_MSU7_flowers_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_flowers_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_flowers_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_flowers_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line24_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Poly(A) sites collected from RNA-seq reads of flower tissue.</td>
                    </tr>
                    <tr>
                        <td>Oryza sativa</td>
                        <td><a id='line25' onclick="GetDetail(this)" style="cursor:pointer;color:blue">leaves_before_flowering</a></td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                        <td><a href='http://rice.plantbiology.msu.edu/' target='_blanck'>MSU v7</a></td>
                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cleaves_before_flowering%20PAT%20plus%20strand%2Cleaves_before_flowering%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>139209</a></td>
                        <td><a href="./download_data.php?type=1&name=Rice_MSU7_leaves_before_flowering_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_leaves_before_flowering_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_leaves_before_flowering_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_leaves_before_flowering_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line25_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Poly(A) sites collected from RNA-seq reads of leaves. The before-ﬂowering sample was deﬁned as a mixture of different stages in a period from panicle initiation to 1 day before ﬂowering.</td>
                    </tr>
                    <tr>
                        <td>Oryza sativa</td>
                        <td><a id='line26' onclick="GetDetail(this)" style="cursor:pointer;color:blue">leaves_after_flowering</a></td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                        <td><a href='http://rice.plantbiology.msu.edu/' target='_blanck'>MSU v7</a></td>
                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cleaves_after_flowering%20PAT%20plus%20strand%2Cleaves_after_flowering%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>127962</a></td>
                        <td><a href="./download_data.php?type=1&name=Rice_MSU7_leaves_after_flowering_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_leaves_after_flowering_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_leaves_after_flowering_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_leaves_after_flowering_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line26_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Poly(A) sites collected from RNA-seq reads of leaves. The after-ﬂowering sample was deﬁned as a mixture of different stages after the ﬂowering day.</td>
                    </tr>
                    <tr>
                        <td>Oryza sativa</td>
                        <td><a id='line27' onclick="GetDetail(this)" style="cursor:pointer;color:blue">roots_before_flowering</a></td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                        <td><a href='http://rice.plantbiology.msu.edu/' target='_blanck'>MSU v7</a></td>
                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Croots_before_flowering%20PAT%20plus%20strand%2Croots_before_flowering%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>168028</a></td>
                        <td><a href="./download_data.php?type=1&name=Rice_MSU7_roots_before_flowering_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_roots_before_flowering_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_roots_before_flowering_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_roots_before_flowering_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line27_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Poly(A) sites collected from RNA-seq reads of roots. The before-ﬂowering sample was deﬁned as a mixture of different stages in a period from panicle initiation to 1 day before ﬂowering.</td>
                    </tr>
                    <tr>
                        <td>Oryza sativa</td>
                        <td><a id='line28' onclick="GetDetail(this)" style="cursor:pointer;color:blue">roots_after_flowering</a></td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                        <td><a href='http://rice.plantbiology.msu.edu/' target='_blanck'>MSU v7</a></td>
                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Croots_after_flowering%20%20PAT%20minus%20strand%2Croots_after_flowering%20PAT%20plus%20strand' title="View in jbrowse" target='_blanck'>114200</a></td>
                        <td><a href="./download_data.php?type=1&name=Rice_MSU7_roots_after_flowering_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_roots_after_flowering_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_roots_after_flowering_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_roots_after_flowering_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line28_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Poly(A) sites collected from RNA-seq reads of roots. The after-ﬂowering sample was deﬁned as a mixture of different stages after the ﬂowering day.</td>
                    </tr>
                    <tr>
                        <td>Oryza sativa</td>
                        <td><a id='line29' onclick="GetDetail(this)" style="cursor:pointer;color:blue">milk_grains</a></td>
                        <td>WT</td>
                        <td>grain</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                        <td><a href='http://rice.plantbiology.msu.edu/' target='_blanck'>MSU v7</a></td>
                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cmilk_grains%20PAT%20plus%20strand%2Cmilk_grains%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>163445</a></td>
                        <td><a href="./download_data.php?type=1&name=Rice_MSU7_milk_grains_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_milk_grains_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_milk_grains_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_milk_grains_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line29_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Poly(A) sites collected from RNA-seq reads of grains.</td>
                    </tr>
                    <tr style="border-bottom: 1px #ccc dashed">
                        <td>Oryza sativa</td>
                        <td><a id='line30' onclick="GetDetail(this)" style="cursor:pointer;color:blue">mature_seeds</a></td>
                        <td>WT</td>
                        <td>seed</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                        <td><a href='http://rice.plantbiology.msu.edu/' target='_blanck'>MSU v7</a></td>
                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cmature_seeds%20PAT%20plus%20strand%2Cmature_seeds%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>140487</a></td>
                        <td><a href="./download_data.php?type=1&name=Rice_MSU7_mature_seeds_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_mature_seeds_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_mature_seeds_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Rice_MSU7_mature_seeds_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line30_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Poly(A) sites collected from RNA-seq reads of seeds.</td>
                    </tr>
                    <tr>
                        <td>Medicago truncatula</td>
                        <td><a id='line31' onclick="GetDetail(this)" style="cursor:pointer;color:blue">wt leaf</a></td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1186%2F1471-2164-15-615">Wu et al. BMC Genomics, 2014</a></td>
                        <td><a href='http://medicago.jcvi.org/medicago/index.php#' target="_blank">JCVI Medtr v4</a></td>
                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=PlantAPA%20stored%20PAC%2CGene%20annotation%2Cwt_leaf_1%20PAT%20minus%20strand%2Cwt_leaf_1%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target="_blank">2747920</a></td>
                        <td><a href="./download_data.php?type=1&name=Mtr_JCVIv4_WT_leaf1_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_WT_leaf1_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_WT_leaf1_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_WT_leaf1_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line31_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: RNA was isolated from the combined leaves and washed roots of 3-4 week-old nodule-free plants.</td>
                    </tr>
                    <tr>
                        <td>Medicago truncatula</td>
                        <td><a id='line32' onclick="GetDetail(this)" style="cursor:pointer;color:blue">hairy root</a></td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387579">Mertens et al. Plant Physiology, 2016</a></td>
                        <td><a href='http://medicago.jcvi.org/medicago/index.php#' target="_blank">JCVI Medtr v4</a></td>
                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Chairy_root%20PAT%20plus%20strand%2Chairy_root%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">6690</a></td>
                        <td><a href="./download_data.php?type=1&name=Mtr_JCVIv4_hairy_root_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_hairy_root_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_hairy_root_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_hairy_root_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line32_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Independent hairy root lines expressing a non-functional GUS gene.</td>
                    </tr>
                    <tr>
                        <td>Medicago truncatula</td>
                        <td><a id='line33' onclick="GetDetail(this)" style="cursor:pointer;color:blue">leaf CK</a></td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                        <td><a href='http://medicago.jcvi.org/medicago/index.php#' target="_blank">JCVI Medtr v4</a></td>
                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cleaf_CK%20PAT%20plus%20strand%2Cleaf_CK%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">51490</a></td>
                        <td><a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_CK_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_CK_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_CK_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_CK_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line33_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: mRNA isolated from leaves of seedlings treated with control (CK).</td>
                    </tr>
                    <tr>
                        <td>Medicago truncatula</td>
                        <td><a id='line34' onclick="GetDetail(this)" style="cursor:pointer;color:blue">leaf OS</a></td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                        <td><a href='http://medicago.jcvi.org/medicago/index.php#' target="_blank">JCVI Medtr v4</a></td>
                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cleaf_OS%20PAT%20plus%20strand%2Cleaf_OS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">37338</a></td>
                        <td><a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_OS_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_OS_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_OS_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_OS_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line34_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: mRNA isolated from leaves of M. truncatula seedlings treated with osmotic stress (OS).</td>
                    </tr>
                    <tr>
                        <td>Medicago truncatula</td>
                        <td><a id='line35' onclick="GetDetail(this)" style="cursor:pointer;color:blue">leaf SS</a></td>
                        <td>WT</td>
                        <td>leaf</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                        <td><a href='http://medicago.jcvi.org/medicago/index.php#' target="_blank">JCVI Medtr v4</a></td>
                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cleaf_SS%20PAT%20plus%20strand%2Cleaf_SS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">29059</a></td>
                        <td><a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_SS_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_SS_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_SS_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_leaf_SS_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line35_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: mRNA isolated from leaves of seedlings treated with salt stress (SS).</td>
                    </tr>
                    <tr>
                        <td>Medicago truncatula</td>
                        <td><a id='line36' onclick="GetDetail(this)" style="cursor:pointer;color:blue">root CK</a></td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                        <td><a href='http://medicago.jcvi.org/medicago/index.php#' target="_blank">JCVI Medtr v4</a></td>
                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Croot_CK%20PAT%20plus%20strand%2Croot_CK%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">42947</a></td>
                        <td><a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_CK_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_CK_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_CK_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_CK_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line36_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: mRNA isolated from roots of seedlings treated with control (CK).</td>
                    </tr>
                    <tr>
                        <td>Medicago truncatula</td>
                        <td><a id='line37' onclick="GetDetail(this)" style="cursor:pointer;color:blue">root OS</a></td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                        <td><a href='http://medicago.jcvi.org/medicago/index.php#' target="_blank">JCVI Medtr v4</a></td>
                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Croot_OS%20PAT%20plus%20strand%2Croot_OS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">47675</a></td>
                        <td><a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_OS_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_OS_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_OS_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_OS_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line37_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: mRNA isolated from roots of seedlings treated with osmotic stress (OS).</td>
                    </tr>
                    <tr style="border-bottom: 1px #ccc dashed">
                        <td>Medicago truncatula</td>
                        <td><a id='line38' onclick="GetDetail(this)" style="cursor:pointer;color:blue">root SS</a></td>
                        <td>WT</td>
                        <td>root</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                        <td><a href='http://medicago.jcvi.org/medicago/index.php#' target="_blank">JCVI Medtr v4</a></td>
                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Croot_SS%20PAT%20plus%20strand%2Croot_SS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">50540</a></td>
                        <td><a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_SS_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_SS_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_SS_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Mtr_JCVIv4_root_SS_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line38_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: mRNA isolated from roots of seedlings treated with salt stress (SS).</td>
                    </tr>
                    <tr>
                        <td>Chlamydomonas reinhardtii</td>
                        <td><a id='line39' onclick="GetDetail(this)" style="cursor:pointer;color:blue">from illumina</a></td>
                        <td>mix</td>
                        <td>mix</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                        <td><a href='https://phytozome.jgi.doe.gov/pz/portal.html#!info?alias=Org_Creinhardtii' target="_blank">Creinhardtii 281 v55</a></td>
                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=PlantAPA%20stored%20PAC%2Cfrom_illumina%20PAT%20plus%20strand%2Cfrom_illumina%20%20PAT%20minus%20strand&highlight=' title="View in Jbrowse" target="_blank">622248</a></td>
                        <td><a href="./download_data.php?type=1&name=Chlamy_C281v55_From_illumina_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_illumina_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_illumina_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_illumina_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line39_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Illumina data were from DNAnexus (http://sra.dnanexus.com/)</td>
                    </tr>
                    <tr>
                        <td>Chlamydomonas reinhardtii</td>
                        <td><a id='line40' onclick="GetDetail(this)" style="cursor:pointer;color:blue">from 454</a></td>
                        <td>mix</td>
                        <td>mix</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                        <td><a href='https://phytozome.jgi.doe.gov/pz/portal.html#!info?alias=Org_Creinhardtii' target="_blank">Creinhardtii 281 v55</a></td>
                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=PlantAPA%20stored%20PAC%2CGene%20annotation%2CFrom_454%20PAT%20minus%20strand%2CFrom_454%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target="_blank">324305</a></td>
                        <td><a href="./download_data.php?type=1&name=Chlamy_C281v55_From_454_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_454_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_454_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_454_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line40_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: 454 data were from DNAnexus (http://sra.dnanexus.com/) or Dr. Olivier Vallon from Institut de Biologie Physico-Chimmique.</td>
                    </tr>
                    <tr>
                        <td>Chlamydomonas reinhardtii</td>
                        <td><a id='line41' onclick="GetDetail(this)" style="cursor:pointer;color:blue">from EST</a></td>
                        <td>mix</td>
                        <td>mix</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                        <td><a href='https://phytozome.jgi.doe.gov/pz/portal.html#!info?alias=Org_Creinhardtii' target="_blank">Creinhardtii 281 v55</a></td>
                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=PlantAPA%20stored%20PAC%2CGene%20annotation%2CFrom_EST%20PAT%20minus%20strand%2CFrom_EST%20PAT%20plus%20strand&highlight=' title="View in Jbrowse" target="_blank">56754</a></td>
                        <td><a href="./download_data.php?type=1&name=Chlamy_C281v55_From_EST_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_EST_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_EST_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_EST_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line41_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: ESTs were collected from both JGI and NCBI GenBank.</td>
                    </tr>
                    <tr>
                        <td>Chlamydomonas reinhardtii</td>
                        <td><a id='line42' onclick="GetDetail(this)" style="cursor:pointer;color:blue">from PAT-seq</a></td>
                        <td>mix</td>
                        <td>mix</td>
                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0146107">Bell et al. PloS one, 2016</a></td>
                        <td><a href='https://phytozome.jgi.doe.gov/pz/portal.html#!info?alias=Org_Creinhardtii' target="_blank">Creinhardtii 281 v55</a></td>
                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=PlantAPA%20stored%20PAC%2CGene%20annotation%2CFrom_PATseq%20PAT%20plus%20strand%2CFrom_PATseq%20%20PAT%20minus%20strand&highlight=' title="View in Jbrowse" target="_blank">12532698</a></td>
                        <td><a href="./download_data.php?type=1&name=Chlamy_C281v55_From_PATseq_PAT" title="Poly(A) site file: Chr, Strand, Coordinate, Number_of_PATs"/>PAT</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_PATseq_PAC" title="File of poly(A) site clusters"/>PAC</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_PATseq_PAC.fa.zip" title="Sequence file of poly(A) site clusters (PACs)"/>Sequence</a>
                        |&nbsp;<a href="./download_data.php?type=1&name=Chlamy_C281v55_From_PATseq_PAT_Track.zip" title="Jbrowse track file of poly(A) site tagnum (PATs)"/>PAT Track</a></td>
                    </tr>
                    <tr>
                        <td id='line42_detail' colspan="8" style="border:1px solid #5db59b;display:none;">Description: Poly(A) sites in in cultures grown in four different media types: Tris-Phosphate (TP), Tris-Phosphate-Acetate (TAP), High-Salt (HS), and High-Salt-Acetate (HAS).</td>
                    </tr>
                </tbody>
            </table><br>
        </div>
        <div class="download" id='demo'>
            <h2 style="border-bottom: 2px #5db95b solid;padding: 15px 0px 0px 0px;margin-bottom: 0px;text-align: left">
                <font color="#224055" ><b>Downloading demo data in PlantAPA</b></font>
            </h2>
            <table  class="bordertable">
                <thead>
                <tr>
                    <th>File</th>
                    <th>Description</th>
                    <th>Size</th>
                    <th>Download</th>
                </tr>
                </thead>
                <tr>
                    <td>Ath.demo.PA</td>
                    <td>Poly(A) site file for PAC Trap (Arabidopsis thaliana)</td>
                    <td>145.8KB</td>
                    <td><a href="./download_data.php?type=1&name=Ath.demo.PA"/>Download</td>
                </tr>
                <tr>
                    <td>Ath.demo.NGS.fq</td>
                    <td>Short read for PAC Trap (Arabidopsis thaliana)</td>
                    <td>25.5MB</td>
                    <td><a href="./download_data.php?type=1&name=arab_sample_100k.fastq"/>Download</td>
                </tr>
                <tr>
                    <td>Ath.demo.EST.fa</td>
                    <td>EST file for PAC Trap (Arabidopsis thaliana)</td>
                    <td>6.2MB</td>
                    <td><a href="./download_data.php?type=1&name=Ath.demo.NGS.fq"/>Download</td>
                </tr>
                <tr>
                    <td>Rice_japonica1.demo.fa</td>
                    <td>Short read for PAC Trap (Japonica rice)</td>
                    <td>636.3KB</td>
                    <td><a href="./download_data.php?type=1&name=Rice_japonica1.demo.fa"/>Download</td>
                </tr>
                <tr>
                    <td>Rice_japonica2.demo.fa</td>
                    <td>Short read for PAC Trap (Japonica rice)</td>
                    <td>638.8KB</td>
                    <td><a href="./download_data.php?type=1&name=Rice_japonica2.demo.fa"/>Download</td>
                </tr>
                <tr>
                    <td>Rice_japonica_3.demo.fa</td>
                    <td>Short read for PAC Trap (Japonica rice)</td>
                    <td>6.4MB</td>
                    <td><a href="./download_data.php?type=1&name=Rice_japonica_3.demo.fa"/>Download</td>
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
                    . "<h2 style=\"border-bottom: 2px #5db95b solid;padding: 15px 0px 0px 0px;margin-bottom: 0px;text-align: left\"><font color=\"#224055\" ><b>Downloading PAC trap results</b></font></h2>"
                    . "<table class='bordertable'>"
                    . "<thead>"
                    . "<tr>"
                    . "<th>Sample label</th>"
                    . "<th>Description</th>"
                    . "<th>Download</th>"
                    . "</tr>"
                    . "</thead>"
                    . "<tbody>"
                    . "<tr>";
                echo "<td>-</td>"
                    . "<td>Top 50 hexamers in near upstream region of PAC</td>"
                    . "<td><a href=\"./download_data.php?type=3&name=$hexamer\"/>Download</td>"
                    . "</tr>";
                echo "<td>-</td>"
                    . "<td>Single Nucleotide Compositions around PAC</td>"
                    . "<td><a href=\"./download_data.php?type=3&name=$snc\"/>Download</td>"
                    . "</tr>";
                foreach ($_SESSION['file_real'] as $key =>$value){
                    echo "<td>$value</td>"
                    . "<td>Poly(A) site</td>"
                    . "<td><a href=\"./download_data.php?type=2&name=$value.pa\"/>Download</td>"
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
