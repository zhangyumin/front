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
        $configure_file=fopen("./tojbrowse/trackList.json", "r+");
           $txt=file('../jbrowse/data/arab20150812035603/trackList.json');
           echo strlen($txt[count($txt)-2]);
           if(strlen($txt[count($txt)-2])==5){
                echo "short";
                    fseek($configure_file, -9, SEEK_END);
           }
            else if(strlen($txt[count($txt)-2])==23){
                echo "long";
                    fseek($configure_file,-33,SEEK_END);
            }
//            fwrite($configure_file,",\n"
//                . "\t\"onClick\" : {\n"
//                . "\t\t\"url\" : \"../front/sequence.php?chr={seq_id}&gene={start}&strand={strand}\",\n"
//                . "\t\t\"label\" : \"see polyA site\",\n"
//                . "\t\t\"action\" : \"newwindow\"\n"
//                . "\t}}]}\n");
//        fwrite($configure_file, "heheheheheheheheeh!!!!");
        ?>
    </body>
</html>
