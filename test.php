<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
    </head>
    <body>
        <?php
           $txt=file('./tojbrowse/trackList.json');
           echo $txt[count($txt)-2];
           echo strlen($txt[count($txt)-2]);
         
        ?>
    </body>
</html>
