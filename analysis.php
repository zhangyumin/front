<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Search</title>
        <script src="./src/jquery-2.0.0.min.js"></script>
        <style>
            fieldset{
                border-color: #5499c9 !important;
                border-style: solid !important;
                border-width: 2px !important;
                padding: 5px 10px !important;
            }
            .style1{
                font-size: 14px;
                text-align: center;
            }
            .theme{
                font-size: 16px;
                text-align: center;
            }
            .wrap{margin:0px auto;width: 500px;}
            .tabs {width:100%;}
            .tabs a{display: block;float: left;width:33.333333%;color: #5499c9;text-align: center;background: #eee;line-height: 40px;font-size:16px;text-decoration: none;}
            .tabs a.active {color: #fff;background: #5499c9;border-radius: 5px 5px 0px 0px;}
            .swiper-container {background:#5499c9;height:425px;border-radius: 0 0 5px 5px;width: 100%;border-top: 0;}
            .swiper-slide {height:325px;width:100%;background: none;color: #fff;}
            .content-slide {padding: 40px;}
            .content-slide p{text-indent:2em;line-height:1.9;}
            .slidebar-content{color: black;}
            .swiper-slide{color:black;}
        </style>
        <script src="./src/idangerous.swiper.min.js"></script> 
        <link rel="stylesheet" href="./src/idangerous.swiper.css">
    </head>
    <body>
        <?php
            include"navbar.php";
        ?>
       <fieldset style="margin: 50px auto 50px auto ;width: 95%;">
                    <legend>
                        <span class="h3_italic" >
                            <font color="#224055" ><b>Search</b>:Search and view the system samples</font>
                        </span>
                    </legend>
           <div style="width:60%;margin:0 auto;">
               <div id="search">
                   <?php
                   session_start();
                   if(!isset($_SESSION['file'])){
                   echo"<label for=\"species\" style=\"margin-right:2%;\">Species:</label>
                   <select id=\"species\" name=\"species\" style=\"width:25%\">
                        <option value=\"arab\" selected=\"selected\">Arabidopsis thaliana</option>
                         <option value=\"rice\">Oryza sativa (Rice)</option>
                        <option value=\"mtr\">Medicago truncatula</option>
                        <option value=\"chlamy\">Chlamydomonas reinhardtii (Green alga)</option>
                    </select>";
                   }
                    ?>
                   <label for="chr" style="margin: 0 1%">in</label>
                        <select id="chr" name="chr" style="width:6%">
                            <option value="all" selected="selected">All</option>
                            <option value="1">Chr1</option>
                            <option value="2">Chr2</option>
                            <option value="3">Chr3</option>
                            <option value="4">Chr4</option>
                            <option value="5">Chr5</option>
                            <option value="chloroplast">chloroplast</option>
                            <option value="mitochondria">mitochondria</option>
                        </select>
                   <label for="start" style="margin:0 1%;"> from</label>
                        <input type="text" name="start" style="width:14%">
                   <label for="end" style="margin:0 1%;"> to</label>
                        <input type="text" name="end"style="width:14%"><br>
<!--                        &nbsp;for
                        <input type="text" name="keyword" style="width:20%;">-->
                        <br><label for="gene_id">Gene ID:(use ',' to split different gene id)</label><br><textarea style="width:100%" name="gene_id"></textarea><br>
                        <br><label for="go_accession">Go term accession:(use ',' to split different gene id)</label><br><textarea style="width:100%" name='go_accession'></textarea><br>
                        <br><label for="go_name" style="padding-right:2%;">Go term name:</label><input type='text' name='go_name' style="width: 40%"/><br>
                        <br><label for="function" style="padding-right:6.7%;">Function:</label><input type='text' name='function' style="width:40%;"/><br>
                        <table id="samples" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td>
                                        <div id="sample1" style="width:50%;margin:auto;">
                                            <label for="all1">Sample 1</label><br>
                                                <?php
                                                    $i=1;
                                                    $sys_sample=array();
                                                    $con=  mysql_connect("localhost","root","root");
                                                    mysql_select_db("db_bio",$con);
                                                    $out1=mysql_query("select distinct label from sample_arab10;");
                                                    while($row1= mysql_fetch_row($out1))
                                                    {
                                                        echo "<input type=\"checkbox\" id=a$i name=sample1[] value=$row1[0] onclick=\"ClickOption(this,'b$i')\">$row1[0]<br>";
                                                        $i++;
                                                        array_push($sys_sample, $row1[0]);
                                                        $_SESSION['sys_real']=$sys_sample;
                                                    }
                                                    if(isset($_SESSION['file'])){
                                                        $j=1;
                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                            echo "<input type=\"checkbox\" name=sample1[] id=sys1$j value=$value onclick=\"ClickOption(this,'sys2$j')\">$value<br>";
                                                            $j++;
                                                        }
                                                    }
                                                    ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div id="sample2" style="width:50%;margin:auto;">
                                            <label for="all2">Sample 2</label><br>
                                                <?php
                                                    $i=1;
                                                    $out2=mysql_query("select distinct label from sample_arab10;");
                                                    while($row2= mysql_fetch_row($out2))
                                                    {
                                                        echo "<input type=\"checkbox\" id=b$i name=sample2[] value=$row2[0] onclick=\"ClickOption(this,'a$i')\">$row2[0]<br>";
                                                        $i++;
                                                    }
                                                    if(isset($_SESSION['file'])){
                                                        $j=1;
                                                        foreach ($_SESSION['file_real'] as $key => $value) {
                                                            echo "<input type=\"checkbox\" id=sys2$j name=sample2[] value=$value onclick=\"ClickOption(this,'sys1$j')\">$value<br>";
                                                            $j++;
                                                        }
                                                    }
                                                    ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
               </div>
                        <div class="wrap">
                            <div class="tabs">
                                <a href="#" hidefocus="true" class="active">DE Gene</a>
                                <a href="#" hidefocus="true">DE PAC</a>
                                <a href="#" hidefocus="true">Switching Gene</a>
                            </div><br>    
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="content-slide">
                                        <form id="degene" method="post" action="./aftertreatment.php?method=degene">
                                            <span class='slidebar-content'>Normalization method:</span><br>
                                            <select name="nor_method" id="nor_method">
                                                  <option value='none' selected="true">None</option>
                                            </select><br>
                                            <span class='slidebar-content'>Method:</span><br>
                                            <select>
                                                    <option value='EdgeR'>EdgeR</option>
                                                    <option value='DESeq'>DESeq</option>
                                              </select><br>
                                            <span class='slidebar-content'>Min PAT:</span><br><input type='text' name='min_pat' value='5'/><br>
                                            <span class='slidebar-content'>Multi-test adjustment method:</span><br>
                                            <select>
                                                    <option value='Bonferroni' selected="true">Bonferroni</option>
                                                    <option value='Not'>Not adjust</option>
                                              </select><br>
                                            <span class='slidebar-content'>Significance Level:</span><br>
                                              <select>
                                                    <option value='0.01'>0.01</option>
                                                    <option value='0.05' selected="true">0.05</option>
                                                    <option value='0.1'>0.1</option>
                                              </select><br>
                                            <button onclick="degene()">submit</button>
                                            <button type="reset">reset</button>
                                        </form>
                                  </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="content-slide">
                                        <form id="depac" method="post" action="./aftertreatment.php?method=depac">
                                            <span class='slidebar-content'>Normalization method:</span><br>
                                            <select name="depac_normethod" id="depac_normethod">
                                                  <option value='none' selected="true">None</option>
                                            </select><br>
                                            <span class='slidebar-content'>Method:</span><br>
                                            <select>
                                                    <option value='DESeq2'>DESeq</option>
                                              </select><br>
                                            <span class='slidebar-content'>Min PAT:</span><br><input type='text' name='depacmin_pat' value='5'/><br>
                                            <span class='slidebar-content'>Multi-test adjustment method:</span><br>
                                            <select>
                                                    <option value='Bonferroni' selected="true">Bonferroni</option>
                                                    <option value='Not'>Not adjust</option>
                                              </select><br>
                                            <span class='slidebar-content'>Significance Level:</span><br>
                                              <select>
                                                    <option value='0.01'>0.01</option>
                                                    <option value='0.05' selected="true">0.05</option>
                                                    <option value='0.1'>0.1</option>
                                              </select><br>
                                            <button onclick="depac()">submit</button>
                                            <button type="reset">reset</button>
                                        </form>
                                    </div>
                                  </div>
                                <div class="swiper-slide">
                                    <div class="content-slide">
                                            <select name="3utr" id="3utr" onchange="div_option(this)">
                                                <option value="choose">please choose</option>
                                                <option value="only3utr">only 3'UTR</option>
                                                <option value="none3utr">none 3'UTR</option>
                                            </select>
                                            <div id="only3utr" style="display: none;">
                                                <form id="only3utr-form" method="post" action="./aftertreatment.php?method=only3utr">
                                                <span class='slidebar-content'>Min PAT:</span><br>
                                                <input type='text' value='5' name="sgminpat"/><br>
                                                <span class='slidebar-content'>Multi-test adjustment method:</span><br>
                                                <select>
                                                    <option checked='true' value='bonferroni' />Bonferroni
                                                    <option value='notadjust'/>Not adjust
                                                </select><br>
                                                <span class='slidebar-content'>Significance Level:</span><br>
                                                <select>
                                                    <option value="0.01"/>0.01
                                                    <option checked='true' value="0.05"/>0.05
                                                    <option value="0.1"/>0.1
                                                </select><br>
                                                <button onclick="only3utr()">submit</button>
                                                <button type="reset">reset</button>
                                        </form>
                                    </div>
                                        <div id="none3utr"  style="display: none;">      
                                            <form id="none3utr-form" method="post" action="./aftertreatment.php?method=none3utr">
                                            <span class='slidebar-content'>Normalization method:</span><br>
                                            <select id="sgnm">
                                                <option value="none" checked="true"/>None
                                            </select><br>
                                             <span class='slidebar-content'>Distance(nt):</span><br>
                                            <input type="text" value="50" name="minpat2"/><br>
                                            <span class='slidebar-content'>Using top two PACs:</span>
                                            <input type="checkbox" checked="true" name="uttp"/><br>
                                            <span class='slidebar-content'>Min PAT of one PAC:</span><br>
                                            <input type="text" value="10"name="minpat3"/><br>
                                            <span class='slidebar-content'>Min total PAT of one PAC in both samples:</span><br>
                                            <input type="text" value="5" name="minpat4"/><br>
                                            <span class='slidebar-content'>Min difference of PAC between the two PAC:</span><br>
                                            <input type="text" value="10" name="minpat5"/><br>
                                             <span class='slidebar-content'>Min fold change of two PAC in at least one sample:</span><br>
                                             <input type="text" value="2" name="minpat6"/><br>
                                             <button onclick="none3utr()">submit</button>
                                            <button type="reset">reset</button>
                                        </form>
                                        </div>
                                        <script>
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
                                            function ClickOption(obj,id){
                                                var O = document.getElementById(id);
                                                O.disabled=obj.checked;
                                                }
                                        </script>
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
                    </form>
           </div>
           
<!--           <div class="tips" style="font-size: 10px;">
               Dataset description: detailed description on the searching dataset can be referred by unfolding more description in the dataset table.
               <br>
               Tips for searching: search using a ID, gene name, symbols or description variable in publications and databases.
     
           </div>-->
        </fieldset>
        <div class="bottom">
        <?php
            include"footer.php";
            ?>
        </div>
    </body>
</html>
