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
//        $list=  scandir("./data/arab20150817021749/");
//        foreach ($list as $key => $value) {
//            if(preg_match("(\w*\.)(fastq$|fa$)", $value))
//                    echo "$value<br>";
//        }
//        var_dump($list);
            var_dump(preg_match("/^\w*", "arabfastq"));
        ?>
    </body>
</html>
