            <?php
                $con=  mysql_connect("localhost","root","root");
                mysql_select_db("db_server",$con);
                session_start();
                #echo  $_SESSION['qct'];
                #echo $_SESSION['mp'];
                #echo $_SESSION['tailremove'];
                #echo $_SESSION['aligner'];
                #echo $_SESSION['rip'];
                #echo $_SESSION['distance'];
                #echo  $_SESSION['file'] ;
                $tmppath="./data/".$_SESSION['tmp']."/";
                $_SESSION['file']=$_POST["species"].$_SESSION['tmp'];
                $_SESSION['species']=$_POST['species'];
                $filename=$_SESSION['file'].".pa";
                $filepath="./data/".$_SESSION['file']."/";
                if(!file_exists($tmppath))
                {
                     echo "<script type='text/javascript'>alert('upload sequence file first'); history.back();</script>";
                }
                else 
                {
                    chmod($tmppath, 0777);
                    rename($tmppath, $filepath);
                    mkdir("./result/".$_SESSION['file']."/");
                    chmod("./result/".$_SESSION['file']."/", 0777);
                    $x=move_uploaded_file($_FILES["file"]["tmp_name"], $filepath.$filename);
                  }
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
                    rename("./data/".$_SESSION['file']."/$file_name[$key]", "./data/".$_SESSION['file']."/$value.fa");
//                    shell_exec("mv ./data/".$_SESSION['file']."/$file_name[$key] ./data/".$_SESSION['file']."/$value.pa");
//               step1:fa文件处理成.m文件
                    $cmd1="./src/perl/EST_markPoly.pl -s \"/var/www/front/data/".$_SESSION['file']."/$value.fa\" -tl ".$_POST['min_tail_length']." -tr ".$_POST['search_tail_within']." -ss \".m\" -poly ".$_POST['poly_type']."";
                    $out1=  shell_exec($cmd1);
                    
                    //step2:GMAP处理.m文件
                    $cmd2="/usr/local/bin/gmap -d ".$_SESSION['species']." -D /usr/local/share/".$_SESSION['species']." -n 2 -A -B 2 -f 3 /var/www/front/data/".$_SESSION['file']."/$value.fa.m > /var/www/front/data/".$_SESSION['file']."/$value.fa.m.gmap3";
                    $out2=  shell_exec($cmd2);
//                    echo $cmd2;
//                 step3:gmap3文件处理得到PA
                    $cmd3="./src/perl/EST_parseGMAP.pl -ndiff ".$_POST['max_distance1']." -taildiff ".$_POST['max_distance2']." -gmap /var/www/front/data/".$_SESSION['file']."/$value.fa.m.gmap3 -opa /var/www/front/data/".$_SESSION['file']."/$value.pa";
                    $out3= shell_exec($cmd3);
                    //step4:去除pa文件第一行
                    $cmd4="sed -i '1d' /var/www/front/data/".$_SESSION['file']."/$value.pa";
                    $out4=  shell_exec($cmd4);
                 //echo"step6:导入PA表到数据库";
                    $cmd6="./src/perl/PAT_alterPA.pl -master db_user.PA_".$_SESSION['file']." -aptbl '/var/www/front/data/".$_SESSION['file']."/$value.pa' -apsmp  $value -format file -conf ./config/db_".$_SESSION['species'].".xml 1>/dev/null";
                    #echo $cmd6;
                    $out6=  shell_exec($cmd6);
//                    echo"<pre>$out6</pre>";
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
//                echo"<pre>$out7</pre>";

                //echo"step8:提取序列并计算单核苷分布 ";
                $cmd8="./src/perl/PAT_trimSeq.pl -tbl db_user.PAC_".$_SESSION['file']." -cond  \"tot_tagnum>=2\" -suf ".$_SESSION['file'].".PAT2 -conf ./config/db_".$_SESSION['species'].".xml -opath './result/".$_SESSION['file']."/'  1>/dev/null";
                #echo $cmd8;
                $out8=  shell_exec($cmd8);
//                echo"<pre>$out8</pre>";
                $cmd9="./src/perl/PAS_kpssm.pl -seqdir \"./result/".$_SESSION['file']."/\" -pat \"".$_SESSION['file'].".PAT2$\" -from 1 -to 400 -k 1 -sort F -cnt T -freq T -tran T -suffix _atcg  1>/dev/null";
                #echo $cmd9;
                $out9=  shell_exec($cmd9);
//                echo"<pre>$out9</pre>";

             //echo"step9:计算polyA信号";
                $cmd10="./src/perl/PAS_kcount.pl -seqdir \"./result/".$_SESSION['file']."/\" -pat \"".$_SESSION['file'].".PAT2$\" -k 6 -from 265 -to 290 -sort T -topn 50 -gap_once \"-1\" " ;
                $out10=  shell_exec($cmd10);
//                echo"<pre>$out10</pre>";
                
                
                 #PAT导入jbrowse显示
                 //shell_exec("cp ./data/".$_SESSION['file']."/$file_real[0].qc.fa.noT.fa.sam.M30S10.PA ./tojbrowse/pat.txt");//移动文件
            shell_exec("cp -r ../jbrowse/data/".$_SESSION['species']."/ ../jbrowse/data/".$_SESSION['file']."/");
            shell_exec("chmod -R 777 ../jbrowse/data/".$_SESSION['file']."/");
            foreach ($upload_name as $key => $value) {
                 shell_exec("cp ./data/".$_SESSION['file']."/$value.pa ../jbrowse/data/".$_SESSION['file']."/$value.txt");
                 shell_exec("./src/c/txt2bedgraph ../jbrowse/data/".$_SESSION['file']."/$value.txt ../jbrowse/data/".$_SESSION['file']."/$value.positive.bedGraph ../jbrowse/data/".$_SESSION['file']."/$value.negative.bedGraph");
                 shell_exec("sort -k1,1 -k2,2n ../jbrowse/data/".$_SESSION['file']."/$value.positive.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.bedGraph ");
                 shell_exec("sort -k1,1 -k2,2n ../jbrowse/data/".$_SESSION['file']."/$value.negative.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.bedGraph ");
                 shell_exec("uniq -u ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.uniq.bedGraph");
                 shell_exec("uniq -u ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.uniq.bedGraph");
                 shell_exec("./src/c/bedGraphToBigWig ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.uniq.bedGraph ./src/".$_SESSION['species'].".sizes ../jbrowse/data/".$_SESSION['file']."/$value.UsrPosPA.bw");
                 shell_exec("./src/c/bedGraphToBigWig ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.uniq.bedGraph ./src/".$_SESSION['species'].".sizes ../jbrowse/data/".$_SESSION['file']."/$value.UsrNegPA.bw");
                 shell_exec("./src/c/jq '(.tracks) |= . + [{\"storeClass\" : \"JBrowse/Store/SeqFeature/BigWig\",\"urlTemplate\" : \"'$value'.UsrPosPA.bw\",\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\"label\" : \"'$value' positive tagNum\",\"key\" : \"'$value' positive tagNum\",\"autoscale\" : \"local\",\"style\" : {\"neg_color\" : \"red\",\"pos_color\" : \"blue\"},\"variance_ban\" : false}]' /var/www/jbrowse/data/".$_SESSION['file']."/trackList.json > /var/www/jbrowse/data/".$_SESSION['file']."/trackList1.json 2>./tojbrowse/haha.tct");
                 shell_exec("./src/c/jq '(.tracks) |= . + [{\"storeClass\" : \"JBrowse/Store/SeqFeature/BigWig\",\"urlTemplate\" : \"'$value'.UsrNegPA.bw\",\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\"label\" : \"'$value' negetive tagNum\",\"key\" : \"'$value' positive tagNum\",\"autoscale\" : \"local\",\"style\" : {\"neg_color\" : \"red\",\"pos_color\" : \"blue\"},\"variance_ban\" : false}]' /var/www/jbrowse/data/".$_SESSION['file']."/trackList1.json > /var/www/jbrowse/data/".$_SESSION['file']."/trackList2.json 2>./tojbrowse/haha.tct");
                 unlink("../jbrowse/data/".$_SESSION['file']."/trackList.json");
                 unlink("../jbrowse/data/".$_SESSION['file']."/trackList1.json");
                 rename("../jbrowse/data/".$_SESSION['file']."/trackList2.json", "../jbrowse/data/".$_SESSION['file']."/trackList.json");
//                     $configure_file=fopen("../jbrowse/data/".$_SESSION['file']."/trackList.json", "r+");
//                     if($key==0){
//                        fseek($configure_file, -98, SEEK_END);
//                     }
//                     else{
//                         fseek($configure_file, -3, SEEK_END);
//                     }
//                    fwrite($configure_file,",\n"
//                            . "\t{\n"
//                            . "\t\t\"storeClass\" : \"JBrowse/Store/SeqFeature/BigWig\",\n"
//                            . "\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
//                            . "\t\t\"urlTemplate\" : \"./$value.UsrPosPA.bw\",\n"
//                            . "\t\t\"uniqueStoreName\" : {\n"
//                            . "\t\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
//                            . "\t\t\t\"urlTemplate\" : \"./$value.UsrPosPA.bw\",\n"
//                            . "\t\t},\n"
//                            . "\t\t\"style\" : {\n"
//                            . "\t\t\t\"pos_color\" : \"blue\",\n"
//                            . "\t\t\t\"neg_color\" : \"red\"\n"
//                            . "\t\t},\n"
//                            . "\t\t\"key\" : \"$value positive polyA site\",\n"
//                            . "\t\t\"autoscale\" : \"local\",\n"
//                            . "\t\t\"variance_band\" : false,\n"
//                            . "\t\t\"label\" : \"$value positive polyA site\"\n"
//                            . "\t}\n");
//                    fwrite($configure_file,",\n"
//                            . "{\n"
//                            . "\t\t \"storeClass\" : \"JBrowse/Store/SeqFeature/BigWig\",\n"
//                            . "\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
//                            . "\t\t\"urlTemplate\" : \"./$value.UsrNegPA.bw\",\n"
//                            . "\t\t\"uniqueStoreName\" : {\n"
//                            . "\t\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
//                            . "\t\t\t\"urlTemplate\" : \"./$value.UsrNegPA.bw\",\n"
//                            . "\t\t},\n"
//                            . "\t\t\"style\" : {\n"
//                            . "\t\t\t\"pos_color\" : \"blue\",\n"
//                            . "\t\t\t\"neg_color\" : \"red\"\n"
//                            . "\t\t},\n"
//                            . "\t\t\"key\" : \"$value negative polyA site\",\n"
//                            . "\t\t\"autoscale\" : \"local\",\n"
//                            . "\t\t\"variance_band\" : false,\n"
//                            . "\t\t\"label\" : \"$value negative polyA site\"\n"
//                            . "\t}]}\n");
//                    fclose($configure_file);
                }
                foreach ($upload_name as $key => $value) {
                    mysql_query("select chr,strand,UPA_start,UPA_end,$value from db_user.PAC_".$_SESSION['file']." where $value>0 into outfile '/var/www/front/tojbrowse/$value.pac'");
                    rename("/var/www/front/tojbrowse/$value.pac", "/var/www/jbrowse/data/".$_SESSION['file']."/$value.pac");
                    shell_exec("./src/c/txt2bed ../jbrowse/data/".$_SESSION['file']."/$value.pac ../jbrowse/data/".$_SESSION['file']."/$value.bed");
                    shell_exec("../jbrowse/bin/flatfile-to-json.pl --bed ../jbrowse/data/".$_SESSION['file']."/$value.bed --trackLabel PAC_$value --out ../jbrowse/data/".$_SESSION['file']."/");
                    shell_exec("./src/c/jq '(.tracks[-1]) |= . +{\"menuTemplate\" : [{\"url\" : \"../../front/converse.php?species=".$_SESSION['species']."&chr={seq_id}&coord={start}&strand={strand}\",\"iconClass\" : \"digitIconDatabase\",\"action\" : \"newwindow\",\"label\" : \"seqence detail for this position\",\"title\" : \"seqence detail for this position\"}]}' /var/www/jbrowse/data/".$_SESSION['file']."/trackList.json > /var/www/jbrowse/data/".$_SESSION['file']."/trackList1.json");
                    unlink("../jbrowse/data/".$_SESSION['file']."/trackList.json");
                    rename("../jbrowse/data/".$_SESSION['file']."/trackList1.json", "../jbrowse/data/".$_SESSION['file']."/trackList.json");               
//                    $txt=file("../jbrowse/data/".$_SESSION['file']."/trackList.json");
//                    $configure_file=fopen("../jbrowse/data/".$_SESSION['file']."/trackList.json", "r+");
//                    rewind($configure_file);
//                    echo $txt[count($txt)-3];
//                    if(strlen($txt[count($txt)-3])==6){
//                        echo "short";
//                        fseek($configure_file, -106, SEEK_END);
//                         fwrite($configure_file,",\n"
//                            . "\t\"onClick\" : {\n"
//                            . "\t\t\"url\" : \"../front/sequence.php?chr={seq_id}&gene={start}&strand={strand}\",\n"
//                            . "\t\t\"label\" : \"see polyA site\",\n"
//                            . "\t\t\"action\" : \"newwindow\"\n"
//                            . "\t}}],"
//                                 . "   \"names\" : {\n"
//                                 . "      \"type\" : \"Hash\",\n"
//                                 . "      \"url\" : \"names/\"\n"
//                                 . "   }\n"
//                                 . "}\n");
//                    }
//                    elseif(strlen($txt[count($txt)-3])==8){
//                        echo "short";
//                        fseek($configure_file, -15, SEEK_END);
//                         fwrite($configure_file,",\n"
//                            . "\t\"onClick\" : {\n"
//                            . "\t\t\"url\" : \"../front/sequence.php?chr={seq_id}&gene={start}&strand={strand}\",\n"
//                            . "\t\t\"label\" : \"see polyA site\",\n"
//                            . "\t\t\"action\" : \"newwindow\"\n"
//                            . "\t}}],"
//                                 . "   \"names\" : {\n"
//                                 . "      \"type\" : \"Hash\",\n"
//                                 . "      \"url\" : \"names/\"\n"
//                                 . "   }\n"
//                                 . "}\n");
//                    }
//                    elseif(strlen($txt[count($txt)-3])==23){
//                        echo "middle";
//                        fseek($configure_file,-82,SEEK_END);
//                         fwrite($configure_file,",\n"
//                            . "\t\"onClick\" : {\n"
//                            . "\t\t\"url\" : \"../front/sequence.php?chr={seq_id}&gene={start}&strand={strand}\",\n"
//                            . "\t\t\"label\" : \"see polyA site\",\n"
//                            . "\t\t\"action\" : \"newwindow\"\n"
//                            . "\t}}],"
//                                 . "   \"names\" : {\n"
//                                 . "      \"type\" : \"Hash\",\n"
//                                 . "      \"url\" : \"names/\"\n"
//                                 . "   }\n"
//                                 . "}\n");
//                    }
//                    else{
//                        echo "Oh no\n";
//                        echo strlen($txt[count($txt)-2]);
//                    }
                    fclose($configure_file);
                }
            print_r(json_encode($_POST));
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
//                 echo"<pre>$test</pre>";
                  
//                 echo '<script>window.location.href="show_result.php";</script>';
//                 echo '<script>window.location.href="http://127.0.0.1/jbrowse/?data=data/'.$_SESSION['file'].'";</script>';
            ?>
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