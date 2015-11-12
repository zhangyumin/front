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
        <script src="./src/jquery.dataTables.min.js"type="text/javascript" ></script>
        <link href="./src/jquery.dataTables.css"type="text/css" rel="stylesheet"></link>
    <?php
        $con=  mysql_connect("localhost","root","root");
        mysql_select_db("db_server",$con);
        session_start();
        $file=$_SESSION['file'];
        if($_GET['result']=='degene'){
            $a=file("./searched/degene.$file");
            $b=file("./searched/degene.$file.stat");
            $c = "degene.$file";
            $insert="create table db_user.Analysis_$file(gene varchar(30),gene_type varchar(50),";
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
            mysql_query("drop table db_user.Analysis_$file");
            mysql_query($insert);
            mysql_query("load data infile '/var/www/front/searched/degene.$file' into table db_user.Analysis_$file IGNORE 1 LINES;");
        }
        if($_GET['result']=='depac'){
            $a=file("./searched/depac.$file");
            $b=file("./searched/depac.$file.stat");
            $c = "depac.$file";
            $insert="create table db_user.Analysis_$file(gene varchar(30),coord varchar(30),chr varchar(30),strand varchar(30),ftr varchar(30),";
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
            mysql_query("drop table db_user.Analysis_$file");
            mysql_query($insert);
            mysql_query("load data infile '/var/www/front/searched/depac.$file' into table db_user.Analysis_$file IGNORE 1 LINES;");
        }
        if($_GET['result']=='switchinggene_o'){
            $a=file("./searched/only3utr.$file");
            $b=file("./searched/only3utr.$file.stat");
            $c = "only3utr.$file";
            $insert="create table db_user.Analysis_$file(gene varchar(30),average_PAT varchar(50),SUTR_length varchar(50),correlation double(20,16),pval varchar(30),switching varchar(30));";
            $title_tmp=  explode("\t", $a[0]);
            mysql_query("drop table db_user.Analysis_$file");
            mysql_query($insert);
            mysql_query("load data infile '/var/www/front/searched/only3utr.$file' into table db_user.Analysis_$file IGNORE 1 LINES;");
        }
        if($_GET['result']=='switchinggene_n'){
            $a=file("./searched/none3utr.$file");
            $b=file("./searched/none3utr.$file.stat");
            $c = "none3utr.$file";
            $insert="create table db_user.Analysis_$file(gene varchar(30),gene_type varchar(30),chr varchar(30),strand varchar(30),coord varchar(30),ftr varchar(30),";
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
            file_put_contents("./tojbrowse/test.txt", $insert);
            mysql_query("drop table db_user.Analysis_$file");
            mysql_query($insert);
            mysql_query("load data infile '/var/www/front/searched/none3utr.$file' into table db_user.Analysis_$file IGNORE 1 LINES;");    
        }
        ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <style type="text/css">
            body{
                font-family:Courier New;
                font-size: 14px;
            }
             span.pt1{
                color:black;
                background-color: #FF83FA;
            }
            span.pt2{
                color:black;
                background-color: #87CEFA;
            }
            span.pt3{
                color:black;
                background-color: #B3EE3A;
            }
             span.pt4{
                color:black;
                background-color: #EEEE00;
            }
             span.pt5{
                color:black;
                background-color: #6F00D2;
            }
             span.pt6{
                color:black;
                background-color: #F75000;
            }
             span.pt7{
                color:black;
                background-color: #FF0000;
            }
             span.pt8{
                color:black;
                background-color: #5B5B5B;
            }
             span.pt9{
                color:black;
                background-color: #984B4B;
            }
            fieldset{
                border-color: #5499c9 !important;
                border-style: solid !important;
                border-width: 2px !important;
                padding: 5px 10px !important;
            }
        </style>
</head>
<body>
        <?php
            include './navbar.php'
        ?>
    <div class="ym-wrapper">
       
        <div id="task_summery" align="center" style="clear: both;color:#224055;font-size: 24px;margin: auto;">summary<br>
            <table style="border-top-style:dashed;border-right-style: dashed;border-left-style: dashed;border-bottom-style: dashed;font-size: 18px;width: 80%;" borderColor="#4a4a84" align=center>
                <?php
                        foreach ($b as $key => $value) {
                            echo "<tr>";
                            $summery_tmp=  explode("\t", $value);
                            foreach ($summery_tmp as $key => $value) {
                                echo "<th>$value</th>";
                            }
                            echo "</tr>";
                        }
                ?>
            </table>
        </div>
        <div class="filter" id="filter">
                    <form>
                        <input type="text" name="search" id="search" />
                        <button type="submit" id="search_button">search</button>
                        <button type="reset" id="reset_button">reset</button>
                    </form>
            </div>
            <div id="jtable" style="clear: both;overflow: auto;"></div>
            <link href="src/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
            <link href="src/jtable.css" rel="stylesheet" type="text/css" />
            <script src="src/jquery-ui-1.8.16.custom.min.js" type="text/javascript" ></script>
            <script src="src/jquery.jtable.js" type="text/javascript" ></script>
             <script type="text/javascript">
                 <?php
                    echo "var species='".$_SESSION['species']."';";
                ?>
                    $(document).ready(function (){
                        $('#jtable').jtable({
                            title:'PAC',
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
                                               return \"<td><a target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"'><span title='Get more information about this sequence' style='background-color:#0066cc;color:#FFFFFF;'>\"+data.record.gene+\"</span></a></td>\";
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
                                                      }";
                                            if($key!=count($title_tmp)-2)
                                                echo ",";
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
                                               return \"<td><a target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"'><span title='Get more information about this sequence' style='background-color:#0066cc;color:#FFFFFF;'>\"+data.record.gene+\"</span></a></td>\";
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
                                            . "}";
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
                                               return \"<td><a target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"'><span title='Get more information about this sequence' style='background-color:#0066cc;color:#FFFFFF;'>\"+data.record.gene+\"</span></a></td>\";
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
                                              }";
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
                                               return \"<td><a target='_blank' href='./sequence_detail.php?species=\"+species+\"&seq=\"+data.record.gene+\"'><span title='Get more information about this sequence' style='background-color:#0066cc;color:#FFFFFF;'>\"+data.record.gene+\"</span></a></td>\";
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
                                            . "}";
                                    }
                                ?>
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
                </script>
        <div id="download"style="border: #ff6600 2px dotted;border-collapse: collapse;text-align: center">
            CLick to download the list data&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button onclick="javascript:window.location.href='./download_data.php?type=4&name=<?php echo $c; ?>'">download</button>
        </div>
    <?php
        include './wheelmenu.php';
        ?>
    <?php
        include './footer.php';
    ?>
</body>
</html>