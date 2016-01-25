<?php
    session_start();
    if($_GET['method'] == 'trap'){
        $_SESSION['file'] = 'demo';
        $_SESSION['species'] = 'arab';
        $_SESSION['usr_group'] = explode(";",file_get_contents("./result/".$_SESSION['file']."/group.txt"));
        $file_name = scandir("./data/".$_SESSION['file']."");
        $file_name = array_slice($file_name, 2);
        $file_real=array();
        $upload_name = array();
        foreach ($file_name as $key => $value) {
            array_push($file_real,str_replace(strchr($value, "."), '', $value));
        }
        array_push($upload_name, $file_real[0]);
        foreach ($file_real as $key => $value) {
            if($value!=$file_real[0])
                array_push ($upload_name, $value);
        }
        $upload_name = array_unique($upload_name);
        $_SESSION['file_real']=$upload_name;
        header("location: ./show_result.php");
    }
    if($_GET['method']=='degene'){
        $_SESSION['analysis'] = 'demo_degene';
        header("location: ./aftertreatment_result_test.php?result=degene&species=arab");
    }
    if($_GET['method']=='depac'){
        $_SESSION['analysis'] = 'demo_depac';
        header("location: ./aftertreatment_result_test.php?result=depac&species=arab");
    }
    if($_GET['method']=='only3utr'){
        $_SESSION['analysis'] = 'demo_only3utr';
        header("location: ./aftertreatment_result_test.php?result=switchinggene_o&species=arab");
    }
    if($_GET['method']=='none3utr'){
        $_SESSION['analysis'] = 'demo_none3utr';
        header("location: ./aftertreatment_result_test.php?result=switchinggene_n&species=arab");
    }
?>