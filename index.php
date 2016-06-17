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
                            <p style="font-size:17px;word-spacing:0px; letter-spacing: 0px;color: #000;text-align: justify;">PlantAPA is a web server for query, visualization, and analysis of poly(A) sites in plants, which can profile heterogeneous cleavage sites and quantify expression pattern of poly(A) sites across different conditions. To date, PlantAPA provides the largest database of APA in plants, including rice, Arabidopsis, <span  style="font-style:italic">Medicago truncatula</span>, and <span style="font-style:italic">Chlamydomonas reinhardtii</span>.</p>
                            <p style="float:left;width:83%;font-weight: bold">Please cite the following paper when using PlantAPA. <br>
                            <a href="http://dx.doi.org/10.3389/fpls.2016.00889" target="_blank">Wu, X., Zhang, Y., and Li, Q.Q. (2016). PlantAPA: a portal for visualization and analysis of alternative polyadenylation in plants. Frontiers in Plant Science 7. doi: 10.3389/fpls.2016.00889.</a></p>
                            <p style="float:right;"><a href="./info.php" style="background-color:#5db95b;color:#fff;padding: 6px 12px;font-size: 12px;text-align: center;vertical-align: middle;border-radius: 4px;">More details</a></p>
                        </div>  
                    </div>
                    <div id="news" style="float: right;width: 30%">
                        <div style="padding-left:20px;font-size: 15px;color: #333">
                            <h2 style="border-bottom:1px solid #5db95b;text-align: left;padding-left: 10px">What's new<a href="./changelog.php" style="background-color:#5db95b;color:#fff;padding: 4px 5px;float:right;font-size: 12px;text-align: center;vertical-align: middle;border-radius: 4px;">More</a></h2>
                            <div style="background-color:#eee;border-radius: 4px;padding: 10px">
                                Article is published&nbsp;&nbsp;&emsp;&emsp;&emsp;2016-06-21
                            <ul>
                                <li>PlantAPA has been published in Frontiers in Plant Science, section Plant Genetics and Genomics.</li>
                            </ul><br>
                                Version 1.2.1&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;2016-05-20
                            <ul>
                                <li>Add new poly(A) site datasets</li>
                            </ul><br>
                                Version 1.2.0&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;2016-03-28
                            <ul>
                                <li>Update the web UI and extensively debugging</li>
                            </ul
                            </div>
                        </div>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div id="floor2">
                    <div style="padding-top: 30px">
                        <h2 style="border-bottom:1px #5db95b solid;text-align: left">
                            <font color="#000">Datasets list: all species documented in PlantAPA</font>
                            <a href="help.php#dataset" target="_blank">
                                <img title="Get help for this page" style="width:20px;height: 20px;display: inline-block" src="./pic/help.png">
                            </a>
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
                                <td>20</td>
                                <td>69577</td>
                                <td>98781156</td>
                                <td>Poly(A) sites from leaf, seed, and root tissues of WT and Oxt6 mutant by poly(A) tag sequencing</td>
                                <td><a id='ref1' class='ref'>Wu et al. PNAS, 2011; Thomas et al. Plant Cell, 2012; Liu et al. PloS One, 2014</a></td>                     
                                <td>
                                    <a class='img' target="_blank" href="../jbrowse/?data=data/arab">
                                        <!--<span class="tablebutton" title="Browse poly(A) sites in Jbrowse">Chr</span>-->
                                        <img src="./pic/browser.png" title="Browse poly(A) sites in Jbrowse">
                                    </a>
                                </td>
                                <td>
                                    <a class='img' target="_blank" href="./sequence_detail.php?species=arab&seq=AT1G01020&method=search">
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

                                        <table cellspacing="1" cellpadding="0" border="0" style="border:1px dotted #5db95b;margin: 10px 1%;">
                                            <thead>
                                                <tr class="theme" bgcolor="#5db95b">
                                                    <td class="theme"  height="24">Label</td>
                                                    <td class="theme"  height="24">Tissue</td>
                                                    <td class="theme"  height="24">Reference</td>
                                                    <td class="theme"  height="24">PATs</td>
                                                    <td class="theme"  height="24">Description</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>wt leaf 1</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_leaf_1%20PAT%20minus%20strand%2Cwt_leaf_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>1055461</a></td>
                                                    <td>Plants were grown in soil in a climate-controlled growth room under short-day (8-h daylight) conditions, or under sterile conditions by germinating seeds, to capture as broad a range of poly(A) sites in leaves as possible.</td>
                                                </tr>
                                                <tr>
                                                    <td>wt leaf 2</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1073%2Fpnas.1019732108">Wu et al. PNAS, 2011</a></td>
                                                   <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_leaf_2%20PAT%20minus%20strand%2Cwt_leaf_2%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>927476</a></td>
                                                   <td>Plants were grown in soil in a climate-controlled growth room under short-day (8-h daylight) conditions, or under sterile conditions by germinating seeds, to capture as broad a range of poly(A) sites in leaves as possible.</td>
                                                </tr>
                                                <tr>
                                                    <td>wt leaf 3</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_leaf_3%20PAT%20minus%20strand%2Cwt_leaf_3%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>24269</a></td>
                                                    <td>Plants were grown in soil in a climate-controlled growth room under short-day (8-h daylight) conditions, or under sterile conditions by germinating seeds, to capture as broad a range of poly(A) sites in leaves as possible.</td>
                                                </tr>
                                                <tr>
                                                    <td>wt seed 1</td>
                                                    <td>seed</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_seed_1%20PAT%20minus%20strand%2Cwt_seed_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>332010</a></td>
                                                    <td>RNA was isolated from dried Arabidopsis seed.</td>
                                                </tr>
                                                <tr>
                                                    <td>wt seed 2</td>
                                                    <td>seed</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1073%2Fpnas.1019732108">Wu et al. PNAS, 2011</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_seed_2%20PAT%20minus%20strand%2Cwt_seed_2%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>2230409</a></td>
                                                    <td>RNA was isolated from dried Arabidopsis seed.</td>
                                                </tr>
                                                <tr>
                                                    <td>wt root 1</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_root_1%20PAT%20minus%20strand%2Cwt_root_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>16232735</a></td>
                                                    <td>Total RNA were isolated from 10-day old roots.</td>
                                                </tr>
                                                <tr>
                                                    <td>wt root 2</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_root_2%20PAT%20minus%20strand%2Cwt_root_2%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>10700327</a></td>
                                                    <td>Total RNA were isolated from 10-day old roots.</td>
                                                </tr>
                                                <tr>
                                                    <td>wt root 3</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_root_3%20PAT%20minus%20strand%2Cwt_root_3%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>12547544</a></td>
                                                    <td>Total RNA were isolated from 10-day old roots.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6 root 1</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_root_1%20PAT%20minus%20strand%2Coxt6_root_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>5861620</a></td>
                                                    <td>A mutant deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6 root 2</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_root_2%20PAT%20minus%20strand%2Coxt6_root_2%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>10174020</a></td>
                                                    <td>A mutant deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6 root 3</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_root_3%20PAT%20minus%20strand%2Coxt6_root_3%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>5653903</a></td>
                                                    <td>A mutant deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6 leaf 1</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_leaf_1%20PAT%20minus%20strand%2Coxt6_leaf_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>1222315</a></td>
                                                    <td>A mutant deﬁcient in CPSF30 expression. Seedlings were germinated and grown in growth chambers set at 22 ℃ under continuous light.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6 leaf 2</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_leaf_2%20PAT%20minus%20strand%2Coxt6_leaf_2%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>74911</a></td>
                                                    <td>A mutant deﬁcient in CPSF30 expression. Seedlings were germinated and grown in growth chambers set at 22 ℃ under continuous light.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6 leaf 3</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_leaf_3%20PAT%20minus%20strand%2Coxt6_leaf_3%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>2569857</a></td>
                                                    <td>A mutant deﬁcient in CPSF30 expression. Seedlings were germinated and grown in growth chambers set at 22 ℃ under continuous light.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6::C30G 1</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cg1%20PAT%20plus%20strand%2Cg1%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>2277879</a></td>
                                                    <td>Transgenes that encode the wild-type AtCPSF30 were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6::C30G 2</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cg2%20PAT%20plus%20strand%2Cg2%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>4324508</a></td>
                                                    <td>Transgenes that encode the wild-type AtCPSF30 were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6::C30G 3</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cg3%20PAT%20plus%20strand%2Cg3%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>5510456</a></td>
                                                    <td>Transgenes that encode the wild-type AtCPSF30 were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6::C30GM 1</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cgm1%20PAT%20plus%20strand%2Cgm1%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>5360339</a></td>
                                                    <td>A mutant deficient in its interaction with calmodulin were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6::C30GM 2</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cgm2%20PAT%20plus%20strand%2Cgm2%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>5204727</a></td>
                                                    <td>A mutant deficient in its interaction with calmodulin were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                                </tr>
                                                <tr>
                                                    <td>oxt6::C30GM 3</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cgm3%20PAT%20plus%20strand%2Cgm3%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>6886779</a></td>
                                                    <td>A mutant deficient in its interaction with calmodulin were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
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
                                            <span>Distributions of poly(A) site clusters (PACs) and poly(A) tags (PATs) </span>
                                        </div>
                                            <div class="ym-grid " style="clear: both;" >
                                                
                                            </div>
                                    </div>
                                    </td>

                            </tr>
                            <tr>
                               <td>Oryza sativa</td>
                                <td>10</td>
                                <td>55465</td>
                                <td>1236410</td>
                                <td>Poly(A) sites extracted from ESTs and RNA-seq reads</td>
                                <td><a id='ref2' class="ref">Shen et al. Nucleic Acids Res, 2008; Davidson et al. Plant J, 2012; Wang et al. Plant J, 2015</a></td>                     
                                <td>
                                    <a class='img' target="_blank" href="../jbrowse/?data=data/japonica">
                                        <!--<span class="tablebutton" title="Browse poly(A) sites in Jbrowse">Chr</span>-->
                                        <img src="./pic/browser.png" title="Browse poly(A) sites in Jbrowse">
                                    </a>
                                </td>
                                <td>
                                    <a class='img' target="_blank" href="./sequence_detail.php?seq=LOC_Os01g01080&species=japonica&method=search">
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
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">Label</td>
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">Tissue</td>
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">Reference</td>
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">PATs</td>
                                                    <td class="theme" bgcolor="#5db95b" align="center" height="24">Description</td>
                                                </tr>
                                                <tr>
                                                    <td>from EST</td>
                                                    <td>mix</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1093%2Fnar%2Fgkn158">Shen et al. Plant Cell, 2012</a></td>
                                                    <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cfrom_EST%20PAT%20minus%20strand%2Cfrom_EST%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>57852</a></td>
                                                    <td>ESTs and partial or complete cDNA sequences, were collected from GenBank.</td>
                                                </tr>
                                                <tr>
                                                    <td>from RNAseq</td>
                                                    <td>mix</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/22443345">Davidson et al. Plant J, 2012</a></td>
                                                    <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cfrom_RNAseq%20PAT%20plus%20strand%2Cfrom_RNAseq%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>47180</a></td>
                                                    <td>Poly(A) sites collected from RNA-seq reads of leaf, endosperm, embryo, seed, pistil, anther, and inflorescence tissues.</td>
                                                </tr>
                                                <tr>
                                                    <td>flower buds</td>
                                                    <td>flower</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cflower_buds%20PAT%20plus%20strand%2Cflower_buds%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>153823</a></td>
                                                    <td>Poly(A) sites collected from RNA-seq reads. Seeds from the cultivated rice subspecies Oryza sativa L. ssp. Japonica cultivar Nipponbare were grown in a greenhouse in Singapore under natural light conditions. Flower buds were collected before ﬂowering.</td>
                                                </tr>
                                                <tr>
                                                    <td>flowers</td>
                                                    <td>flower</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cflowers%20PAT%20plus%20strand%2Cflowers%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>124224</a></td>
                                                    <td>Poly(A) sites collected from RNA-seq reads of flower tissue.</td>
                                                </tr>
                                                <tr>
                                                    <td>leaves before flowering</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cleaves_before_flowering%20PAT%20plus%20strand%2Cleaves_before_flowering%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>139209</a></td>
                                                    <td>Poly(A) sites collected from RNA-seq reads of leaves. The before-ﬂowering sample was deﬁned as a mixture of different stages in a period from panicle initiation to 1 day before ﬂowering.</td>
                                                </tr>
                                                <tr>
                                                    <td>leaves after flowering</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cleaves_after_flowering%20PAT%20plus%20strand%2Cleaves_after_flowering%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>127962</a></td>
                                                    <td>Poly(A) sites collected from RNA-seq reads of leaves. The after-ﬂowering sample was deﬁned as a mixture of different stages after the ﬂowering day.</td>
                                                </tr>
                                                <tr>
                                                    <td>roots before flowering</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Croots_before_flowering%20PAT%20plus%20strand%2Croots_before_flowering%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>168028</a></td>
                                                    <td>Poly(A) sites collected from RNA-seq reads of roots. The before-ﬂowering sample was deﬁned as a mixture of different stages in a period from panicle initiation to 1 day before ﬂowering.</td>
                                                </tr>
                                                <tr>
                                                    <td>roots after flowering</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Croots_after_flowering%20%20PAT%20minus%20strand%2Croots_after_flowering%20PAT%20plus%20strand' title="View in jbrowse" target='_blanck'>114200</a></td>
                                                    <td>Poly(A) sites collected from RNA-seq reads of roots. The after-ﬂowering sample was deﬁned as a mixture of different stages after the ﬂowering day.</td>
                                                </tr>
                                                <tr>
                                                    <td>milk grains</td>
                                                    <td>grain</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cmilk_grains%20PAT%20plus%20strand%2Cmilk_grains%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>163445</a></td>
                                                    <td>Poly(A) sites collected from RNA-seq reads of grains.</td>
                                                </tr>
                                                <tr>
                                                    <td>mature seeds</td>
                                                    <td>seed</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cmature_seeds%20PAT%20plus%20strand%2Cmature_seeds%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>140487</a></td>
                                                    <td>Poly(A) sites collected from RNA-seq reads of seeds.</td>
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
                                            <span>Distributions of poly(A) site clusters (PACs) and poly(A) tags (PATs) </span>
                                        </div>
                                    </div>
                            </td>
                            </tr>
                            <tr>
                                <td>Medicago truncatula</td>
                                <td>8</td>
                                <td>61258</td>
                                <td>3412026</td>
                                <td>Poly(A) sites extracted from RNA-seq and  PAT-seq sequence reads</td>
                                <td><a id='ref3' class="ref">Wu et al. BMC Genomics, 2014; Wang et al.  BMC Plant Biology, 2015; Mertens et al. Plant Physiology, 2016</a></td>                     
                                <td>
                                    <a class='img' target="_blank" href="../jbrowse/?data=data/mtr">
                                        <!--<span class="tablebutton" title="Browse poly(A) sites in Jbrowse">Chr</span>-->
                                        <img src="./pic/browser.png" title="Browse poly(A) sites in Jbrowse">
                                    </a>
                                </td>
                                <td>
                                    <a class='img' target="_blank" href="./sequence_detail.php?seq=Medtr0019s0160&species=mtr&method=search">
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
                                                    <td class="theme"  height="24">Label</td>
                                                    <td class="theme"  height="24">Tissue</td>
                                                    <td class="theme"  height="24">Reference</td>
                                                    <td class="theme"  height="24">PATs</td>
                                                    <td class="theme"  height="24">Description</td>
                                                </tr>
                                                <tr>
                                                    <td>wt leaf</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1186%2F1471-2164-15-615">Wu et al. BMC Genomics, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cwt_leaf_1%20PAT%20minus%20strand%2Cwt_leaf_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target="_blank">3146287</a></td>
                                                    <td>RNA was isolated from the combined leaves and washed roots of 3-4 week-old nodule-free plants.</td>
                                                </tr>
                                                <tr>
                                                    <td>hairy root</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387579">Mertens et al. Plant Physiology, 2016</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Chairy_root%20PAT%20plus%20strand%2Chairy_root%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">6690</a></td>
                                                    <td>Independent hairy root lines expressing a non-functional GUS gene.</td>
                                                </tr>
                                                <tr>
                                                    <td>leaf CK</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cleaf_CK%20PAT%20plus%20strand%2Cleaf_CK%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">29059</a></td>
                                                    <td>mRNA isolated from leaves of seedlings treated with control (CK).</td>
                                                </tr>
                                                <tr>
                                                    <td>leaf OS</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cleaf_OS%20PAT%20plus%20strand%2Cleaf_OS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">51490</a></td>
                                                    <td>mRNA isolated from leaves of M. truncatula seedlings treated with osmotic stress (OS).</td>
                                                </tr>
                                                <tr>
                                                    <td>leaf SS</td>
                                                    <td>leaf</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cleaf_SS%20PAT%20plus%20strand%2Cleaf_SS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">37338</a></td>
                                                    <td>mRNA isolated from leaves of seedlings treated with salt stress (SS).</td>
                                                </tr>
                                                <tr>
                                                    <td>root CK</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Croot_CK%20PAT%20plus%20strand%2Croot_CK%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">50540</a></td>
                                                    <td>mRNA isolated from roots of seedlings treated with control (CK).</td>
                                                </tr>
                                                <tr>
                                                    <td>root OS</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Croot_OS%20PAT%20plus%20strand%2Croot_OS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">42947</a></td>
                                                    <td>mRNA isolated from roots of seedlings treated with osmotic stress (OS).</td>
                                                </tr>
                                                <tr>
                                                    <td>root SS</td>
                                                    <td>root</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Croot_SS%20PAT%20plus%20strand%2Croot_SS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">47675</a></td>
                                                    <td>mRNA isolated from roots of seedlings treated with salt stress (SS).</td>
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
                                            <span>Distributions of poly(A) site clusters (PACs) and poly(A) tags (PATs) </span>

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
                                <td>Poly(A) sites extracted from ESTs, 454, Illumina, and  PAT-seq sequence reads</td>
                                <td><a id='ref4' class="ref">Zhao et al. G3:Genes|Genomes|Genetics, 2014; Bell et al. PloS one, 2016</a></td>                     
                                <td>
                                    <a class='img' target="_blank" href="../jbrowse/?data=data/chlamy">
                                        <!--<span class="tablebutton" title="Browse poly(A) sites in Jbrowse">Chr</span>-->
                                        <img src="./pic/browser.png" title="Browse poly(A) sites in Jbrowse">
                                    </a>
                                </td>
                                <td>
                                    <a class='img' target="_blank" href="./sequence_detail.php?seq=Cre01.g000650&species=chlamy&method=search">
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
                                                    <td class="theme"  height="24">Label</td>
                                                    <td class="theme"  height="24">Tissue</td>
                                                    <td class="theme"  height="24">Reference</td>
                                                    <td class="theme"  height="24">PATs</td>
                                                    <td class="theme"  height="24">Description</td>
                                                </tr>
                                                <tr>
                                                    <td>from illumina</td>
                                                    <td>mix</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2Cfrom_illumina%20PAT%20plus%20strand%2Cfrom_illumina%20%20PAT%20minus%20strand&highlight=' title="View in jbrowse" target="_blank">622248</a></td>
                                                    <td>Illumina data were from DNAnexus (<a href='http://sra.dnanexus.com'/>http://sra.dnanexus.com/</a>)</td>
                                                </tr>
                                                <tr>
                                                    <td>from 454</td>
                                                    <td>mix</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2CGene%20annotation%2CFrom_454%20PAT%20minus%20strand%2CFrom_454%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target="_blank">324305</a></td>
                                                    <td>454 data were from DNAnexus (<a href='http://sra.dnanexus.com'/>http://sra.dnanexus.com/</a>) or Dr. Olivier Vallon from Institut de Biologie Physico-Chimmique.</td>
                                                </tr>
                                                <tr>
                                                    <td>from EST</td>
                                                    <td>mix</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2CGene%20annotation%2CFrom_EST%20PAT%20minus%20strand%2CFrom_EST%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target="_blank">56754</a></td>
                                                    <td>ESTs were collected from both JGI and NCBI GenBank.</td>
                                                </tr>
                                                <tr>
                                                    <td>from PAT-seq</td>
                                                    <td>mix</td>
                                                    <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0146107">Bell et al. PloS one, 2016</a></td>
                                                    <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2CGene%20annotation%2CFrom_PATseq%20PAT%20plus%20strand%2CFrom_PATseq%20%20PAT%20minus%20strand&highlight=' title="View in jbrowse" target="_blank">12532698</a></td>
                                                    <td>Poly(A) sites in in cultures grown in four different media types: Tris-Phosphate (TP), Tris-Phosphate-Acetate (TAP), High-Salt (HS), and High-Salt-Acetate (HAS).</td>
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
                                            <span>Distributions of poly(A) site clusters (PACs) and poly(A) tags (PATs) </span>
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
                content:'<a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1093%2Fnar%2Fgkn158">Shen et al. Nucleic Acids Res, 2008</a><br><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/22443345">Davidson et al. Plant J, 2012</a><br><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a>',
                trigger:'hover',
                type:'html'
            });
            $('#ref3').webuiPopover({
                placement:'right',//值: auto,top,right,bottom,left,top-right,top-left,bottom-right,bottom-left
                title:'Link to',
                content:'<a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1186%2F1471-2164-15-615">Wu et al. BMC Genomics, 2014</a><br><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a><br><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387579">Mertens et al. Plant Physiology, 2016</a>',
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
