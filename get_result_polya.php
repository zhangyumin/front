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
                 mysql_select_db("db_server",$con);
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
//                $file_num = sizeof($file_name);
                $file_real=array();
                $upload_name = array();
                foreach ($file_name as $key => $value) {
                    array_push($file_real,str_replace(strchr($value, "."), '', $value));
                    //var_dump($file_real);
                }
                array_push($upload_name, $file_real[0]);
                foreach ($file_real as $key => $value) {
                    if($value!=$file_real[0])
                        array_push ($upload_name, $value);
                }
                $upload_name = array_unique($upload_name);
                //$_SESSION['file_real']=array();
                $_SESSION['file_real']=$upload_name;
                foreach($upload_name as $key => $value)
                {
                    //step0:统一文件后缀名
                    rename("./data/".$_SESSION['file']."/$file_name[$key]", "./data/".$_SESSION['file']."/$value.pa");
//                    shell_exec("mv ./data/".$_SESSION['file']."/$file_name[$key] ./data/".$_SESSION['file']."/$value.pa");
                    //文件校验处理、
                    $pafile=array();
                    $tmp_pa=array();
                    $row_pa=array();
                    $pafile=file("./data/".$_SESSION['file']."/$value.pa");
                    $tmp_pa=  explode("\t",$pafile[0]);
                    if(count($tmp_pa)==3){
                        foreach ($pafile as $pa_key => $pa_value) {
                            $pa_value= chop($pa_value);
                            $row_pa=  explode("\t", $pa_value);
                            array_push($row_pa, 1);
                            file_put_contents("./data/".$_SESSION['file']."/$value.new.pa", implode("\t", $row_pa)."\n",FILE_APPEND);
                        }
                        rename("./data/".$_SESSION['file']."/$value.pa", "./data/".$_SESSION['file']."/$value.old.pa");
                        rename("./data/".$_SESSION['file']."/$value.new.pa", "./data/".$_SESSION['file']."/$value.pa");
                    }
                    else if(count($tmp_pa)==4){
                        echo "ok";
                    }
                    else{
                        echo"<script language=javascript>alert('Error file format');history.go(-1);</script>";
                    }
                    
                 //echo"step6:导入PA表到数据库";
                    $cmd6="./src/perl/PAT_alterPA.pl -master db_user.PA_".$_SESSION['file']." -aptbl '/var/www/front/data/".$_SESSION['file']."/$value.pa' -apsmp  $value -format file -conf ./config/db_".$_SESSION['species'].".xml 1>/dev/null";
                    #echo $cmd6;
                    $out6=  shell_exec($cmd6);
                    echo"<pre>$out6</pre>";
                }
                 
                
                //echo"step7:PA聚类成PAC";
//                $cmd7origin="./src/perl/PAT_PA2PAC.pl -d ".$_SESSION['distance']." -remap T -mtbl db_user.PA_".$_SESSION['file']." -gfftbl db_server.t_".$_SESSION['species']."_gff -anti T -otbl db_user.PAC_".$_SESSION['file']." -smps ";
                $cmd7origin="Rscript ./src/r/PAT_PA2PAC.r path=\"/var/www/front/searched/\" bigmem=F mtbls=\"db_user.PA_".$_SESSION['file']."\" osmps=NULL d=24 noGFF=F anti=F gfftbl=\"db_server.t_".$_SESSION['species']."_gff\" otbl=\"db_user.PAC_".$_SESSION['file']."\" smps=";
                foreach ($upload_name as $key => $value) {
                    if($key!=0)
                        $cmd7plus.=":".$value;
                    else
                        $cmd7plus.=$value;
                }
               $cmd7=$cmd7origin.$cmd7plus." conf=\"/var/www/front/config/db_".$_SESSION['species'].".xml\"  >>./log/".$_SESSION['file'].".txt";
//               echo $cmd7; 
               $out7=  shell_exec($cmd7);
                echo"<pre>$out7</pre>";

                //echo"step8:提取序列并计算单核苷分布 ";
                $cmd8="./src/perl/PAT_trimSeq.pl -tbl db_user.PAC_".$_SESSION['file']." -cond  \"tot_tagnum>=2\" -suf ".$_SESSION['file'].".PAT2 -conf ./config/db_".$_SESSION['species'].".xml -opath './result/".$_SESSION['file']."/'  1>/dev/null";
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
                
 /*               
                 #PAT导入jbrowse显示
                 //shell_exec("cp ./data/".$_SESSION['file']."/$file_real[0].qc.fa.noT.fa.sam.M30S10.PA ./tojbrowse/pat.txt");//移动文件
                shell_exec("cp -r ../jbrowse/data/arabidopsis/ ../jbrowse/data/".$_SESSION['file']."/");
                shell_exec("chmod -R 777 ../jbrowse/data/".$_SESSION['file']."/");
                foreach ($upload_name as $key => $value) {
                     shell_exec("cp ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam.M30S10.PA ../jbrowse/data/".$_SESSION['file']."/$value.txt");
                     shell_exec("./src/c/txt2bedgraph ../jbrowse/data/".$_SESSION['file']."/$value.txt ../jbrowse/data/".$_SESSION['file']."/$value.positive.bedGraph ../jbrowse/data/".$_SESSION['file']."/$value.negative.bedGraph");
                     shell_exec("sort -k1,1 -k2,2n ../jbrowse/data/".$_SESSION['file']."/$value.positive.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.bedGraph ");
                     shell_exec("sort -k1,1 -k2,2n ../jbrowse/data/".$_SESSION['file']."/$value.negative.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.bedGraph ");
                     shell_exec("uniq -u ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.uniq.bedGraph");
                     shell_exec("uniq -u ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.uniq.bedGraph");
                     shell_exec("./src/c/bedGraphToBigWig ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.bedGraph ./src/arab.sizes ../jbrowse/data/".$_SESSION['file']."/$value.UsrPosPA.bw");
                     shell_exec("./src/c/bedGraphToBigWig ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.bedGraph ./src/arab.sizes ../jbrowse/data/".$_SESSION['file']."/$value.UsrNegPA.bw");
                     $configure_file=fopen("../jbrowse/data/".$_SESSION['file']."/trackList.json", "r+");
                     if($key==0){
                        fseek($configure_file, -98, SEEK_END);
                     }
                     else{
                         fseek($configure_file, -3, SEEK_END);
                     }
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
                foreach ($upload_name as $key => $value) {
                    mysql_query("select chr,strand,coord,$value from db_user.PAC_$value into outfile '../jbrowse/data/".$_SESSION['file']."/$value.txt'");
                    shell_exec("./src/c/txt2bed ../jbrowse/data/".$_SESSION['file']."/$value.txt ../jbrowse/data/".$_SESSION['file']."/$value.bed");
                    shell_exec("../jbrowse/bin/flatfile-to-json.pl --bed ../jbrowse/data/".$_SESSION['file']."/$value.bed --trackLabel PAC_$value --out ../jbrowse/data/".$_SESSION['file']."/");                    
                    $txt=file("../jbrowse/data/".$_SESSION['file']."/trackList.json");
                    $configure_file=fopen("../jbrowse/data/".$_SESSION['file']."/trackList.json", "r+");
                    rewind($configure_file);
                    echo $txt[count($txt)-3];
                    if(strlen($txt[count($txt)-3])==6){
                        echo "short";
                        fseek($configure_file, -106, SEEK_END);
                         fwrite($configure_file,",\n"
                            . "\t\"onClick\" : {\n"
                            . "\t\t\"url\" : \"../front/sequence.php?chr={seq_id}&gene={start}&strand={strand}\",\n"
                            . "\t\t\"label\" : \"see polyA site\",\n"
                            . "\t\t\"action\" : \"newwindow\"\n"
                            . "\t}}],"
                                 . "   \"names\" : {\n"
                                 . "      \"type\" : \"Hash\",\n"
                                 . "      \"url\" : \"names/\"\n"
                                 . "   }\n"
                                 . "}\n");
                    }
                    elseif(strlen($txt[count($txt)-3])==8){
                        echo "short";
                        fseek($configure_file, -15, SEEK_END);
                         fwrite($configure_file,",\n"
                            . "\t\"onClick\" : {\n"
                            . "\t\t\"url\" : \"../front/sequence.php?chr={seq_id}&gene={start}&strand={strand}\",\n"
                            . "\t\t\"label\" : \"see polyA site\",\n"
                            . "\t\t\"action\" : \"newwindow\"\n"
                            . "\t}}],"
                                 . "   \"names\" : {\n"
                                 . "      \"type\" : \"Hash\",\n"
                                 . "      \"url\" : \"names/\"\n"
                                 . "   }\n"
                                 . "}\n");
                    }
                    elseif(strlen($txt[count($txt)-3])==23){
                        echo "middle";
                        fseek($configure_file,-82,SEEK_END);
                         fwrite($configure_file,",\n"
                            . "\t\"onClick\" : {\n"
                            . "\t\t\"url\" : \"../front/sequence.php?chr={seq_id}&gene={start}&strand={strand}\",\n"
                            . "\t\t\"label\" : \"see polyA site\",\n"
                            . "\t\t\"action\" : \"newwindow\"\n"
                            . "\t}}],"
                                 . "   \"names\" : {\n"
                                 . "      \"type\" : \"Hash\",\n"
                                 . "      \"url\" : \"names/\"\n"
                                 . "   }\n"
                                 . "}\n");
                    }
                    else{
                        echo "Oh no\n";
                        echo strlen($txt[count($txt)-2]);
                    }
                    fclose($configure_file);
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
*/                  
                 echo '<script>window.location.href="show_result.php";</script>';
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