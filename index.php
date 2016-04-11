<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>PlantAPA-Home</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <script src="./src/jquery-1.10.1.min.js"></script>        
        <!-- webui popover -->
        <script src="./src/jquery.webui-popover.js"></script>
        <link href="./src/jquery.webui-popover.css" rel="stylesheet" type="text/css"/>
        <style>
            .tablebutton{
                    background-color:#5db95b;
                    color: #FFFFFF;
                    cursor: pointer;
            }
            a:hover{
                cursor: pointer;
            }
            .img:hover{
               background-color:#fff;
            }
/*            td a:hover{
                background-color: #fff;
            }
            .ref:hover{
                color:#fff;
                cursor: pointer;
                background-color: #5db95b;
            }
            .ref{
                color:blue;
            }*/
            a{
                color:blue;
            }
        </style>
    </head>
    <body>
        <?php include './navbar.php'; ?>
        <div class="ym-wrapper">
            <div class="ym-wbox">
                <div id="floor1">
                    <div id="info" style="float:left;background-color: #eee;width: 70%;border-radius: 20px">
                        <div style="padding:20px">
                            <img src="./pic/logo.png" style="float:left;height: 70px"> 
                            <h2 style="padding:20px 0px 0px 0px;text-align: left;vertical-align: bottom;font-weight: bold">Welcome to PlantAPA!</h2>
                            <hr style="margin:10px 0px;border-top: 1px solid #d5d5d5;clear: both">
                            <p style="font-size:17px;word-spacing:0px; letter-spacing: 0px;color: #000;text-align: justify;">PlantAPA is a web server for query, visualization, and analysis of poly(A) sites in plants, which can profile heterogeneous cleavage sites and quantify expression pattern of poly(A) sites across different conditions. To date, PlantAPA provides the largest database of APA in plants, including rice, Arabidopsis, Medicago truncatula, and Chlamydomonas reinhardtii.</p>
                            <p style="float:right;"><a href="./info.php" style="background-color:#5db95b;color:#fff;padding: 6px 12px;font-size: 12px;text-align: center;vertical-align: middle;border-radius: 4px;">More details</a></p>
                        </div>  
                    </div>
                    <div id="news" style="float: right;width: 30%">
                        <div style="padding-left:20px;font-size: 15px;color: #333">
                            <h2 style="border-bottom:1px solid #5db95b;text-align: left;padding-left: 10px">What's new</h2>
                            <div style="background-color:#eee;border-radius: 4px;padding: 10px">
                                Version 1.2.0&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;2016-03-12
                            <ul>
                                <li>Adjust the layout of the page</li>
                                <li>Add new sample("from PAT-seq") in chlamy</li>
                            </ul><br>
                                Version 1.1.0&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;2015-10-12
                            <ul>
                                <li>Add function of <a href="analysis.php">data analysis</a> </li>
                            </ul><br>
                                Version 1.0.0&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;2015-06-01
                            <ul>
                                <li>Release first version of PlantAPA</li>
                            </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div id="floor2">
                    <div style="padding-top: 30px">
                        <h2 style="border-bottom:1px #5db95b solid;text-align: left">
                            <font color="#000">Datasets list: all species documented in browser</font>
                        </h2>
                    <table cellspacing="1" cellpadding="0" border="0" style="border:1px solid #5db95b;">
                        <thead>
                            <tr class="theme">
                                <td class="theme" bgcolor="#e1e1e1" height="24">Species</td>
                                <td class="theme" bgcolor="#e1e1e1" height="24">Samples</td>
                                <td class="theme" bgcolor="#e1e1e1" height="24">PAC</td>
                                <td class="theme" bgcolor="#e1e1e1" height="24">PAT</td>
                                <td class="theme" bgcolor="#e1e1e1" height="24">Description</td>
                                <td class="theme" bgcolor="#e1e1e1" height="24">Reference</td>
                                <td class="theme" bgcolor="#e1e1e1" height="24">Jbrowse</td>
                                <td class="theme" bgcolor="#e1e1e1" height="24">Example</td>
                                <td class="theme" bgcolor="#e1e1e1" height="24"></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Arabidopsis thanliana</td>
                                <td>14</td>
                                <td>62834</td>
                                <td>68388227</td>
                                <td>poly(A) sites from leaf, seed, and root tissues of WT and Oxt6 mutant by poly(A) tag sequencing</td>
                                <td><a id='ref1' class='ref'>Wu et al. PNAS, 2011; Thomas et al. Plant Cell, 2012; Liu et al. PloS One, 2014</a></td>                     
                                <td>
                                    <a class='img' target="_blank" href="../jbrowse/?data=data/arab">
                                        <!--<span class="tablebutton" title="Browse poly(A) sites in Jbrowse">Chr</span>-->
                                        <img src="./pic/browser.png" title="Browse poly(A) sites in Jbrowse">
                                    </a>
                                </td>
                                <td>
                                    <a class='img' target="_blank" href="./sequence_detail.php?species=arab&seq=AT1G01020">
                                        <!--<span class="tablebutton" title="View detailed information for an example gene 'AT1G01020'">View</span>-->
                                        <img src="./pic/detail.png" title="View detailed information for an example gene 'AT1G01020'">
                                    </a>
                                </td>
                                <td class="style1"  width='50' style="white-space: nowrap;">
                                    <span class="more1 tablebutton" title="View more information about arabidopsis thanliana">More</span>
                                </td>
                            </tr>
                            <tr  >

                                    <td class="more1_content" colspan="9" >
                                        <div class="box info">

                                        <table cellspacing="1" cellpadding="0" border="0" style="border:1px dotted #5db95b;width:90%;margin: 10px 5%;">
                                            <thead>
                                                <tr class="theme" bgcolor="#5db95b">
                                                    <td class="theme"  height="24">Species</td>
                                                    <td class="theme"  height="24">Label</td>
                                                    <td class="theme"  height="24">Cell Line</td>
                                                    <td class="theme"  height="24">Tissue</td>
                                                    <td class="theme"  height="24">Reference</td>
                                                    <td class="theme"  height="24">Genome Annotation</td>
                                                    <td class="theme"  height="24">PATs</td>
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
                                <td><a id='ref2' class="ref">Shen et al. Nucleic Acids Res, 2008</a></td>                     
                                <td>
                                    <a class='img' target="_blank" href="../jbrowse/?data=data/japonica">
                                        <!--<span class="tablebutton" title="Browse poly(A) sites in Jbrowse">Chr</span>-->
                                        <img src="./pic/browser.png" title="Browse poly(A) sites in Jbrowse">
                                    </a>
                                </td>
                                <td>
                                    <a class='img' target="_blank" href="./sequence_detail.php?seq=LOC_Os01g01080&species=japonica">
                                        <!--<span class="tablebutton" title="View detailed information for an example gene 'LOC_Os01g01080'">View</span>-->
                                        <img src="./pic/detail.png" title="View detailed information for an example gene 'LOC_Os01g01080'">
                                    </a>
                                </td>
                                <td class="style1" width='50' style="white-space: nowrap;">
                                    <span class="more2 tablebutton" title="Browse search result for 'LOC_Os01g01080'">More</span>
                                </td>
                            </tr>
                            <tr >

                                <td class="more2_content" colspan="9" >
                                    <div  class="box info">
                                        <table cellspacing="1" cellpadding="0" border="0" style="border:1px dotted #5db95b;width:100%;margin: 10px 2px;">
                                            <tbody>
                                                <tr class="theme">
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">Species</td>
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">Label</td>
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">Cell Line</td>
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">Tissue</td>
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">Reference</td>
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">Genome Annotation</td>
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">PATs</td>
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
                                    </div>
                            </td>
                            </tr>
                            <tr>
                                <td>Medicago truncatula</td>
                                <td>1</td>
                                <td>42691</td>
                                <td>2747920</td>
                                <td>poly(A) sites from leaf tissue by poly(A) tag sequencing</td>
                                <td><a id='ref3' class="ref">Wu et al. BMC Genomics, 2014</a></td>                     
                                <td>
                                    <a class='img' target="_blank" href="../jbrowse/?data=data/mtr">
                                        <!--<span class="tablebutton" title="Browse poly(A) sites in Jbrowse">Chr</span>-->
                                        <img src="./pic/browser.png" title="Browse poly(A) sites in Jbrowse">
                                    </a>
                                </td>
                                <td>
                                    <a class='img' target="_blank" href="./sequence_detail.php?seq=Medtr0019s0160&species=mtr">
                                        <!--<span class="tablebutton" title="View detailed information for an example gene 'Medtr0019s0160'">View</span>-->
                                        <img src="./pic/detail.png" title="View detailed information for an example gene 'Medtr0019s0160'">
                                    </a>
                                </td>
                                <td class="style1" width='50' style="white-space: nowrap;">
                                    <span class="more3 tablebutton" title="View more information about Medicago truncatula'">More</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="more3_content" colspan="9" >
                                    <div class="box info">
                                        <table cellspacing="1" cellpadding="0" border="0" style="border:1px dotted #5db95b;width:100%;margin: 10px 2px;">
                                            <tbody>
                                                <tr class="theme" bgcolor="#5db95b">
                                                    <td class="theme"  height="24">Species</td>
                                                    <td class="theme"  height="24">Label</td>
                                                    <td class="theme"  height="24">Cell Line</td>
                                                    <td class="theme"  height="24">Tissue</td>
                                                    <td class="theme"  height="24">Reference</td>
                                                    <td class="theme"  height="24">Genome Annotation</td>
                                                    <td class="theme"  height="24">PATs</td>
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
                                        </div>
                                </td>
                            </tr>
                            <tr style="border-bottom:2px solid #5db95b;">
                                <td>Chlamydomonas reinhardtii</td>
                                <td>4</td>
                                <td>45372</td>
                                <td>13536005</td>
                                <td>poly(A) sites extracted from ESTs, 454, Illumina, and  PAT-seq sequence reads</td>
                                <td><a id='ref4' class="ref">Zhao et al. G3:Genes|Genomes|Genetics, 2014; Bell et al. PloS one, 2016</a></td>                     
                                <td>
                                    <a class='img' target="_blank" href="../jbrowse/?data=data/chlamy">
                                        <!--<span class="tablebutton" title="Browse poly(A) sites in Jbrowse">Chr</span>-->
                                        <img src="./pic/browser.png" title="Browse poly(A) sites in Jbrowse">
                                    </a>
                                </td>
                                <td>
                                    <a class='img' target="_blank" href="./sequence_detail.php?seq=Cre01.g000650&species=chlamy">
                                        <!--<span class="tablebutton" title="View detailed information for an example gene ''">View</span>-->
                                        <img src="./pic/detail.png" title="View detailed information for an example gene 'Cre01.g000650'">
                                    </a>
                                </td>
                                <td class="style1" width='50' style="white-space: nowrap;">
                                    <span class="more4 tablebutton" title="View more information about Chlamydomonas reinhardtii'">More</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="more4_content"  colspan="9">
                                    <div class="box info">
                                        <table cellspacing="1" cellpadding="0" border="0" style="border:1px dotted #5db95b;width:100%;margin: 10px 2px;">
                                            <tbody>
                                                <tr class="theme" bgcolor="#5db95b">
                                                    <td class="theme"  height="24">Species</td>
                                                    <td class="theme"  height="24">Label</td>
                                                    <td class="theme"  height="24">Cell Line</td>
                                                    <td class="theme"  height="24">Tissue</td>
                                                    <td class="theme"  height="24">Reference</td>
                                                    <td class="theme"  height="24">Genome Annotation</td>
                                                    <td class="theme"  height="24">PATs</td>
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
                                        </div>        
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                        </div>
                    </div>
                </div>
            </div>
             <script type="text/javascript">

            $(document).ready(function(){
                
                $(".more1").click(function(){
                    $(".more1_content div,.more1_content").slideToggle("slow");
                    if($(".more1").text()=='More')
                        $(".more1").html("Close");
                    else
                        $(".more1").html("More");
                });
                $(".more2").click(function(){
                    $(".more2_content div,.more2_content").slideToggle("slow");
                    if($(".more2").text()=='More')
                        $(".more2").html("Close");
                    else
                        $(".more2").html("More");
                });
                $(".more3").click(function(){
                    $(".more3_content div,.more3_content").slideToggle("slow");
                    if($(".more3").text()=='More')
                        $(".more3").html("Close");
                    else
                        $(".more3").html("More");
                });
                $(".more4").click(function(){
                    $(".more4_content div,.more4_content").slideToggle("slow");
                    if($(".more4").text()=='More')
                        $(".more4").html("Close");
                    else
                        $(".more4").html("More");
                });
            });
            $('#ref1').webuiPopover({
                placement:'right',//值: auto,top,right,bottom,left,top-right,top-left,bottom-right,bottom-left
                title:'Link to',
                content:'<a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1073%2Fpnas.1019732108">Wu et al. PNAS, 2011</a><br><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a><br><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a>',
                trigger:'hover',
                type:'html'
            });
            $('#ref2').webuiPopover({
                placement:'right',//值: auto,top,right,bottom,left,top-right,top-left,bottom-right,bottom-left
                title:'Link to',
                content:'<a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1093%2Fnar%2Fgkn158">Shen et al. Nucleic Acids Res, 2008</a>',
                trigger:'hover',
                type:'html'
            });
            $('#ref3').webuiPopover({
                placement:'right',//值: auto,top,right,bottom,left,top-right,top-left,bottom-right,bottom-left
                title:'Link to',
                content:'<a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1186%2F1471-2164-15-615">Wu et al. BMC Genomics, 2014</a>',
                trigger:'hover',
                type:'html'
            });
            $('#ref4').webuiPopover({
                placement:'right',//值: auto,top,right,bottom,left,top-right,top-left,bottom-right,bottom-left
                title:'Link to',
                content:'<a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a><br><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0146107">Bell et al. PloS one, 2016</a>',
                trigger:'hover',
                type:'html'
            });
            </script>
        <?php
            include"./footer.php";
        ?>
    </body>
</html>
