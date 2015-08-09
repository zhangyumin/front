<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Details of data processing</title>
    </head>
    <body>
            <?php
                $con=  mysql_connect("localhost","root","root");
                 mysql_select_db("db_bio",$con);
            ?>
            <?php
                session_start();
                #echo  $_SESSION['qct'];
                #echo $_SESSION['mp'];
                #echo $_SESSION['tailremove'];
                #echo $_SESSION['aligner'];
                #echo $_SESSION['rip'];
                #echo $_SESSION['distance'];
                #echo  $_SESSION['file'] ;
                $file_name = scandir("./data/".$_SESSION['file']."");
                $file_name = array_slice($file_name, 2);
                $file_num = sizeof($file_name);
                $file_real=array();
                foreach ($file_name as $key => $value) {
                    array_push($file_real,str_replace(strrchr($value, "."), '', $value));
                    //var_dump($file_real);
                }
                //$_SESSION['file_real']=array();
                $_SESSION['file_real']=$file_real;
                foreach($file_real as $key => $value)
                {
                    //echo "step1:原始序列预处理";
                
                    $cmd1="fastq_quality_filter -q ".$_SESSION['qct']." -p ".$_SESSION['mp']." -v -Q 33 -i ./data/".$_SESSION['file']."/$value.fastq -o ./data/".$_SESSION['file']."/$value.qc.fa  >>./log/".$_SESSION['file'].".txt";
                    #echo $cmd1;
                    $out1=shell_exec($cmd1);
                echo "<pre>$out1</pre>";

                //echo "step2:去除polyT/polyA tail";
                    if($_SESSION['tailremove']=='T')
                    {
                        $cmd2="./src/perl/MAP_filterPolySeq.pl -s \"./data/".$_SESSION['file']."/$value.qc.fa\" -if fq -tl 8 -tr 20 -poly T -ml ".$_SESSION['minlength']." -qc T -of fq -st .noT.fa >>./log/".$_SESSION['file'].".txt";
                        #echo $cmd2;
                    }
                    else if($_SESSION['tailremove']=='A')
                    {
                        $cmd2="./src/perl/MAP_filterPolySeq.pl -s \"./data/".$_SESSION['file']."/$value.qc.fa\" -if fq -tl 8 -tr 20 -poly A -ml ".$_SESSION['minlength']." -qc T -of fq -st .noT.fa >>./log/".$_SESSION['file'].".txt";
                    }
                    else if($_SESSION['tailremove']=='unknown')
                    {
                        $grep_a=shell_exec("./src/fastq-tool/fastq-grep -c AAAAAAAA \"./data/".$_SESSION['file']."/$value.qc.fa \" ");
                        echo "<pre>AAAAAAAA=$grep_a</pre>";
                        $grep_t=shell_exec("./src/fastq-tool/fastq-grep -c TTTTTTTT \"./data/".$_SESSION['file']."/$value.qc.fa \" ");
                        echo "<pre>TTTTTTTT=$grep_t</pre>";
                        if($grep_a>=$grep_t)
                            $cmd2="./src/perl/MAP_filterPolySeq.pl -s \"./data/".$_SESSION['file']."/$value.qc.fa \" -if fq -tl 8 -tr 20 -poly A -ml ".$_SESSION['minlength']." -qc T -of fq -st .noT.fa >>./log/".$_SESSION['file'].".txt";
                        else
                            $cmd2="./src/perl/MAP_filterPolySeq.pl -s \"./data/".$_SESSION['file']."/$value.qc.fa \" -if fq -tl 8 -tr 20 -poly T -ml ".$_SESSION['minlength']." -qc T -of fq -st .noT.fa >>./log/".$_SESSION['file'].".txt";
                    }
                    $out2=  shell_exec($cmd2);
                echo "<pre>$out2</pre>";
                
                //echo"step3: 序列比对";
                    if($_SESSION['aligner']=='bowtie2')
                    {
                        $cmd3="./src/bowtie2-2.2.4/bowtie2 -L 25 -N 0 -i S,1,1.15 --no-unal -x ./src/bowtie2-2.2.4/indexes/bwt2_TAIR10 -q -U ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa -S ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam 2>>./log/".$_SESSION['file'].".txt";
                        #echo $cmd3;
                    }
                    else{
                        $cmd3="./src/bowtie2-2.2.4/bowtie2 -L 25 -N 0 -i S,1,1.15 --no-unal -x ./src/bowtie2-2.2.4/indexes/bwt2_TAIR10 -q -U ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa -S ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam 2>>./log/".$_SESSION['file'].".txt";
                        #echo $cmd3;
                    }
                    $out3=  shell_exec($cmd3);
                echo"<pre>$out3</pre>";
                
                //echo "step4:获取polyA位点";
                    $cmd4="./src/perl/PAT_parseSAM2PA_II.pl -sam ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam -m 30 -s 10 -ofile ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam.M30S10.PA 1>/dev/null ";
                    #echo $cmd4;
                    $out4=  shell_exec($cmd4);
                 echo"<pre>$out4</pre>";
                 
                 if($_SESSION['rip']=='yes')
                 {
                    //echo"step5:去除internal priming";
                    $cmd5="./src/perl/PAT_setIP.pl -itbl /var/www/html/front/data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam.M30S10.PA -otbl /var/www/html/front/data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam.M30S10.PA -flds 0:1:2 -format file -conf ./db.xml >>./log/".$_SESSION['file'].".txt";
                    #echo $cmd5;
                    $out5=  shell_exec($cmd5);
                    echo"<pre>$out5</pre>";
                 }
                 
                 //echo"step6:导入PA表到数据库";
                    $cmd6="./src/perl/PAT_alterPA.pl -master db_user.PA_".$_SESSION['file']." -aptbl '/var/www/html/front/data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam.M30S10.PA' -apsmp  $value -format file -conf ./db.xml 1>/dev/null";
                    #echo $cmd6;
                    $out6=  shell_exec($cmd6);
                    echo"<pre>$out6</pre>";
                }
                 
                
                //echo"step7:PA聚类成PAC";
                //$cmd7="./src/perl/PAT_PA2PAC.pl -d ".$_SESSION['distance']." -remap T -mtbl db_user.PA_".$_SESSION['file']." -gfftbl db_bio.gff_arab10 -anti T -otbl db_user.PAC_".$_SESSION['file']." -smps $file_real[0] -conf ./db.xml  >>./log/".$_SESSION['file'].".txt";
                $cmd7origin="./src/perl/PAT_PA2PAC.pl -d ".$_SESSION['distance']." -remap T -mtbl db_user.PA_".$_SESSION['file']." -gfftbl db_bio.gff_arab10 -anti T -otbl db_user.PAC_".$_SESSION['file']." -smps ";
                foreach ($file_real as $key => $value) {
                    if($key!=0)
                        $cmd7plus.=":".$value;
                    else
                        $cmd7plus.=$value;
                }
               $cmd7=$cmd7origin.$cmd7plus." -conf ./db.xml  >>./log/".$_SESSION['file'].".txt";
                #echo $cmd7;
                $out7=  shell_exec($cmd7);
                echo"<pre>$out7</pre>";

                //echo"step8:提取序列并计算单核苷分布 ";
                $cmd8="./src/perl/PAT_trimSeq.pl -tbl db_user.PAC_".$_SESSION['file']." -cond  \"tot_tagnum>=2\" -suf ".$_SESSION['file'].".PAT2 -conf ./db.xml -opath './result/".$_SESSION['file']."/'  1>/dev/null";
                #echo $cmd8;
                $out8=  shell_exec($cmd8);
                echo"<pre>$out8</pre>";
                $cmd9="./src/perl/PAS_kpssm.pl -seqdir \"./result/".$_SESSION['file']."/\" -pat \"".$_SESSION['file'].".PAT2.*s$\" -from 1 -to 400 -k 1 -sort F -cnt T -freq T -tran T -suffix _atcg  1>/dev/null";
                #echo $cmd9;
                $out9=  shell_exec($cmd9);
                echo"<pre>$out9</pre>";

             //echo"step9:计算polyA信号";
                $cmd10="./src/perl/PAS_kcount.pl -seqdir \"./result/".$_SESSION['file']."/\" -pat \"".$_SESSION['file'].".PAT2.*s$\" -k 6 -from 265 -to 290 -sort T -topn 50 -gap_once \"-1\" " ;
                $out10=  shell_exec($cmd10);
                echo"<pre>$out10</pre>";
                
                 
                 #PAT导入jbrowse显示
                 //shell_exec("cp ./data/".$_SESSION['file']."/$file_real[0].qc.fa.noT.fa.sam.M30S10.PA ./tojbrowse/pat.txt");//移动文件
                shell_exec("cp -r ../jbrowse/data/arabidopsis/ ../jbrowse/data/".$_SESSION['file']."/");
                shell_exec("chmod -R 777 ../jbrowse/data/".$_SESSION['file']."/");
                foreach ($file_real as $key => $value) {
                     shell_exec("cp ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam.M30S10.PA ../jbrowse/data/".$_SESSION['file']."/$value.txt");
                     shell_exec("./src/c/txt2bedgraph ../jbrowse/data/".$_SESSION['file']."/$value.txt ../jbrowse/data/".$_SESSION['file']."/$value.positive.bedGraph ../jbrowse/data/".$_SESSION['file']."/$value.negative.bedGraph");
                     shell_exec("sort -k1,1 -k2,2n ../jbrowse/data/".$_SESSION['file']."/$value.positive.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.bedGraph ");
                     shell_exec("sort -k1,1 -k2,2n ../jbrowse/data/".$_SESSION['file']."/$value.negative.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.bedGraph ");
                     shell_exec("uniq -u ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.uniq.bedGraph");
                     shell_exec("uniq -u ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.uniq.bedGraph");
                     shell_exec("./src/c/bedGraphToBigWig ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.bedGraph ./src/arab.sizes ../jbrowse/data/".$_SESSION['file']."/$value.UsrPosPA.bw");
                     shell_exec("./src/c/bedGraphToBigWig ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.bedGraph ./src/arab.sizes ../jbrowse/data/".$_SESSION['file']."/$value.UsrNegPA.bw");
                     $configure_file=fopen("../jbrowse/data/".$_SESSION['file']."/trackList.json", "r+");
                    fseek($configure_file, -3, SEEK_END);
                    fwrite($configure_file,",\n"
                            . "\t{\n"
                            . "\t\t\"storeClass\" : \"JBrowse/Store/SeqFeature/BigWig\",\n"
                            . "\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
                            . "\t\t\"urlTemplate\" : \"./$value.UsrPosPA.bw\",\n"
                            . "\t\t\"uniqueStoreName\" : {\n"
                            . "\t\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
                            . "\t\t\t\"urlTemplate\" : \"./$value.UsrPosPA.bw\",\n"
                            . "\t\t},\n"
                            . "\t\t\"style\" : {\n"
                            . "\t\t\t\"pos_color\" : \"blue\",\n"
                            . "\t\t\t\"neg_color\" : \"red\"\n"
                            . "\t\t},\n"
                            . "\t\t\"key\" : \"$value positive polyA site\",\n"
                            . "\t\t\"autoscale\" : \"local\",\n"
                            . "\t\t\"variance_band\" : false,\n"
                            . "\t\t\"label\" : \"$value positive polyA site\"\n"
                            . "\t}\n");
                    fwrite($configure_file,",\n"
                            . "{\n"
                            . "\t\t \"storeClass\" : \"JBrowse/Store/SeqFeature/BigWig\",\n"
                            . "\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
                            . "\t\t\"urlTemplate\" : \"./$value.UsrNegPA.bw\",\n"
                            . "\t\t\"uniqueStoreName\" : {\n"
                            . "\t\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
                            . "\t\t\t\"urlTemplate\" : \"./$value.UsrNegPA.bw\",\n"
                            . "\t\t},\n"
                            . "\t\t\"style\" : {\n"
                            . "\t\t\t\"pos_color\" : \"blue\",\n"
                            . "\t\t\t\"neg_color\" : \"red\"\n"
                            . "\t\t},\n"
                            . "\t\t\"key\" : \"$value negative polyA site\",\n"
                            . "\t\t\"autoscale\" : \"local\",\n"
                            . "\t\t\"variance_band\" : false,\n"
                            . "\t\t\"label\" : \"$value negative polyA site\"\n"
                            . "\t}]}\n");
                    fclose($configure_file);
                }
                foreach ($file_real as $key => $value) {
                    mysql_query("select chr,strand,coord,$value from db_user.PAC_$value into outfile '../jbrowse/data/".$_SESSION['file']."/$value.txt'");
                    shell_exec("./src/c/txt2bed ../jbrowse/data/".$_SESSION['file']."/$value.txt ../jbrowse/data/".$_SESSION['file']."/$value.bed");
                    shell_exec("../jbrowse/bin/flatfile-to-json.pl --bed ../jbrowse/data/".$_SESSION['file']."/$value.bed --trackLabel PAC_$value --out ../jbrowse/data/".$_SESSION['file']."/");
                }
                 //shell_exec("./tojbrowse/txt2bedgraph");//转换为bedgraph文件
                 //shell_exec("sort -k1,1 -k2,2n ./tojbrowse/Uppat.bedGraph > ./tojbrowse/Uppat.sorted.bedGraph ");
                 //shell_exec("sort -k1,1 -k2,2n ./tojbrowse/Unpat.bedGraph > ./tojbrowse/Unpat.sorted.bedGraph ");#排序
                 //shell_exec("uniq -u ./tojbrowse/Uppat.sorted.bedGraph > ./tojbrowse/Uppat.sorted.uniq.bedGraph");
                 //shell_exec("uniq -u ./tojbrowse/Unpat.sorted.bedGraph > ./tojbrowse/Unpat.sorted.uniq.bedGraph");#去除重复行
                 //shell_exec("./tojbrowse/bedGraphToBigWig ./tojbrowse/Uppat.sorted.uniq.bedGraph ./tojbrowse/arab.sizes ./tojbrowse/Uppat.bw");
                 //shell_exec("./tojbrowse/bedGraphToBigWig ./tojbrowse/Unpat.sorted.uniq.bedGraph ./tojbrowse/arab.sizes ./tojbrowse/Unpat.bw");
                 //shell_exec("cp ./tojbrowse/Uppat.bw ../jbrowse/data/");
                 //shell_exec("cp ./tojbrowse/Unpat.bw ../jbrowse/data/");
                 
//                 #PAC导入jbrowse显示
//                 $tag_num=$_SESSION['file'];
//                 $cmd11="select chr,strand,coord,$tag_num from pac_arab10 into outfile '/var/www/html/front/tojbrowse/pac.txt' ";
//                 //echo $cmd11;
//                 mysql_query($cmd11);
//                 shell_exec("./tojbrowse/txt2bed");
//                 $cmd12="/var/www/html/jbrowse/bin/flatfile-to-json.pl --bed /var/www/html/front/tojbrowse/pac.bed --trackLabel usrPac --out /var/www/html/jbrowse/data/";
//                 //echo $cmd12;
//                 $test=shell_exec($cmd12);
                 echo"<pre>$test</pre>";
                  
                 echo '<script>window.location.href="task_summary.php";</script>';
//                 echo '<script>window.location.href="http://127.0.0.1/jbrowse/?data=data/'.$_SESSION['file'].'";</script>';
            ?>
<!--        <div id="task_summery" align="center">
            <div id="title">
                Task Summary
            </div>
            <?php
//                $file=  file_get_contents("./log/".$_SESSION['file'].".txt");
//                $array_file=  explode("\n", $file);
//
//                $array_input=explode(" ",$array_file[2]);
//                $array_discard=explode(" ",$array_file[4]);
//                $array_tail=explode(" ",$array_file[6]);
//                $array_read=explode(" ",$array_file[9]);
//                $array_internal=explode(" ",$array_file[28]);
//                $array_pat=explode(" ",$array_file[61]);
//                $array_pac=explode(" ",$array_file[62]);
//
//                $input_reads=$array_input[1];
//                $low_quality_reads=$array_discard[1];
//                $reads_with_tail=$array_tail[0];
//                $aligned_reads=$array_read[4];
//                $array_read[5]=  substr($array_read[5], 1, 6);
//                $alignment_rate=$array_read[5];
//                $array_internal[1]=  substr($array_internal[1], 0,1);
//                $internal_priming_reads=$array_internal[1];
//                $pat=$array_pat[0];
//                $pac=$array_pac[0];
//                
//                echo "<br><br><br><br><br><br><br><br><br>";
//                echo "<br>Input reads : $input_reads<br>";
//                echo "<br>Low quality reads : $low_quality_reads<br>";
//                echo "<br>Reads with tail : $reads_with_tail<br>";
//                echo "<br>Aligned reads : $aligned_reads<br>";
//                echo "<br>Alignment rate : $alignment_rate<br>";
//                echo "<br>Internal priming reads : $internal_priming_reads<br>";
//                echo "<br>PAT : $pat<br>";
//                echo "<br>PAC : $pac<br>";
//                echo "<br><br><br><br>";
            ?>
            <input type="button" onclick="javascirpt:window.open('show_result.php','_blank');" value="continue"/>
            <input type="button" onclick="javascirpt:window.open('download_data.php','_blank');" value="download" />
        </div>-->
    </body>
</html>