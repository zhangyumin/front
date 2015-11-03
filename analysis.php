<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Search</title>
        <link href="./src/index.css" rel="stylesheet" type="text/css" />
        <script src="./src/jquery-2.0.0.min.js"></script>
        <script src="./src/jquery.slides.min.js"></script>
        <!-- Mobile viewport optimisation -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="js/wowslider/style.css" />
        <script type="text/javascript" src="js/wowslider/jquery.js"></script>
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
    <body 
        <?php 
            session_start();
            $con=  mysql_connect("localhost","root","root");
            mysql_select_db("db_server",$con);
            if(!isset($_SESSION['species'])&&isset($_SESSION['file'])){
                $_SESSION['species']=substr($_SESSION['file'], 0,  strpos($_SESSION['file'], "201"));
            }
            if (!isset($_SESSION['file'])){
                echo "onload=\"getchr();\"";
            }
            else if($_SESSION['species']=='arab')
                echo "onload=\"onload=userchr(0)\"";
            else if($_SESSION['species']=='japonica')
                echo "onload=\"onload=userchr(1)\"";
            else if($_SESSION['species']=='mtr')
                echo "onload=\"onload=userchr(2)\"";
            else if($_SESSION['species']=='chlamy')
                echo "onload=\"onload=userchr(3)\"";
            
        ?>
    >
        <?php
            include"navbar.php";
        ?>
    <div class="ym-wrapper">
       <fieldset style="margin: 50px auto 50px auto ;width: 95%;">
            <legend>
                <span class="h3_italic" >
                    <font color="#224055" ><b>Search</b>:Search and view the system samples</font>
                </span>
            </legend>
            <div class="ym-grid">
                <div id="search">
                    <div class="box info ym-form">
                        <?php
                            if(!isset($_SESSION['file'])){
                            echo"<div class=\"ym-grid ym-fbox\">
                                    <div class=\"ym-g33 ym-gl\">
                                        <label for=\"species\" style=\"margin-right:2%\">Species:</label>
                                        <select id=\"species\" name=\"species\" style=\"width:80%\" onchange=\"div_option2(this);getchr()\">
                                             <option value=\"arab\" selected=\"selected\">Arabidopsis thaliana</option>
                                            <option value=\"japonica\">Japonica rice</option>
                                            <option value=\"mtr\">Medicago truncatula</option>
                                            <option value=\"chlamy\">Chlamydomonas reinhardtii (Green alga)</option>
                                         </select>
                                    </div>
                                         ";
                            echo "<div class=\"ym-g33 ym-gl\">
                                    <label for=\"chr\" style=\"margin-right:2%\">in</label>
                                      <select id=\"chr\" name=\"chr\" style=\"width:80%\">
                                            <option value=\"all\" selected=\"selected\">All</option>
                                     </select>
                                </div>
                                     ";
                            }
                            else{
                                   echo "<div class=\"ym-grid ym-fbox\">
                                            <div class=\"ym-g33 ym-gl\">
                                                <label for=\"chr\" style=\"margin: 0 1%\">in</label>
                                                <select id=\"chr\" name=\"chr\" style=\"width:6%\">
                                                      <option value=\"all\" selected=\"selected\">All</option>
                                                </select>
                                            </div>
                                            ";
                           }
                        ?>
                        <div class="ym-g33 ym-gl">
                            <label for="start" style="margin:0 1%;"> from</label>
                            <input type="text" name="start" style="width:40%">
                            <label for="end" style="margin:0 1%;"> to</label>
                            <input type="text" name="end"style="width:40%">
                        </div>
                    </div>
                    <div class="ym-grid ym-fbox">
                        <label for="gene_id">Gene ID:(use ',' to split different gene id)</label>
                        <textarea style="width:100%" name="gene_id"></textarea>
                    </div>
                    <div class="ym-grid ym-fbox">
                        <label for="go_accession">Go term accession:(use ',' to split different gene id)</label>
                        <textarea style="width:100%" name='go_accession'></textarea>
                    </div>
                    <div class="ym-grid ym-fbox">
                        <label for="go_name" >Go term name:</label>
                        <input type='text' name='go_name' class="ym-gr" style="width:89%;"/>
                    </div>
                    <div class="ym-grid ym-fbox">
                        <label for="function">Function:</label>
                        <input type='text' name='function' class="ym-gr" style="width:89%;"/><br>
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
                                                if(isset($_SESSION['file'])){
                                                    $i=1;
                                                    $sys_sample=array();
                                                    $out1=mysql_query("select distinct PA_col from t_sample_desc where species='".$_SESSION['species']."';");
                                                    while($row1= mysql_fetch_row($out1))
                                                    {
                                                        echo "<input type=\"checkbox\" id=a$i name=sample1[] value=$row1[0] onclick=\"ClickOption(this,'b$i')\">$row1[0]<br>";
                                                        $i++;
                                                        array_push($sys_sample, $row1[0]);
                                                        $_SESSION['sys_real']=$sys_sample;
                                                    }
                                                    $j=1;
                                                    foreach ($_SESSION['file_real'] as $key => $value) {
                                                        echo "<input type=\"checkbox\" name=sample1[] id=sys1$j value=$value onclick=\"ClickOption(this,'sys2$j')\">$value<br>";
                                                        $j++;
                                                    }
                                                }
                                                else{
                                                    //arab
                                                    $i=1;
                                                    $mysql_arab=mysql_query("select distinct PA_col from t_sample_desc where species='arab';");
                                                    echo "<div id='arab1'>";
                                                    while($arab_row= mysql_fetch_row($mysql_arab))
                                                    {
                                                        echo "<input type=\"checkbox\" id=a1$i name=sample1[] value=$arab_row[0] onclick=\"ClickOption(this,'b1$i')\">$arab_row[0]<br>";
                                                        $i++;
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
                                                    }
                                                    echo "</div>";
                                                }
                                                ?>
                                    </div>
                                </td>
                                <td>
                                    <div id="sample2" style="width:50%;margin:auto;">
                                        <label for="all2">Sample 2</label><br>
                                            <?php
                                                if(isset($_SESSION['file'])){
                                                    $i=1;
                                                    $out2=mysql_query("select distinct PA_col from t_sample_desc where species='".$_SESSION['species']."';");
                                                    while($row2= mysql_fetch_row($out2))
                                                    {
                                                        echo "<input type=\"checkbox\" id=b$i name=sample2[] value=$row2[0] onclick=\"ClickOption(this,'a$i')\">$row2[0]<br>";
                                                        $i++;
                                                    }
                                                    $j=1;
                                                    foreach ($_SESSION['file_real'] as $key => $value) {
                                                        echo "<input type=\"checkbox\" id=sys2$j name=sample2[] value=$value onclick=\"ClickOption(this,'sys1$j')\">$value<br>";
                                                        $j++;
                                                    }
                                                }
                                                else{
                                                    //arab
                                                    $i=1;
                                                    $mysql_arab=mysql_query("select distinct PA_col from t_sample_desc where species='arab';");
                                                    echo "<div id='arab2'>";
                                                    while($arab_row= mysql_fetch_row($mysql_arab))
                                                    {
                                                        echo "<input type=\"checkbox\" id=b1$i name=sample2[] value=$arab_row[0] onclick=\"ClickOption(this,'a1$i')\">$arab_row[0]<br>";
                                                        $i++;
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
                                                    echo "</div>";
                                                }
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
                                    <form id="degene" method="post" class="ym-form" action="./aftertreatment.php?method=degene">
                                        <div class="ym-grid ym-fbox">
                                            <div class="ym-g33 ym-gl">
                                                <div class="ym-gbox-left">
                                                    <label for="nor_method">Normalization method</label>
                                                    <select name="nor_method" id="nor_method">
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
                                                    <select name="method">
                                                        <option value='EdgeR'>EdgeR</option>
                                                        <option value='DESeq'>DESeq</option>
                                                        <option value='DESeq2'>DESeq2</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="ym-g33 ym-gl">
                                                <label for="min_pat">Min PAT</label>
                                                <input type='text' name='min_pat' value='5'/>
                                            </div>
                                        </div>
                                            <div class="ym-grid ym-fbox">
                                                <div class="ym-g50 ym-gl">
                                                    <label for="multi_test">Multi-test adjustment method</label>
                                                    <select name="multi_test">
                                                        <option value='Bonferroni' selected="true">Bonferroni</option>
                                                        <option value='Not'>Not adjust</option>
                                                   </select>
                                                </div>
                                                <div class="ym-g50 ym-gl">
                                                    <label for="sig">Significance Level</label>
                                                    <select name="sig">
                                                        <option value='0.01'>0.01</option>
                                                        <option value='0.05' selected="true">0.05</option>
                                                        <option value='0.1'>0.1</option>
                                                   </select>
                                                </div>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <button onclick="degene()">submit</button>
                                                <button type="reset">reset</button>
                                            </div>
                                    </form>
                              </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="content-slide">
                                    <form id="depac" method="post" class="ym-form" action="./aftertreatment.php?method=depac">
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
                                                    <label for="method">Method</label>
                                                    <select name="method">
                                                            <option value='DEXSEQ'>DEXSEQ</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="ym-g33 ym-gl">
                                                <label for="depacmin_pat">Min PAT</label>
                                                <input type='text' name='depacmin_pat' value='5'/>
                                            </div>
                                        </div>
                                        <div class="ym-grid ym-fbox">
                                            <div class="ym-g50 ym-gl">
                                                <label for="multi_test">Multi-test adjustment method</label>
                                                <select name="multi_test">
                                                    <option value='Bonferroni' selected="true">Bonferroni</option>
                                                    <option value='Not'>Not adjust</option>
                                                </select>
                                            </div>
                                            <div class="ym-g50 ym-gl">
                                                <label for="sig">Significance Level</label>
                                                <select name="sig">
                                                    <option value='0.01'>0.01</option>
                                                    <option value='0.05' selected="true">0.05</option>
                                                    <option value='0.1'>0.1</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="ym-grid ym-fbox">
                                            <button onclick="depac()">submit</button>
                                            <button type="reset">reset</button>
                                        </div>
                                    </form>
                                </div>
                              </div>
                            <div class="swiper-slide">
                                <div class="content-slide">
                                        <form id="only3utr-form" class="ym-form" method="post" action="./aftertreatment.php?method=only3utr">
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
                                                <label for="sig">Significance Level</label>
                                                <select name="sig">
                                                    <option value="0.01"/>0.01
                                                    <option checked='true' value="0.05"/>0.05
                                                    <option value="0.1"/>0.1
                                                </select>
                                            </div>
                                            <div class="ym-grid ym-fbox">
                                                <button onclick="only3utr()">submit</button>
                                                <button type="reset">reset</button>
                                            </div>
                                        </form>
                                        
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
                                        function degene(){
                                            $('#search').appendTo('#degene');
                                            $('#degene').submit();
                                        }
                                        function depac(){
                                            $('#search').appendTo('#depac');
                                            $('#depac').submit();
                                        }
                                        function only3utr(){
                                            $('#search').appendTo('#only3utr-form');
                                            $('#only3utr').submit();
                                        }
                                        function none3utr(){
                                            $('#search').appendTo('#none3utr-form');
                                            $('#none3utr').submit();
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
                                    </script>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="content-slide">
                                    <form id="none3utr-form" method="post" class="ym-form" action="./aftertreatment.php?method=none3utr">
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
                                            <button onclick="none3utr()">submit</button>
                                            <button type="reset">reset</button>
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