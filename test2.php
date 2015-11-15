<?php
    $a=file("/var/www/front/searched/grpac.tmp");
    $b=explode("\t", $a[0]);
    var_dump($b);
?>