<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
         <?php
                $con=  mysql_connect("localhost","root","root");
                 mysql_select_db("db_user",$con);
                 session_start();
            ?>
        <title>Task summary</title>
        <link href="./src/navbar.css" rel="stylesheet"/>
        <script src="./src/jquery-2.0.0.min.js"></script>
        <style>
            #content{
                    font-weight: 400;
                    font-style: normal;
                    font-family: 'Lato', sans-serif;
                    color: #224055;
                    font-size: 17px;
                    line-height: 1.8;
                    letter-spacing: 0px;
                    border-style: dashed;
                    width:800px;
            }
            #title{
                    font-weight: lighter;
                    font-style: normal;
                    font-family: 'Lato', sans-serif;
                    color: #224055;
                    font-size: 55px;
                    line-height: 1.2;
                    letter-spacing: 0px;
            }
            #task_summery{
                background-color: #ddddff;
            }
        </style>
    </head>
    <body>
        <div style="width: 100%;margin:0 auto;" class="page">
            <?php
                include "./navbar.php";
            ?>
            <div id="task_summery" align="center" style="clear: both;"><br>
            <div id="title">
                Task Summary
            </div>
             <div id="content">
                <?php
                    $file=  file_get_contents("./log/".$_SESSION['file'].".txt");
                    $array_file=  explode("\n", $file);
                    foreach ($array_file as $key => $value) {
                        $array_input=  strstr($value, 'Input');
                        if( $array_input!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_discard=  strstr($value, 'discarded');
                        if( $array_discard!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_internal=  strstr($value, 'Total');
                        if( $array_internal!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_tail=  strstr($value, ' reads; of these',true);
                        if( $array_tail!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_read=  strstr($value, 'aligned exactly 1 time',true);
                        if( $array_read!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_pat=  strstr($value,  "rows to db_user.PA_".$_SESSION['file']."",true);
                        if( $array_pat!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_pac=  strstr($value, ' PAC',true);
                        if( $array_pac!=false)
                            break;
                    }
                    //var_dump($array_input);
                    $array_input=explode(" ",$array_input);
                    $array_discard=explode(" ",$array_discard);
                    $array_tail=explode(" ",$array_tail);
                    $array_read=explode(" ",$array_read);
                    $array_internal=explode(" ",$array_internal);
                    $array_pat=explode(" ",$array_pat);
                    $array_pac=explode(" ",$array_pac);

                    $input_reads=$array_input[1];
                    $low_quality_reads=$array_discard[1];
                    $reads_with_tail=$array_tail[0];
                    $aligned_reads=$array_read[4];
                    $array_read[5]=  substr($array_read[5], 1, strlen($array_read[5])-2);
                    $alignment_rate=$array_read[5];
                    $array_internal[1]=  substr($array_internal[1], 0,strlen($array_internal[1])/2);
                    $internal_priming_reads=$array_internal[1];
                    $pat=$array_pat[0];
                    $pac=$array_pac[0];
                    
                    echo "<span style='color:red'>Task id : ".$_SESSION['file']."</span><br>";
                    echo "Input reads : $input_reads<br>";
                    echo "Low quality reads : $low_quality_reads<br>";
                    echo "Reads with tail : $reads_with_tail<br>";
                    echo "Aligned reads : $aligned_reads<br>";
                    echo "Alignment rate : $alignment_rate<br>";
                    echo "Internal priming reads : $internal_priming_reads<br>";
                    echo "<br>PAT : $pat";
                    echo "&nbsp&nbspPAC : $pac<br>";
                ?>
             </div>
                <div style="position:relative;margin-left: 40%;">
                    <a href="show_result.php" target="_blank" style="text-decoration:none;"><img src=./pic/continue.png /><span style="color:black;font-weight: bold;font-size: 48px;bottom:0;position:absolute;bottom: 0;">Continue</span></a>
                </div>
            </div>
        </div>
        <?php
                include './footer.php';
                ?>
    </body>
</html>
