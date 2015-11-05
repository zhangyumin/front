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
    <?php
        session_start();
        $file=$_SESSION['file'];
        if($_GET['result']=='degene'){
            $a=file("./searched/degene.$file");
            $b=file("./searched/degene.$file.stat");
            $c = "degene.$file";
        }
        if($_GET['result']=='depac'){
            $a=file("./searched/depac.$file");
            $b=file("./searched/depac.$file.stat");
            $c = "depac.$file";
        }
        if($_GET['result']=='switchinggene_o'){
            $a=file("./searched/only3utr.$file");
            $b=file("./searched/only3utr.$file.stat");
            $c = "only3utr.$file";
        }
        if($_GET['result']=='switchinggene_n'){
            $a=file("./searched/none3utr.$file");
            $b=file("./searched/none3utr.$file.stat");
            $c = "none3utr.$file";
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
        $con=  mysql_connect("localhost","root","root");
        mysql_select_db("db_server",$con);
        ?>
    <div style="width: 100%;margin:0 auto;background-color: #ddddff;" class="page">
        <?php
            include './navbar.php'
        ?>
        <script src="./src/jquery-1.10.1.min.js" type="text/javascript"></script>
        <script src="./src/jquery.dataTables.min.js"type="text/javascript" ></script>
        <link href="./src/jquery.dataTables.css"type="text/css" rel="stylesheet"></link>
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
        </div><br><br>
        <div id="table"  style="width: 90%;overflow-x: auto;background-color: #fff;margin:auto;">
            <table id="example" class="display dataTable" cellspacing="0" role="grid" aria-describedby="example_infox">
                <thead>
                    <tr>
                        <?php
                            $title_tmp=  explode("\t", $a[0]);
                            foreach ($title_tmp as $key => $value) {
                                echo "<th>$value</th>";
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                        <?php
                            foreach ($a as $key => $value) {
                                    if($key!=0){
                                        echo "<tr>";
                                        $tmp=  explode("\t", $a[$key]);
                                        foreach ($tmp as $key1 => $value1) {
                                            if($_GET['result']=='degene'){
                                                if($key1==0){
                                                    echo "<td><a href='./sequence_detail.php?seq=$value1'>$value1</a></td>";
                                                    }
                                                else
                                                    echo "<td>$value1</td>";
                                            }
                                            else if($_GET['result']=='depac'){
                                                if($key1==0){
                                                   echo "<td><a href='./sequence_detail.php?seq=$value1'>$value1</a></td>";
                                                }
                                                else
                                                    echo "<td>$value1</td>";
                                            }
                                            else if($_GET['result']=='switchinggene_o'){
                                                if($key1==0){
                                                    echo "<td><a href='./sequence_detail.php?seq=$value1'>$value1</a></td>";
                                                }
                                                else
                                                    echo "<td>$value1</td>";
                                            }
                                            else if($_GET['result']=='switchinggene_n'){
                                                if($key1==0){
                                                    echo "<td><a href='./sequence_detail.php?seq=$value1'>$value1</a></td>";
                                                }
                                                else
                                                    echo "<td>$value1</td>";
                                            }
                                        }
                                        echo "</tr >";
                                    }
                            }
                        ?>
                </tbody>
            </table>
        </div><br><br>
        <script>
            $(document).ready(function(){
                $('#example').dataTable({
                    "lengthMenu":[[10,25,50,-1],[10,25,50,"all"]],
                    "pagingType":"full_numbers"
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