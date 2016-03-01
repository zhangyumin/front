<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>polyA browser</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <script src="./src/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="./src/slidebar-help/js/jquery.jumpto.js"></script>
        <link rel="stylesheet" type="text/css" href="./src/slidebar-help/css/jumpto.css" />
        <link rel="stylesheet" type="text/css" href="./src/slidebar-help/css/style.css" />
        <script type="text/javascript" src="./src/slidebar-help/js/modernizr.js"></script>
        <style>
            .pic{
                    -webkit-box-shadow: 3px 3px 4px #000;
            }
        </style>
    </head>
    <body>
        <?php include './navbar.php'; ?>
	<div class="main">
                    <div class="page_container">
                      <div class="jumpto-block">
                        <h1 style="text-align: center;">How to use PlantAPA</h1>
                        <span style="font: 25px bold;">Suggested Browsers</span>
                        <p>Advanced browsers, such as Chrome, Firefox, Safari, and Internet Explorer (10.0 or later) can be used for browsing PlantAPA. 
                       <br>Firefox and Chrome are recommended:
                       <br>Mozilla Firefox 3.5 or greater (<a href=http://www.mozilla.org>http://www.mozilla.org</a>)
                       <br>Google Chrome 6.0 or greater (<a href=http://www.google.com/chrome/>http://www.google.com/chrome/</a>)</p>
                      </div>
                      <div class="jumpto-block">
                        <h2>1. Introduction</h2>
                        <p>PlantAPA is a web server for query, visualization, and analysis of poly(A) sites in plants, which can profile heterogeneous cleavage sites and quantify expression pattern of poly(A) sites across different conditions. To date, PlantAPA provides the largest database of APA in plants, including rice, Arabidopsis, Medicago truncatula, and Chlamydomonas reinhardtii (see data sets).</p>
                      </div>
                      <div class="jumpto-block">
                        <h2>2. PAC trap: extracting poly(A) sites from uploaded sequences</h2>
                        <h3>2.1. Start a new task for poly(A) site extraction</h3>
                        <p>1) Choose file type and species
                        <br>You can upload two kinds of sequences, short reads and ESTs. You can also click “Try an example” button to load the demo data. In addition, you can upload a file to specific coordinates of poly(A) sites. Also, the supported species are listed in the drop-down box.
                        <br><img class="pic" src="./pic/help/species.png"/>
                        </p>
                        <p>2) Input
                        <br>There are two input ways provided for users. The first one serves for users to upload a file where sequences are stored.
                        <br><img class="pic" src="./pic/help/input.png"/>
                        <br>PlantAPA allows users to upload more than one files, each file can be assigned two labels, one denoting the respective sample (ex. leaf_replicate1), the other denoting its group (ex. leaf).Assigning each file with sample or group label is useful in the analysis of APA, for example, detecting DE genes or PACs, and is also useful in visualizing poly(A) sites from individual groups in our graphic module. 
                        <br><img class="pic" src="./pic/help/group.png"/>
                        <br>The second one serves for users to paste sequencs in the textarea if they do not prepare a file.  If data is provided, PlantAPA will skip the extraction pipeline and load the input sites to a user database directly.
                        <br><img class="pic" src="./pic/help/text.png"/>
                        </p>
                        <p>3) Options for mapping reads (optional)
                        <br>If sequences are uploaded, users can use the default parameters set by PlantAPA or specific the parameters for mapping reads.
                        <br><img class="pic" src="./pic/help/fastq_option.png"/>
                        <br><img class="pic" src="./pic/help/est_option.png"/>
                        </p>
                        <h3>2.2. Get a task by task ID</h3>
                        <p>Each time, when a user visits our website to conduct a poly(A) site extraction, an unique task ID will be assigned. In our server, a folder in the same name as this project name will be created to hold all relevant data for the poly(A) site extraction. The user can further obtain all results of this task by searching the task ID in the “My task” page.
                        <br><img class="pic" src="./pic/help/task.png"/>
                        <br>All tasks started from the same IP address are also listed in the “My task” page for the user to retrieve at any time.
                        <br><img class="pic" src="./pic/help/task_list.png"/>
                        </p>
                        <h3>2.3. Outputs of poly(A) site extraction pipeline</h3>
                        <p>Upon the completion of the PAC extraction process, users can download the PAC list directly from the web site onto their local computers. 
                        <br><img class="pic" src="./pic/help/download.png"/>
                        <br>Also, additional information, such as mapping summary, single nucleotide compositions around PACs, top hexamers upstream of PACs, will be displayed in the result page to facilitate users to evaluate their own data.
                        <br><img class="pic" src="./pic/help/result.png"/>
                        <br>By following the web link on a particular PAC, users can continue to use other seamlessly integrated modules for PAC visualization and mining. Particularly, if there are multiple input files, users can further compare poly(A) site usage between their input libraries or with PlantAPA-provided libraries through the PAC analysis module.</p>
                      </div>
                      <div class="jumpto-block">
                        <h2>3. PAC search: searching PlantAPA</h2>
                        <h3>3.1. Multi-keywords search</h3>
                        <p>The PAC search module allows users to query genes or PACs in the data sets of interest by a variety of keywords, such as gene ID, chromosome fragment, gene functions, GO term, and GO ID.
                        <br><img class="pic" src="./pic/help/search.png"/>
                        </p>
                        <h3>3.2. Batch search</h3>
                        <p>Batch search is supported, where lists of Gene IDs, GO terms, or GO IDs can be provided as query input. </p>
                        <h3>3.3. Fuzzy search</h3>
                        <p>Fuzzy search, is also permitted, which allows user to search various database entries by a single keyword. 
                        <br><img class="pic" src="./pic/help/fuzzy_search.png"/>
                        </p>
                        <h3>3.4. Search result</h3>
                        <p>Users can retrieve the search result of an example gene by clicking the button labeled ‘Example’, and view the subsets and detail descriptions of a data set by clicking the corresponding ‘More’- labeled icon. 
                        <br>The query output is a media page that lists all the matched PACs in a dynamic table, where users can choose to view detailed information (poly(A) signals, sequences, expression patterns, etc.) and graphics of PACs and the corresponding gene by clicking the link on a PAC of interest. The PAC list tabulating all PACs in genomic regions as well as intergenic regions would facilitate the inspection of polyadenylation events associated with novel transcripts, lincRNAs, or antisense transcription.
                        <br><img class="pic" src="./pic/help/search_result.png"/>
                        </p>
                      </div>
                      <div class="jumpto-block">
                        <h2>4. Exporting sequences</h2>
                        <p>To facilitate further inspection poly(A) site or genes, we designed a toolbar above the resulted PAC list which allows users to input a keyword to locate entries within the list and export sequences of interest. Users can export various kinds of sequences onto their local computers for other analysis purpose.
                        </p>
                        <h3>4.1. Export sequences of PACs</h3>
                        <p>Sequences regarding PACs are exportable, including upstream and downstream sequences around PACs, sequences of PACs in a defined region (3’ UTR, 5’ UTR, CDS, intron, intergenic region, etc.).
                        <br><img class="pic" src="./pic/help/export2.png"/>
                        </p>
                        <h3>4.2. Export sequences of regions of PACs</h3>
                        <p>Users can also export sequences of genomic regions where PACs are located.
                        <br><img class="pic" src="./pic/help/export3.png"/>
                        </p>
                        <h3>4.3. Export gene sequences</h3>
                        <p>Sequences of genes with PACs based on original version or the extended version of genome annotation are also exportable.
                        <br><img class="pic" src="./pic/help/export1.png"/>
                        </p>
                      </div>
                      <div class="jumpto-block">
                        <h2>5. PAC browse: browsing PACs in the PAC browser</h2>
                        <p>Users can have a quick access to the PAC browser by clicking the “PAC browse” tab in the main menu or the “View” link in a PAC list.
                        <br><img class="pic" src="./pic/help/browse.png"/>
                        <br>One or more data sets from each plant species can be quickly loaded and graphically browsed online, by selecting the checkboxes of data sets in the ‘Tracks’ panel. Users can conduct a search with a gene or chromosome fragment to zoom in on particular PAC regions. Data tracks of PACs from different cells, tissues or conditions can be displayed in sync with tracks of PATs, offering a more intuitive way to explore and compare the usage of PACs among different samples.
                        <br>Users can download the data of one or more tracks onto their local computers, or choose to view detailed information of a gene or PAC by right clicking a PAC or gene model in the browser.
                        <br><img class="pic" src="./pic/help/browse2pac.png"/>
                        <br><img class="pic" src="./pic/help/browser_download.png"/>
                        </p>
                      </div>
                      <div class="jumpto-block">
                        <h2>6. Quantification and visualization of PACs across different conditions</h2>
                        <p>By following the web link on a particular PAC or gene, a user can inspect various graphics and detailed information of the PACs in a gene or in a intergenic region, such as gene/PAC sequence, poly(A) signals, and PAT distributions across diverse conditions in the PAC viewer module. 
                        <br>Summary information about the PAC and the associated gene was given at the top of the web page. </p>
                        <br><img class="pic" src="./pic/help/gene_summary.png"/>
                        <h3>6.1. Graphical representation</h3>
                        <p>Three kinds of graphs are displayed in the middle of the page to quantify and visualize the PAC/PAT distributions across samples.
                        <br>One is a screenshot of a particular section of the PAC browser to show the intron-exon structure of a gene (if the PAC is located in genomic region) and the PAC/PAT tracks, which facilitates users to preview the gene model and locations of PACs before switching to the PAC browser.
                        <br><img class="pic" src="./pic/help/gene_browser.png"/>
                        <br>Another graph presents both the original and the modified versions of the gene model and the distributions of PACs/PATs across different samples in a more intuitive way, where users can inspect the locations, expression patterns, and differential usage of PACs in a gene, especially the selection of heterogeneous cleavage sites. Cleavage sites in a PAC are depicted in vertical lines with height representing number of supporting PATs, and the dominant cleavage site that supported by maximum number of PATs in a PAC is highlighted in red. There is a text label under each PAC to clearly indicate its expression level (total number of supporting PATs). 
                        <br><img class="pic" src="./pic/help/pat_distribution.png"/>
                        <br>An additional bar chart is presented to profile the usage quantification of all PACs of the queried gene across different samples, providing a simple and direct way to compare the usage of PACs and further find the ubiquitous and specific PACs.
                        <br><img class="pic" src="./pic/help/pac_usage.png"/>
                        </p>
                        <h3>6.2. Gene sequence viewer</h3>
                        <p>PlantAPA provides the gene sequence annotated with exon-intron structure and 3’ UTR, poly(A) signals and positions. By default the most dominant poly(A) signal, AAUAAA, and its 1 nt variants are scanned to obtain poly(A) signals. Users can also specify other patterns to locate possible poly(A) signals. Further, users can choose highlight genomic regions (intron, exon, 3’ UTR etc.), cleavage sites, and poly(A) signals in different styles or colors in the corresponding gene sequence, facilitating manual inspection of poly(A) sites in different genomic locations. Particularly, the heterogeneous cleavage sites of each PAC are underlined, and the most dominant cleavage site in each PAC is highlighted in dark red and underlined in bold.
                        <br><img class="pic" src="./pic/help/gene_viewer.png"/>
                        </p>
                    </div>
                    <div class="jumpto-block">
                        <h2>7. PAC analysis: analysis of APA switching between two conditions</h2>
                        <p>Following the “PAC analysis” tab in the main menu, users can choose to generate lists of differentially expressed genes, PACs with differential usage, genes with 3’ UTR lengthening or shortening, and APA-site switching genes, using the user uploaded PACs together with PlantAPA stored PACs.
                        <br>Two groups of samples each with one or more than one condition need to be selected in order to obtain the output gene/PAC lists. 
                        <br>Raw count or normalized count based on TPM normalization and methods in EdgeR or DESeq can be chosen as input. Additional parameters can be set for each assay, such as minimum number of PATs for pre-filtering of PACs, significance level, p-value adjusted method.
                        <br><img class="pic" src="./pic/help/analysis.png"/>
                        <br>To make the result statistically significant, a p-value or adjusted p-value will be calculated and assigned to each PAC/gene. Users can download the output lists to their local computer, or continue to inspect the result PAC/gene by clicking the link on a particular item in the list.
                        </p>
                        <h3>7.1. Detection of differentially expressed genes</h3>
                        <p>After choosing two groups of samples, users can specific parameters for DE gene detection.
                        <br>The main output is a table that lists all resulted DE genes.
                        <br><img class="pic" src="./pic/help/analysis_result1.png"/>
                        </p>
                        <h3>7.2. Detection of PACs with differential usage</h3>
                        <p>Users can specific parameters for detecting PACs with differential usage.
                        <br>The main output is a table that lists all resulted PACs.
                        <br><img class="pic" src="./pic/help/analysis_result2.png"/>
                        </p>
                        <h3>7.3. Detection of genes with 3’ UTR lengthening or shortening</h3>
                        <p>After choosing two groups of samples, users can specific parameters for detecting genes with tandem 3’ UTR switching.
                        <br>The main output is a table that lists all genes with 3’ UTR lengthening or shortening and the associated PACs.
                        <br><img class="pic" src="./pic/help/analysis_result3.png"/>
                        </p>
                        <h3>7.4. Detection of nonconnonical APA-site switching genes</h3>
                        <p>After choosing two groups of samples, users can specific parameters for the detection of nonconnonical APA-site switching genes. These genes involve at least one PAC located in CDS or introns.
                        <br>The main output is a table that lists all resulted genes and PACs.
                        <br><img class="pic" src="./pic/help/analysis_result4.png"/>
                        </p>
                    </div>
                    </div>
	<script type="text/javascript">
	  $(document).ready( function() {
	    $(".page_container").jumpto({
	      firstLevel: "> h2",
	      secondLevel: "> h3",
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
