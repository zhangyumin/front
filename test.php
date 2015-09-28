<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <script type="text/javascript" src="./src/jquery-2.0.0.min.js"></script>
    </head>
    <body>
            <?php
            session_start();
//                echo $_GET['method']."<br>";
//                echo $_POST['species']."<br>";
//                echo $_POST['chr']."<br>";
//                echo $_POST['start']."<br>";
//                echo $_POST['end']."<br>";
//                echo $_POST['gene_id']."<br>";
//                echo $_POST['go_accession']."<br>";
//                echo $_POST['go_name']."<br>";
//                echo $_POST['function']."<br>";
//                var_dump($_POST['sample1']."<br>");
//                var_dump($_POST['sample2']."<br>");
//                echo $_POST['nor_method']."<br>";
//                echo $_POST['min_pat']."<br>";
//                var_dump($_SESSION['file_real']."<br>");
//                echo $_SESSION['sys_real']."<br>";
            var_dump($_SESSION['sys_real']);
            foreach ($_SESSION['sys_real'] as $key => $value) {
                echo $value;
            }
            ?>
        <?php
//                session_start();
//                session_unset();
//                session_destroy();
        ?>
    </body>
</html>
