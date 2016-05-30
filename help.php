<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>PlantAPA-Help</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <script src="./src/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="./src/slidebar-help/js/jquery.jumpto.js"></script>
        <link rel="stylesheet" type="text/css" href="./src/slidebar-help/css/jumpto.css" />
        <link rel="stylesheet" type="text/css" href="./src/slidebar-help/css/style.css" />
        <script type="text/javascript" src="./src/slidebar-help/js/modernizr.js"></script>
        <style>
            .pic{
                    /*margin-bottom: 15px;*/
                    width: 100%;
                    text-align: center;
            }
            .pic_red{
                color: red;
            }
            .picdiv{
                margin:0 auto 1.5em auto;
                width: 80%;
                text-align: center;
                font-size: 15px;
                color: #696969;
            }
            .status{
                color:green;
            }
        </style>
    </head>
    <body>
        <?php include './navbar.php'; ?>
        <div class="ym-wrapper">
	<div class="main">
                    <div class="page_container">
                       <div class="jumpto-block">
                            <h1 style="text-align: center;">How to use PlantAPA</h1>
                            <div  style="color:#a94442;background-color: #f2dede;border-color: #ebccd1;padding: 15px;border:1px solid transparent;border-radius: 4px;">
                                <p style="color:#a94442;font-size: 16px;margin-bottom: 0px">For better browse experience, advanced browsers such as Chrome, Firefox, Safari, and Internet Explorer (11.0 or later) can be used for browsing PlantAPA. 
                               <br><br>Firefox and Chrome are recommended:
                               <br>Mozilla Firefox 39.0 or greater (<a href=http://www.mozilla.org>http://www.mozilla.org</a>)
                               <br>Google Chrome 46.0 or greater (<a href=http://www.google.com/chrome/>http://www.google.com/chrome/</a>)
                                </p><br>
                                <table>
                                    <thead>
                                        <th>OS</th>
                                        <th>Browser</th>
                                        <th>Static pages</th>
                                        <th>PAC browse page</th>
                                        <th>PAC trap page</th>
                                        <th>Sequence detail page</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Windows</td>
                                            <td>Chrome 46.0</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Firefox 39.0</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Internet Explorer 11</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                        </tr>
                                        <tr>
                                            <td>Linux</td>
                                            <td>Chrome 46.0</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Firefox 39</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                        </tr>
                                        <tr style="border-bottom:2px black solid;">
                                            <td>Mac</td>
                                            <td>Safari</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                            <td class="status">OK</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                      </div>
                      <div class="jumpto-block">
                        <h2>1. <span>Introduction</span></h2>
                        <p>PlantAPA is a web server for query, visualization, and analysis of poly(A) sites in plants, which can profile heterogeneous cleavage sites and quantify expression pattern of poly(A) sites across different conditions. To date, PlantAPA provides the largest database of APA in plants, including rice, Arabidopsis, <span style="font-style:italic">Medicago truncatula</span>, and <span style="font-style:italic">Chlamydomonas reinhardtii</span> (see <a href="./index.php">datasets</a>).</p>
                        <p id="dataset">A computational pipeline was implemented in Perl to detect poly(A) sites from next generation sequencing (NGS) or EST data. Reads with T stretch at the 5’ end or A stretch at the 3’ end are filtered and the A/T stretches trimmed off (step 1). After obtaining reads that can be uniquely mapped to the reference genome, coordinates of candidate poly(A) sites can be obtained. Candidate poly(A) sites that represent possible internal priming by reverse transcriptase are discarded (step 2). Further, to reduce the impact of microheterogeneity, a snowball-like method was adopted to cluster poly(A) tags within a given distance into a poly(A) site cluster (PAC) (step 3). PACs are annotated based on the refined genome annotation to determine their genomic locations (3’ UTR, CDS, intron, AMB, or intergenic).</p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/pipeline.png"/>
                            <br>Pipeline to determine PAC and the PAC table
                        </div>
                        <p id='datasets'>
                            <table cellspacing="1" cellpadding="0" border="0" style="border:1px dotted #5db95b;margin: 10px 1%;">
                                <thead>
                                    <tr class="theme" bgcolor="#5db95b">
                                        <td class="theme"  height="24">Label</td>
                                        <td class="theme"  height="24">Tissue</td>
                                        <td class="theme"  height="24">Reference</td>
                                        <td class="theme"  height="24">PATs</td>
                                        <td class="theme"  height="24">PACs</td>
                                        <td class="theme"  height="24">Description</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="6" style="background-color:#f2dede">Arabidopsis thaliana (Genome annotation: TAIR 10; all raw data were sequenced by PAT-seq)</td>
                                    </tr>
                                    <tr>
                                        <td>wt leaf 1</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_leaf_1%20PAT%20minus%20strand%2Cwt_leaf_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>1055461</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,wt_leaf_1_PAC" target="_blank">11570</a></td>
                                        <td>Plants were grown in soil in a climate-controlled growth room under short-day (8-h daylight) conditions, or under sterile conditions by germinating seeds, to capture as broad a range of poly(A) sites in leaves as possible.</td>
                                    </tr>
                                    <tr>
                                        <td>wt leaf 2</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1073%2Fpnas.1019732108">Wu et al. PNAS, 2011</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_leaf_2%20PAT%20minus%20strand%2Cwt_leaf_2%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>927476</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,wt_leaf_2_PAC" target="_blank">13678</a></td>
                                        <td>Plants were grown in soil in a climate-controlled growth room under short-day (8-h daylight) conditions, or under sterile conditions by germinating seeds, to capture as broad a range of poly(A) sites in leaves as possible.</td>
                                    </tr>
                                    <tr>
                                        <td>wt leaf 3</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_leaf_3%20PAT%20minus%20strand%2Cwt_leaf_3%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>24269</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,wt_leaf_3_PAC" target="_blank">3989</a></td>
                                        <td>Plants were grown in soil in a climate-controlled growth room under short-day (8-h daylight) conditions, or under sterile conditions by germinating seeds, to capture as broad a range of poly(A) sites in leaves as possible.</td>
                                    </tr>
                                    <tr>
                                        <td>wt seed 1</td>
                                        <td>seed</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_seed_1%20PAT%20minus%20strand%2Cwt_seed_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>332010</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,wt_seed_1_PAC" target="_blank">7567</a></td>
                                        <td>RNA was isolated from dried Arabidopsis seed.</td>
                                    </tr>
                                    <tr>
                                        <td>wt seed 2</td>
                                        <td>seed</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1073%2Fpnas.1019732108">Wu et al. PNAS, 2011</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_seed_2%20PAT%20minus%20strand%2Cwt_seed_2%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>2230409</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,wt_seed_2_PAC" target="_blank">15106</a></td>
                                        <td>RNA was isolated from dried Arabidopsis seed.</td>
                                    </tr>
                                    <tr>
                                        <td>wt root 1</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_root_1%20PAT%20minus%20strand%2Cwt_root_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>16232735</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,wt_root_1_PAC" target="_blank">53850</a></td>
                                        <td>Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td>wt root 2</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_root_2%20PAT%20minus%20strand%2Cwt_root_2%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>10700327</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,wt_root_2_PAC" target="_blank">18993</a></td>
                                        <td>Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td>wt root 3</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cwt_root_3%20PAT%20minus%20strand%2Cwt_root_3%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>12547544</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,wt_root_3_PAC" target="_blank"/>55747</td>
                                        <td>Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6 root 1</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_root_1%20PAT%20minus%20strand%2Coxt6_root_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>5861620</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,oxt6_root_1_PAC" target="_blank"/>23428</td>
                                        <td>A mutant deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6 root 2</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_root_2%20PAT%20minus%20strand%2Coxt6_root_2%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>10174020</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,oxt6_root_2_PAC" target="_blank"/>18923</td>
                                        <td>A mutant deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6 root 3</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_root_3%20PAT%20minus%20strand%2Coxt6_root_3%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>5653903</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,oxt6_root_3_PAC" target="_blank"/>48191</td>
                                        <td>A mutant deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6 leaf 1</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_leaf_1%20PAT%20minus%20strand%2Coxt6_leaf_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>1222315</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,oxt6_leaf_1_PAC" target="_blank"/>13112</td>
                                        <td>A mutant deﬁcient in CPSF30 expression. Seedlings were germinated and grown in growth chambers set at 22 ℃ under continuous light.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6 leaf 2</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_leaf_2%20PAT%20minus%20strand%2Coxt6_leaf_2%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>74911</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,oxt6_leaf_2_PAC" target="_blank"/>6950</td>
                                        <td>A mutant deﬁcient in CPSF30 expression. Seedlings were germinated and grown in growth chambers set at 22 ℃ under continuous light.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6 leaf 3</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/23136375">Thomas et al. Plant Cell, 2012</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Coxt6_leaf_3%20PAT%20minus%20strand%2Coxt6_leaf_3%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>2569857</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,oxt6_leaf_3_PAC" target="_blank"/>15788</td>
                                        <td>A mutant deﬁcient in CPSF30 expression. Seedlings were germinated and grown in growth chambers set at 22 ℃ under continuous light.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6::C30G 1</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cg1%20PAT%20plus%20strand%2Cg1%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>2277879</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,g1_PAC" target="_blank"/>24681</td>
                                        <td>Transgenes that encode the wild-type AtCPSF30 were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6::C30G 2</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cg2%20PAT%20plus%20strand%2Cg2%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>4324508</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,g2_PAC" target="_blank"/>18838</td>
                                        <td>Transgenes that encode the wild-type AtCPSF30 were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6::C30G 3</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cg3%20PAT%20plus%20strand%2Cg3%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>5510456</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,g3_PAC" target="_blank"/>47620</td>
                                        <td>Transgenes that encode the wild-type AtCPSF30 were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6::C30GM 1</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cgm1%20PAT%20plus%20strand%2Cgm1%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>5360339</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,gm1_PAC" target="_blank"/>23679</td>
                                        <td>A mutant deficient in its interaction with calmodulin were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6::C30GM 2</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cgm2%20PAT%20plus%20strand%2Cgm2%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>5204727</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,gm2_PAC" target="_blank"/>16862</td>
                                        <td>A mutant deficient in its interaction with calmodulin were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td>oxt6::C30GM 3</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0115779">Liu et al. PloS One, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data/arab&tracks=Gene%20annotation%2CDNA%2Cgm3%20PAT%20plus%20strand%2Cgm3%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>6886779</a></td>
                                        <td><a href="/jbrowse/?data=data/arab&tracks=PlantAPA stored PAC,gm3_PAC" target="_blank"/>49522</td>
                                        <td>A mutant deficient in its interaction with calmodulin were introduced into the oxt6 mutant that is deﬁcient in CPSF30 expression. Total RNA were isolated from 10-day old roots.</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="background-color:#f2dede">Oryza sativa (Genome annotation: MSU v7; raw data were ESTs or RNA-seq reads)</td>
                                    </tr>
                                    <tr>
                                        <td>from EST</td>
                                        <td>mix</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1093%2Fnar%2Fgkn158">Shen et al. Plant Cell, 2012</a></td>
                                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cfrom_EST%20PAT%20minus%20strand%2Cfrom_EST%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target='_blanck'>57852</a></td>
                                        <td><a href="/jbrowse/?data=data/japonica&tracks=PlantAPA stored PAC,From_EST_PAC" target="_blank"/>28616</td>
                                        <td>ESTs and partial or complete cDNA sequences, were collected from GenBank.</td>
                                    </tr>
                                    <tr>
                                        <td>from RNAseq</td>
                                        <td>mix</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/22443345">Davidson et al. Plant J, 2012</a></td>
                                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cfrom_RNAseq%20PAT%20plus%20strand%2Cfrom_RNAseq%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>47180</a></td>
                                        <td><a href="/jbrowse/?data=data/japonica&tracks=PlantAPA stored PAC,From_RNAseq_PAC" target="_blank"/>11870</td>
                                        <td>Poly(A) sites collected from RNA-seq reads of leaf, endosperm, embryo, seed, pistil, anther, and inflorescence tissues.</td>
                                    </tr>
                                    <tr>
                                        <td>flower buds</td>
                                        <td>flower</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cflower_buds%20PAT%20plus%20strand%2Cflower_buds%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>153823</a></td>
                                        <td><a href="/jbrowse/?data=data/japonica&tracks=PlantAPA stored PAC,flower_buds_PAC" target="_blank"/>25115</td>
                                        <td>Poly(A) sites collected from RNA-seq reads. Seeds from the cultivated rice subspecies Oryza sativa L. ssp. Japonica cultivar Nipponbare were grown in a greenhouse in Singapore under natural light conditions. Flower buds were collected before ﬂowering.</td>
                                    </tr>
                                    <tr>
                                        <td>flowers</td>
                                        <td>flower</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cflowers%20PAT%20plus%20strand%2Cflowers%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>124224</a></td>
                                        <td><a href="/jbrowse/?data=data/japonica&tracks=PlantAPA stored PAC,flowers_PAC" target="_blank"/>22116</td>
                                        <td>Poly(A) sites collected from RNA-seq reads of flower tissue.</td>
                                    </tr>
                                    <tr>
                                        <td>leaves before flowering</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cleaves_before_flowering%20PAT%20plus%20strand%2Cleaves_before_flowering%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>139209</a></td>
                                        <td><a href="/jbrowse/?data=data/japonica&tracks=PlantAPA stored PAC,leaves_before_flowering_PAC" target="_blank"/>21345</td>
                                        <td>Poly(A) sites collected from RNA-seq reads of leaves. The before-ﬂowering sample was deﬁned as a mixture of different stages in a period from panicle initiation to 1 day before ﬂowering.</td>
                                    </tr>
                                    <tr>
                                        <td>leaves after flowering</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cleaves_after_flowering%20PAT%20plus%20strand%2Cleaves_after_flowering%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>127962</a></td>
                                        <td><a href="/jbrowse/?data=data/japonica&tracks=PlantAPA stored PAC,leaves_after_flowering_PAC" target="_blank"/>21147</td>
                                        <td>Poly(A) sites collected from RNA-seq reads of leaves. The after-ﬂowering sample was deﬁned as a mixture of different stages after the ﬂowering day.</td>
                                    </tr>
                                    <tr>
                                        <td>roots before flowering</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Croots_before_flowering%20PAT%20plus%20strand%2Croots_before_flowering%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>168028</a></td>
                                        <td><a href="/jbrowse/?data=data/japonica&tracks=PlantAPA stored PAC,roots_before_flowering_PAC" target="_blank"/>23692</td>
                                        <td>Poly(A) sites collected from RNA-seq reads of roots. The before-ﬂowering sample was deﬁned as a mixture of different stages in a period from panicle initiation to 1 day before ﬂowering.</td>
                                    </tr>
                                    <tr>
                                        <td>roots after flowering</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Croots_after_flowering%20%20PAT%20minus%20strand%2Croots_after_flowering%20PAT%20plus%20strand' title="View in jbrowse" target='_blanck'>114200</a></td>
                                        <td><a href="/jbrowse/?data=data/japonica&tracks=PlantAPA stored PAC,roots_after_flowering_PAC" target="_blank"/>19770</td>
                                        <td>Poly(A) sites collected from RNA-seq reads of roots. The after-ﬂowering sample was deﬁned as a mixture of different stages after the ﬂowering day.</td>
                                    </tr>
                                    <tr>
                                        <td>milk grains</td>
                                        <td>grain</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cmilk_grains%20PAT%20plus%20strand%2Cmilk_grains%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>163445</a></td>
                                        <td><a href="/jbrowse/?data=data/japonica&tracks=PlantAPA stored PAC,milk_grains_PAC" target="_blank"/>15720</td>
                                        <td>Poly(A) sites collected from RNA-seq reads of grains.</td>
                                    </tr>
                                    <tr>
                                        <td>mature seeds</td>
                                        <td>seed</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387578">Wang et al. Plant J, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data/japonica&tracks=DNA%2CGene%20annotation%2Cmature_seeds%20PAT%20plus%20strand%2Cmature_seeds%20%20PAT%20minus%20strand' title="View in jbrowse" target='_blanck'>140487</a></td>
                                        <td><a href="/jbrowse/?data=data/japonica&tracks=PlantAPA stored PAC,mature_seeds_PAC" target="_blank"/>18778</td>
                                        <td>Poly(A) sites collected from RNA-seq reads of seeds.</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="background-color:#f2dede">Medicago truncatula (Genome annotation: JCVI Medtr v4; raw data were sequenced from PAT-seq or RNA-seq)</td>
                                    </tr>
                                    <tr>
                                        <td>wt leaf 1</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1186%2F1471-2164-15-615">Wu et al. BMC Genomics, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cwt_leaf_1%20PAT%20minus%20strand%2Cwt_leaf_1%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target="_blank">3146287</a></td>
                                        <td><a href="/jbrowse/?data=data/mtr&tracks=PlantAPA stored PAC,wt_leaf_1_PAC" target="_blank"/>44685</td>
                                        <td>RNA was isolated from the combined leaves and washed roots of 3-4 week-old nodule-free plants.</td>
                                    </tr>
                                    <tr>
                                        <td>hairy root</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26387579">Mertens et al. Plant Physiology, 2016</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Chairy_root%20PAT%20plus%20strand%2Chairy_root%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">6690</a></td>
                                        <td><a href="/jbrowse/?data=data/mtr&tracks=PlantAPA stored PAC,hairy_root_PAC" target="_blank"/>3262</td>
                                        <td>Independent hairy root lines expressing a non-functional GUS gene.</td>
                                    </tr>
                                    <tr>
                                        <td>leaf CK</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cleaf_CK%20PAT%20plus%20strand%2Cleaf_CK%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">29059</a></td>
                                        <td><a href="/jbrowse/?data=data/mtr&tracks=PlantAPA stored PAC,leaf_CK_PAC" target="_blank"/>4099</td>
                                        <td>mRNA isolated from leaves of seedlings treated with control (CK).</td>
                                    </tr>
                                    <tr>
                                        <td>leaf OS</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cleaf_OS%20PAT%20plus%20strand%2Cleaf_OS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">51490</a></td>
                                        <td><a href="/jbrowse/?data=data/mtr&tracks=PlantAPA stored PAC,leaf_OS_PAC" target="_blank"/>7225</td>
                                        <td>mRNA isolated from leaves of M. truncatula seedlings treated with osmotic stress (OS).</td>
                                    </tr>
                                    <tr>
                                        <td>leaf SS</td>
                                        <td>leaf</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Cleaf_SS%20PAT%20plus%20strand%2Cleaf_SS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">37338</a></td>
                                        <td><a href="/jbrowse/?data=data/mtr&tracks=PlantAPA stored PAC,leaf_SS_PAC" target="_blank"/>4911</td>
                                        <td>mRNA isolated from leaves of seedlings treated with salt stress (SS).</td>
                                    </tr>
                                    <tr>
                                        <td>root CK</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Croot_CK%20PAT%20plus%20strand%2Croot_CK%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">50540</a></td>
                                        <td><a href="/jbrowse/?data=data/mtr&tracks=PlantAPA stored PAC,root_CK_PAC" target="_blank"/>9877</td>
                                        <td>mRNA isolated from roots of seedlings treated with control (CK).</td>
                                    </tr>
                                    <tr>
                                        <td>root OS</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Croot_OS%20PAT%20plus%20strand%2Croot_OS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">42947</a></td>
                                        <td><a href="/jbrowse/?data=data/mtr&tracks=PlantAPA stored PAC,root_OS_PAC" target="_blank"/>9413</td>
                                        <td>mRNA isolated from roots of seedlings treated with osmotic stress (OS).</td>
                                    </tr>
                                    <tr>
                                        <td>root SS</td>
                                        <td>root</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/26048392">Wang et al.  BMC Plant Biology, 2015</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fmtr&tracks=DNA%2CGene%20annotation%2Croot_SS%20PAT%20plus%20strand%2Croot_SS%20%20PAT%20minus%20strand' title="View in jbrowse" target="_blank">47675</a></td>
                                        <td><a href="/jbrowse/?data=data/mtr&tracks=PlantAPA stored PAC,root_SS_PAC" target="_blank"/>13287</td>
                                        <td>mRNA isolated from roots of seedlings treated with salt stress (SS).</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="background-color:#f2dede">Chlamydomonas reinhardtii (Genome annotation: Creinhardtii 281 v55)</td>
                                    </tr>
                                    <tr>
                                        <td>from illumina</td>
                                        <td>mix</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2Cfrom_illumina%20PAT%20plus%20strand%2Cfrom_illumina%20%20PAT%20minus%20strand&highlight=' title="View in jbrowse" target="_blank">622248</a></td>
                                        <td><a href="/jbrowse/?data=data/chlamy&tracks=PlantAPA stored PAC,From_illumina_PAC" target="_blank"/>35630</td>
                                        <td>Illumina data were from DNAnexus (<a href='http://sra.dnanexus.com'/>http://sra.dnanexus.com/</a>)</td>
                                    </tr>
                                    <tr>
                                        <td>from 454</td>
                                        <td>mix</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2CGene%20annotation%2CFrom_454%20PAT%20minus%20strand%2CFrom_454%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target="_blank">324305</a></td>
                                        <td><a href="/jbrowse/?data=data/chlamy&tracks=PlantAPA stored PAC,From_454_PAC" target="_blank"/>20423</td>
                                        <td>454 data were from DNAnexus (<a href='http://sra.dnanexus.com'/>http://sra.dnanexus.com/</a>) or Dr. Olivier Vallon from Institut de Biologie Physico-Chimmique.</td>
                                    </tr>
                                    <tr>
                                        <td>from EST</td>
                                        <td>mix</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1534%2Fg3.114.010249">Zhao et al. G3:Genes|Genomes|Genetics, 2014</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2CGene%20annotation%2CFrom_EST%20PAT%20minus%20strand%2CFrom_EST%20PAT%20plus%20strand&highlight=' title="View in jbrowse" target="_blank">56754</a></td>
                                        <td><a href="/jbrowse/?data=data/chlamy&tracks=PlantAPA stored PAC,From_EST_PAC" target="_blank"/>9512</td>
                                        <td>ESTs were collected from both JGI and NCBI GenBank.</td>
                                    </tr>
                                    <tr>
                                        <td>from PAT-seq</td>
                                        <td>mix</td>
                                        <td><a target="_blank" href="http://www.ncbi.nlm.nih.gov/pubmed/?term=10.1371%2Fjournal.pone.0146107">Bell et al. PloS one, 2016</a></td>
                                        <td><a href='../jbrowse/?data=data%2Fchlamy&tracks=DNA%2CGene%20annotation%2CFrom_PATseq%20PAT%20plus%20strand%2CFrom_PATseq%20%20PAT%20minus%20strand&highlight=' title="View in jbrowse" target="_blank">12532698</a></td>
                                        <td><a href="/jbrowse/?data=data/chlamy&tracks=PlantAPA stored PAC,From_PATseq_PAC" target="_blank"/>14820</td>
                                        <td>Poly(A) sites in in cultures grown in four different media types: Tris-Phosphate (TP), Tris-Phosphate-Acetate (TAP), High-Salt (HS), and High-Salt-Acetate (HAS).</td>
                                    </tr>
                                </tbody>
                            </table>
                        </p>
                        <p id="download">Users can download PATs, PACs, and relevent sequences from the <a href="./download.php">download</a> page.</p> 
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/download.png"/>
                            <br>Datasets for downloading in PlantAPA
                        </div>
                      </div>
                        <div class="jumpto-block">
                        <h2>2. <span>Refined gene model</span>: gene model with AMB region and extended 3' UTR</h2>
                        <p id="refined">The genome annotations were refined to determine AMBiguous regions and extended 3’ UTR regions to enable more accurate mapping of PACs. If a region is not the same among different transcripts of the same gene, it is denoted as an AMB region. The incorporation of an AMB region prevents double counting of poly(A) sites corresponding to multiple transcripts from the same gene. Furhter, genes that had no annotated 3’ UTR downstream from the ends of the protein-coding regions were extended by the average length of 3’ UTRs in the respective species. Also, genes that had annotated 3’ UTR were extended by an arbitrary length as described in previous studies. These steps can improve the “recovery” of poly(A) sites that fall within authentic 3’ UTRs. Therefore, users can query and visualize poly(A) sites in extended 3’ UTR regions, ambiguous regions owing to alternative transcription or RNA processing, which enables users to explore more deeply the mechanistic of polyadenylation events in previously overlooked genomic regions. We used this modified version of genome annotation to better annotate poly(A) sites in a majority of PlantAPA modules, while the original genome annotation was also presented in the PAC browse module as an alternative way for users to display poly(A) sites.</p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/refine1.png"/>
                            <br>Schema of refined gene model with AMBiguous regions<br><br>
                            <img class="pic" src="./pic/help/refine2.png"/>
                            <br>Schema of refined gene model with AMBiguous regions and extended 3’UTR  (Click <a href="./sequence_detail.php?species=arab&seq=AT1G08970&method=search">here</a> for an example)
                        </div>
                      </div>
                      <div class="jumpto-block">
                        <h2>3. <span>PAC trap</span>: extracting poly(A) sites from uploaded sequences</h2>
                        <!--<h3>2.1. Start a new task for poly(A) site extraction</h3>-->
                        <!--<p>1) Choose file type and species-->
                        <p id="trapstep1">
                            In the <a href="./upload_option.php">PAC trap</a> module, users can upload two kinds of sequences, short reads and ESTs. You can also click “Try an example” button to load the demo data. In addition, you can upload a file to specific coordinates of poly(A) sites. Also, the supported species are listed in the drop-down box.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/species.png"/>
                            <br>Panel of choosing species and file types
                        </div>
                        <!--<p>2) Input-->
                        <p id="trapstep2">
                        There are two input ways provided for users. The first one serves for users to upload a file where sequences are stored.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/input.bmp"/>
                            <br>Panel of file uploading
                        </div>
                        <p>
                        PlantAPA allows users to upload more than one files, each file can be assigned two labels, one denoting the respective sample (ex. leaf_replicate1), the other denoting its group.Assigning each file with sample or group label is useful in the analysis of APA, for example, detecting DE genes or PACs, and is also useful in visualizing poly(A) sites from individual groups in our graphic module. 
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/group.bmp"/>
                            <br>Adding group label for each input file
                        </div>
                        <p>
                        The second one serves for users to paste sequencs in the textbox if they do not prepare a file. It is of note that only one sample can be input if this way is used.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/text.bmp"/>
                            <br>Inputting sequences in a textbox
                        </div>
                        <!--<p>3) Options for mapping reads (optional)-->
                        <p>
                            Users can also provide the poly(A) site file directly. In this way, PlantAPA will skip the extraction pipeline and load the input sites to a user database directly. Then users can browse, visualize, and analyze their own poly(A) sites through the PAC browse module, PAC viewer module, and PAC analysis module in PlantAPA.
                            <div class="picdiv">
                                <img class="pic" src="./pic/help/upload_polya.png"/>
                                <br>Inputting poly(A) sites, each line is Chr, Strand, Coordinate, Number of PATs
                            </div>
                        </p>
                        <p id="trapstep3">
                        If sequences are uploaded, users can use the default parameters set by PlantAPA or specific the parameters for mapping reads.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/fastq_option.png"/>
                            <br>Options of uploading short read
                            <br><br>
                            <img class="pic" src="./pic/help/est_option.png"/>
                            <br>Options of uploading EST
                        </div>
                        <!--<h3>2.2. Get a task by task ID</h3>-->
                        <p id="task">
                            Each time, when a user visits PlantAPA to conduct a poly(A) site extraction, an unique task ID will be assigned. In our server, a folder in the same name as this project name will be created to hold all relevant data for the poly(A) site extraction. The user can further obtain all results of this task by searching the task ID in the “<a href="./task.php">My task</a>” page.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/task.bmp"/>
                            <br>Data retrieving page
                        </div>
                        <p>
                            All tasks started from the same IP address are also listed in the “<a href="./task.php">My task</a>” page for the user to retrieve at any time.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/task_list.png"/>
                            <br>Task hitory of users' results
                        </div>
                        <!--<h3>2.3. Outputs of poly(A) site extraction pipeline</h3>-->
                        <p>Upon the completion of the PAC extraction process, users can download the PAC list directly from the web site onto their local computers.
                        </p>
                        <p>
                        Also, additional information, such as mapping summary, single nucleotide compositions around PACs, top hexamers upstream of PACs, will be displayed in the result page to facilitate users to evaluate their own data.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/result.bmp"/>
                            <br>Result page of a PAC trap task (Click <a href="./demo.php?method=trap">here</a> to view an example)
                        </div>
                        <p>
                        By following the web link on a particular PAC, users can continue to use other seamlessly integrated modules for PAC visualization and mining. Particularly, if there are multiple input files, users can further compare poly(A) site usage between their input libraries or with PlantAPA-provided libraries through the PAC analysis module.</p>
                      </div>
                      <div class="jumpto-block">
                        <h2>4. <span>PAC search</span>: searching PlantAPA</h2>
                        <!--<h3>3.1. Multi-keywords search</h3>-->
                        <p id="searchmodule">The <a href="./search.php">PAC search</a> module allows users to query genes or PACs in the data sets of interest by a variety of keywords, such as gene ID, chromosome fragment, gene functions, GO term, and GO ID.Batch search is supported, where lists of Gene IDs, GO terms, or GO IDs can be provided as query input.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/search.bmp"/>
                            <br>Search panel
                        </div>
                        <!--<h3>3.2. Batch search</h3>-->
                        <!--<h3>3.3. Fuzzy search</h3>-->
                        <p>Fuzzy search, is also permitted, which allows user to search various database entries by a single keyword.</p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/fuzzy_search.bmp"/>
                            <br>Fuzzy search panel
                        </div>
                        <!--<h3>3.4. Search result</h3>-->
                        <p>
                            The query output is a media page that lists all the matched PACs in a dynamic table, where users can choose to view detailed information (poly(A) signals, sequences, expression patterns, etc.) and graphics of PACs and the corresponding gene by clicking the link on a PAC of interest. The PAC list tabulating all PACs in genomic regions as well as intergenic regions would facilitate the inspection of polyadenylation events associated with novel transcripts, lincRNAs, or antisense transcription.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/search_result.png"/>
                            <br>Search result page (Click <a href='search_process.php?method=fuzzy&keyword=Chr1&species=arab'>here</a> to view an example)
                        </div>
                      </div>
                      <div class="jumpto-block">
                        <h2>5. <span>Exporting sequences</span></h2>
                        <p>To facilitate further inspection poly(A) site or genes, we designed a toolbar above the result PAC list which allows users to input a keyword to locate entries within the list and export sequences of interest. Users can export various kinds of sequences onto their local computers for other analysis purpose.
                        </p>
                        <!--<h3>4.1. Export sequences of PACs</h3>-->
                        <p>Sequences regarding PACs are exportable, including upstream and downstream sequences around PACs, sequences of PACs in a defined region (3’ UTR, 5’ UTR, CDS, intron, intergenic region, etc.).
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/export2.png"/>
                            <br>Panel of exporting sequences of PACs
                        </div>
                        <!--<h3>4.2. Export sequences of regions of PACs</h3>-->
                        <p>Users can also export sequences of genomic regions where PACs are located.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/export3.png"/>
                            <br>Panel of exporting sequences of genomic regions of PACs
                        <!--<h3>4.3. Export gene sequences</h3>-->
                        </div>
                        <p>Sequences of genes with PACs based on original version or the extended version of genome annotation are also exportable.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/export1.png"/>
                            <br>Panel of exporting gene sequences
                        </div>
                      </div>
                      <div class="jumpto-block">
                        <h2>6. <span>PAC browse</span>: browsing PACs in the PAC browser</h2>
                        <p id="browse">Users can have a quick access to the PAC browser by clicking the “<a href="./browse.php">PAC browse</a>” tab in the main menu or the “View” link in a PAC list.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/browse.bmp"/>
                            <br>Web page of the PAC browser
                        </div>
                        <p>
                            One or more data sets from each plant species can be quickly loaded and graphically browsed online, by selecting the checkboxes of data sets in the ‘Available Tracks’ panel. Users can conduct a search with a gene or chromosome fragment to zoom in on particular PAC regions. Data tracks of PACs from different cells, tissues or conditions can be displayed in sync with tracks of PATs, offering a more intuitive way to explore and compare the usage of PACs among different samples. Users can download the data of one or more tracks onto their local computers.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/jbrowsesave.png"/>
                            <br>Right-click context menu on a gene model or PAC 
<!--                            <br><br>
                            <img class="pic" src="./pic/help/browse2pac.png"/>
                            <br>Link of view gene details-->
                        </div>
                      </div>
                      <div class="jumpto-block">
                        <h2>7. <span>PAC viewer</span>: quantification and visualization of PACs across different conditions</h2>
                        <p id="pacviewer">By following the web link on a particular PAC or gene, a user can inspect various graphics and detailed information of the PACs in a gene or in a intergenic region, such as gene/PAC sequence, poly(A) signals, and PAT distributions across diverse conditions in the <a href="./sequence_detail.php?species=arab&seq=AT1G04480&method=search">PAC viewer</a> module.
                        Summary information about the PAC and the associated gene was given at the top of the web page. </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/gene_summary.bmp"/>
                            <br>Summary information
                        </div>
                        <!--<h3>6.1. Graphical representation</h3>-->
                        <p id='seqresult'>Three kinds of graphs are displayed in the middle of the page to quantify and visualize the PAC/PAT distributions across samples.
                        One is a screenshot of a particular section of the PAC browser to show the intron-exon structure of a gene (if the PAC is located in genomic region) and the PAC/PAT tracks, which facilitates users to preview the gene model and locations of PACs before switching to the PAC browser.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/gene_browser.bmp"/>
                            <br>PAC browser
                        </div>
                        <p>
                            Another graph presents both the original and the modified versions of the gene model and the distributions of PACs/PATs across different samples in a more intuitive way, where users can inspect the locations, expression patterns, and differential usage of PACs in a gene, especially the selection of heterogeneous cleavage sites. Cleavage sites of a PAC are depicted in vertical lines with height representing the number of supporting PATs. The dominant cleavage site supported by the maximum number of PATs in a PAC is marked by a thick line. If the number of PATs of the dominant cleavage site exceeds the maximum scale value (default of 50) of the vertical axis, a small horizontal line will be shown on the top of the thick line. A text label is found under each dominant cleavage site to clearly indicate the expression level, i.e., total number of supporting PATs, of the respective PAC.Users can also view selected samples by choosing specific samples in the ‘Individual’ drop-down list or group replicates within each individual sample by clicking the ‘Grouping’ drop-down list.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/pat_distribution.png"/>
                            <br>Graph presents both the gene model and the distributions of PACs/PATs across different samples
                        </div>
                        <p>
                            An additional bar chart is presented to profile the usage quantification of all PACs of the queried gene across different samples, providing a simple and direct way to compare the usage of PACs and determine ubiquitous or sample-specific PACs. By default, the bar chart displays the number of PATs of all samples. By checking the “Ratio” box, users can compare the usage of PACs within each sample to avoid great disparity in PAT number among samples.
                        </p>
                        <div class="picdiv">
                            <br><img class="pic" src="./pic/help/pac_usage.bmp"/>
                            <br>Bar chart profiles the usage quantification of all PACs in a gene
                        </div>
                        <!--<h3>6.2. Gene sequence viewer</h3>-->
                        <p>
                            PlantAPA provides the gene sequence annotated with exon-intron structure and 3’ UTR, poly(A) signals and positions. By default the most dominant poly(A) signal, AATAAA, and its 1 nt variants are scanned to obtain poly(A) signals. Users can also specify additional patterns to locate possible poly(A) signals. Users can set a region around poly(A) sites to narrow the scope of poly(A) signal search by dragging the slider. Further, users can choose to highlight genic regions, e.g., intron, exon, or 3’UTR, cleavage sites, and poly(A) signals in different styles or colors in the corresponding sequence, facilitating manual inspection of the sequence of poly(A) sites in different genic locations. Particularly, heterogeneous cleavage sites of each PAC are in pink background, and the most dominant cleavage site in each PAC is denoted in red and highlighted in yellow.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/gene_viewer.bmp"/>
                            <br>Gene sequence annotated with gene model, poly(A) signals, and cleavage sites
                        </div>
                    </div>
                    <div class="jumpto-block">
                        <h2>8. <span>PAC analysis</span>: analysis of APA switching between two conditions</h2>
                        <p id="analysistitle">Following the “<a href="./analysis.php">PAC analysis</a>” tab in the main menu, users can choose to generate lists of differentially expressed genes, PACs with differential usage, genes with 3’ UTR lengthening or shortening, and APA-site switching genes, using the user uploaded PACs together with PlantAPA stored PACs.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/analysispic.png"/>
                            <br>Schema of four functions in PAC analysis module
                        </div>
                        <p>
                        Two groups of samples each with one or more than one condition need to be selected in order to obtain the output gene/PAC lists. 
                        Raw count or normalized count based on TPM normalization and methods in EdgeR or DESeq can be chosen as input. Additional parameters can be set for each assay, such as minimum number of PATs for pre-filtering of PACs, significance level, p-value adjusted method.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/analysis.png"/>
                            <br>Page of PAC analysis module 
                        </div>
                        <p id="analysishelp1">
                            Before analyzing PACs between two samples, users can filter PACs or genes by various combination of filtering conditions.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/analysis_additional_option.png"/>
                            <br>Additional options for PAC analysis
                        </div>
                        <p id='analysishelp2'>
                        To make the result statistically significant, a p-value or adjusted p-value will be calculated and assigned to each PAC/gene. Users can download the output lists to their local computer, or continue to inspect the result PAC/gene by clicking the link on a particular item in the list.
                        </p>
                        <!--<h3>7.1. Detection of differentially expressed genes</h3>-->
                        <p>After choosing two groups of samples, users can specific parameters for DE gene detection.
                        The main output is a table that lists all result DE genes.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/analysis_result1.png"/>
                            <br>Result page of 'DE Gene' function (Click <a href="demo.php?method=degene">here</a> for an example)
                        </div>
                        <!--<h3>7.2. Detection of PACs with differential usage</h3>-->
                        <p id='analysishelp3'>Users can specific parameters for detecting PACs with differential usage.
                        The main output is a table that lists all result PACs.</p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/analysis_result2.png"/>
                            <br>Result page of 'DE PAC' function (Click <a href="demo.php?method=depac">here</a> for an example)
                        </div>
                        <!--<h3>7.3. Detection of genes with 3’ UTR lengthening or shortening</h3>-->
                        <p id='analysishelp4'>After choosing two groups of samples, users can specific parameters for detecting genes with tandem 3’ UTR switching.
                            The main output is a table that lists all genes with 3’ UTR lengthening or shortening and the associated PACs.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/analysis_result3.png"/>
                            <br>Result page of '3'UTR Lengthening' function (Click <a href="demo.php?method=only3utr">here</a> for an example)
                        </div>
                        <!--<h3>7.4. Detection of nonconnonical APA-site switching genes</h3>-->
                        <p id='analysishelp5'>After choosing two groups of samples, users can specific parameters for the detection of nonconnonical APA-site switching genes. These genes involve at least one PAC located in CDS or introns.
                            The main output is a table that lists all result genes and PACs.</p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/apaswitching.png"/>
                            <br>The panel of APA Switching
                            <br><br>
                            <img class="pic" src="./pic/help/analysis_result4.png"/>
                            <br>Result page of 'APA Switching' function (Click <a href="demo.php?method=none3utr">here</a> for an example)
                        </div>
                    </div>
                    </div>
        </div>
        </div>
	<script type="text/javascript">
	  $(document).ready( function() {
	    $(".page_container").jumpto({
	      firstLevel: "> h2 span",
	      animate: false,
                      offset: 150,
                      innerWrapper: ".jumpto-block",
	    });
	  });
    
 	</script>
        <?php
            include"./footer.php";
        ?>
    </body>
</html>
