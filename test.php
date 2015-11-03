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
            var_dump($_SESSION['sample']);
            ?>
        <?php
//                session_start();
//                session_unset();
//                session_destroy();
        ?>
    </body>
</html>
