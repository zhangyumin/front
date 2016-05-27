<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>PlantAPA-PAC browse</title>
        <script src="./src/jquery-1.10.1.min.js"></script>

        <link href="./src/index.css" rel="stylesheet" type="text/css" />

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
    <style>
        .table1{
            white-space: nowrap;
            text-align: center;
            font-weight: bold;
            background-color: #F9F9F9;
            BORDER-top: 2px solid #5db95b;
            BORDER-left: 1px solid #5db95b;
            BORDER-right: 1px solid #5db95b;
        }
        .table2{
            BORDER-bottom: 1px solid #5db95b;
        }
        .log h4{
                padding-bottom: 9px;
                margin: 40px 0 10px;
                border-bottom: 1px solid #5db95b;
        }
        .title{
            font-size: 15px;
            font-weight: bold;
            padding-left: 10px;
        }
        .text{
            font-size: 15px;
            padding-left: 10px;
        }
    </style>
    </head>
    <body>
        <?php
            include"navbar.php";
        ?>
        <div class="ym-wrapper">
            <table style="margin:20px 0px;">
                <tbody>
                    <tr>
                        <td class="table1" style="width: 350px;">Changelog of PlantAPA</td>
                        <td class="table2"></td>
                    </tr>
                </tbody>
            </table>
            <div class="log">
                <h4>V1.2.1 (2016-05-20)</h4>
                <span class="title">Add new poly(A) site datasets.</span>
                <br><span class="text">We have collected additional six samples from mutant samples for Arabidopsis and 18 samples from RNA-seq data for rice and Medicago.</span>
            </div>
            <div class="log">
                <h4>V1.2.0 (2016-03-28)</h4>
                <span class="title">Update the web UI and extensively debugging.</span>
                <br><span class="text">Change the logo, adjust the text, alter the layout of web pages, and extensively test the website.</span>
            </div>
            <div class="log">
                <h4>V1.1.9 (2016-02-20)</h4>
                <span class="title">Add new dataset for Chlamy</span>
                <br><span class="text">Add a new dataset from PAT-sequencing for Chlamy (Bell et al. PloS one, 2016).</span>
            </div>
            <div class="log">
                <h4>V1.1.8 (2016-02-12)</h4>
                <span class="title">Add PAC analysis module</span>
                <br><span class="text">Users can compare the general poly(A) site usage and changes in gene expression levels between libraries, combining user uploaded PACs, (if any) , and the collected PlantAPA collected PACs.</span>
            </div>
            <div class="log">
                <h4>V1.1.6 (2016-01-16)</h4>
                <span class="title">Update the web UI</span>
                <br><span class="text">Change the logo and alter the layout of web pages.</span>
            </div>
            <div class="log">
                <h4>V1.1.4 (2015-12-15)</h4>
                <span class="title">Add a toolbar for exporting sequences</span>
                <br><span class="text">Add a toolbar for all result tables for exporting sequences involving PACs or genes are exportable.</span>
            </div>
            <div class="log">
                <h4>V1.1.2 (2015-11-27)</h4>
                <span class="title">Update PAC trap module</span>
                <br><span class="text">A computational pipeline was implemented in Perl to detect poly(A) sites from ESTs.</span>
            </div>
            <div class="log">
                <h4>V1.1.0 (2015-10-21)</h4>
                <span class="title">Update PAC viewer page</span>
                <br><span class="text">Summary information is given on the top of the web page. Three kinds of graphs are displayed in the middle of the page. The bottom of the page presents the gene sequence annotated with gene model.</span>
            </div>
            <div class="log">
                <h4>V1.0.6 (2015-09-28)</h4>
                <span class="title">Add PAC search module</span>
                <br><span class="text">Users can query PACs by various kinds of keywords and choose to visualize the detailed information such as poly(A) signals, expression patterns, and sequence in the PAC viewer module.</span>
            </div>
            <div class="log">
                <h4>V1.0.4 (2015-07-16)</h4>
                <span class="title">Add PAC viewer module</span>
                <br><span class="text">Allow the user to inspect various graphics and detaled information of all PACs in a gene or intergenic region.</span>
            </div>
            <div class="log">
                <h4>V1.0.2 (2015-04-28)</h4>
                <span class="title">Add PAC trap module</span>
                <br><span class="text">A computational pipeline was implemented in Perl to detect poly(A) sites from next generation sequencing.</span>
            </div>
            <div class="log">
                <h4>V1.0.0 (2014-11-18)</h4>
                <span class="title">The first version online</span>
                <br><span class="text">Integrate a genome browser (Jbrowse) for dynamic browsing of PACs together with genomes, genes, transcripts and annotations on a genome-wide scale.</span>
            </div>
        </div>
        <?php
            include"footer.php";
            ?>
       </div>
    </body>
</html>