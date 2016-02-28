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
        <script src="./src/idangerous.swiper.min.js"></script>
        <link rel="stylesheet" href="./src/idangerous.swiper.css">
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
                    var params = $('#species,#degene-form').serialize(); //序列化表单的值
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
                 });
                 $('#depac-submit').click(function (){
                    var params = $('#species,#depac-form').serialize(); //序列化表单的值
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
                 });
                 $('#only3utr-submit').click(function (){
                    var params = $('#species,#only3utr-form').serialize(); //序列化表单的值
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
                 });
                 $('#none3utr-submit').click(function (){
                    var params = $('input,textarea,select').serialize(); //序列化表单的值
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
       <fieldset >
            <legend>
                <h4 >
                    <font color="#224055" ><b>Analysis: </b>analysis of APA switching between two conditions</font>
                </h4>
            </legend>
           <div class="box info ym-form">
               <label for="species" style="float:left;width:7%">Species:&nbsp;</label>
                <select id="species" name="species" style="width:93%" onchange="div_option2(this);getchr()">
                    <option value="japonica">Japonica rice</option>
                     <option value="arab" selected="selected">Arabidopsis thaliana</option>
                    <option value="mtr">Medicago truncatula</option>
                    <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
                 </select>
            </div>
            <div class="ym-grid" >
                <div > 
                    <div class="wrap">
                        <div class="tabs">
                            <a href="#" hidefocus="true" class="active">DE Gene</a>
                            <a href="#" hidefocus="true">DE PAC</a>
                            <a href="#" hidefocus="true">3'UTR Lenghening</a>
                            <a href="#" hidefocus="true">APA Switching</a>
                        </div><br>    
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="content-slide">
                                    <form id="degene-form">
                                    <div class="box info samples">
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
                                                                        echo "<input type=\"checkbox\" id=a1$i name=sample1[] value=$arab_row[0] onclick=\"ClickOption(this,'b1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                        array_push($sys_sample_arab, $arab_row[0]);
                                                                    }
                                                                    $_SESSION['sys_real_arab']=$sys_sample_arab;
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=asys1$j value=$value onclick=\"ClickOption(this,'asys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=a2$i name=sample1[] value=$japonica_row[0] onclick=\"ClickOption(this,'b2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                        array_push($sys_sample_japonica, $japonica_row[0]);
                                                                    }
                                                                    $_SESSION['sys_real_japonica']=$sys_sample_japonica;
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=asys1$j value=$value onclick=\"ClickOption(this,'asys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=a3$i name=sample1[] value=$mtr_row[0] onclick=\"ClickOption(this,'b3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                        array_push($sys_sample_mtr, $mtr_row[0]);
                                                                    }
                                                                    $_SESSION['sys_real_mtr']=$sys_sample_mtr;
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=asys1$j value=$value onclick=\"ClickOption(this,'asys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=a4$i name=sample1[] value=$chlamy_row[0] onclick=\"ClickOption(this,'b4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                        array_push($sys_sample_chlamy, $chlamy_row[0]);
                                                                    }
                                                                    $_SESSION['sys_real_chlamy']=$sys_sample_chlamy;
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=asys1$j value=$value onclick=\"ClickOption(this,'asys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=b1$i name=sample2[] value=$arab_row[0] onclick=\"ClickOption(this,'a1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=asys2$j value=$value onclick=\"ClickOption(this,'asys1$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=b2$i name=sample2[] value=$japonica_row[0] onclick=\"ClickOption(this,'a2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=asys2$j value=$value onclick=\"ClickOption(this,'asys1$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=b3$i name=sample2[] value=$mtr_row[0] onclick=\"ClickOption(this,'a3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=asys2$j value=$value onclick=\"ClickOption(this,'asys1$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=b4$i name=sample2[] value=$chlamy_row[0] onclick=\"ClickOption(this,'a4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=asys2$j value=$value onclick=\"ClickOption(this,'asys1$j')\">$value<br>";
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
                                    <div id="degene" class="ym-form box" style="padding: 1.42857em;">
                                        <div id="degeneOptionType" class="ym-fbox" style="margin: 0px;width: 300px">
                                            <input type="radio" checked="true" name="option" onclick="chgoption('degene')" value="degene_option">Must options&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="option" onclick="chgoption('degene')" value="degene_addition">Filter options
                                            <hr style=" height:2px;border:none;border-top:1px solid #ccc;" />
                                        </div>
                                        <div id="degene_option" class="ym-fbox">
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
                                         <div id="degene_addition" class="ym-fbox" style="display: none;">
                                             <br><label for="chr" style="display: inline;padding-right: 15px">in</label>
                                              <select id="chr1" name="chr" style="display: inline;">
                                                    <option value="all" selected="selected">All</option>
                                             </select><br>
                                            <label for="start" style="display: inline"> from</label>
                                            <input type="text" id='start' name="start" style="display: inline;"><br>
                                            <label for="end" style="display: inline"> to</label>
                                            <input type="text" id='end' name="end" style="display: inline;margin-left: 14px">
                                            <label for="gene_id">Gene ID:(use ',' to split different gene id)</label>
                                            <textarea style="width:77%" name="gene_id" id='gene_id'></textarea>
                                            <label for="go_accession">Go term accession:(use ',' to split different gene id)</label>
                                            <textarea style="width:77%" name='go_accession' id='go_accession'></textarea>
                                            <label for="go_name" >Go term name:</label>
                                            <input type='text' name='go_name' id='go_name' style="width:77%;"/>
                                            <label for="function">Function:</label>
                                            <input type='text' name='function' id='function' style="width:77%;"/>
                                        </div>
                                    </div>
                                    <div class="ym-clearfix"></div>
                                    <div class="box info fsubmit">
                                        <input type="button" id='degene-submit' value="submit">
                                        <button type="reset">reset</button>
                                        <input type="button" onclick="demo('degene')" value="demo">
                                    </div>
                                    </form>
                              </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="content-slide">
                                    <form id="depac-form">
                                    <div class="box info samples">
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
                                                                        echo "<input type=\"checkbox\" id=c1$i name=sample1[] value=$arab_row[0] onclick=\"ClickOption(this,'d1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    unset($sys_sample);
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=bsys1$j value=$value onclick=\"ClickOption(this,'bsys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=c2$i name=sample1[] value=$japonica_row[0] onclick=\"ClickOption(this,'d2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=bsys1$j value=$value onclick=\"ClickOption(this,'bsys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=c3$i name=sample1[] value=$mtr_row[0] onclick=\"ClickOption(this,'d3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=bsys1$j value=$value onclick=\"ClickOption(this,'bsys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=c4$i name=sample1[] value=$chlamy_row[0] onclick=\"ClickOption(this,'d4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=bsys1$j value=$value onclick=\"ClickOption(this,'bsys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=d1$i name=sample2[] value=$arab_row[0] onclick=\"ClickOption(this,'c1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=bsys2$j value=$value onclick=\"ClickOption(this,'bsys1$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=d2$i name=sample2[] value=$japonica_row[0] onclick=\"ClickOption(this,'c2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=bsys2$j value=$value onclick=\"ClickOption(this,'bsys1$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=d3$i name=sample2[] value=$mtr_row[0] onclick=\"ClickOption(this,'c3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=bsys2$j value=$value onclick=\"ClickOption(this,'bsys1$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=d4$i name=sample2[] value=$chlamy_row[0] onclick=\"ClickOption(this,'c4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=bsys2$j value=$value onclick=\"ClickOption(this,'bsys1$j')\">$value<br>";
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
                                    <div id="depac" class="ym-form box" style="padding: 1.42857em;">
                                        <div id="depacOptionType" class="ym-fbox" style="margin: 0px;width: 300px">
                                            <input type="radio" checked="true" name="option" onclick="chgoption('depac')" value="depac_option">Must options&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="option" onclick="chgoption('depac')" value="depac_addition">Filter options
                                            <hr style=" height:2px;border:none;border-top:1px solid #ccc;" />
                                        </div>
                                        <div id="depac_option" class="ym-fbox ">
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
                                        <div id="depac_addition" class="ym-fbox" style="display: none;">
                                            <br><label for="chr" style="display: inline;padding-right: 15px">in</label>
                                                  <select id="chr2" name="chr" style="display: inline;">
                                                        <option value="all" selected="selected">All</option>
                                                 </select><br>
                                                <label for="start" style="display: inline"> from</label>
                                                <input type="text" id='start' name="start" style="display: inline;"><br>
                                                <label for="end" style="display: inline"> to</label>
                                                <input type="text" id='end' name="end" style="display: inline;margin-left: 14px">
                                                <label for="gene_id">Gene ID:(use ',' to split different gene id)</label>
                                                <textarea style="width:77%" name="gene_id" id='gene_id'></textarea>
                                                <label for="go_accession">Go term accession:(use ',' to split different gene id)</label>
                                                <textarea style="width:77%" name='go_accession' id='go_accession'></textarea>
                                                <label for="go_name" >Go term name:</label>
                                                <input type='text' name='go_name' id='go_name' style="width:77%;"/>
                                                <label for="function">Function:</label>
                                                <input type='text' name='function' id='function' style="width:77%;"/>
                                        </div>
                                    </div>
                                    <div class="ym-clearfix"></div>
                                    <div class="box info fsubmit">
                                        <input type="button" id='depac-submit' value="submit">
                                        <button type="reset">reset</button>
                                        <input type="button" onclick="demo('depac')" value="demo">
                                    </div>
                                    </form>
                                </div>
                              </div>
                            <div class="swiper-slide">
                                <div class="content-slide">
                                    <form id="only3utr-form">
                                        <div class="box info samples">
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
                                                                            echo "<input type=\"checkbox\" id=e1$i name=sample1[] value=$arab_row[0] onclick=\"ClickOption(this,'f1$i')\">$arab_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='arab'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" name=sample1[] id=csys1$j value=$value onclick=\"ClickOption(this,'csys2$j')\">$value<br>";
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
                                                                            echo "<input type=\"checkbox\" id=e2$i name=sample1[] value=$japonica_row[0] onclick=\"ClickOption(this,'f2$i')\">$japonica_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='japonica'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" name=sample1[] id=csys1$j value=$value onclick=\"ClickOption(this,'csys2$j')\">$value<br>";
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
                                                                            echo "<input type=\"checkbox\" id=e3$i name=sample1[] value=$mtr_row[0] onclick=\"ClickOption(this,'f3$i')\">$mtr_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='mtr'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" name=sample1[] id=csys1$j value=$value onclick=\"ClickOption(this,'csys2$j')\">$value<br>";
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
                                                                            echo "<input type=\"checkbox\" id=e4$i name=sample1[] value=$chlamy_row[0] onclick=\"ClickOption(this,'f4$i')\">$chlamy_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='chlamy'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" name=sample1[] id=csys1$j value=$value onclick=\"ClickOption(this,'csys2$j')\">$value<br>";
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
                                                                            echo "<input type=\"checkbox\" id=f1$i name=sample2[] value=$arab_row[0] onclick=\"ClickOption(this,'e1$i')\">$arab_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='arab'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" name=sample2[] id=csys2$j value=$value onclick=\"ClickOption(this,'csys1$j')\">$value<br>";
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
                                                                            echo "<input type=\"checkbox\" id=f2$i name=sample2[] value=$japonica_row[0] onclick=\"ClickOption(this,'e2$i')\">$japonica_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='japonica'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" name=sample2[] id=csys2$j value=$value onclick=\"ClickOption(this,'csys1$j')\">$value<br>";
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
                                                                            echo "<input type=\"checkbox\" id=f3$i name=sample2[] value=$mtr_row[0] onclick=\"ClickOption(this,'e3$i')\">$mtr_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='mtr'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" name=sample2[] id=csys2$j value=$value onclick=\"ClickOption(this,'csys1$j')\">$value<br>";
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
                                                                            echo "<input type=\"checkbox\" id=f4$i name=sample2[] value=$chlamy_row[0] onclick=\"ClickOption(this,'e4$i')\">$chlamy_row[0]<br>";
                                                                            $i++;
                                                                        }
                                                                        if($_SESSION['species']=='chlamy'){
                                                                            $j=1;
                                                                            foreach ($_SESSION['file_real'] as $key => $value) {
                                                                                echo "<input type=\"checkbox\" name=sample2[] id=csys2$j value=$value onclick=\"ClickOption(this,'csys1$j')\">$value<br>";
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
                                                <div class="ym-grid ym-fbox">
                                                    <label for="sgminpat">Min PAT</label>
                                                    <input type='text' value='5' name="sgminpat"/>
                                                </div>
                                                <div class="ym-grid ym-fbox">
                                                    <label for="">Multi-test adjustment method</label>
                                                    <select>
                                                        <option checked='true' value='bonferroni' />Bonferroni
                                                        <option value='notadjust'/>Not adjust
                                                    </select>
                                                </div>
                                                <div class="ym-grid ym-fbox">
                                                    <label for="only3utr_sig">Significance Level</label>
                                                    <select name="only3utr_sig">
                                                        <option value="0.01"/>0.01
                                                        <option checked='true' value="0.05"/>0.05
                                                        <option value="0.1"/>0.1
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="ym-clearfix"></div>
                                    <div class="box info additonal">   
                                        <a class="ym-button ym-add flip">Additional Options</a>
                                        <div id="search">
                                            <div class="box info ym-form">
                                            <div class="ym-grid ym-fbox">
                                                <div class="ym-g33 ym-gl">
                                                    <label for="chr" style="margin-right:2%">in</label>
                                                      <select id="chr3" name="chr" style="width:80%">
                                                            <option value="all" selected="selected">All</option>
                                                     </select>
                                                </div>
                                                <div class="ym-g50 ym-gl">
                                                    <label for="start"style="margin:0 1%;"> from</label>
                                                    <input type="text" id='start' name="start">
                                                    <label for="end" style="margin:0 1%;"> to</label>
                                                    <input type="text" id='end' name="end">
                                                </div>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="gene_id">Gene ID:(use ',' to split different gene id)</label>
                                                <textarea style="width:100%" name="gene_id" id='gene_id'></textarea>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="go_accession">Go term accession:(use ',' to split different gene id)</label>
                                                <textarea style="width:100%" name='go_accession' id='go_accession'></textarea>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="go_name" >Go term name:</label>
                                                <input type='text' name='go_name' class="ym-gr" id='go_name' style="width:89%;"/>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="function">Function:</label>
                                                <input type='text' name='function' id='function' class="ym-gr" style="width:89%;"/><br>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box info fsubmit">
                                        <input type="button" id='only3utr-submit' value="submit">
                                        <button type="reset">reset</button>
                                        <input type="button" onclick="demo('only3utr')" value="demo">
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="content-slide">
                                    <form id="none3utr-form">
                                    <div class="box info samples">
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
                                                                        echo "<input type=\"checkbox\" id=g1$i name=sample1[] value=$arab_row[0] onclick=\"ClickOption(this,'h1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=dsys1$j value=$value onclick=\"ClickOption(this,'dsys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=g2$i name=sample1[] value=$japonica_row[0] onclick=\"ClickOption(this,'h2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=dsys1$j value=$value onclick=\"ClickOption(this,'dsys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=g3$i name=sample1[] value=$mtr_row[0] onclick=\"ClickOption(this,'h3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=dsys1$j value=$value onclick=\"ClickOption(this,'dsys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=g4$i name=sample1[] value=$chlamy_row[0] onclick=\"ClickOption(this,'h4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample1[] id=dsys1$j value=$value onclick=\"ClickOption(this,'dsys2$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=h1$i name=sample2[] value=$arab_row[0] onclick=\"ClickOption(this,'g1$i')\">$arab_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='arab'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=dsys2$j value=$value onclick=\"ClickOption(this,'dsys1$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=h2$i name=sample2[] value=$japonica_row[0] onclick=\"ClickOption(this,'g2$i')\">$japonica_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='japonica'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=dsys2$j value=$value onclick=\"ClickOption(this,'dsys1$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=h3$i name=sample2[] value=$mtr_row[0] onclick=\"ClickOption(this,'g3$i')\">$mtr_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='mtr'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=dsys2$j value=$value onclick=\"ClickOption(this,'dsys1$j')\">$value<br>";
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
                                                                        echo "<input type=\"checkbox\" id=h4$i name=sample2[] value=$chlamy_row[0] onclick=\"ClickOption(this,'g4$i')\">$chlamy_row[0]<br>";
                                                                        $i++;
                                                                    }
                                                                    if($_SESSION['species']=='chlamy'){
                                                                        $j=1;
                                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                                            echo "<input type=\"checkbox\" name=sample2[] id=dsys2$j value=$value onclick=\"ClickOption(this,'dsys1$j')\">$value<br>";
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
                                            <div class="ym-grid ym-fbox">
                                                <label for="sgnm">Normalization method</label>
                                                <select id="sgnm">
                                                    <option value="none" checked="true"/>None
                                                    <option value="TPM"/>None
                                                    <option value="DESeq"/>None
                                                </select>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="minpat2">Distance(nt)</label>
                                                <input type="text" value="50" name="minpat2"/>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="uttp">Using top two PACs</label>
                                                <input type="checkbox" checked="true" name="uttp"/>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="minpat3">Min PAT of one PAC</label>
                                                <input type="text" value="5"name="minpat3"/>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="minpat4">Min total PAT of one PAC in both samples</label>
                                                <input type="text" value="10" name="minpat4"/>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="minpat5">Min difference of PAC between the two PAC</label>
                                                <input type="text" value="5" name="minpat5"/>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="minpat6">Min fold change of two PAC in at least one sample</label>
                                                <input type="text" value="2" name="minpat6"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ym-clearfix"></div>
                                    <div class="box info additonal">   
                                        <a class="ym-button ym-add flip">Additional Options</a>
                                        <div id="search">
                                            <div class="box info ym-form">
                                                <div class="ym-grid ym-fbox">
                                                    <div class="ym-g33 ym-gl">
                                                        <label for="chr" style="margin-right:2%">in</label>
                                                          <select id="chr4" name="chr" style="width:80%">
                                                                <option value="all" selected="selected">All</option>
                                                         </select>
                                                    </div>
                                                    <div class="ym-g50 ym-gl">
                                                        <label for="start"style="margin:0 1%;"> from</label>
                                                        <input type="text" id='start' name="start">
                                                        <label for="end" style="margin:0 1%;"> to</label>
                                                        <input type="text" id='end' name="end">
                                                    </div>
                                                </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="gene_id">Gene ID:(use ',' to split different gene id)</label>
                                                <textarea style="width:100%" name="gene_id" id='gene_id'></textarea>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="go_accession">Go term accession:(use ',' to split different gene id)</label>
                                                <textarea style="width:100%" name='go_accession' id='go_accession'></textarea>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="go_name" >Go term name:</label>
                                                <input type='text' name='go_name' class="ym-gr" id='go_name' style="width:89%;"/>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <label for="function">Function:</label>
                                                <input type='text' name='function' id='function' class="ym-gr" style="width:89%;"/><br>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box info fsubmit">
                                        <input type="button" id='none3utr-submit' value="submit">
                                        <button type="reset">reset</button>
                                        <input type="button" onclick="demo('none3utr')" value="demo">
                                    </div>
                                    </form>
                                </div>
                            </div>
                          </div>
                       </div>
                    </div>
                </div>
            </div>
           
            <script>
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
                function getchr(){
                    var sltSpecies=document.getElementById("species");
                    var sltChr1=document.getElementById("chr1");
                    var sltChr2=document.getElementById("chr2");
                    var sltChr3=document.getElementById("chr3");
                    var sltChr4=document.getElementById("chr4");
                    var speciesChr=chr[sltSpecies.selectedIndex];
                    sltChr1.length=1;
                    sltChr2.length=1;
                    sltChr3.length=1;
                    sltChr4.length=1;
                    for(var i=0;i<speciesChr.length;i++){
                        sltChr1[i+1]=new Option(speciesChr[i],speciesChr[i]);
                        sltChr2[i+1]=new Option(speciesChr[i],speciesChr[i]);
                        sltChr3[i+1]=new Option(speciesChr[i],speciesChr[i]);
                        sltChr4[i+1]=new Option(speciesChr[i],speciesChr[i]);
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
                var tabsSwiper = new Swiper('.swiper-container',{
                  speed:500,
                  onSlideChangeStart: function(){
                    $(".tabs .active").removeClass('active');
                    $(".tabs a").eq(tabsSwiper.activeIndex).addClass('active');
                  }
                });
                $(".tabs a").on('touchstart mousedown',function(e){
                  e.preventDefault()
                  $(".tabs .active").removeClass('active');
                  $(this).addClass('active');
                  tabsSwiper.swipeTo($(this).index());
                })
                $(".tabs a").click(function(e){
                  e.preventDefault()
                })
                function chgoption(b){
                    $("#"+b+"_option").hide();
                    $("#"+b+"_addition").hide();
                    var a = $('#'+b+'OptionType input:radio[name=option]:checked').val();
                    $('#'+a).show();
                }
            </script>
        </fieldset>
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