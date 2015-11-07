<?php
    $c=array();
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_server",$con);
    $a=mysql_query("select gene from db_server.t_mtr_gff_genes;");
    while($b=mysql_fetch_row($a)){
        array_push($c, $b[0]);
    }
    foreach ($c as $key => $value) {
        echo $value."\n";
        shell_exec("./src/perl/SVR_extractSeqbyID.pl $value < /var/www/front/seq/mtr/t_mtr_gff_genes.fa > /var/www/front/tojbrowse/mtr/$value.fa 2>&1");
    }
?>