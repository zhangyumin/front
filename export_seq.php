<?php
    session_start();
    $con=  mysql_connect("localhost","root","root");
    mysql_select_db("db_server",$con);
//    echo $_GET['source'];
//    echo "--->method".var_dump($_POST['method']);
//    echo "--->upstream".var_dump($_POST['upstream']);
//    echo "--->downstream".var_dump($_POST['downstream']);
//    echo "--->pac_region".var_dump($_POST['pac_region']);
//    echo "--->pacs_region".var_dump($_POST['pacs_region']);
//    echo "--->anno-version".var_dump($_POST['anno_version']);
//    echo "--->export".var_dump($_POST['export']);
    $file=$_SESSION['file'];
    $species=$_SESSION['species'];
    $source=$_GET['source'];
    $table="db_user.".$source."_".$file;
    $upstream=$_POST['upstream'];
    $downstream=$_POST['downstream'];
    $pac_region=$_POST['pac_region'];
    $pacs_region=$_POST['pacs_region'];
    $anno_version=$_POST['anno_version'];
    $export=$_POST['export'];
    if($_POST['method']=='pacs'){
        if($pac_region=='all')
            $cmd1="./src/perl/PAT_trimSeq.pl -tbl $table -from \"-$downstream\" -to $upstream -cond \"\" -opath \"/var/www/front/searched/\" -suf up$upstream.dn$downstream -conf \"/var/www/front/config/db_$species.xml\"";
        else if($pac_region=='genomic-region')
            $cmd1="./src/perl/PAT_trimSeq.pl -tbl $table -from \"-$downstream\" -to $upstream -cond \"ftr not like '%inter%'\" -opath \"/var/www/front/searched/\" -suf up$upstream.dn$downstream -conf \"/var/www/front/config/db_$species.xml\"";
        else
            $cmd1="./src/perl/PAT_trimSeq.pl -tbl $table -from \"-$downstream\" -to $upstream -cond \"ftr='$pac_region'\" -opath \"/var/www/front/searched/\" -suf up$upstream.dn$downstream -conf \"/var/www/front/config/db_$species.xml\"";
//        echo $cmd1;
        shell_exec($cmd1);
        $name=explode("/", glob("/var/www/front/searched/db_user.Search_arab20151114095429.SQL.401nt.up200.dn200.3*")[0]);
        echo "<script>window.location.href=\"./download_data.php?type=4&name=$name[5]\";</script>";
    }
    else if($_POST['method']=='pacs-region'){
        if($pacs_region=='all')
            $cmd2="./src/perl/PAT_outputGeneseq.pl -annotbl $table -cond \"\" -ofile \"/var/www/front/searched/Pacs_Seq_$file\"  -conf \"/var/www/front/config/db_$species.xml\""; 
        else if($pacs_region=='genomic-region')
            $cmd2="./src/perl/PAT_outputGeneseq.pl -annotbl $table -cond \"ftr not like '%inter%'\" -ofile \"/var/www/front/searched/Pacs_Seq_$file\"  -conf \"/var/www/front/config/db_$species.xml\""; 
        else
            $cmd2="./src/perl/PAT_outputGeneseq.pl -annotbl $table -cond \"ftr='$pacs_region'\" -ofile \"/var/www/front/searched/Pacs_Seq_$file\"  -conf \"/var/www/front/config/db_$species.xml\""; 
//        echo $cmd2;
        shell_exec($cmd2);
        
        echo "<script>window.location.href=\"./download_data.php?type=4&name=Pacs_Seq_$file\";</script>";
    }
    else if($_POST['method']=='seq'){
        
    }
?>