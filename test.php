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
        $list=  scandir("./data/arab20150817021749/");
        $list = array_slice($list, 2);
        $list_name=array();
        $list_real=array();
        foreach ($list as $key => $value) {
                    array_push($list_name,str_replace(strchr($value, "."), '', $value));
                }
        foreach ($list_name as $key => $value) {
            if($value!=$list_name[0])
                array_push ($list_real, $value);
        }
        array_push($list_real, $list_name[0]);
        $list_real = array_unique($list_real);
        var_dump($list_real);
        ?>
    </body>
</html>
