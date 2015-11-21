<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Search</title>
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
    <body>
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
                    var params = $('input,textarea,select').serialize(); //序列化表单的值
//                    console.log(params);
//                    alert(params);
                    $.ajax({  
                        url:'aftertreatment.php?method=degene', //后台处理程序  
                        type:'post',       //数据传送方式  
                        dataType:'json',   //接受数据格式  
                        data:params,       //要传送的数据  
                        success:update_page1//回传函数(这里是函数名字)  
                    });  
                 });
                 $('#depac-submit').click(function (){
                    var params = $('input,textarea,select').serialize(); //序列化表单的值
//                    console.log(params);
//                    alert(params);
                    $.ajax({  
                        url:'aftertreatment.php?method=depac', //后台处理程序  
                        type:'post',       //数据传送方式  
                        dataType:'json',   //接受数据格式  
                        data:params,       //要传送的数据  
                        success:update_page2//回传函数(这里是函数名字)  
                    });  
                 });
                 $('#only3utr-submit').click(function (){
                    var params = $('input,textarea,select').serialize(); //序列化表单的值
//                    console.log(params);
//                    alert(params);
                    $.ajax({  
                        url:'aftertreatment.php?method=only3utr', //后台处理程序  
                        type:'post',       //数据传送方式  
                        dataType:'json',   //接受数据格式  
                        data:params,       //要传送的数据  
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
                        success:update_page4//回传函数(这里是函数名字)  
                    });  
                 });
                 $(".flip").click(function(){
                    $('#search div').slideToggle("slow");


                 });
        });
        function update_page1(json) { //回传函数实体，参数为XMLhttpRequest.responseText  
                window.location.href="aftertreatment_result_test.php?result=degene";
//            alert("successful");
        }
        function update_page2(json) { //回传函数实体，参数为XMLhttpRequest.responseText  
            window.location.href="aftertreatment_result_test.php?result=depac";
//            alert("successful");
        }
        function update_page3(json) { //回传函数实体，参数为XMLhttpRequest.responseText  
            window.location.href="aftertreatment_result_test.php?result=switchinggene_o";
//            alert("successful");
        }
        function update_page4(json) { //回传函数实体，参数为XMLhttpRequest.responseText  
            window.location.href="aftertreatment_result_test.php?result=switchinggene_n";
//            alert("successful");
        }
        </script>
    <div class="ym-wrapper">
       <fieldset >
            <legend>
                <h4 >
                    <font color="#224055" ><b>Search</b>:Search and view the system samples</font>
                </h4>
            </legend>
           <div class="ym-g33 ym-gl">
                <label for="species" style="margin-right:2%">Species:</label>
                <select id="species" name="species" style="width:80%" onchange="div_option2(this);getchr()">
                     <option value="arab" selected="selected">Arabidopsis thaliana</option>
                    <option value="japonica">Japonica rice</option>
                    <option value="mtr">Medicago truncatula</option>
                    <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
                 </select>
            </div>
            <div class="ym-grid">
         
                <div class="flip" ><h4>Addtional Options</h4></div>
                <div id="search">
                    <div class="box info ym-form">
                    <div class="ym-g33 ym-gl">
                        <label for="chr" style="margin-right:2%">in</label>
                          <select id="chr" name="chr" style="width:80%">
                                <option value="all" selected="selected">All</option>
                         </select>
                    </div>
                    <div class="ym-g33 ym-gl">
                        <label for="start"style="margin:0 1%;"> from</label>
                        <input type="text" id='start' name="start">
                        <label for="end" style="margin:0 1%;"> to</label>
                        <input type="text" id='end' name="end">
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
            <div class="ym-grid">
                <div class="box info">
                    <table id="samples" style="width:100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <div id="sample1" style="width:50%;margin:auto;">
                                        <label for="all1">Sample 1</label><br>
                                            <?php
                                            $sys_sample=array();
                                                //arab
                                                $i=1;
                                                $mysql_arab=mysql_query("select distinct PA_col from t_sample_desc where species='arab';");
                                                echo "<div id='arab1'>";
                                                while($arab_row= mysql_fetch_row($mysql_arab))
                                                {
                                                    echo "<input type=\"checkbox\" id=a1$i name=sample1[] value=$arab_row[0] onclick=\"ClickOption(this,'b1$i')\">$arab_row[0]<br>";
                                                    $i++;
                                                    array_push($sys_sample, $arab_row[0]);
                                                }
                                                $_SESSION['sys_real_arab']=$sys_sample;
                                                unset($sys_sample);
                                                if($_SESSION['species']=='arab'){
                                                    $j=1;
                                                    foreach ($_SESSION['file_real'] as $key => $value) {
                                                        echo "<input type=\"checkbox\" name=sample1[] id=sys1$j value=$value onclick=\"ClickOption(this,'sys2$j')\">$value<br>";
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
                                                    array_push($sys_sample, $japonica_row[0]);
                                                }
                                                $_SESSION['sys_real_japonica']=$sys_sample;
                                                unset($sys_sample);
                                                if($_SESSION['species']=='japonica'){
                                                    $j=1;
                                                    foreach ($_SESSION['file_real'] as $key => $value) {
                                                        echo "<input type=\"checkbox\" name=sample1[] id=sys1$j value=$value onclick=\"ClickOption(this,'sys2$j')\">$value<br>";
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
                                                    array_push($sys_sample, $mtr_row[0]);
                                                }
                                                $_SESSION['sys_real_mtr']=$sys_sample;
                                                unset($sys_sample);
                                                if($_SESSION['species']=='mtr'){
                                                    $j=1;
                                                    foreach ($_SESSION['file_real'] as $key => $value) {
                                                        echo "<input type=\"checkbox\" name=sample1[] id=sys1$j value=$value onclick=\"ClickOption(this,'sys2$j')\">$value<br>";
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
                                                    array_push($sys_sample, $chlamy_row[0]);
                                                }
                                                $_SESSION['sys_real_chlamy']=$sys_sample;
                                                unset($sys_sample);
                                                if($_SESSION['species']=='chlamy'){
                                                    $j=1;
                                                    foreach ($_SESSION['file_real'] as $key => $value) {
                                                        echo "<input type=\"checkbox\" name=sample1[] id=sys1$j value=$value onclick=\"ClickOption(this,'sys2$j')\">$value<br>";
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
                                                        echo "<input type=\"checkbox\" name=sample2[] id=sys2$j value=$value onclick=\"ClickOption(this,'sys1$j')\">$value<br>";
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
                                                        echo "<input type=\"checkbox\" name=sample2[] id=sys2$j value=$value onclick=\"ClickOption(this,'sys1$j')\">$value<br>";
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
                                                        echo "<input type=\"checkbox\" name=sample2[] id=sys2$j value=$value onclick=\"ClickOption(this,'sys1$j')\">$value<br>";
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
                                                        echo "<input type=\"checkbox\" name=sample2[] id=sys2$j value=$value onclick=\"ClickOption(this,'sys1$j')\">$value<br>";
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
            </div>
            <div class="ym-grid" >
                <div class="box info"> 
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
                                    <div id="degene" class="ym-form">
                                        <div class="ym-grid ym-fbox">
                                            <div class="ym-g33 ym-gl">
                                                <div class="ym-gbox-left">
                                                    <label for="nor_method">Normalization method</label>
                                                    <select name="degene_nor_method" id="nor_method">
                                                          <option value='none' selected="true">None</option>
                                                          <option value='TPM'>TPM</option>
                                                          <option value='DESeq'>DESeq</option>
                                                          <option value='EdgeR'>EdgeR</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="ym-g33 ym-gl">
                                                <div class="ym-gbox-left">
                                                    <label for="method">Method</label>
                                                    <select name="degene_method">
                                                        <option value='EdgeR'>EdgeR</option>
                                                        <option value='DESeq'>DESeq</option>
                                                        <option value='DESeq2'>DESeq2</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="ym-g33 ym-gl">
                                                <label for="min_pat">Min PAT</label>
                                                <input type='text' name='degene_min_pat' value='5'/>
                                            </div>
                                        </div>
                                            <div class="ym-grid ym-fbox">
                                                <div class="ym-g50 ym-gl">
                                                    <label for="multi_test">Multi-test adjustment method</label>
                                                    <select name="degene_multi_test">
                                                        <option value='Bonferroni' selected="true">Bonferroni</option>
                                                        <option value='Not'>Not adjust</option>
                                                   </select>
                                                </div>
                                                <div class="ym-g50 ym-gl">
                                                    <label for="sig">Significance Level</label>
                                                    <select name="degene_sig">
                                                        <option value='0.01'>0.01</option>
                                                        <option value='0.05' selected="true">0.05</option>
                                                        <option value='0.1'>0.1</option>
                                                   </select>
                                                </div>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <button id='degene-submit'>submit</button>
                                                <button type="reset">reset</button>
                                            </div>
                                    </div>
                              </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="content-slide">
                                    <div id="depac" method="post" class="ym-form" action="./aftertreatment.php?method=depac">
                                        <div class="ym-grid ym-fbox">
                                            <div class="ym-g33 ym-gl">
                                                <div class="ym-gbox-left">
                                                    <label for="depac_normethod">Normalization method</label>
                                                    <select name="depac_normethod" id="depac_normethod">
                                                            <option value='none' selected="true">None</option>
                                                            <option value='TPM'>TPM</option>
                                                            <option value='DESeq'>DESeq</option>
                                                            <option value='EdgeR'>EdgeR</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="ym-g33 ym-gl">
                                                <div class="ym-gbox-left">
                                                    <label for="depac_method">Method</label>
                                                    <select name="depac_method">
                                                            <option value='DEXSEQ'>DEXSEQ</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="ym-g33 ym-gl">
                                                <label for="depac_min_pat">Min PAT</label>
                                                <input type='text' name='depac_min_pat' value='5'/>
                                            </div>
                                        </div>
                                        <div class="ym-grid ym-fbox">
                                            <div class="ym-g50 ym-gl">
                                                <label for="depac_multi_test">Multi-test adjustment method</label>
                                                <select name="depac_multi_test">
                                                    <option value='Bonferroni' selected="true">Bonferroni</option>
                                                    <option value='Not'>Not adjust</option>
                                                </select>
                                            </div>
                                            <div class="ym-g50 ym-gl">
                                                <label for="depac_sig">Significance Level</label>
                                                <select name="depac_sig">
                                                    <option value='0.01'>0.01</option>
                                                    <option value='0.05' selected="true">0.05</option>
                                                    <option value='0.1'>0.1</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="ym-grid ym-fbox">
                                            <button id='depac-submit'>submit</button>
                                            <button type="reset">reset</button>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            <div class="swiper-slide">
                                <div class="content-slide">
                                        <div id="only3utr-form" class="ym-form" method="post" action="./aftertreatment.php?method=only3utr">
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
                                            <div class="ym-grid ym-fbox">
                                                <button id='only3utr-submit'>submit</button>
                                                <button type="reset">reset</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="content-slide">
                                    <div id="none3utr-form" method="post" class="ym-form" action="./aftertreatment.php?method=none3utr">
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
                                        <div class="ym-grid ym-fbox">
                                            <button id='none3utr-submit'>submit</button>
                                            <button type="reset">reset</button>
                                        </div>
                                    </div>
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
                    $arab_sql=mysql_query("select distinct chr from t_arab_gff;");
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
                    $arab_sql=mysql_query("select distinct chr from t_japonica_gff;");
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
                <?php
                if(!isset($_SESSION['file'])){
                    echo "function getchr(){
                                var sltSpecies=document.getElementById(\"species\");
                                var sltChr=document.getElementById(\"chr\");
                                var speciesChr=chr[sltSpecies.selectedIndex];
                                sltChr.length=1;
                                for(var i=0;i<speciesChr.length;i++){
                                    sltChr[i+1]=new Option(speciesChr[i],speciesChr[i]);
                                }
                            }";   
                }
                ?>
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
                    var a1=document.getElementById(t1);
                    var a2=document.getElementById(t2);
                    for(var i=0;i<a.length;i++)
                    {
                        var x=a.options[i].value;
                        var x1=x+"1";
                        var x2=x+"2";
                        document.getElementById(x1).style.display='none';
                        document.getElementById(x2).style.display='none';
                    }
                    if(a1.value!="choose")
                    {
                        a1.style.display="block";
                        a2.style.display="block";
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
            </script>
        </fieldset>
    </div>
        <div class="bottom">
        <?php
            include"footer.php";
            ?>
        </div>
    </body>
</html>