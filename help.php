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
        <script src="js/modernizr.js"></script>
    </head>
    <body>
        <?php include './navbar.php'; ?>
	<div class="main">
                    <div class="page_container">
                      <div class="jumpto-block">
                        <h1>How to use PlantAPA</h1>
                        <span>Suggested Browsers</span>
                        <p>Advanced browsers, such as Chrome, Firefox, Safari, and Internet Explorer (10.0 or later) can be used for browsing PlantAPA. 
                       <br>Firefox and Chrome are recommended:
                       <br>Mozilla Firefox 3.5 or greater (http://www.mozilla.org/)
                       <br>Google Chrome 6.0 or greater (http://www.google.com/chrome/)</p>
                      </div>
                      <div class="jumpto-block">
                        <h2>1. Introduction</h2>
                        <p>PlantAPA is a web server for query, visualization, and analysis of poly(A) sites in plants, which can profile heterogeneous cleavage sites and quantify expression pattern of poly(A) sites across different conditions. To date, PlantAPA provides the largest database of APA in plants, including rice, Arabidopsis, Medicago truncatula, and Chlamydomonas reinhardtii (see data sets).</p>
                      </div>
                      <div class="jumpto-block">
                        <h2>2. PAC trap: extracting poly(A) sites from uploaded sequences</h2>
                        <h3>2.1. Start a new task for poly(A) site extraction</h3>
                        <p>1) Choose species
                        <br>The supported species are listed in the drop-down box.
                        </p>
                        <p>2) Input
                        <br>You can upload two kinds of sequences, short reads and ESTs. You can also click “Try an example” button to load the demo data. In addition, you can upload a file to specific coordinates of poly(A) sites. If this file is provided, PASPA will skip the extraction pipeline and load the input sites to a user database directly.
                        </p>
                        <p>3) Options for mapping reads (optional)
                        <br>If sequences are uploaded, users can use the default parameters set by PlantAPA or specific the parameters for mapping reads.
                        </p>
                        <h3>2.2. Get a task by task ID</h3>
                        <p>Each time, when a user visits our website to conduct a poly(A) site extraction, an unique task ID will be assigned. In our server, a folder in the same name as this project name will be created to hold all relevant data for the poly(A) site extraction. The user can further obtain all results of this task by searching the task ID in the “My task” page.
                        <br>All tasks started from the same IP address are also listed in the “My task” page for the user to retrieve at any time.
                        </p>
                        <h3>2.3. Outputs of poly(A) site extraction pipeline</h3>
                        <p>Upon the completion of the PAC extraction process, users can download the PAC list directly from the web site onto their local computers. Also, additional information, such as mapping summary, single nucleotide compositions around PACs, top hexamers upstream of PACs, will be displayed in the result page to facilitate users to evaluate their own data.
                        <br>By following the web link on a particular PAC, users can continue to use other seamlessly integrated modules for PAC visualization and mining. Particularly, if there are multiple input files, users can further compare poly(A) site usage between their input libraries or with PlantAPA-provided libraries through the PAC analysis module.</p>
                      </div>
                      <div class="jumpto-block">
                        <h2>3. PAC search: searching PlantAPA</h2>
                        <h3>3.1. Multi-keywords search</h3>
                        <p>The PAC search module allows users to query genes or PACs in the data sets of interest by a variety of keywords, such as gene ID, chromosome fragment, gene functions, GO term, and GO ID.</p>
                        <h3>3.2. Batch search</h3>
                        <p>Batch search is supported, where lists of Gene IDs, GO terms, or GO IDs can be provided as query input. </p>
                        <h3>3.3. Fuzzy search</h3>
                        <p>Fuzzy search, is also permitted, which allows user to search various database entries by a single keyword. </p>
                        <h3>3.4. Search result</h3>
                        <p>Users can retrieve the search result of an example gene by clicking the button labeled ‘Example’, and view the subsets and detail descriptions of a data set by clicking the corresponding ‘More’- labeled icon. 
                        <br>The query output is a media page that lists all the matched PACs in a dynamic table, where users can choose to view detailed information (poly(A) signals, sequences, expression patterns, etc.) and graphics of PACs and the corresponding gene by clicking the link on a PAC of interest. The PAC list tabulating all PACs in genomic regions as well as intergenic regions would facilitate the inspection of polyadenylation events associated with novel transcripts, lincRNAs, or antisense transcription.</p>
                      </div>
                      <div class="jumpto-block">
                        <h2>4. Exporting sequences</h2>
                        <p>To facilitate further inspection poly(A) site or genes, we designed a toolbar above the resulted PAC list which allows users to input a keyword to locate entries within the list and export sequences of interest. Users can export various kinds of sequences onto their local computers for other analysis purpose. </p>
                        <h3>4.1. Export sequences of PACs</h3>
                        <p>Sequences regarding PACs are exportable, including upstream and downstream sequences around PACs, sequences of PACs in a defined region (3’ UTR, 5’ UTR, CDS, intron, intergenic region, etc.).</p>
                        <h3>4.2. Export sequences of regions of PACs</h3>
                        <p>Users can also export sequences of genomic regions where PACs are located.</p>
                        <h3>4.3. Export gene sequences</h3>
                        <p>Sequences of genes with PACs based on original version or the extended version of genome annotation are also exportable.</p>
                      </div>
                      <div class="jumpto-block">
                        <h2>5. PAC browse: browsing PACs in the PAC browser</h2>
                        <p>Users can have a quick access to the PAC browser by clicking the “PAC browse” tab in the main menu or the “View” link in a PAC list. 
                        <br>One or more data sets from each plant species can be quickly loaded and graphically browsed online, by selecting the checkboxes of data sets in the ‘Tracks’ panel. Users can conduct a search with a gene or chromosome fragment to zoom in on particular PAC regions. Data tracks of PACs from different cells, tissues or conditions can be displayed in sync with tracks of PATs, offering a more intuitive way to explore and compare the usage of PACs among different samples.
                        <br>Users can download the data of one or more tracks onto their local computers, or choose to view detailed information of a gene or PAC by right clicking a PAC or gene model in the browser.
                        </p>
                      </div>
                      <div class="jumpto-block">
                        <h2>Header 6</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                      </div>
                    </div>
                    <div class="other">
                      <h1>Stop following me!</h1>
                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    </div>
	</div>
	<script type="text/javascript">
	  $(document).ready( function() {
	    $(".page_container").jumpto({
	      firstLevel: "> h2",
	      secondLevel: "> h3",
	      animate: 500
	    });
	  });
    
 	</script>
        <?php
            include"./footer.php";
        ?>
    </body>
</html>
