<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Aftertreatment Result</title>
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
        if(isset($_SESSION['species'])){
            $species=$_SESSION['species'];
        }
        else{
            $species=$_GET['species'];
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
                if($value=='gene'||$value=='gene_type'||$value=='coord'||$value=='chr'||$value=='strand'||$value=='ftr'||$value=='column1_average'||$value=='column2_average'||$key==count($title_tmp)-1)
                {

                }
                else{
                    $insert.="$value int(10), ";
                }
            }
            $insert.="column1_average int(10),column2_average int(10),switching_type varchar(20));";
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
        </style>
</head>
<body onload="DelOption()">
        <?php
            include './navbar.php'
        ?>
    <div class="ym-wrapper">
       
        <fieldset>
                    <legend>
                        <h4>
                            <font color="#224055"><b>Summary</b></font>
                        </h4>
                    </legend>
            <table style="font-size: 15px;TABLE-LAYOUT:fixed;WORD-WRAP:break_word;">
                <?php
                        foreach ($b as $key => $value) {
                            echo "<tr>";
                            $summary = explode(":", $value,2);
                            echo "<td style='width:20%;font-weight:bold;' bgcolor=\"#e1e1e1\">$summary[0]</td>";
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
        </fieldset>
        <div id="ly" style="position: absolute; top: 0px; opacity:0.4; background-color: #777;z-index: 2; left: 0px; display: none;">
        </div>
        <!--          浮层框架开始         -->
        <div id="Layer2" align="center" style="border: 1px solid;position: absolute; z-index: 3; left: 40%; top: 50%;background-color: #fff; display: none;" >
            <table width="540" height="300" border="0" cellpadding="0" cellspacing="0" style="border: 0    solid    #e7e3e7;border-collapse: collapse ;" >
                <tr>
                    <td style="background-color: #73A2d6; color: #fff; padding-left: 4px; padding-top: 2px;font-weight: bold; font-size: 12px;" height="10" valign="middle">
                         <div align="right">
                             <a href=JavaScript:; class="STYLE1" onclick="Lock_CheckForm(this);">[Close]
                             </a> &nbsp;&nbsp;&nbsp;&nbsp;
                         </div>
                    </td>
                </tr>
                <tr>
                    <td height="130" align="center">
                        <form name="pac_export" method="post" action="export_seq.php?source=Analysis&species=<?php echo $species; ?>" target="_blank">
                            method<select id="method" name="method" onchange="ChgMtd()">
                                <option value="choose">Please choose</option>
                                <option value="pacs">export sequences of PACs</option>
                                <option value="seq">export gene sequences</option>
                            </select><br>
                            <div id="pacs" style="display:none">
                                upstream (nt) <input type="text" value="200" name='upstream'></input><br>
                                downstream (nt) <input type="text" value="200" name='downstream'></input><br>
                                PAC in region <select name='pac_region'>
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
                                annotation version <select name='anno_version'>
                                    <option value="raw-annotation">raw annotation</option>
                                    <option value="3utr-extended-annotation">3' UTR extended annotation</option>
                                </select><br>
                                export <select name='export'>
                                    <option value="whole-gene">whole gene</option>
                                    <option value="joined-cds">joined CDS</option>
                                    <option value="3utr-only">3' UTR only</option>
                                </select>
                            </div>
                            <button id="sub" type="submit" disabled="true">Submit</button>
                            <button id="can" type="reset" disabled="true">Reset</button>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
        <div class="filter" id="filter">
            <button onclick="javascript:window.location.href='./download_data.php?type=4&name=<?php echo $c; ?>'">download analysis result</button>
            <button onclick="locking()">export sequences</button>
            <form>
                <input type="text" name="search" id="search" />
                <button type="submit" id="search_button">search</button>
                <button type="reset" id="reset_button">reset</button>
            </form>
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
                    document.getElementById("can").disabled=true;
                }
                else{
                    document.getElementById("sub").disabled=false;
                    document.getElementById("can").disabled=false;
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
                        pageSize:5,
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
                                        title:'gene',
                                        edit:false,
                                        display: function (data) {
                                           return \"<a title='click to view detail' target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"&analysis=1'>\"+data.record.gene+\"</a>\";
                                        }
                                        },
                                        gene_type:{
                                            title:'gene_type',
                                            edit:false
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
                                        title:'gene',
                                        edit:false,
                                        display: function (data) {
                                           return \"<a title='click to view detail' target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"&analysis=1'>\"+data.record.gene+\"</a>\";
                                        }
                                        },";
                                foreach ($title_tmp as $key => $value) {
                                    if($value=='gene'||$key==count($title_tmp)-1)
                                    {}
                                    else{
                                        echo "$value:{
                                                  title:'$value',
                                                  edit:false
                                                  }";
                                        echo ",";
                                    }
                                }
                                echo "padj:{"
                                        . "title:'padj',"
                                        . "edit:false"
                                        . "},";
                            }
                            else if($_GET['result']=='switchinggene_o'){
                                echo "gene:{
                                        key:true,
                                        edit:false,
                                        create:false,
                                        columnResizable:false,
                                        title:'gene',
                                        edit:false,
                                        display: function (data) {
                                           return \"<a title='click to view detail' target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"'>\"+data.record.gene+\"</a>\";
                                        }
                                        },";
                                echo "average_PAT:{
                                          title:'average_PAT',
                                          edit:false
                                          },";
                                echo "SUTR_length:{
                                          title:'3UTR_length',
                                          edit:false
                                          },";
                                echo "correlation:{
                                          title:'correlation',
                                          edit:false
                                          },";
                                echo "pval:{
                                          title:'pval',
                                          edit:false
                                          },";
                                echo "switching:{
                                          title:'switching',
                                          edit:false
                                          },";
                            }
                            else if($_GET['result']=='switchinggene_n'){
                                echo "gene:{
                                        key:true,
                                        edit:false,
                                        create:false,
                                        columnResizable:false,
                                        title:'gene',
                                        edit:false,
                                        display: function (data) {
                                           return \"<a title='click to view detail' target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"&analysis=1'>\"+data.record.gene+\"</a>\";
                                        }
                                        },";
                                foreach ($title_tmp as $key => $value) {
                                    if($value=='gene'||$key==count($title_tmp)-1)
                                    {}
                                    else{
                                        echo "$value:{
                                                  title:'$value',
                                                  edit:false
                                                  }";
                                        echo ",";
                                    }
                                }
                                     echo "switching_type:{"
                                        . "title:'switchinge type',"
                                        . "edit:false"
                                        . "},";
                                }
                            ?>
                            detail:{
                                title:'view',
                                display: function (data) {
                                    if(data.record.ftr=='intergenic.igt' || data.record.ftr=='intergenic.pm'){
                                        if(data.record.strand=='-'){
                                            return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"&strand=-1&flag=intergenic&coord="+data.record.coord+"\"><img align='center' src='./pic/browser.png'/></a>";
                                        }
                                        else
                                            return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"&strand=1&flag=intergenic&coord="+data.record.coord+"\"><img align='center' src='./pic/browser.png'/></a>";
                                    }
                                    else{
                                        return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"\"><img align='center' src='./pic/browser.png'/></a>";
                                    }
                                }
                            },
                            view:{
                                title:'jbrowse',
                                display: function (data) {
                                    return "<a title='click to view detail in jbrowse' target=\"_blank\" href=\"../jbrowse/?data=data/"+species+"&amp;loc="+data.record.chr+":"+data.record.coord+"\">"+"<img src='./pic/detail.png'/></a>";
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
//                    if($(".jtable tbody").width()>960)
//                        $(".jtable-title").width($(".jtable tbody").width());
                });
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