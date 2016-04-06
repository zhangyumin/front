<head>
    <meta charset="utf-8">
    <title>PlantAPA-Analysis results</title>
    <!-- Mobile viewport optimisation -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css" />
    <!--[if lte IE 7]>
        <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
        <![endif]-->
    <!--[if lt IE 9]>
        <script src="./js/html5shiv/html5shiv.js"></script>
        <![endif]-->
     <script src="./src/jquery-1.10.1.min.js" type="text/javascript"></script>
    <?php
        $con=  mysql_connect("localhost","root","root");
        mysql_select_db("db_server",$con);
        session_start();
        $analysis=$_SESSION['analysis'];
        if(isset($_GET['species'])){
            $species=$_GET['species'];
        }
        else{
            $species=$_SESSION['species'];
        }
//        var_dump($analysis);
//        var_dump($_SESSION['file_real']);
//        var_dump($_SESSION['sys_real_arab']);
//        var_dump($_SESSION['sys_real_mtr']);
//        var_dump($_SESSION['sys_real_chlamy']);
//        var_dump($_SESSION['sys_real_japonica']);
//        var_dump($_SESSION['sys_real']);
        if($_GET['result']=='degene'){
            $title = "Differentially expressed genes";
            $a=file("./searched/degene.$analysis");
            $b=file("./searched/degene.$analysis.stat");
            $c = "degene.$analysis";
            $insert="create table db_user.Analysis_$analysis(gene varchar(30),gene_type varchar(50),";
            $title_tmp=  explode("\t", $a[0]);
            foreach ($title_tmp as $key => $value) {
                if($value=='gene'||$value=='gene_type'||$key==count($title_tmp)-1)
                {

                }
                else{
                    $insert.="$value int(10), ";
                }
            }
            $insert.="padj double(19,17));";
            mysql_query("drop table db_user.Analysis_$analysis");
            mysql_query($insert);
            mysql_query("load data infile '/var/www/front/searched/degene.$analysis' into table db_user.Analysis_$analysis IGNORE 1 LINES;");
        }
        if($_GET['result']=='depac'){
            $title = "PAC with differential usage";
            $a=file("./searched/depac.$analysis");
            $b=file("./searched/depac.$analysis.stat");
            $c = "depac.$analysis";
            $insert="create table db_user.Analysis_$analysis(gene varchar(30),coord varchar(30),chr varchar(30),strand varchar(30),ftr varchar(30),";
            $title_tmp=  explode("\t", $a[0]);
            foreach ($title_tmp as $key => $value) {
                if($value=='gene'||$value=='coord'||$value=='chr'||$value=='strand'||$value=='ftr'||$key==count($title_tmp)-1)
                {

                }
                else{
                    $insert.="$value int(10), ";
                }
            }
            $insert.="padj double(20,18));";
            mysql_query("drop table db_user.Analysis_$analysis");
            mysql_query($insert);
            mysql_query("load data infile '/var/www/front/searched/depac.$analysis' into table db_user.Analysis_$analysis IGNORE 1 LINES;");
        }
        if($_GET['result']=='switchinggene_o'){
            $title = "Genes with 3’ UTR lengthening or shortening";
            $a=file("./searched/only3utr.$analysis");
            $b=file("./searched/only3utr.$analysis.stat");
            $c = "only3utr.$analysis";
            $insert="create table db_user.Analysis_$analysis(gene varchar(30),average_PAT varchar(50),SUTR_length varchar(50),correlation double(20,16),pval varchar(30),switching varchar(30));";
            $title_tmp=  explode("\t", $a[0]);
            mysql_query("drop table db_user.Analysis_$analysis");
            mysql_query($insert);
            mysql_query("load data infile '/var/www/front/searched/only3utr.$analysis' into table db_user.Analysis_$analysis IGNORE 1 LINES;");
        }
        if($_GET['result']=='switchinggene_n'){
            $title = "Nonconnonical APA-site switching genes";
            $a=file("./searched/none3utr.$analysis");
            $b=file("./searched/none3utr.$analysis.stat");
            $c = "none3utr.$analysis";
            $insert="create table db_user.Analysis_$analysis(gene varchar(30),gene_type varchar(30),chr varchar(30),strand varchar(30),coord varchar(30),ftr varchar(30),";
            $title_tmp=  explode("\t", $a[0]);
            foreach ($title_tmp as $key => $value) {
                if($value=='gene'||$value=='gene_type'||$value=='coord'||$value=='chr'||$value=='strand'||$value=='ftr'||$value=='sample1_average'||$value=='sample2_average'||$key==count($title_tmp)-1)
                {

                }
                else{
                    $insert.="$value int(10), ";
                }
            }
            $insert.="sample1_average int(10),sample2_average int(10),switching_type varchar(20));";
//            file_put_contents("./tojbrowse/test.txt", $insert);
            mysql_query("drop table db_user.Analysis_$analysis");
            mysql_query($insert);
            mysql_query("load data infile '/var/www/front/searched/none3utr.$analysis' into table db_user.Analysis_$analysis IGNORE 1 LINES;");    
        }
        ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <style>
            a:link {
        color: #5499c9;
        text-decoration: none;
        }
        a:visited {
        text-decoration: none;
        }
        a:hover {
        color: #FFFFFF;
        text-decoration: none;
        }
        a:active {
        text-decoration: none;
        }
        .jtable{
            margin: 0px auto;
        }
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
        .left{
            text-align: right;
            font-weight: bold;
        }
        #result-table td{
            padding: 4px;
        }
        </style>
</head>
<body onload="DelOption()">
        <?php
            include './navbar.php'
        ?>
    <div class="ym-wrapper">
        <table id='result-table' style="margin-top:20px;">
                <tbody>
                    <tr class="flip" onclick="chgArrow()">
                        <td colspan="2" class="step-title">
                            <img id="arrow" src="./pic/down.png" style="height:18px">
                            <h4 style="display:inline">
                                <font color="#224055">Results summary</font>
                            </h4>
                        </td>
                    </tr>
                    <tr class="result">
                        <td>
                            <div class="result">
                            <table id='result' style="font-size: 12px;TABLE-LAYOUT:fixed;WORD-WRAP:break_word;width: 70%">
                                <?php
                                        foreach ($b as $key => $value) {
                                            echo "<tr>";
                                            $summary = explode(":", $value,2);
                                            echo "<td class='left' style='width:20%'>$summary[0]</td>";
                                            if($key == 0){
                                                $sample = explode(";", $summary[1]);
                                                echo "<td>$sample[0]<br>$sample[1]</td>";
                                            }
                                            else{
                                                echo "<td>$summary[1]</td>";
                                            }
                                            echo "</tr>";
                                        }
                                ?>
                            </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
    <div id="ly" style="position: absolute; top: 0px; opacity:0.4; background-color: rgba(94, 110, 141, 0.9);z-index: 2; left: 0px; display: none;">
        </div>
        <!--          浮层框架开始         -->
        <div id="Layer2" align="center" style="position: absolute; z-index: 3; left: 40%; top: 30%;background-color: #fff; display: none;box-shadow: 0 0 20px;border-radius: .25em .25em .4em .4em; " >
            <div style="padding:20px;">
                <p style="color:#8f9cb5;font-size: 16px">Export sequences of interest</p>
                <form name="pac_export" method="post" action="export_seq.php?source=Search&species=<?php echo $species; ?>" target="_blank">
                    Method<select id="method" name="method" onchange="ChgMtd()" style="margin-left:70;margin-bottom: 5;width: 226px">
                        <option value="choose">Please choose</option>
                        <option value="pacs">export sequences of PACs</option>
                        <option value="seq">export gene sequences</option>
                    </select><br>
                    <div id="pacs" style="display:none">
                        Upstream (nt) <input type="text" value="200" name='upstream' style="margin-left:33;margin-bottom: 5;width: 226px"></input><br>
                        Downstream (nt) <input type="text" value="200" name='downstream' style="margin-left:18;margin-bottom: 5;width: 226px"></input><br>
                        PAC in region <select name='pac_region' style="margin-left:33;margin-bottom: 5;width: 226px">
                            <option value="all">all</option>
                            <option value="genomic-region">genomic region</option>
                            <option value="3TUR">3'UTR</option>
                            <option value="5UTR">5‘UTR</option>
                            <option value="CDS">CDS</option>
                            <option value="intron">intron</option>
                            <option value="intergenic.igt">intergenic</option>
                            <option value="intergenic.pm">promoter</option>
                        </select>
                    </div>
                    <div id="seq" style="display:none">
                        Annotation version <select name='anno_version' style="margin-left:7;margin-bottom: 5;width: 226px">
                            <option value="raw-annotation">raw annotation</option>
                            <option value="3utr-extended-annotation">3' UTR extended annotation</option>
                        </select><br>
                        Export <select name='export' style="margin-left:71;margin-bottom: 5;width: 226px">
                            <option value="whole-gene">whole gene</option>
                            <option value="joined-cds">joined CDS</option>
                            <option value="3utr-only">3' UTR only</option>
                        </select>
                    </div>
                    <!--<button id="sub" type="submit" disabled="true">Download</button>-->
                </form>
            </div>
                    <ul>
                        <li style="float:left;width: 50%;margin-left: 0px"><a id='sub' style="background: #5db95d;border-radius: 0 0 0 .25em;display: block;height: 40px;line-height: 40px;text-transform: uppercase;color: #fff;-webkit-transition: background-color 0.2s;-moz-transition: background-color 0.2s;transition: background-color 0.2s;text-decoration: none;">Download</a></li>
                        <li style="float:left;width: 50%;margin-left: 0px"><a id='can' href='javascript:Lock_CheckForm(this);' style="background: #c5ccd8;border-radius: 0 0 0 .25em;display: block;height: 40px;line-height: 40px;text-transform: uppercase;color: #fff;-webkit-transition: background-color 0.2s;-moz-transition: background-color 0.2s;transition: background-color 0.2s;text-decoration: none;">Cancel</a></li>
                    </ul>
        </div>
        <div class="filter" id="filter">
            <form>
                <input type="text" name="search" id="search" style="height:26px"/>
                <button title="search" type="submit" id="search_button"><img src="./pic/search.png"/></button>
            </form>
            <button title="download result" onclick="window.location.href='./download_data.php?type=4&name=<?php echo $c; ?>'"><img src="./pic/download.png"/></button>
            <button title="export sequences" onclick="locking()"><img style="height:23px" src="./pic/export.png"/></button>
            <div style="clear:both;"></div>
        </div>
        <div id="jtable" style="overflow-x: scroll;"></div>
        <link href="src/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
        <link href="src/jtable.css" rel="stylesheet" type="text/css" />
        <script src="src/jquery-ui-1.8.16.custom.min.js" type="text/javascript" ></script>
        <script src="src/jquery.jtable.js" type="text/javascript" ></script>
         <script type="text/javascript">
             var result = '<?php echo $_GET['result'];?>';
             function DelOption(){
                 //设置jtable适应屏幕
                if($(".jtable tbody").width()>960){
                    $(".jtable-title").width($(".jtable tbody").width()-1);
                    $(".jtable-bottom-panel").width($(".jtable tbody").width()-3);
                }
                 if(result == 'degene' || result == 'switchinggene_o')
                     $("#method option[value='pacs']").remove();   //删除第一个method选项
             }
            function locking(){   
               document.all.ly.style.display="block";   
               document.all.ly.style.width=document.body.clientWidth;   
               document.all.ly.style.height=document.body.offsetHeight;   
               document.all.Layer2.style.display='block';  
               }   
            function Lock_CheckForm(theForm){   
                document.all.ly.style.display='none';document.all.Layer2.style.display='none';
                return   false;   
             }
             function ChgMtd(){
                document.getElementById("pacs").style.display='none';
                document.getElementById("seq").style.display='none';
                if(document.getElementById("method").value=='choose'){
                    document.getElementById("sub").disabled=true;
                    document.getElementById("sub").removeAttribute('href');
                }
                else{
                    document.getElementById("sub").disabled=false;
                    document.getElementById("sub").setAttribute("href",'javascript:document.pac_export.submit();');
                    document.getElementById(document.getElementById("method").value).style.display='block';
//                console.log(document.getElementById("method").value);
                }
            }
             <?php
                echo "var species='$species';";
            ?>
                $(document).ready(function (){
                    $('#jtable').jtable({
                        title:'<?php echo $title;?>',
                        paging:true,
                        pageSize:10,
                        sorting:true,
                        defaultSorting:'gene ASC',
                        actions:{
                            listAction:'Analysis_PAClist.php'
                        },
                        fields:{
                            <?php
                            if($_GET['result']=='degene'){
                                echo "gene:{
                                        key:true,
                                        edit:false,
                                        create:false,
                                        columnResizable:false,
                                        title:'Gene',
                                        edit:false,
                                        display: function (data) {
                                           return \"<a title='click to view detail' target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"&analysis=1'>\"+data.record.gene+\"</a>\";
                                        }
                                        },";
                                foreach ($title_tmp as $key => $value) {
                                    if($value=='gene'||$value=='gene_type'||$key==count($title_tmp)-1)
                                    {}
                                    else{
                                        echo "$value:{
                                                  title:'$value',
                                                  edit:false
                                                  },";
                                    }
                                }
                            }
                            else if($_GET['result']=='depac'){
                                echo "gene:{
                                        key:true,
                                        edit:false,
                                        create:false,
                                        columnResizable:false,
                                        title:'Gene',
                                        edit:false,
                                        display: function (data) {
                                           return \"<a title='click to view detail' target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"&analysis=1'>\"+data.record.gene+\"</a>\";
                                        }
                                        },";
                                foreach ($title_tmp as $key => $value) {
                                    if($value=='gene'||$value=='chr'||$value=='strand'||$key==count($title_tmp)-1)
                                    {}
                                    else{
                                        if($value == 'coord'){
                                            echo "$value:{
                                                      title:'".ucfirst($value)."',
                                                      edit:false
                                                      }";
                                            echo ",";
                                        }
                                        elseif ($value == 'ftr') {
                                        echo "$value:{
                                                      title:'Genomic&nbspregion',
                                                      edit:false
                                                      }";
                                            echo ",";
                                        }
                                        else{
                                            echo "$value:{
                                                      title:'$value',
                                                      edit:false
                                                      }";
                                            echo ",";
                                        }
                                    }
                                }
                                echo "padj:{"
                                        . "title:'Padj',"
                                        . "edit:false"
                                        . "},";
                            }
                            else if($_GET['result']=='switchinggene_o'){
                                echo "gene:{
                                        key:true,
                                        edit:false,
                                        create:false,
                                        columnResizable:false,
                                        title:'Gene',
                                        edit:false,
                                        display: function (data) {
                                           return \"<a title='click to view detail' target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"'>\"+data.record.gene+\"</a>\";
                                        }
                                        },";
                                echo "average_PAT:{
                                          title:'Average_PAT',
                                          edit:false
                                          },";
                                echo "SUTR_length:{
                                          title:'3UTR_length',
                                          edit:false
                                          },";
                                echo "correlation:{
                                          title:'Correlation',
                                          edit:false
                                          },";
                                echo "pval:{
                                          title:'Pval',
                                          edit:false
                                          },";
                                echo "switching:{
                                          title:'Switching',
                                          edit:false
                                          },";
                            }
                            else if($_GET['result']=='switchinggene_n'){
                                echo "gene:{
                                        key:true,
                                        edit:false,
                                        create:false,
                                        columnResizable:false,
                                        title:'Gene',
                                        edit:false,
                                        display: function (data) {
                                           return \"<a title='click to view detail' target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"&analysis=1'>\"+data.record.gene+\"</a>\";
                                        }
                                        },";
                                foreach ($title_tmp as $key => $value) {
                                    if($value=='gene'||$value=='gene_type'||$value=='chr'||$value=='strand'||$key==count($title_tmp)-1)
                                    {}
                                    else{
                                        if($value == 'coord' || $value=='sample1_average' || $value=='sample2_average' ){
                                            echo "$value:{
                                                  title:'".ucfirst($value)."',
                                                  edit:false
                                                  }";
                                            echo ",";
                                        }
                                        else if($value == 'ftr'){
                                            echo "$value:{
                                                  title:'Genomic&nbspregion',
                                                  edit:false
                                                  }";
                                            echo ",";
                                        }
                                        else{
                                            echo "$value:{
                                                      title:'$value',
                                                      edit:false
                                                      }";
                                            echo ",";
                                        }
                                    }
                                }
                                     echo "switching_type:{"
                                        . "title:'Switching_type',"
                                        . "edit:false"
                                        . "},";
                                }
                            ?>
                            detail:{
                                title:'View',
                                display: function (data) {
                                    if(data.record.ftr=='intergenic.igt' || data.record.ftr=='intergenic.pm'){
                                        if(data.record.strand=='-'){
                                            return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"&strand=-1&flag=intergenic&coord="+data.record.coord+"\"><img align='center' src='./pic/detail.png'/></a>";
                                        }
                                        else
                                            return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"&strand=1&flag=intergenic&coord="+data.record.coord+"\"><img align='center' src='./pic/detail.png'/></a>";
                                    }
                                    else{
                                        return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"&analysis=1\"><img align='center' src='./pic/detail.png'/></a>";
                                    }
                                }
                            },
                            view:{
                                title:'Jbrowse',
                                display: function (data) {
                                    return "<a title='click to view detail in jbrowse' target=\"_blank\" href=\"../jbrowse/?data=data/"+species+"&amp;loc="+data.record.gene+"\">"+"<img src='./pic/browser.png'/></a>";
                                }
                            }
                        }
                    });
                    $('#jtable').jtable('load');
                    $('#filter').appendTo(".jtable-title").addClass('filter_class');
                    $('#search_button').click(function (e){
                        e.preventDefault();
                                $('#jtable').jtable('load',{
                                    search: $('#search').val()
                                });
                            });
                    $('#reset_button').click(function(e){
                        e.preventDefault();
                                $('#jtable').jtable('load');
                            });
                });
                $(".flip").click(function(){
                    $('.result').slideToggle("slow");
                 });
                function chgArrow(){
                    if($('#result').is(":visible")){
                        $('#arrow').attr("src","./pic/down.png");
                    }
                    else{
                        $('#arrow').attr("src","./pic/up.png");
                    }
                }
            </script>
    </div>
    <?php
//        include './wheelmenu.php';
        ?>
    <?php
        include './footer.php';
    ?>
</body>
</html>