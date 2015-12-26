<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>sequence viewer</title>
        <script src="./src/jquery-1.10.1.min.js"></script>
    </head>
    <body>
        <?php 
            $con=  mysql_connect("localhost","root","root");
            mysql_select_db("db_server",$con);
            session_start();
            
            $seq = $_GET['seq'];
            $species = $_GET['species'];
            //获得strand
            if($_GET['flag']=='intergenic'){
                 $cgs=  mysql_query("select * from db_server.t_".$species."_gff_all where gene='".$_GET['seq']."' and ftr='intergenic';");
            }
            else{
                $cgs=  mysql_query("select * from db_server.t_".$species."_gff_all where gene='".$_GET['seq']."' and ftr='gene';");
            }
            while($cgs_row=  mysql_fetch_row($cgs)){
                $chr=$cgs_row[0];
                $gene=($cgs_row[3]+$cgs_row[4])/2;
    //            echo $gene;
                if($cgs_row[1]=='+'){
                    $strand=1;
                }
                else{
                    $strand=-1;
                }
            }
        ?>
        <?php
            $seq = file_get_contents("./seq/$species/$seq.fa");
            //反转互补
            if(strcmp($strand,-1)==0)
             {
                 $seq= strrev($seq);
                 $seq_arr=str_split($seq);
                 array_shift($seq_arr);
                 foreach ($seq_arr as &$value) {
                     if($value=='A')
                         $value='T';
                     else if($value=='T'||$value=='U')
                         $value='A';
                     else if($value=='C')
                         $value='G';
                     else if($value=='G')
                         $value='C';
                     else
                         $value='N';
                 }
                 $seq=  implode($seq_arr);
             }
             else{
                 $seq=substr($seq,0,strlen($seq)-1); 
             }
        ?>
        <div id="seq_viewer">
        </div>
    </body>
</html>