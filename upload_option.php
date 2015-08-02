<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
    session_start();
    $_SESSION['tmp']=0;
    ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Upload sequence</title>
        <link href="./src/navbar.css" rel="stylesheet"/>
        
            <!--fineuploader的依赖包-->
         <script src="./src/fineuploader/jquery-2.0.0.min.js"></script>
         <link href="./src/fineuploader/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="./src/fineuploader/fineuploader-gallery.css" rel="stylesheet" type="text/css"/>
        <link href="./src/fineuploader/styles.css" rel="stylesheet" type="text/css"/>
        <script src="./src/fineuploader/all.fine-uploader.js"></script>
        <script src="./src/fineuploader/devenv.js"></script>
        
        <script type="text/template" id="qq-template">
            <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop sequence files here">
                <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
                </div>
                <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                    <span class="qq-upload-drop-area-text-selector">Drop files here to upload</span>
                </div>
                <div class="qq-upload-button-selector qq-upload-button">
                    <div>Upload files</div>
                </div>
                <span class="qq-drop-processing-selector qq-drop-processing">
                    <span>Processing dropped files...</span>
                    <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
                </span>
                <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                    <li>
                        <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                        <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                        </div>
                        <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                        <div class="qq-thumbnail-wrapper">
                            <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                        </div>
                        <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                        <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                            <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                            Retry
                        </button>

                        <div class="qq-file-info">
                            <div class="qq-file-name">
                                <span class="qq-upload-file-selector qq-upload-file"></span>
                                <span class="qq-edit-filename-icon-selector qq-btn qq-edit-filename-icon" aria-label="Edit filename"></span>
                            </div>
                            <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                            <span class="qq-upload-size-selector qq-upload-size"></span>
                            <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                                <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                            </button>
                            <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                                <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                            </button>
                            <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                                <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                            </button>
                        </div>
                    </li>
                </ul>

                <dialog class="qq-alert-dialog-selector">
                    <div class="qq-dialog-message-selector"></div>
                    <div class="qq-dialog-buttons">
                        <button type="button" class="qq-cancel-button-selector">Close</button>
                    </div>
                </dialog>

                <dialog class="qq-confirm-dialog-selector">
                    <div class="qq-dialog-message-selector"></div>
                    <div class="qq-dialog-buttons">
                        <button type="button" class="qq-cancel-button-selector">No</button>
                        <button type="button" class="qq-ok-button-selector">Yes</button>
                    </div>
                </dialog>

                <dialog class="qq-prompt-dialog-selector">
                    <div class="qq-dialog-message-selector"></div>
                    <input type="text">
                    <div class="qq-dialog-buttons">
                        <button type="button" class="qq-cancel-button-selector">Cancel</button>
                        <button type="button" class="qq-ok-button-selector">Ok</button>
                    </div>
                </dialog>
            </div>

        </script>
        <style>
            fieldset{
                border-color: #5499c9 !important;
                border-style: solid !important;
                border-width: 2px !important;
                padding: 5px 10px !important;
            }    
        </style>
    </head>
    <body>
        <div style="width: 100%;margin:0 auto;" class="page">
            <?php
                include "./navbar.php";
                 $_SESSION['tmp']=date("Y").date("m").date("d").date("h").date("i").date("s");
            ?>
            <div class="upload" style="clear: both;">
                <fieldset style="margin-top: 20px;margin-left: 10%;width: 80%;">
                    <legend>
                        <span class="h3_italic">
                            <font color="#224055"><b>STEP 1</b>:Upload file(s)</font>
                        </span>
                    </legend>
                <form id="upload" action="upload_data.php" method="post">
                    <label title="Select species" for="species">Select species here:</label>
                    <select id="species" name="species">
                        <option value="arab" selected="selected">Arabidopsis thaliana</option>
                         <option value="rice">Oryza sativa (Rice)</option>
                        <option value="mtr">Medicago truncatula</option>
                        <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
                    </select>
   
                <ul id="foobar"></ul>
                <div id="examples">
                    <div class="example">
                        <ul id="manual-example" class="unstyled"></ul>
                        <button type="button" id="triggerUpload" class="btn btn-primary">Upload Queued Files</button>
                    </div>
                </div>
                </fieldset>
                
                <fieldset style="margin-top: 20px;margin-left: 10%;width: 80%;">
                    <legend>
                        <span class="h3_italic">
                            <font color="#224055"><b>STEP 2</b>:Additional options</font>
                        </span>
                    </legend>
                    <font color="#224055" style="font-weight:100;"><b>1. quality filter
                    <br>
                    Quality cut-off: <input type="text" name="qct" value="20" size="1" style="margin-left: 2%;margin-right:10%;height:30px;"/>
                    Minimum percentage: <input type="text" name="mp" value="50" size="1" style="margin-left:2%;height:30px;"/>
                    <br><br>
                        2. remove poly(A/T) tail
                    <br>
                    Poly type:<select id="tailremove" name="tailremove" style="margin-left:6%;margin-right: 10%;height:30px;width:210px;">
                                        <option value="A">A</option>
                                        <option value="T">T</option>
                                        <option value="unknown">unknown</option>
                                        </select>
                        Min length: <input type="text" name="minlength" value="25" size="1" style="margin-left: 8.5%;height:30px;width:210px;"/>
                    <br><br>
                         3. read mapping
                    <br>
                        Aligner: <select id="aligner" name="aligner" style="margin-left: 7%;height:30px;width:210px;">
                                        <option value="bowtie2">bowtie2</option>
                                        <option value="bowtie">bowtie</option>
                                        </select>
                    <br><br>
                        4. remove internal priming
                    <br>
                        <input type="radio" name="rip" value="yes" checked="checked"/>YES
                        <input type="radio" name="rip" value="no" style="margin-left:9%;margin-top: 5px;"/>NO
                    <br><br>
                        5. cluster PAT
                    <br>
                        distance: <input type="text" name="distance" value="24" size="1" style="margin-left: 6.5%;height:30px;width:210px;"/>
                        </b></font>
                </fieldset>
                <fieldset style="margin-top: 20px;margin-left: 10%;margin-bottom: 5px;width: 80%;">
                    <legend>
                        <span class="h3_italic">
                            <font color="#224055"><b>STEP 3</b>:Submit</font>
                        </span>
                    </legend>
                        <button type="submit" name="submit" value="submit" style="margin-left: 40%;">Submit</button>
                        <button type="reset"    name="reset"    value="reset" style="margin-left: 10%">Reset</button>
                </fieldset>
                </form>
        </div>
    </div>
        <?php
            include "./footer.php";
        ?>
    </body>
</html>
