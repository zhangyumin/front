<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Analysis</title>
        <link href="./src/index.css" rel="stylesheet" type="text/css" />
        <script src="./src/jquery-1.10.1.min.js"></script>
        <script src="./src/jquery.slides.min.js"></script>
        <!-- Mobile viewport optimisation -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css" />
        <!--[if lte IE 7]>
            <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
            <![endif]-->
        <!--[if lt IE 9]>
            <script src="./js/html5shiv/html5shiv.js"></script>
            <![endif]-->
        <!--<link rel="stylesheet" href="./src/font-awesome.min.css">-->
        <!--<link rel="stylesheet" href="./src/example.css">-->
        <link type="text/css" rel="stylesheet" href="./src/pws-tabs/jquery.pwstabs-1.2.1.css"></link>
        <script src="./src/pws-tabs/jquery.pwstabs-1.2.1.js"></script>
        <style>
                    .step-title{
                        margin: auto;
                        margin-top: 15px;
                        margin-bottom: 0px;
                        height: 20px;
                        background-color: #5db95b;
                        padding: 7px 18px 7px;
                        border: 0px solid #000;
                        border-radius: 8px;
                        cursor: pointer;
                    }
        </style>
    </head>
    <body onload="getchr()">
        <?php
            include"navbar.php";
            session_start();
            $con=  mysql_connect("localhost","root","root");
            mysql_select_db("db_server",$con);
//            var_dump($_SESSION['analysis']);
        ?>
        <script>  
        $(document).ready(function(){  
                 $('#degene-submit').click(function (){
                    if($(".degene1:checked").length>0 && $(".degene2:checked").length> 0){
                        var params = $('#species,#degene-form,#search').serialize(); //序列化表单的值
    //                    console.log(params);
    //                    alert(params);
                        $.ajax({  
                            url:'aftertreatment.php?method=degene', //后台处理程序  
                            type:'post',       //数据传送方式  
                            dataType:'json',   //接受数据格式  
                            data:params,       //要传送的数据
                            beforeSend:loading,
                            success:update_page1//回传函数(这里是函数名字)  
                        });
                    }else{
                        alert("Please select two groups of samples.");
                    }
                 });
                 $('#depac-submit').click(function (){
                     if($(".depac1:checked").length>0 && $(".depac2:checked").length> 0){
                        var params = $('#species,#depac-form,#search').serialize(); //序列化表单的值
    //                    console.log(params);
    //                    alert(params);
                        $.ajax({  
                            url:'aftertreatment.php?method=depac', //后台处理程序  
                            type:'post',       //数据传送方式  
                            dataType:'json',   //接受数据格式  
                            data:params,       //要传送的数据  
                            beforeSend:loading,
                            success:update_page2//回传函数(这里是函数名字)  
                        });  
                    }else{
                        alert("Please select two groups of samples.");
                    }
                 });
                 $('#only3utr-submit').click(function (){
                     if($(".only3utr1:checked").length>0 && $(".only3utr2:checked").length> 0){
                        var params = $('#species,#only3utr-form,#search').serialize(); //序列化表单的值
    //                    console.log(params);
    //                    alert(params);
                        $.ajax({  
                            url:'aftertreatment.php?method=only3utr', //后台处理程序  
                            type:'post',       //数据传送方式  
                            dataType:'json',   //接受数据格式  
                            data:params,       //要传送的数据 
                            beforeSend:loading, 
                            success:update_page3//回传函数(这里是函数名字)  
                        });  
                    }else{
                        alert("Please select two groups of samples.");
                    }
                 });
                 $('#none3utr-submit').click(function (){
                     if($(".none3utr1:checked").length>0 && $(".none3utr2:checked").length> 0){
                        var params = $('#species,#none3utr-form,#search').serialize(); //序列化表单的值
    //                    console.log(params);
    //                    alert(params);
                        $.ajax({  
                            url:'aftertreatment.php?method=none3utr', //后台处理程序  
                            type:'post',       //数据传送方式  
                            dataType:'json',   //接受数据格式  
                            data:params,       //要传送的数据  
                            beforeSend:loading,
                            success:update_page4//回传函数(这里是函数名字)  
                        });  
                    }else{
                        alert("Please select two groups of samples.");
                    }
                 });
                 $(".flip").click(function(){
                    $('#search div').slideToggle("slow");

                 });
        });
        function test(json){
            alert(json.species);
        }
        function update_page1(json) { //回传函数实体，参数为XMLhttpRequest.responseText  
                window.location.href='aftertreatment_result_test.php?result=degene&species='+json.species;
//            alert("successful");
        }
        function update_page2(json) { //回传函数实体，参数为XMLhttpRequest.responseText  
            window.location.href='aftertreatment_result_test.php?result=depac&species='+json.species;
//            alert("successful");
        }
        function update_page3(json) { //回传函数实体，参数为XMLhttpRequest.responseText  
            window.location.href='aftertreatment_result_test.php?result=switchinggene_o&species='+json.species;
//            alert("successful");
        }
        function update_page4(json) { //回传函数实体，参数为XMLhttpRequest.responseText  
            window.location.href='aftertreatment_result_test.php?result=switchinggene_n&species='+json.species;
//            alert("successful");
        }
        function loading(){
            $('#mainpage').hide();
            $('#loading').show();
        }
        function demo(a){
            window.location.href='demo.php?method='+a;
        }
        </script>
    <div class="ym-wrapper" id='mainpage'>
                <h2 style="border-bottom: 2px #5db95b solid;padding: 15px 0px 0px 0px;margin-bottom: 0px;text-align: left;">
                    <font color="#224055" ><b>Analysis: </b>analysis of APA switching between two conditions</font>
                </h2>
           <div class="box info ym-form">
               <label for="species" style="float:left;width:7%">Species:&nbsp;</label>
                <select id="species" name="species" style="width:93%" onchange="div_option2(this);getchr();refresh();unchecked();">
                    <option value="japonica">Japonica rice</option>
                     <option value="arab" selected="selected">Arabidopsis thaliana</option>
                    <option value="mtr">Medicago truncatula</option>
                    <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
                 </select>
            </div>
            <div class="info">   
                <div class="step-title flip" onclick="chgArrow()">
                    <a href="./help.php#analysishelp1"><img src="./pic/help.png" style="width:20px;height: 20px;display: inline-block"></a>
                    <img id="arrow" src="./pic/down.png" style="height:18px">
                    <h4 style="display:inline">
                        <font color="#224055">Additional options</font>
                    </h4>
                </div>
                <form id="search">
                    <div class="box info ym-form" id='hid' style="padding:0px;margin:0 1.4857em 1.4857em 1.4857em">
                    <div class="ym-grid ym-fbox">
                        <div class="ym-g38 ym-gl">
                            <label for="chr" style="margin-right:2%">Gene / poly(A) sites in</label>
                              <select id="chr1" name="chr" style="width:60%">
                                    <option value="all" selected="selected">All</option>
                             </select>
                        </div>
                        <div class="ym-g50 ym-gl">
                            <label for="start"style="margin:0 1%;"> From</label>
                            <input type="text" id='start' name="start">
                            <label for="end" style="margin:0 1%;"> To</label>
                            <input type="text" id='end' name="end">
                        </div>
                    </div>           
                    <div class="ym-grid ym-fbox">
                        <label for="gene_id">Gene symbol or locus ID: (use ',' to split different ID)</label>
                        <textarea style="width:100%" name="gene_id" id='gene_id'></textarea>
                    </div>
                    <div class="ym-grid ym-fbox">
                        <label for="go_accession">GO term accession:(use ',' to split different GO ID)</label>
                        <textarea style="width:100%" name='go_accession' id='go_accession'></textarea>
                    </div>
                    <div class="ym-grid ym-fbox">
                        <label for="go_name" >GO term name:</label>
                        <input type='text' name='go_name' class="ym-gr" id='go_name' style="width:89%;"/>
                    </div>
                    <div class="ym-grid ym-fbox">
                        <label for="function">Function:</label>
                        <input type='text' name='function' id='function' class="ym-gr" style="width:89%;"/>
                    </div>
                    </div>
                </form>
            </div>
        <div class="ym-grid" style="padding-top:10px">
                <div > 
                    <div class="wrap">
                         <div class="tabs" style="padding:0px;border:solid #5db95b">
                             <div data-pws-tab="DE Gene" data-pws-tab-name="DE Gene" style="width: 100%">
                                 <form id="degene-form" class="info">
                                    <div class="box samples">
                                        <table id="samples" >
                                            <thead><center>Select two groups of samples</center></thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div id="sample1" style="width:50%;margin:auto;">
                                                            <label for="all1">Sample 1</label><br>
                                                                <?php
                                                                    $sys_sample_arab=array();
                                                                    $sys_sample_japonica=array();
                                                                    $sys_sample_mtr=array();
                                                                    $sys_sample_chlamy=array();
                                                                    //arab
                                                                    $i=1;
                                                                    $mysql_arab=mysql_query("select distinct PA_col from t_sample_desc where species='arab';");
                                                                    echo "<div id='arab1'>";
                                                                    while($arab_row= mysql_fetch_row($mysql_arab))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='degene1' id=a1$i name=sample1[] value=$arab_row[0] onclick=\"ClickOption(this,'b1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                        array_push($sys_sample_arab, $arab_row[0]);
                                                                    }
                                                                    $_SESSION['sys_real_arab']=$sys_sample_arab;
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='degene1' name=sample1[] id=asys1$j value=$value onclick=\"ClickOption(this,'asys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //japonica
                                                                    $i=1;
                                                                    $mysql_japonica=mysql_query("select distinct PA_col from t_sample_desc where species='japonica';");
                                                                    echo "<div id='japonica1' style='display:none'>";
                                                                    while($japonica_row= mysql_fetch_row($mysql_japonica))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='degene1' id=a2$i name=sample1[] value=$japonica_row[0] onclick=\"ClickOption(this,'b2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                        array_push($sys_sample_japonica, $japonica_row[0]);
                                                                    }
                                                                    $_SESSION['sys_real_japonica']=$sys_sample_japonica;
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='degene1' name=sample1[] id=asys1$j value=$value onclick=\"ClickOption(this,'asys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //mtr
                                                                    $i=1;
                                                                    $mysql_mtr=mysql_query("select distinct PA_col from t_sample_desc where species='mtr';");
                                                                    echo "<div id='mtr1' style='display:none'>";
                                                                    while($mtr_row= mysql_fetch_row($mysql_mtr))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='degene1' id=a3$i name=sample1[] value=$mtr_row[0] onclick=\"ClickOption(this,'b3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                        array_push($sys_sample_mtr, $mtr_row[0]);
                                                                    }
                                                                    $_SESSION['sys_real_mtr']=$sys_sample_mtr;
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='degene1' name=sample1[] id=asys1$j value=$value onclick=\"ClickOption(this,'asys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //chlamy
                                                                    $i=1;
                                                                    $mysql_chlamy=mysql_query("select distinct PA_col from t_sample_desc where species='chlamy';");
                                                                    echo "<div id='chlamy1' style='display:none'>";
                                                                    while($chlamy_row= mysql_fetch_row($mysql_chlamy))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='degene1' id=a4$i name=sample1[] value=$chlamy_row[0] onclick=\"ClickOption(this,'b4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                        array_push($sys_sample_chlamy, $chlamy_row[0]);
                                                                    }
                                                                    $_SESSION['sys_real_chlamy']=$sys_sample_chlamy;
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='degene1' name=sample1[] id=asys1$j value=$value onclick=\"ClickOption(this,'asys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="sample2" style="width:50%;margin:auto;">
                                                            <label for="all2">Sample 2</label><br>
                                                                <?php
                                                                    //arab
                                                                    $i=1;
                                                                    $mysql_arab=mysql_query("select distinct PA_col from t_sample_desc where species='arab';");
                                                                    echo "<div id='arab2'>";
                                                                    while($arab_row= mysql_fetch_row($mysql_arab))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='degene2' id=b1$i name=sample2[] value=$arab_row[0] onclick=\"ClickOption(this,'a1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='degene2' name=sample2[] id=asys2$j value=$value onclick=\"ClickOption(this,'asys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //japonica
                                                                    $i=1;
                                                                    $mysql_japonica=mysql_query("select distinct PA_col from t_sample_desc where species='japonica';");
                                                                    echo "<div id='japonica2' style='display:none'>";
                                                                    while($japonica_row= mysql_fetch_row($mysql_japonica))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='degene2' id=b2$i name=sample2[] value=$japonica_row[0] onclick=\"ClickOption(this,'a2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='degene2' name=sample2[] id=asys2$j value=$value onclick=\"ClickOption(this,'asys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //mtr
                                                                    $i=1;
                                                                    $mysql_mtr=mysql_query("select distinct PA_col from t_sample_desc where species='mtr';");
                                                                    echo "<div id='mtr2' style='display:none'>";
                                                                    while($mtr_row= mysql_fetch_row($mysql_mtr))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='degene2' id=b3$i name=sample2[] value=$mtr_row[0] onclick=\"ClickOption(this,'a3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='degene2' name=sample2[] id=asys2$j value=$value onclick=\"ClickOption(this,'asys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //chlamy
                                                                    $i=1;
                                                                    $mysql_chlamy=mysql_query("select distinct PA_col from t_sample_desc where species='chlamy';");
                                                                    echo "<div id='chlamy2' style='display:none'>";
                                                                    while($chlamy_row= mysql_fetch_row($mysql_chlamy))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='degene2' id=b4$i name=sample2[] value=$chlamy_row[0] onclick=\"ClickOption(this,'a4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='degene2' name=sample2[] id=asys2$j value=$value onclick=\"ClickOption(this,'asys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="degene" class="ym-form">
                                        <div class="ym-fbox box">
                                                    <label for="nor_method">Normalization method</label>
                                                    <select name="degene_nor_method" id="nor_method">
                                                          <option value='none' selected="true">None</option>
                                                          <option value='TPM'>TPM</option>
                                                          <option value='DESeq'>DESeq</option>
                                                          <option value='EdgeR'>EdgeR</option>
                                                    </select>
                                                    <label for="method">Method</label>
                                                    <select name="degene_method">
                                                        <option value='EdgeR'>EdgeR</option>
                                                        <option value='DESeq'>DESeq</option>
                                                        <option value='DESeq2'>DESeq2</option>
                                                    </select>
                                                  <label for="min_pat">Min PAT</label>
                                                <input type='text' name='degene_min_pat' value='5'/>
                                           
                                        
                                              <label for="multi_test">Multi-test adjustment method</label>
                                                <select name="degene_multi_test">
                                                    <option value='Bonferroni' selected="true">Bonferroni</option>
                                                    <option value='Not'>Not adjust</option>
                                               </select>
            
                                                <label for="sig">Significance Level</label>
                                                <select name="degene_sig">
                                                    <option value='0.01'>0.01</option>
                                                    <option value='0.05' selected="true">0.05</option>
                                                    <option value='0.1'>0.1</option>
                                               </select>
                                        </div>    
                                       
                                    </div>
                                    <div class="ym-clearfix"></div>
                                    <div class="box fsubmit">
                                        <input type="button" id='degene-submit' value="Submit">
                                        <button type="reset">Reset</button>
                                        <input type="button" onclick="demo('degene')" value="Demo">
                                        <input type="button" onclick="javascript:window.location.href='./help.php#analysishelp2'" value='Help'>
                                    </div>
                                    </form>
                             </div>
                             <div data-pws-tab="DE PAC" data-pws-tab-name="DE PAC" style="width: 100%">
                                 <form id="depac-form" class="info">
                                    <div class="box samples">
                                        <table id="samples" >
                                            <thead><center>Select two groups of samples</center></thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div id="sample3" style="width:50%;margin:auto;">
                                                            <label for="all1">Sample 1</label><br>
                                                                <?php
                                                                    //arab
                                                                    $i=1;
                                                                    $mysql_arab=mysql_query("select distinct PA_col from t_sample_desc where species='arab';");
                                                                    echo "<div id='arab3'>";
                                                                    while($arab_row= mysql_fetch_row($mysql_arab))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='depac1' id=c1$i name=sample1[] value=$arab_row[0] onclick=\"ClickOption(this,'d1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    unset($sys_sample);
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='depac1' name=sample1[] id=bsys1$j value=$value onclick=\"ClickOption(this,'bsys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //japonica
                                                                    $i=1;
                                                                    $mysql_japonica=mysql_query("select distinct PA_col from t_sample_desc where species='japonica';");
                                                                    echo "<div id='japonica3' style='display:none'>";
                                                                    while($japonica_row= mysql_fetch_row($mysql_japonica))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='depac1' id=c2$i name=sample1[] value=$japonica_row[0] onclick=\"ClickOption(this,'d2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='depac1' name=sample1[] id=bsys1$j value=$value onclick=\"ClickOption(this,'bsys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //mtr
                                                                    $i=1;
                                                                    $mysql_mtr=mysql_query("select distinct PA_col from t_sample_desc where species='mtr';");
                                                                    echo "<div id='mtr3' style='display:none'>";
                                                                    while($mtr_row= mysql_fetch_row($mysql_mtr))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='depac1' id=c3$i name=sample1[] value=$mtr_row[0] onclick=\"ClickOption(this,'d3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='depac1' name=sample1[] id=bsys1$j value=$value onclick=\"ClickOption(this,'bsys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //chlamy
                                                                    $i=1;
                                                                    $mysql_chlamy=mysql_query("select distinct PA_col from t_sample_desc where species='chlamy';");
                                                                    echo "<div id='chlamy3' style='display:none'>";
                                                                    while($chlamy_row= mysql_fetch_row($mysql_chlamy))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='depac1' id=c4$i name=sample1[] value=$chlamy_row[0] onclick=\"ClickOption(this,'d4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='depac1' name=sample1[] id=bsys1$j value=$value onclick=\"ClickOption(this,'bsys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="sample4" style="width:50%;margin:auto;">
                                                            <label for="all2">Sample 2</label><br>
                                                                <?php
                                                                    //arab
                                                                    $i=1;
                                                                    $mysql_arab=mysql_query("select distinct PA_col from t_sample_desc where species='arab';");
                                                                    echo "<div id='arab4'>";
                                                                    while($arab_row= mysql_fetch_row($mysql_arab))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='depac2' id=d1$i name=sample2[] value=$arab_row[0] onclick=\"ClickOption(this,'c1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='depac2' name=sample2[] id=bsys2$j value=$value onclick=\"ClickOption(this,'bsys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //japonica
                                                                    $i=1;
                                                                    $mysql_japonica=mysql_query("select distinct PA_col from t_sample_desc where species='japonica';");
                                                                    echo "<div id='japonica4' style='display:none'>";
                                                                    while($japonica_row= mysql_fetch_row($mysql_japonica))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='depac2' id=d2$i name=sample2[] value=$japonica_row[0] onclick=\"ClickOption(this,'c2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='depac2' name=sample2[] id=bsys2$j value=$value onclick=\"ClickOption(this,'bsys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //mtr
                                                                    $i=1;
                                                                    $mysql_mtr=mysql_query("select distinct PA_col from t_sample_desc where species='mtr';");
                                                                    echo "<div id='mtr4' style='display:none'>";
                                                                    while($mtr_row= mysql_fetch_row($mysql_mtr))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='depac2' id=d3$i name=sample2[] value=$mtr_row[0] onclick=\"ClickOption(this,'c3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='depac2' name=sample2[] id=bsys2$j value=$value onclick=\"ClickOption(this,'bsys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //chlamy
                                                                    $i=1;
                                                                    $mysql_chlamy=mysql_query("select distinct PA_col from t_sample_desc where species='chlamy';");
                                                                    echo "<div id='chlamy4' style='display:none'>";
                                                                    while($chlamy_row= mysql_fetch_row($mysql_chlamy))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='depac2' id=d4$i name=sample2[] value=$chlamy_row[0] onclick=\"ClickOption(this,'c4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='depac2' name=sample2[] id=bsys2$j value=$value onclick=\"ClickOption(this,'bsys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="depac" method="post" class="ym-form">
                                        <div class="ym-fbox box">
                                            <label for="depac_normethod">Normalization method</label>
                                            <select name="depac_normethod" id="depac_normethod">
                                                    <option value='none' selected="true">None</option>
                                                    <option value='TPM'>TPM</option>
                                                    <option value='DESeq'>DESeq</option>
                                                    <option value='EdgeR'>EdgeR</option>
                                            </select>
                                            <label for="depac_method">Method</label>
                                            <select name="depac_method">
                                                    <option value='DEXSEQ'>DEXSEQ</option>
                                            </select>
                                            <label for="depac_min_pat">Min PAT</label>
                                            <input type='text' name='depac_min_pat' value='5'/>
                                            <label for="depac_multi_test">Multi-test adjustment method</label>
                                            <select name="depac_multi_test">
                                                <option value='Bonferroni' selected="true">Bonferroni</option>
                                                <option value='Not'>Not adjust</option>
                                            </select>
                                            <label for="depac_sig">Significance Level</label>
                                            <select name="depac_sig">
                                                <option value='0.01'>0.01</option>
                                                <option value='0.05' selected="true">0.05</option>
                                                <option value='0.1'>0.1</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ym-clearfix"></div>
                                    <div class="box fsubmit">
                                        <input type="button" id='depac-submit' value="Submit">
                                        <button type="reset">Reset</button>
                                        <input type="button" onclick="demo('depac')" value="Demo">
                                        <input type="button" onclick="javascript:window.location.href='./help.php#analysishelp3'" value='Help'>
                                    </div>
                                    </form>
                             </div>
                             <div data-pws-tab="3'UTR Lengthening" data-pws-tab-name="3'UTR Lengthening" style="width: 100%">
                                 <form id="only3utr-form" class="info">
                                        <div class="box samples">
                                            <table id="samples" >
                                                <thead><center>Select two groups of samples</center></thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div id="sample5" style="width:50%;margin:auto;">
                                                                <label for="all1">Sample 1</label><br>
                                                                    <?php
                                                                    $sys_sample=array();
                                                                        //arab
                                                                        $i=1;
                                                                        $mysql_arab=mysql_query("select distinct PA_col from t_sample_desc where species='arab';");
                                                                        echo "<div id='arab5'>";
                                                                        while($arab_row= mysql_fetch_row($mysql_arab))
                                                                        {
                                                                            echo "<input type=\"checkbox\" class='only3utr1' id=e1$i name=sample1[] value=$arab_row[0] onclick=\"ClickOption(this,'f1$i')\">$arab_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='arab'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" class='only3utr1' name=sample1[] id=csys1$j value=$value onclick=\"ClickOption(this,'csys2$j')\">$value<br>";
                                                                                $j++;
                                                                            }
                                                                        }
                                                                        echo "</div>";
                                                                        //japonica
                                                                        $i=1;
                                                                        $mysql_japonica=mysql_query("select distinct PA_col from t_sample_desc where species='japonica';");
                                                                        echo "<div id='japonica5' style='display:none'>";
                                                                        while($japonica_row= mysql_fetch_row($mysql_japonica))
                                                                        {
                                                                            echo "<input type=\"checkbox\" class='only3utr1' id=e2$i name=sample1[] value=$japonica_row[0] onclick=\"ClickOption(this,'f2$i')\">$japonica_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='japonica'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" class='only3utr1' name=sample1[] id=csys1$j value=$value onclick=\"ClickOption(this,'csys2$j')\">$value<br>";
                                                                                $j++;
                                                                            }
                                                                        }
                                                                        echo "</div>";
                                                                        //mtr
                                                                        $i=1;
                                                                        $mysql_mtr=mysql_query("select distinct PA_col from t_sample_desc where species='mtr';");
                                                                        echo "<div id='mtr5' style='display:none'>";
                                                                        while($mtr_row= mysql_fetch_row($mysql_mtr))
                                                                        {
                                                                            echo "<input type=\"checkbox\" class='only3utr1' id=e3$i name=sample1[] value=$mtr_row[0] onclick=\"ClickOption(this,'f3$i')\">$mtr_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='mtr'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" class='only3utr1' name=sample1[] id=csys1$j value=$value onclick=\"ClickOption(this,'csys2$j')\">$value<br>";
                                                                                $j++;
                                                                            }
                                                                        }
                                                                        echo "</div>";
                                                                        //chlamy
                                                                        $i=1;
                                                                        $mysql_chlamy=mysql_query("select distinct PA_col from t_sample_desc where species='chlamy';");
                                                                        echo "<div id='chlamy5' style='display:none'>";
                                                                        while($chlamy_row= mysql_fetch_row($mysql_chlamy))
                                                                        {
                                                                            echo "<input type=\"checkbox\" class='only3utr1' id=e4$i name=sample1[] value=$chlamy_row[0] onclick=\"ClickOption(this,'f4$i')\">$chlamy_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='chlamy'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" class='only3utr1' name=sample1[] id=csys1$j value=$value onclick=\"ClickOption(this,'csys2$j')\">$value<br>";
                                                                                $j++;
                                                                            }
                                                                        }
                                                                        echo "</div>";
                                                                    ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div id="sample6" style="width:50%;margin:auto;">
                                                                <label for="all2">Sample 2</label><br>
                                                                    <?php
                                                                        //arab
                                                                        $i=1;
                                                                        $mysql_arab=mysql_query("select distinct PA_col from t_sample_desc where species='arab';");
                                                                        echo "<div id='arab6'>";
                                                                        while($arab_row= mysql_fetch_row($mysql_arab))
                                                                        {
                                                                            echo "<input type=\"checkbox\" class='only3utr2' id=f1$i name=sample2[] value=$arab_row[0] onclick=\"ClickOption(this,'e1$i')\">$arab_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='arab'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" class='only3utr2' name=sample2[] id=csys2$j value=$value onclick=\"ClickOption(this,'csys1$j')\">$value<br>";
                                                                                $j++;
                                                                            }
                                                                        }
                                                                        echo "</div>";
                                                                        //japonica
                                                                        $i=1;
                                                                        $mysql_japonica=mysql_query("select distinct PA_col from t_sample_desc where species='japonica';");
                                                                        echo "<div id='japonica6' style='display:none'>";
                                                                        while($japonica_row= mysql_fetch_row($mysql_japonica))
                                                                        {
                                                                            echo "<input type=\"checkbox\" class='only3utr2' id=f2$i name=sample2[] value=$japonica_row[0] onclick=\"ClickOption(this,'e2$i')\">$japonica_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='japonica'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" class='only3utr2' name=sample2[] id=csys2$j value=$value onclick=\"ClickOption(this,'csys1$j')\">$value<br>";
                                                                                $j++;
                                                                            }
                                                                        }
                                                                        echo "</div>";
                                                                        //mtr
                                                                        $i=1;
                                                                        $mysql_mtr=mysql_query("select distinct PA_col from t_sample_desc where species='mtr';");
                                                                        echo "<div id='mtr6' style='display:none'>";
                                                                        while($mtr_row= mysql_fetch_row($mysql_mtr))
                                                                        {
                                                                            echo "<input type=\"checkbox\" class='only3utr2' id=f3$i name=sample2[] value=$mtr_row[0] onclick=\"ClickOption(this,'e3$i')\">$mtr_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='mtr'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" class='only3utr2' name=sample2[] id=csys2$j value=$value onclick=\"ClickOption(this,'csys1$j')\">$value<br>";
                                                                                $j++;
                                                                            }
                                                                        }
                                                                        echo "</div>";
                                                                        //chlamy
                                                                        $i=1;
                                                                        $mysql_chlamy=mysql_query("select distinct PA_col from t_sample_desc where species='chlamy';");
                                                                        echo "<div id='chlamy6' style='display:none'>";
                                                                        while($chlamy_row= mysql_fetch_row($mysql_chlamy))
                                                                        {
                                                                            echo "<input type=\"checkbox\" class='only3utr2' id=f4$i name=sample2[] value=$chlamy_row[0] onclick=\"ClickOption(this,'e4$i')\">$chlamy_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='chlamy'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" class='only3utr2' name=sample2[] id=csys2$j value=$value onclick=\"ClickOption(this,'csys1$j')\">$value<br>";
                                                                                $j++;
                                                                            }
                                                                        }
                                                                        echo "</div>";
                                                                    ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="only3utr" class="ym-form">
                                            <div class="box ym-fbox">
                                                    <label for="sgminpat">Min PAT</label>
                                                    <input type='text' value='5' name="sgminpat"/>
                                                    <label for="">Multi-test adjustment method</label>
                                                    <select>
                                                        <option checked='true' value='bonferroni' />Bonferroni
                                                        <option value='notadjust'/>Not adjust
                                                    </select>
                                                    <label for="only3utr_sig">Significance Level</label>
                                                    <select name="only3utr_sig">
                                                        <option value="0.01"/>0.01
                                                        <option checked='true' value="0.05"/>0.05
                                                        <option value="0.1"/>0.1
                                                    </select>
                                            </div>
                                        </div>
                                    <div class="ym-clearfix"></div>
                                    <div class="box fsubmit">
                                        <input type="button" id='only3utr-submit' value="Submit">
                                        <button type="reset">Reset</button>
                                        <input type="button" onclick="demo('only3utr')" value="Demo">
                                        <input type="button" onclick="javascript:window.location.href='./help.php#analysishelp4'" value='Help'>
                                    </div>
                                    </form>
                             </div>
                             <div data-pws-tab="APA Switching" data-pws-tab-name="APA Switching" style="width: 100%">
                                 <form id="none3utr-form" class="info">
                                    <div class="box samples">
                                        <table id="samples" >
                                            <thead><center>Select two groups of samples</center></thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div id="sample7" style="width:50%;margin:auto;">
                                                            <label for="all1">Sample 1</label><br>
                                                                <?php
                                                                $sys_sample=array();
                                                                    //arab
                                                                    $i=1;
                                                                    $mysql_arab=mysql_query("select distinct PA_col from t_sample_desc where species='arab';");
                                                                    echo "<div id='arab7'>";
                                                                    while($arab_row= mysql_fetch_row($mysql_arab))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='none3utr1' id=g1$i name=sample1[] value=$arab_row[0] onclick=\"ClickOption(this,'h1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='none3utr1' name=sample1[] id=dsys1$j value=$value onclick=\"ClickOption(this,'dsys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //japonica
                                                                    $i=1;
                                                                    $mysql_japonica=mysql_query("select distinct PA_col from t_sample_desc where species='japonica';");
                                                                    echo "<div id='japonica7' style='display:none'>";
                                                                    while($japonica_row= mysql_fetch_row($mysql_japonica))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='none3utr1' id=g2$i name=sample1[] value=$japonica_row[0] onclick=\"ClickOption(this,'h2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='none3utr1' name=sample1[] id=dsys1$j value=$value onclick=\"ClickOption(this,'dsys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //mtr
                                                                    $i=1;
                                                                    $mysql_mtr=mysql_query("select distinct PA_col from t_sample_desc where species='mtr';");
                                                                    echo "<div id='mtr7' style='display:none'>";
                                                                    while($mtr_row= mysql_fetch_row($mysql_mtr))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='none3utr1' id=g3$i name=sample1[] value=$mtr_row[0] onclick=\"ClickOption(this,'h3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='none3utr1' name=sample1[] id=dsys1$j value=$value onclick=\"ClickOption(this,'dsys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //chlamy
                                                                    $i=1;
                                                                    $mysql_chlamy=mysql_query("select distinct PA_col from t_sample_desc where species='chlamy';");
                                                                    echo "<div id='chlamy7' style='display:none'>";
                                                                    while($chlamy_row= mysql_fetch_row($mysql_chlamy))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='none3utr1' id=g4$i name=sample1[] value=$chlamy_row[0] onclick=\"ClickOption(this,'h4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='none3utr1' name=sample1[] id=dsys1$j value=$value onclick=\"ClickOption(this,'dsys2$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div id="sample8" style="width:50%;margin:auto;">
                                                            <label for="all2">Sample 2</label><br>
                                                                <?php
                                                                    //arab
                                                                    $i=1;
                                                                    $mysql_arab=mysql_query("select distinct PA_col from t_sample_desc where species='arab';");
                                                                    echo "<div id='arab8'>";
                                                                    while($arab_row= mysql_fetch_row($mysql_arab))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='none3utr2' id=h1$i name=sample2[] value=$arab_row[0] onclick=\"ClickOption(this,'g1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='none3utr2' name=sample2[] id=dsys2$j value=$value onclick=\"ClickOption(this,'dsys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //japonica
                                                                    $i=1;
                                                                    $mysql_japonica=mysql_query("select distinct PA_col from t_sample_desc where species='japonica';");
                                                                    echo "<div id='japonica8' style='display:none'>";
                                                                    while($japonica_row= mysql_fetch_row($mysql_japonica))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='none3utr2' id=h2$i name=sample2[] value=$japonica_row[0] onclick=\"ClickOption(this,'g2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='none3utr2' name=sample2[] id=dsys2$j value=$value onclick=\"ClickOption(this,'dsys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //mtr
                                                                    $i=1;
                                                                    $mysql_mtr=mysql_query("select distinct PA_col from t_sample_desc where species='mtr';");
                                                                    echo "<div id='mtr8' style='display:none'>";
                                                                    while($mtr_row= mysql_fetch_row($mysql_mtr))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='none3utr2' id=h3$i name=sample2[] value=$mtr_row[0] onclick=\"ClickOption(this,'g3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='none3utr2' name=sample2[] id=dsys2$j value=$value onclick=\"ClickOption(this,'dsys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                    //chlamy
                                                                    $i=1;
                                                                    $mysql_chlamy=mysql_query("select distinct PA_col from t_sample_desc where species='chlamy';");
                                                                    echo "<div id='chlamy8' style='display:none'>";
                                                                    while($chlamy_row= mysql_fetch_row($mysql_chlamy))
                                                                    {
                                                                        echo "<input type=\"checkbox\" class='none3utr2' id=h4$i name=sample2[] value=$chlamy_row[0] onclick=\"ClickOption(this,'g4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" class='none3utr2' name=sample2[] id=dsys2$j value=$value onclick=\"ClickOption(this,'dsys1$j')\">$value<br>";
                                                                            $j++;
                                                                        }
                                                                    }
                                                                    echo "</div>";
                                                                ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="none3utr" method="post" class="ym-form">
                                        <div class="box ym-fbox">
                                                <label for="sgnm">Normalization method</label>
                                                <select id="sgnm">
                                                    <option value="none" checked="true"/>None
                                                    <option value="TPM"/>None
                                                    <option value="DESeq"/>None
                                                </select>
                                                <label for="minpat2">Distance(nt)</label>
                                                <input type="text" value="50" name="minpat2"/>
                                                <label for="uttp">Using top two PACs</label>
                                                <input type="checkbox" checked="true" name="uttp"/>
                                                <label for="minpat3">Min PAT of one PAC</label>
                                                <input type="text" value="5"name="minpat3"/>
                                                <label for="minpat4">Min total PAT of one PAC in both samples</label>
                                                <input type="text" value="10" name="minpat4"/>
                                                <label for="minpat5">Min difference of PAC between the two PAC</label>
                                                <input type="text" value="5" name="minpat5"/>
                                                <label for="minpat6">Min fold change of two PAC in at least one sample</label>
                                                <input type="text" value="2" name="minpat6"/>
                                        </div>
                                    </div>
                                    <div class="ym-clearfix"></div>
                                    <div class="box fsubmit">
                                        <input type="button" id='none3utr-submit' value="Submit">
                                        <button type="reset">Reset</button>
                                        <input type="button" onclick="demo('none3utr')" value="Demo">
                                        <input type="button" onclick="javascript:window.location.href='./help.php#analysishelp5'" value='Help'>
                                    </div>
                                    </form>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
           
            <script>
                jQuery(document).ready(function($){
                                $('.tabs').pwstabs({
                                effect: 'slideleft',
                                defaultTab: 1,
                                containerWidth: '1080px'
                             });
                             });
                function refresh(){
                    var tabDataIdValue = $('.pws_tab_active').data('tab-id');
                    var pwsParent = $('.pws_tab_active').parent().parent().parent();
                    var currentTab = pwsParent.find('div[data-pws-tab="' + tabDataIdValue + '"]');
                    currentTab.parent().height(parseInt(currentTab.height()));
                }
                <?php
                    $arr_arab=array();
                    $arr_japonica=array();
                    $arr_mtr=array();
                    $arr_chlamy=array();
                    echo "var chr=[";
                    //arabidopsis
                    $arab_sql=mysql_query("select distinct chr from t_japonica_gff;");
                    $i=0;
                    while($arab_row=  mysql_fetch_row($arab_sql)){
                        array_push($arr_arab, $arab_row[0]);
                    }
                    echo "[\"";
                    foreach ($arr_arab as $key => $value) {
                        if($key!=  count($arr_arab)-1)
                            echo $value."\",\"";
                        else
                            echo $value;
                    }
                    echo "\"],";
                    //japonica
                    $arab_sql=mysql_query("select distinct chr from t_arab_gff;");
                    $i=0;
                    while($arab_row=  mysql_fetch_row($arab_sql)){
                        array_push($arr_japonica, $arab_row[0]);
                    }
                    echo "[\"";
                    foreach ($arr_japonica as $key => $value) {
                        if($key!=  count($arr_japonica)-1)
                            echo $value."\",\"";
                        else
                            echo $value;
                    }
                    echo "\"],";
                    //mtr
                    $arab_sql=mysql_query("select distinct chr from t_mtr_gff;");
                    $i=0;
                    while($arab_row=  mysql_fetch_row($arab_sql)){
                        array_push($arr_mtr, $arab_row[0]);
                    }
                    echo "[\"";
                    foreach ($arr_mtr as $key => $value) {
                        if($key!=  count($arr_mtr)-1)
                            echo $value."\",\"";
                        else
                            echo $value;
                    }
                    echo "\"],";
                    //chlamy
                    $arab_sql=mysql_query("select distinct chr from t_chlamy_gff;");
                    $i=0;
                    while($arab_row=  mysql_fetch_row($arab_sql)){
                        array_push($arr_chlamy, $arab_row[0]);
                    }
                    echo "[\"";
                    foreach ($arr_chlamy as $key => $value) {
                        if($key!=  count($arr_chlamy)-1)
                            echo $value."\",\"";
                        else
                            echo $value;
                    }
                    echo "\"]";
                    echo "];";
                ?>
                function chgArrow(){
                    if($('#hid').is(":visible")){
                        $('#arrow').attr("src","./pic/down.png");
                    }
                    else{
                        $('#arrow').attr("src","./pic/up.png");
                    }
                }
                function getchr(){
                    var sltSpecies=document.getElementById("species");
                    var sltChr1=document.getElementById("chr1");
                    var speciesChr=chr[sltSpecies.selectedIndex];
                    sltChr1.length=1;
                    for(var i=0;i<speciesChr.length;i++){
                        sltChr1[i+1]=new Option(speciesChr[i],speciesChr[i]);
                    }
                };   
                function userchr(a){
                    var sltChr=document.getElementById("chr");
                    var speciesChr=chr[a];
                    for(var i=0;i<speciesChr.length;i++){
                        sltChr[i+1]=new Option(speciesChr[i],speciesChr[i]);
                    }
                }
                function div_option(t)
                {
                    for(var i=1;i<t.length;i++)
                    {
                        document.getElementById(t.options[i].value).style.display="none";
                    }
                    if(t.value!="choose")
                    {
                        document.getElementById(t.value).style.display="block";
                     }   
                }
                function div_option2(a){
                    var t=a.options[a.selectedIndex].value;
                    var t1=t+"1";
                    var t2=t+"2";
                    var t3=t+"3";
                    var t4=t+"4";
                    var t5=t+"5";
                    var t6=t+"6";
                    var t7=t+"7";
                    var t8=t+"8";
                    var a1=document.getElementById(t1);
                    var a2=document.getElementById(t2);
                    var a3=document.getElementById(t3);
                    var a4=document.getElementById(t4);
                    var a5=document.getElementById(t5);
                    var a6=document.getElementById(t6);
                    var a7=document.getElementById(t7);
                    var a8=document.getElementById(t8);
                    for(var i=0;i<a.length;i++)
                    {
                        var x=a.options[i].value;
                        var x1=x+"1";
                        var x2=x+"2";
                        var x3=x+"3";
                        var x4=x+"4";
                        var x5=x+"5";
                        var x6=x+"6";
                        var x7=x+"7";
                        var x8=x+"8";
                        document.getElementById(x1).style.display='none';
                        document.getElementById(x2).style.display='none';
                        document.getElementById(x3).style.display='none';
                        document.getElementById(x4).style.display='none';
                        document.getElementById(x5).style.display='none';
                        document.getElementById(x6).style.display='none';
                        document.getElementById(x7).style.display='none';
                        document.getElementById(x8).style.display='none';
                    }
                    if(a1.value!="choose")
                    {
                        a1.style.display="block";
                        a2.style.display="block";
                        a3.style.display="block";
                        a4.style.display="block";
                        a5.style.display="block";
                        a6.style.display="block";
                        a7.style.display="block";
                        a8.style.display="block";
                     }   
                }
                function ClickOption(obj,id){
                    var O = document.getElementById(id);
                    O.disabled=obj.checked;
                }
                function unchecked(){
                    $("[type='checkbox']").removeAttr("checked");
                }
            </script>
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
                    <br>Please <font color='red'>don't</font> close this page.
                </div>
            </fieldset>
        </div>
        <div class="bottom">
        <?php
            include"footer.php";
            ?>
        </div>
    </body>
</html>