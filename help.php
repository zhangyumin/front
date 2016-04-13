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
                        <p>PlantAPA is a web server for query, visualization, and analysis of poly(A) sites in plants, which can profile heterogeneous cleavage sites and quantify expression pattern of poly(A) sites across different conditions. To date, PlantAPA provides the largest database of APA in plants, including rice, Arabidopsis, Medicago truncatula, and Chlamydomonas reinhardtii (see <a href="./index.php">datasets</a>).</p>
                        <p id="download">Users can download PATs, PACs, and relevent sequences from the <a href="./download.php">download</a> page.</p>
                      </div>
                      <div class="jumpto-block">
                        <h2>2. <span>PAC trap</span>: extracting poly(A) sites from uploaded sequences</h2>
                        <!--<h3>2.1. Start a new task for poly(A) site extraction</h3>-->
                        <!--<p>1) Choose file type and species-->
                        <p id="trapstep1">
                            You can upload two kinds of sequences, short reads and ESTs. You can also click “Try an example” button to load the demo data. In addition, you can upload a file to specific coordinates of poly(A) sites. Also, the supported species are listed in the drop-down box.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/species.png"/>
                            <br>The species and files type selection panel
                        </div>
                        <!--<p>2) Input-->
                        <p id="trapstep2">
                        There are two input ways provided for users. The first one serves for users to upload a file where sequences are stored.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/input.bmp"/>
                            <br>The file uploading panel
                        </div>
                        <p>
                        PlantAPA allows users to upload more than one files, each file can be assigned two labels, one denoting the respective sample (ex. leaf_replicate1), the other denoting its group.Assigning each file with sample or group label is useful in the analysis of APA, for example, detecting DE genes or PACs, and is also useful in visualizing poly(A) sites from individual groups in our graphic module. 
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/group.bmp"/>
                            <br>Add group information
                        </div>
                        <p>
                        The second one serves for users to paste sequencs in the textarea if they do not prepare a file.  If data is provided, PlantAPA will skip the extraction pipeline and load the input sites to a user database directly.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/text.bmp"/>
                            <br>Area of pasting sequence
                        </div>
                        <!--<p>3) Options for mapping reads (optional)-->
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
                        <p>
                        Each time, when a user visits our website to conduct a poly(A) site extraction, an unique task ID will be assigned. In our server, a folder in the same name as this project name will be created to hold all relevant data for the poly(A) site extraction. The user can further obtain all results of this task by searching the task ID in the “My task” page.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/task.bmp"/>
                            <br>The data retrieving page
                        </div>
                        <p>
                        All tasks started from the same IP address are also listed in the “My task” page for the user to retrieve at any time.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/task_list.bmp"/>
                            <br>Task hitory of your results
                        </div>
                        <!--<h3>2.3. Outputs of poly(A) site extraction pipeline</h3>-->
                        <p>Upon the completion of the PAC extraction process, users can download the PAC list directly from the web site onto their local computers.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/download.png"/>
                            <br>The data download page
                        </div>
                        <p>
                        Also, additional information, such as mapping summary, single nucleotide compositions around PACs, top hexamers upstream of PACs, will be displayed in the result page to facilitate users to evaluate their own data.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/result.bmp"/>
                            <br>Results of your task
                        </div>
                        <p>
                        By following the web link on a particular PAC, users can continue to use other seamlessly integrated modules for PAC visualization and mining. Particularly, if there are multiple input files, users can further compare poly(A) site usage between their input libraries or with PlantAPA-provided libraries through the PAC analysis module.</p>
                      </div>
                      <div class="jumpto-block">
                        <h2>3. <span>PAC search</span>: searching PlantAPA</h2>
                        <!--<h3>3.1. Multi-keywords search</h3>-->
                        <p>The PAC search module allows users to query genes or PACs in the data sets of interest by a variety of keywords, such as gene ID, chromosome fragment, gene functions, GO term, and GO ID.Batch search is supported, where lists of Gene IDs, GO terms, or GO IDs can be provided as query input.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/search.bmp"/>
                            <br>The search panel
                        </div>
                        <!--<h3>3.2. Batch search</h3>-->
                        <!--<h3>3.3. Fuzzy search</h3>-->
                        <p>Fuzzy search, is also permitted, which allows user to search various database entries by a single keyword.</p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/fuzzy_search.bmp"/>
                            <br>The fuzzy search panel
                        </div>
                        <!--<h3>3.4. Search result</h3>-->
                        <p>Users can retrieve the search result of an example gene by clicking the button labeled ‘Example’, and view the subsets and detail descriptions of a data set by clicking the corresponding ‘More’- labeled icon. 
                        The query output is a media page that lists all the matched PACs in a dynamic table, where users can choose to view detailed information (poly(A) signals, sequences, expression patterns, etc.) and graphics of PACs and the corresponding gene by clicking the link on a PAC of interest. The PAC list tabulating all PACs in genomic regions as well as intergenic regions would facilitate the inspection of polyadenylation events associated with novel transcripts, lincRNAs, or antisense transcription.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/search_result.bmp"/>
                            <br>The search result page
                        </div>
                      </div>
                      <div class="jumpto-block">
                        <h2>4. <span>Exporting sequences</span></h2>
                        <p>To facilitate further inspection poly(A) site or genes, we designed a toolbar above the result PAC list which allows users to input a keyword to locate entries within the list and export sequences of interest. Users can export various kinds of sequences onto their local computers for other analysis purpose.
                        </p>
                        <!--<h3>4.1. Export sequences of PACs</h3>-->
                        <p>Sequences regarding PACs are exportable, including upstream and downstream sequences around PACs, sequences of PACs in a defined region (3’ UTR, 5’ UTR, CDS, intron, intergenic region, etc.).
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/export2.bmp"/>
                            <br>The panel of exporting sequences of PACs
                        </div>
                        <!--<h3>4.2. Export sequences of regions of PACs</h3>-->
                        <p>Users can also export sequences of genomic regions where PACs are located.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/export3.bmp"/>
                            <br>The panel of exporting sequences of regions of PACs
                        <!--<h3>4.3. Export gene sequences</h3>-->
                        </div>
                        <p>Sequences of genes with PACs based on original version or the extended version of genome annotation are also exportable.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/export1.bmp"/>
                            <br>The panel of exporting gene sequences
                        </div>
                      </div>
                      <div class="jumpto-block">
                        <h2>5. <span>PAC browse</span>: browsing PACs in the PAC browser</h2>
                        <p>Users can have a quick access to the PAC browser by clicking the “PAC browse” tab in the main menu or the “View” link in a PAC list.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/browse.bmp"/>
                            <br>The page of PAC browser
                        </div>
                        <p>
                        One or more data sets from each plant species can be quickly loaded and graphically browsed online, by selecting the checkboxes of data sets in the ‘Tracks’ panel. Users can conduct a search with a gene or chromosome fragment to zoom in on particular PAC regions. Data tracks of PACs from different cells, tissues or conditions can be displayed in sync with tracks of PATs, offering a more intuitive way to explore and compare the usage of PACs among different samples.
                        Users can download the data of one or more tracks onto their local computers, or choose to view detailed information of a gene or PAC by right clicking a PAC or gene model in the browser.   
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/browser_download.png"/>
                            <br>Ways of saving track data
<!--                            <br><br>
                            <img class="pic" src="./pic/help/browse2pac.png"/>
                            <br>Link of view gene details-->
                        </div>
                      </div>
                      <div class="jumpto-block">
                        <h2>6. <span>PAC viewer</span>: quantification and visualization of PACs across different conditions</h2>
                        <p>By following the web link on a particular PAC or gene, a user can inspect various graphics and detailed information of the PACs in a gene or in a intergenic region, such as gene/PAC sequence, poly(A) signals, and PAT distributions across diverse conditions in the PAC viewer module.
                        Summary information about the PAC and the associated gene was given at the top of the web page. </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/gene_summary.bmp"/>
                            <br>Gene summary information
                        </div>
                        <!--<h3>6.1. Graphical representation</h3>-->
                        <p id='seqresult'>Three kinds of graphs are displayed in the middle of the page to quantify and visualize the PAC/PAT distributions across samples.
                        One is a screenshot of a particular section of the PAC browser to show the intron-exon structure of a gene (if the PAC is located in genomic region) and the PAC/PAT tracks, which facilitates users to preview the gene model and locations of PACs before switching to the PAC browser.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/gene_browser.bmp"/>
                            <br>The display of the gene data in browser
                        </div>
                        <p>
                        Another graph presents both the original and the modified versions of the gene model and the distributions of PACs/PATs across different samples in a more intuitive way, where users can inspect the locations, expression patterns, and differential usage of PACs in a gene, especially the selection of heterogeneous cleavage sites. Cleavage sites in a PAC are depicted in vertical lines with height representing number of supporting PATs, and the dominant cleavage site that supported by maximum number of PATs in a PAC is highlighted in red. There is a text label under each PAC to clearly indicate its expression level (total number of supporting PATs). 
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/pat_distribution.png"/>
                            <br>The Details of PAT distribution
                        </div>
                        <p>
                        An additional bar chart is presented to profile the usage quantification of all PACs of the queried gene across different samples, providing a simple and direct way to compare the usage of PACs and further find the ubiquitous and specific PACs.
                        </p>
                        <div class="picdiv">
                            <br><img class="pic" src="./pic/help/pac_usage.bmp"/>
                            <br>The Details of PAC usage
                        </div>
                        <!--<h3>6.2. Gene sequence viewer</h3>-->
                        <p>PlantAPA provides the gene sequence annotated with exon-intron structure and 3’ UTR, poly(A) signals and positions. By default the most dominant poly(A) signal, AAUAAA, and its 1 nt variants are scanned to obtain poly(A) signals. Users can also specify other patterns to locate possible poly(A) signals. Further, users can choose highlight genomic regions (intron, exon, 3’ UTR etc.), cleavage sites, and poly(A) signals in different styles or colors in the corresponding gene sequence, facilitating manual inspection of poly(A) sites in different genomic locations. Particularly, the heterogeneous cleavage sites of each PAC are underlined, and the most dominant cleavage site in each PAC is highlighted in dark red and underlined in bold. 
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/gene_viewer.bmp"/>
                            <br>The page of sequence viewer
                        </div>
                    </div>
                    <div class="jumpto-block">
                        <h2>7. <span>PAC analysis</span>: analysis of APA switching between two conditions</h2>
                        <p id="analysishelp1">Following the “PAC analysis” tab in the main menu, users can choose to generate lists of differentially expressed genes, PACs with differential usage, genes with 3’ UTR lengthening or shortening, and APA-site switching genes, using the user uploaded PACs together with PlantAPA stored PACs.
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
                            <img class="pic" src="./pic/help/analysis.bmp"/>
                            <br>The page of analysis function
                            <br><br>
                            <img class="pic" src="./pic/help/analysis_additional_option.png"/>
                            <br>The panel of analysis addtional option
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
                            <br>The results of DE Gene
                        </div>
                        <!--<h3>7.2. Detection of PACs with differential usage</h3>-->
                        <p id='analysishelp3'>Users can specific parameters for detecting PACs with differential usage.
                        The main output is a table that lists all result PACs.</p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/analysis_result2.png"/>
                            <br>The results of DE PAC
                        </div>
                        <!--<h3>7.3. Detection of genes with 3’ UTR lengthening or shortening</h3>-->
                        <p id='analysishelp4'>After choosing two groups of samples, users can specific parameters for detecting genes with tandem 3’ UTR switching.
                            The main output is a table that lists all genes with 3’ UTR lengthening or shortening and the associated PACs.
                        </p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/analysis_result3.png"/>
                            <br>The results of 3'UTR Lengthening
                        </div>
                        <!--<h3>7.4. Detection of nonconnonical APA-site switching genes</h3>-->
                        <p id='analysishelp5'>After choosing two groups of samples, users can specific parameters for the detection of nonconnonical APA-site switching genes. These genes involve at least one PAC located in CDS or introns.
                            The main output is a table that lists all result genes and PACs.</p>
                        <div class="picdiv">
                            <img class="pic" src="./pic/help/apaswitching.png"/>
                            <br>The panel of APA Switching
                            <br><br>
                            <img class="pic" src="./pic/help/analysis_result4.png"/>
                            <br>The results of APA Switching
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
