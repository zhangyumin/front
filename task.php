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
           .summary{
                border-color: #5499c9 !important;
                border-style: solid !important;
                border-width: 2px !important;
                padding: 5px 10px !important;
                width:60%;
                font-size: 15px;
                font-family: 'Lato', sans-serif;
        }
        </style>
    </head>
    <body>
        <?php
            include"navbar.php";
        ?>
        <fieldset id="get_back" style="margin:20px auto;;clear:both;width: 50%;text-align: center;">
                    <legend style="text-align:left;">
                        <span class="h3_italic">
                            <font color="#224055"><b>Search task</b>:Get your results back</font>
                        </span>
                    </legend>
                    <form method="post" id="getback" action="#">
                        Task ID:&nbsp;&nbsp;
                        <input name="getback" style="width: 40%;"/>
                        <button type="submit">submit</button>
                    </form>
        </fieldset>
        <?php
            session_start();
            if($_POST['getback']!=NULL){
                $_SESSION['file']=$_POST['getback'];
                $file_name = scandir("./data/".$_SESSION['file']."");
                $file_name = array_slice($file_name, 2);
//                $file_num = sizeof($file_name);
                $file_real=array();
                $upload_name = array();
                foreach ($file_name as $key => $value) {
                    array_push($file_real,str_replace(strchr($value, "."), '', $value));
                    //var_dump($file_real);
                }
                array_push($upload_name, $file_real[0]);
                foreach ($file_real as $key => $value) {
                    if($value!=$file_real[0])
                        array_push ($upload_name, $value);
                }
                $upload_name = array_unique($upload_name);
                //$_SESSION['file_real']=array();
                $_SESSION['file_real']=$upload_name;
            }
            ?>
            <?php
                if(isset($_SESSION['file']))
                {
                    echo "<div id=\"task_summery\" align=\"center\" style=\"clear: both;margin:40px auto 40px auto;\">
                                    <fieldset class=\"summary\">
                                        <legend>
                                            <span class=\"h3_italic\">
                                                <font color=\"#224055\"><b>Task Summary</b></font>
                                            </span>
                                        </legend>";
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
                    
             echo "<div style=\"position:relative;margin-left: 40%;\">
                        <a href=\"show_result.php\" target=\"_blank\" style=\"text-decoration:none;\"><img style=\"width:34px;height:34px\" src=./pic/continue.png /><span style=\"color:black;font-weight: bold;font-size: 24px;bottom:0;position:absolute;bottom: 0;\">Continue</span></a>
                    </div>
                    </div>";
                }
             ?>
       <div class="bottom">
        <?php
            include"footer.php";
            ?>
       </div>
    </body>
</html>