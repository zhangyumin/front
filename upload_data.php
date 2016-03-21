<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php 
    session_start();
    $_SESSION['file']=0;
    ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <p align="center"><font size="40">The data is uploading , please wait...</font></p>
        <p align="center"><img src="./pic/loading.gif"/></p>
        <?php
            #定义上传路径,文件名,允许上传文件类型,使用时间戳来保证文件名的唯一性.
            
            $tmppath="./data/".$_SESSION['tmp']."/";
            $_SESSION['file']=$_POST["species"].$_SESSION['tmp'];
            $_SESSION['species']=$_POST['species'];
            $filename=$_SESSION['file'].".fa";
            $filepath="./data/".$_SESSION['file']."/";
            //echo $filepath;
            #传递上传文件参数设置
            $_SESSION['qct']=$_POST['qct'];
            $_SESSION['mp']=$_POST['mp'];
            $_SESSION['tailremove']=$_POST['tailremove'];
            $_SESSION['aligner']=$_POST['aligner'];
            $_SESSION['minlength']=$_POST['minlength'];
            $_SESSION['rip']=$_POST['rip'];
            $_SESSION['distance']=$_POST['distance'];

           
                if(!file_exists($tmppath)&&$_POST['sys_example']!='on')
                {
                     echo "<script type='text/javascript'>alert('upload sequence file first'); history.back();</script>";
                }
                else if($_POST['sys_example']=='on'){
                    mkdir("./data/".$_SESSION['file']."/");
                    chmod("./data/".$_SESSION['file']."/", 0777);
                    mkdir("./result/".$_SESSION['file']."/");
                    chmod("./result/".$_SESSION['file']."/", 0777);
                    copy("./data/sys_example/arab.fastq", "./data/".$_SESSION['file']."/arab.fastq");
                    echo '<script>window.location.href="get_result.php";</script>';
                }
                else 
                {
                        chmod($tmppath, 0777);
                        rename($tmppath, $filepath);
                        mkdir("./result/".$_SESSION['file']."/");
                        chmod("./result/".$_SESSION['file']."/", 0777);
                        $x=move_uploaded_file($_FILES["file"]["tmp_name"], $filepath.$filename);
                        echo '<script>window.location.href="get_result.php";</script>';
                        #echo $x;
                  }
            
        ?>
    </body>
</html>
