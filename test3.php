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
            $sequence = file_get_contents("./seq/$species/$seq.fa");
            //如果是intergenic
            if($_GET['flag']=='intergenic'){
                $coord=$_GET['coord'];
                $coordL=$coord-200;
                $coordH=$coord+200;
                $seq_result=mysql_query("select substring(seq,$coordL,401) from db_server.t_".$species."_fa where title='$chr';");
                while($rows=mysql_fetch_row($seq_result))
                {
                    $sequence=$rows[0];
                }
            }
            //反转互补
            if(strcmp($strand,-1)==0)
            {
                $sequence = strrev($sequence);
                $seq_arr=str_split($sequence);
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
                $sequence=  implode($seq_arr);
            }
            else{
                $sequence=substr($sequence,0,strlen($sequence)-1); 
            }
            $singnals = array("AATAAA","TATAAA","CATAAA","GATAAA","ATTAAA","ACTAAA","AGTAAA","AAAAAA","AACAAA","AAGAAA","AATTAA","AATCAA","AATGAA","AATATA","AATACA","AATAGA","AATAAT","AATAAC","AATAAG");        
            //取sequence的起始和终点坐标
            if($_GET['flag']=='intergenic'){
                $a="SELECT * from db_server.t_".$species."_gff_all where gene='$seq' and ftr='intergenic';";
            }
            else{
                $a="SELECT * from db_server.t_".$species."_gff_all where gene='$seq' and ftr='gene';";
            }
//            echo $sequence;
            $result=mysql_query($a);
            while($row=mysql_fetch_row($result))
            {
                $gene_start=$row[3];
                $gene_end=$row[4];
            }
            //读取全序列信息
            $c="select * from db_server.t_".$species."_gff where gene like '$seq' ;";
            $seq_feature=  mysql_query($c);
            while($row_f=  mysql_fetch_row($seq_feature))
            {
                $ftr[]=$row_f[2];
                $f_start[]=$row_f[3];
                $f_end[]=$row_f[4];
            }
            //3utr extend 位置信息
            $ext_start = array();
            $ext_end = array();
            $extend=  mysql_query("select * from t_".$species."_gff_org where gene='$seq' and ftr='3UTR';");
            while($ext_r=  mysql_fetch_row($extend)){
                array_push($ext_start, $ext_r[3]);
                array_push($ext_end, $ext_r[4]);
            }
            //polyA 位点信息
            $pa_start=array();
            $pa_result=mysql_query("select * from db_server.t_".$_GET['species']."_pa1 where chr='$chr' and coord>=$gene_start and coord<=$gene_end and tot_tagnum>0;");
            while ($pa_row=  mysql_fetch_row($pa_result))
            {
                array_push($pa_start, $pa_row[2]);
            }
            echo "<script type=\"text/javascript\">";
            echo "var sequences=[];";
            echo "var current_seq = '$seq';";
            echo "sequences.push('$seq');";
            echo "var sutr_start=[];";
            echo "var sutr_end=[];";
            echo "var ext_start=[];";
            echo "var ext_end=[];";
            echo "var wutr_start=[];";
            echo "var wutr_end=[];";
            echo "var cds_start=[];";
            echo "var cds_end=[];";
            echo "var intron_start=[];";
            echo "var intron_end=[];";
            echo "var exon_start=[];";
            echo "var exon_end=[];";
            echo "var amb_start=[];";
            echo "var amb_end=[];";
            echo "var pa_start=[];";
            foreach ($pa_start as $key => $value)
            {
                echo "pa_start.push('$value');";
            }
            $i = -1;
            while(list($f_key,$val)=each($ftr))
            {
                if(strcmp($val, '3UTR')==0)
                {
                    $i++;
//                    echo "sutr_start.push('$f_start[$f_key]');";
//                    echo "sutr_end.push('$ext_start[$i]');";
//                    $ext_start_pos = $ext_start[$i]+1;
//                    echo "ext_start.push('$ext_start_pos');";
//                    echo "ext_end.push('$f_end[$f_key]');";
                    echo "sutr_start.push('$f_start[$f_key]');";
                    echo "sutr_end.push('$f_end[$f_key]');";
                    echo "ext_start.push('$ext_start[$i]');";
                    echo "ext_end.push('$ext_end[$i]');";
                    
                }
                if(strcmp($val, '5UTR')==0)
                {
                    echo "wutr_start.push('$f_start[$f_key]');";
                    echo "wutr_end.push('$f_end[$f_key]');";
                }
                if(strcmp($val, 'CDS')==0)
                {
                    echo "cds_start.push('$f_start[$f_key]');";
                    echo "cds_end.push('$f_end[$f_key]');";
                }
                if(strcmp($val, 'intron')==0)
                {
                    echo "intron_start.push('$f_start[$f_key]');";
                    echo "intron_end.push('$f_end[$f_key]');";
                }
               if(strcmp($val, 'exon')==0)
                {
                    echo "exon_start.push('$f_start[$f_key]');";
                    echo "exon_end.push('$f_end[$f_key]');";
                }
                if(strcmp($val, 'AMB')==0)
                {
                       echo "amb_start.push('$f_start[$f_key]');";
                       echo "amb_end.push('$f_end[$f_key]');";
                }
            }
         echo "</script>";

         ?>
        <div id="seq_viewer">
        </div>
    </body>
</html>