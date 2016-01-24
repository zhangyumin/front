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
        <script src="./src/jquery-1.10.1.min.js"></script>
            <!--fineuploader的依赖包-->
         <script src="./src/fineuploader/jquery-2.0.0.min.js"></script>
<!--          <link href="./src/fineuploader/bootstrap.min.css" rel="stylesheet" type="text/css"/>
 -->        <link href="./src/fineuploader/fineuploader-gallery.css" rel="stylesheet" type="text/css"/>
        <link href="./src/fineuploader/styles.css" rel="stylesheet" type="text/css"/>
        <script src="./src/fineuploader/all.fine-uploader.js"></script>
        <script src="./src/fineuploader/devenv.js"></script>
        <script src="./src/fineuploader/devenv-polya.js"></script>
        <script src="./src/fineuploader/devenv-est.js"></script>
        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <!--[if lte IE 7]>
        <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <!--[if lt IE 9]>
        <script src="./js/html5shiv/html5shiv.js"></script>
        <![endif]-->
        
        <script type="text/template" id="qq-template">
            <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
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
    </head>
    <body>
        <script>  
            $(document).ready(function(){  
                     $('#seq-submit').click(function (){
                        var params = $('#species,#upload_seq').serialize(); //序列化表单的值
    //                    console.log(params);
    //                    alert(params);
                        $.ajax({  
                            url:'get_result.php', //后台处理程序  
                            type:'post',       //数据传送方式  
                            dataType:'json',   //接受数据格式  
                            data:params,       //要传送的数据
                            beforeSend:loading,
                            success:update_page//回传函数(这里是函数名字)  
                        });  
                     });
                     $('#polya-submit').click(function (){
                        var params = $('#species,#upload_polya').serialize(); //序列化表单的值
    //                    console.log(params);
    //                    alert(params);
                        $.ajax({  
                            url:'get_result_polya.php', //后台处理程序  
                            type:'post',       //数据传送方式  
                            dataType:'json',   //接受数据格式  
                            data:params,       //要传送的数据
                            beforeSend:loading,
                            success:update_page//回传函数(这里是函数名字)  
                        });  
                     });
                     $('#est-submit').click(function (){
                        var params = $('#species,#upload_est').serialize(); //序列化表单的值
    //                    console.log(params);
    //                    alert(params);
                        $.ajax({  
                            url:'get_result_est.php', //后台处理程序  
                            type:'post',       //数据传送方式  
                            dataType:'json',   //接受数据格式  
                            data:params,       //要传送的数据
                            beforeSend:loading,
                            success:update_page//回传函数(这里是函数名字)  
                        });  
                     });
            });
            function test(json){
                alert(json.species);
            }
            function update_page(json) { //回传函数实体，参数为XMLhttpRequest.responseText  
                    window.location.href='show_result.php';
//                alert("successful");
            }
            function loading(){
                $('#mainpage').hide();
                $('#loading').show();
                showspecies();
            }
            function div_hidden(a,b,c,d){
                if($('#'+b).is(":visible")){
                    $('#'+a).attr("src","./pic/up.png");
                    $('#'+d).html('Click here to upload files');
                }
                else{
                    $('#'+a).attr("src","./pic/down.png");
                    $('#'+d).html('Or click here to enter a sequence');
                }
                $('#'+b).slideToggle('slow');
                $('#'+c).slideToggle('slow');
            }
            </script>
            <?php
                include "./navbar.php";
                 $_SESSION['tmp']=date("Y").date("m").date("d").date("h").date("i").date("s");
            ?>
        <div class="ym-wrapper" id='mainpage'>
            <fieldset >
                <legend>
                    <h4>
                        <font color="#224055"><b>STEP 1:</b> Select file(s) type</font>
                    </h4>
                </legend>
                <div class="box info ym-form">
                    <input type='radio' name='upload_method' value='upload' checked="true" onclick="SltFileType()"/>Short read&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type='radio' name='upload_method' value='up_est' onclick="SltFileType()"/>EST&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type='radio' name='upload_method' value='up_polya' onclick="SltFileType()"/>Poly(A) Site&nbsp;&nbsp;&nbsp;&nbsp;<br><br>
                    <label title="Select species" for="species" style="display: inline">Select species here:</label>
                     <select id="species" name="species" style="display: inline;margin-left: 2%;width: 85%">
                        <option value="japonica">Japonica rice</option>
                        <option value="arab" selected="selected">Arabidopsis thaliana</option>
                        <option value="mtr">Medicago truncatula</option>
                        <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
                    </select>
                </div>
            </fieldset>
            <div class="upload" id='upload'>
                <form id="upload_seq" class="ym-form" action="get_result.php" method="post">
                    <fieldset>
                        <legend>
                            <h4>
                                <font color="#224055"><b>STEP 2:</b> Upload file(s)</font>
                            </h4>
                        </legend>
                        <div class="box info">
                            <ul id="foobar"></ul>
                            <div id="seq-examples">
                                <div class="example">
                                    <ul id="manual-example" class="unstyled"></ul>
                                    <button type="button" id="triggerUpload" class="btn btn-primary">Upload Queued Files</button>
                                </div>
                            </div>
                            <hr style="border-bottom: 2px #5499c9 solid">
                            <img id="uploadtext" src="pic/down.png" onclick="div_hidden('uploadtext','seq-examples','uploadtextdiv','seq-note')"><div style="display: inline" id='seq-note'>Or click here to enter a sequence</div>
                            <br><br>
                            <div id='uploadtextdiv' style="display: none">
                                name:&nbsp;<input name='text_name'>&nbsp;&nbsp;&nbsp;&nbsp;group:&nbsp;<input name='text_group'><br>
                                sequence in FASTA format: <br>
                                <textarea name='sequence_text' style="margin: 0px;width: 900px;height: 230px"></textarea>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset style="margin-top: 20px;">
                        <legend>
                            <h4>
                                <font color="#224055"><b>STEP 3:</b> Additional options</font>
                            </h4>
                        </legend>
                        <div class="box info">
                            <font color="#224055" style="font-weight:100;"><b>1. quality filter
                            <br>
                            Quality cut-off: <input type="text" name="qct" value="20" size="1" style="margin-left: 21px;margin-right: 23px;height:30px;width: 210px"/>
                            Minimum percentage: <input type="text" name="mp" value="50" size="1" style="height:30px;width: 210px"/>
                            <br><br>
                                2. remove poly(A/T) tail
                            <br>
                            Poly type:<select id="tailremove" name="tailremove" style="margin-left:53px;margin-right: 23px;height:30px;width:210px;">
                                                <option value="A">A</option>
                                                <option value="T">T</option>
                                                <option value="unknown">unknown</option>
                                                </select>
                                Min length: <input type="text" name="minlength" value="25" size="1" style="margin-left: 58px;height:30px;width: 210px"/>
                            <br><br>
                                 3. read mapping
                            <br>
                                Aligner: <select id="aligner" name="aligner" style="margin-left: 61px;height:30px;width:210px;">
                                                <option value="bowtie2">bowtie2</option>
                                                <option value="bowtie">bowtie</option>
                                                </select>
                            <br><br>
                                4. remove internal priming
                            <br>
                                <input type="radio" name="rip" value="yes" checked="checked"/>YES
                                <input type="radio" name="rip" value="no" style="margin-left:65px;margin-top: 5px;"/>NO
                            <br><br>
                                5. cluster PAT
                            <br>
                                distance: <input type="text" name="distance" value="24" size="1" style="margin-left: 55px;height:30px;width:210px;"/>
                                </b></font>
                        </div>
                    </fieldset>
                    <fieldset >
                        <legend>
                            <h4>
                                <font color="#224055"><b>STEP 4:</b> Submit</font>
                            </h4>
                        </legend>
                        <div class="box info">
                            <input type="button" id='seq-submit' value="submit">
                            <input type="reset" value="reset"/>
                        </div>
                    </fieldset>
                </form>
            </div>
        
        <div class="upload_polya" id='up_polya' style='display: none'>
            <form id="upload_polya" class="ym-form" action="upload_polya.php" method="post">
                <fieldset>
                    <legend>
                        <h4>
                            <font color="#224055"><b>STEP 2:</b> Upload file(s)</font>
                        </h4>
                    </legend>
                    <div class="box info">
                        <ul id="foobar-polya"></ul>
                        <div id="polya-examples">
                            <div class="example">
                                <ul id="manual-example-polya" class="unstyled"></ul>
                                <button type="button" id="triggerUpload-polya" class="btn btn-primary">Upload Queued Files</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset style="margin-top: 20px;margin-bottom: 5px;">
                    <legend>
                        <h4>
                            <font color="#224055"><b>STEP 3:</b> Submit</font>
                        </h4>
                    </legend>
                    <div class="box info">
                        <input type="button" id='polya-submit' value="submit" style="width: auto"/>
                        <input type="reset" value="reset" style="width: auto"/>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="upload_est" id='up_est' style='display: none'>
            <form id="upload_est" class="ym-form" action="get_result_est.php" method="post">
                <fieldset>
                    <legend>
                        <h4>
                            <font color="#224055"><b>STEP 2:</b> Upload file(s)</font>
                        </h4>
                    </legend>
                    <div class="box info">
                        <ul id="foobar-est"></ul>
                        <div id="est-examples">
                            <div class="example">
                                <ul id="manual-example-est" class="unstyled"></ul>
                                <button type="button" id="triggerUpload-est" class="btn btn-primary">Upload Queued Files</button>
                            </div>
                        </div>
                        <hr style="border-bottom: 2px #5499c9 solid">
                            <img id="est-uploadtext" src="pic/down.png" onclick="div_hidden('est-uploadtext','est-examples','est-uploadtextdiv','est-note')"><div style="display: inline" id='est-note'>Or click here to enter a sequence</div>
                            <br><br>
                            <div id='est-uploadtextdiv' style="display: none">
                                name:&nbsp;<input name='est_text_name' style="width: auto;display: inline">&nbsp;&nbsp;&nbsp;&nbsp;group:&nbsp;<input name='est_text_group' style="width: auto;display: inline"><br>
                                sequence in EST format: <br>
                                <textarea name='est_sequence_text' style="margin: 0px;width: 900px;height: 230px"></textarea>
                            </div>
                    </div>
                </fieldset>
                    <fieldset style="margin-top: 20px;">
                        <legend>
                            <h4>
                                <font color="#224055"><b>STEP 3:</b> Additional options</font>
                            </h4>
                        </legend>
                        <div class="box info">
                            PolyA type: <select id="poly_type" name="poly_type" style="margin-left:200px;margin-bottom:5px;height:30px;width:210px;display: inline">
                                                <option value="A">A</option>
                                                <option value="T">T</option>
                                                <option value="AT">A&T</option>
                                                </select>
                            <br>
                            Min tail length (nt) : <input type="text" name="min_tail_length" value="8" size="1" style="margin-left:158px;margin-bottom:5px;height:30px;width:210px;display: inline"/>
                            <br>
                            Search tail within (nt) : <input type="text" name="search_tail_within" value="15" size="1" style="margin-left:142px;margin-bottom: 5px;width: 210px;display: inline;height:30px;"/>
                            <br>
                            Max distance from aligned-end to EST-end (nt) : <input type="text" name="max_distance1" value="20" size="1" style="margin-left:4px;margin-bottom: 5px;width: 210px;display: inline;height:30px;"/>
                            <br>
                            Max distance from aligned-end to poly tail (nt) : <input type="text" name="max_distance1" value="5" size="1" style="margin-left:11px;display: inline;width: 210px;height:30px;"/>
                        </div>
                    </fieldset>
                <fieldset >
                    <legend>
                        <h4>
                            <font color="#224055"><b>STEP 4:</b> Submit</font>
                        </h4>
                    </legend>
                    <div class="box info">
                        <input type="button" id='est-submit' value="submit" style="width: auto"/>
                        <input type="reset" value="reset" style="width: auto"/>
                    </div>
                </fieldset>
            </form>
        </div>
        </div>
        <div class="ym-wrapper" id='loading' style="display: none">
            <fieldset >
                <legend>
                    <h4 >
                        <font color="#224055" ><b>Loading</b>:data is being processed</font>
                    </h4>
                </legend>
                <div style="text-align: center;color:rgb(34,34,85)">
                    Your request is being processed , please wait...
                    <br><img src="./pic/loading1.gif" style="width: 200px;height: 150px;"/>
                    <br>This page will be refreshed automatically when the results are available. 
                    <br>Task id: <div id="showspecies" style="display: inline;color: red"></div>.You can retrieve the result of your task at <a href="./task.php">data retrieval page</a> by your task id.
                </div>
            </fieldset>
        </div>
            
            <script type="text/javascript">
                function showspecies(){
                    var tmp = '<?php echo $_SESSION['tmp'];?>';
                    var sp = document.getElementById("species").value;
                    var id = sp+tmp;
                    document.getElementById('showspecies').innerHTML=id;
                }
                function SltFileType(){
                    var upload_method=document.getElementsByName("upload_method");
                    for(var i=0; i<upload_method.length; i++){
                        if(upload_method.item(i).checked){
                            document.getElementById(upload_method.item(i).value).style.display="inline";
                        }
                        else
                            document.getElementById(upload_method.item(i).value).style.display="none";
                    }
                }
            </script>
  
        <?php
            include "./footer.php";
        ?>
    </body>
</html>
