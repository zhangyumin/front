        <?php
            $con=  mysql_connect("localhost","root","root");
             mysql_select_db("db_server",$con);
            session_start();
            #定义上传路径,文件名,允许上传文件类型,使用时间戳来保证文件名的唯一性.
            $tmppath="./data/".$_SESSION['tmp']."/";
            $_SESSION['file']=$_POST["species"].$_SESSION['tmp'];
            $_SESSION['species']=$_POST['species'];
            $filename=$_SESSION['file'].".fa";
            $filepath="./data/".$_SESSION['file']."/";
            
            #获得ip并存入数据库
            function getIP() { 
                if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) 
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
                else if (@$_SERVER["HTTP_CLIENT_IP"]) 
                    $ip = $_SERVER["HTTP_CLIENT_IP"]; 
                else if (@$_SERVER["REMOTE_ADDR"]) 
                    $ip = $_SERVER["REMOTE_ADDR"]; 
                else if (@getenv("HTTP_X_FORWARDED_FOR"))
                    $ip = getenv("HTTP_X_FORWARDED_FOR"); 
                else if (@getenv("HTTP_CLIENT_IP")) 
                    $ip = getenv("HTTP_CLIENT_IP"); 
                else if (@getenv("REMOTE_ADDR")) 
                    $ip = getenv("REMOTE_ADDR"); 
                else 
                    $ip = "Unknown"; 
                return $ip; 
            }
            $uip = getIP();
            $mysqltime=date('Y-m-d H:i:s',time());
            mysql_query("INSERT INTO `User_Task`(`id`, `species`, `ip`, `time`) VALUES ('".$_SESSION['file']."','".$_SESSION['species']."','$uip','$mysqltime')");
            //echo $filepath;
            #传递上传文件参数设置
//                $_SESSION['qct']=$_POST['qct'];
//                $_SESSION['mp']=$_POST['mp'];
//                $_SESSION['tailremove']=$_POST['tailremove'];
//                $_SESSION['aligner']=$_POST['aligner'];
//                $_SESSION['minlength']=$_POST['minlength'];
//                $_SESSION['rip']=$_POST['rip'];
//                $_SESSION['distance']=$_POST['distance'];
            if(!file_exists($tmppath)&&$_POST['sys_example']!='on')
            {
                 echo "<script type='text/javascript'>alert('upload sequence file first'); history.back();</script>";
            }
            else if($_POST['sys_example']=='on'){
                mkdir("./data/".$_SESSION['file']."/");
                chmod("./data/".$_SESSION['file']."/", 0777);
                mkdir("./result/".$_SESSION['file']."/");
                chmod("./result/".$_SESSION['file']."/", 0777);
                copy("./data/sys_example/arab.fastq", "./data/".$_SESSION['file']."/arab.fastq");
//                    echo '<script>window.location.href="get_result.php";</script>';
            }
            else 
            {
                    chmod($tmppath, 0777);
                    rename($tmppath, $filepath);
                    mkdir("./result/".$_SESSION['file']."/");
                    chmod("./result/".$_SESSION['file']."/", 0777);
                    $x=move_uploaded_file($_FILES["file"]["tmp_name"], $filepath.$filename);
//                        echo '<script>window.location.href="get_result.php";</script>';
                    #echo $x;
              }
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
            //读取group信息
            $usr_group = array();
            foreach ($_SESSION['file_real'] as $key => $value) {
                array_push($usr_group, $_POST['group-'.$value]);
            }
            $_SESSION['usr_group'] = $usr_group;
            file_put_contents("./result/".$_SESSION['file']."/group.txt", implode(";", $_SESSION['usr_group']));
            foreach($upload_name as $key => $value)
            {
                //step0:统一文件后缀名
                shell_exec("mv ./data/".$_SESSION['file']."/$file_name[$key] ./data/".$_SESSION['file']."/$value.fastq");
                //echo "step1:原始序列预处理";与物种无关

                $cmd1="fastq_quality_filter -q ".$_POST['qct']." -p ".$_POST['mp']." -v -Q 33 -i ./data/".$_SESSION['file']."/$value.fastq -o ./data/".$_SESSION['file']."/$value.qc.fa  >>./log/".$_SESSION['file'].".txt";
                #echo $cmd1;
                $out1=shell_exec($cmd1);
//                echo "<pre>$out1</pre>";

            //echo "step2:去除polyT/polyA tail";与物种无关
                if($_POST['tailremove']=='T')
                {
                    $cmd2="./src/perl/MAP_filterPolySeq.pl -s \"./data/".$_SESSION['file']."/$value.qc.fa\" -if fq -tl 8 -tr 20 -poly T -ml ".$_POST['minlength']." -qc T -of fq -st .noT.fa >>./log/".$_SESSION['file'].".txt";
                    #echo $cmd2;
                }
                else if($_POST['tailremove']=='A')
                {
                    $cmd2="./src/perl/MAP_filterPolySeq.pl -s \"./data/".$_SESSION['file']."/$value.qc.fa\" -if fq -tl 8 -tr 20 -poly A -ml ".$_POST['minlength']." -qc T -of fq -st .noT.fa >>./log/".$_SESSION['file'].".txt";
                }
                else if($_POST['tailremove']=='unknown')
                {
                    $grep_a=shell_exec("./src/fastq-tool/fastq-grep -c AAAAAAAA \"./data/".$_SESSION['file']."/$value.qc.fa \" ");
//                        echo "<pre>AAAAAAAA=$grep_a</pre>";
                    $grep_t=shell_exec("./src/fastq-tool/fastq-grep -c TTTTTTTT \"./data/".$_SESSION['file']."/$value.qc.fa \" ");
//                        echo "<pre>TTTTTTTT=$grep_t</pre>";
                    if($grep_a>=$grep_t)
                        $cmd2="./src/perl/MAP_filterPolySeq.pl -s \"./data/".$_SESSION['file']."/$value.qc.fa \" -if fq -tl 8 -tr 20 -poly A -ml ".$_POST['minlength']." -qc T -of fq -st .noT.fa >>./log/".$_SESSION['file'].".txt";
                    else
                        $cmd2="./src/perl/MAP_filterPolySeq.pl -s \"./data/".$_SESSION['file']."/$value.qc.fa \" -if fq -tl 8 -tr 20 -poly T -ml ".$_POST['minlength']." -qc T -of fq -st .noT.fa >>./log/".$_SESSION['file'].".txt";
                }
                $out2=  shell_exec($cmd2);
//                echo "<pre>$out2</pre>";

            //echo"step3: 序列比对";
                if($_POST['aligner']=='bowtie2')
                {
                    $cmd3="./src/bowtie2-2.2.4/bowtie2 -L 25 -N 0 -i S,1,1.15 --no-unal -x ./src/bowtie2-2.2.4/indexes/bwt2_".$_SESSION['species']." -q -U ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa -S ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam 2>>./log/".$_SESSION['file'].".txt";
                    #echo $cmd3;
                }
                else{
                    $cmd3="./src/bowtie2-2.2.4/bowtie2 -L 25 -N 0 -i S,1,1.15 --no-unal -x ./src/bowtie2-2.2.4/indexes/bwt2_".$_SESSION['species']." -q -U ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa -S ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam 2>>./log/".$_SESSION['file'].".txt";
                    #echo $cmd3;
                }
                $out3=  shell_exec($cmd3);
//                echo"<pre>$out3</pre>";

            //echo "step4:获取polyA位点";与物种无关
                $cmd4="./src/perl/PAT_parseSAM2PA_II.pl -sam ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam -m 30 -s 10 -ofile ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam.M30S10.PA 1>/dev/null ";
                #echo $cmd4;
                $out4=  shell_exec($cmd4);
//                 echo"<pre>$out4</pre>";

             if($_POST['rip']=='yes')
             {
                //echo"step5:去除internal priming";
                $cmd5="./src/perl/PAT_setIP.pl -itbl /var/www/front/data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam.M30S10.PA -otbl /var/www/front/data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam.M30S10.PA -flds 0:1:2 -format file -conf ./config/db_".$_SESSION['species'].".xml >>./log/".$_SESSION['file'].".txt";
                #echo $cmd5;
                $out5=  shell_exec($cmd5);
//                    echo"<pre>$out5</pre>";
             }

             //echo"step6:导入PA表到数据库";
                $cmd6="./src/perl/PAT_alterPA.pl -master db_user.PA_".$_SESSION['file']." -aptbl '/var/www/front/data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam.M30S10.PA' -apsmp  $value -format file -conf ./config/db_".$_SESSION['species'].".xml 1>/dev/null";
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
            //导出PAC列表
            mysql_query("select * from db_user.PAC_".$_SESSION['file']." into outfile '/var/www/front/searched/PAC_".$_SESSION['file'].".txt'");
            
             #PAT导入jbrowse显示
             //shell_exec("cp ./data/".$_SESSION['file']."/$file_real[0].qc.fa.noT.fa.sam.M30S10.PA ./tojbrowse/pat.txt");//移动文件
            shell_exec("cp -r ../jbrowse/data/".$_SESSION['species']."/ ../jbrowse/data/".$_SESSION['file']."/");
            shell_exec("chmod -R 777 ../jbrowse/data/".$_SESSION['file']."/");
            foreach ($upload_name as $key => $value) {
                 shell_exec("cp ./data/".$_SESSION['file']."/$value.qc.fa.noT.fa.sam.M30S10.PA ../jbrowse/data/".$_SESSION['file']."/$value.txt");
                 shell_exec("./src/c/txt2bedgraph ../jbrowse/data/".$_SESSION['file']."/$value.txt ../jbrowse/data/".$_SESSION['file']."/$value.positive.bedGraph ../jbrowse/data/".$_SESSION['file']."/$value.negative.bedGraph");
                 shell_exec("sort -k1,1 -k2,2n ../jbrowse/data/".$_SESSION['file']."/$value.positive.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.bedGraph ");
                 shell_exec("sort -k1,1 -k2,2n ../jbrowse/data/".$_SESSION['file']."/$value.negative.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.bedGraph ");
                 shell_exec("uniq -u ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.uniq.bedGraph");
                 shell_exec("uniq -u ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.bedGraph > ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.uniq.bedGraph");
                 shell_exec("./src/c/bedGraphToBigWig ../jbrowse/data/".$_SESSION['file']."/$value.positive.sorted.uniq.bedGraph ./src/".$_SESSION['species'].".sizes ../jbrowse/data/".$_SESSION['file']."/$value.UsrPosPA.bw");
                 shell_exec("./src/c/bedGraphToBigWig ../jbrowse/data/".$_SESSION['file']."/$value.negative.sorted.uniq.bedGraph ./src/".$_SESSION['species'].".sizes ../jbrowse/data/".$_SESSION['file']."/$value.UsrNegPA.bw");
                 shell_exec("./src/c/jq '(.tracks) |= . + [{\"storeClass\" : \"JBrowse/Store/SeqFeature/BigWig\",\"urlTemplate\" : \"'$value'.UsrPosPA.bw\",\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\"label\" : \"'$value' PAT  plus strand\",\"key\" : \"'$value' PAT  plus strand\",\"autoscale\" : \"local\",\"style\" : {\"neg_color\" : \"red\",\"pos_color\" : \"blue\"},\"variance_ban\" : false}]' /var/www/jbrowse/data/".$_SESSION['file']."/trackList.json > /var/www/jbrowse/data/".$_SESSION['file']."/trackList1.json");
                 shell_exec("./src/c/jq '(.tracks) |= . + [{\"storeClass\" : \"JBrowse/Store/SeqFeature/BigWig\",\"urlTemplate\" : \"'$value'.UsrNegPA.bw\",\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\"label\" : \"'$value' PAT minus strand\",\"key\" : \"'$value' PAT minus strand\",\"autoscale\" : \"local\",\"style\" : {\"neg_color\" : \"red\",\"pos_color\" : \"blue\"},\"variance_ban\" : false}]' /var/www/jbrowse/data/".$_SESSION['file']."/trackList1.json > /var/www/jbrowse/data/".$_SESSION['file']."/trackList2.json");
                 unlink("../jbrowse/data/".$_SESSION['file']."/trackList.json");
                 unlink("../jbrowse/data/".$_SESSION['file']."/trackList1.json");
                 rename("../jbrowse/data/".$_SESSION['file']."/trackList2.json", "../jbrowse/data/".$_SESSION['file']."/trackList.json");
//                 $configure_file=fopen("../jbrowse/data/".$_SESSION['file']."/trackList.json", "r+");
//                 if($key==0){
//                    fseek($configure_file, -98, SEEK_END);
//                 }
//                 else{
//                     fseek($configure_file, -3, SEEK_END);
//                 }
//                fwrite($configure_file,",\n"
//                        . "\t{\n"
//                        . "\t\t\"storeClass\" : \"JBrowse/Store/SeqFeature/BigWig\",\n"
//                        . "\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
//                        . "\t\t\"urlTemplate\" : \"./$value.UsrPosPA.bw\",\n"
//                        . "\t\t\"uniqueStoreName\" : {\n"
//                        . "\t\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
//                        . "\t\t\t\"urlTemplate\" : \"./$value.UsrPosPA.bw\",\n"
//                        . "\t\t},\n"
//                        . "\t\t\"style\" : {\n"
//                        . "\t\t\t\"pos_color\" : \"blue\",\n"
//                        . "\t\t\t\"neg_color\" : \"red\"\n"
//                        . "\t\t},\n"
//                        . "\t\t\"key\" : \"$value positive polyA site\",\n"
//                        . "\t\t\"autoscale\" : \"local\",\n"
//                        . "\t\t\"variance_band\" : false,\n"
//                        . "\t\t\"label\" : \"$value positive polyA site\"\n"
//                        . "\t}\n");
//                fwrite($configure_file,",\n"
//                        . "{\n"
//                        . "\t\t \"storeClass\" : \"JBrowse/Store/SeqFeature/BigWig\",\n"
//                        . "\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
//                        . "\t\t\"urlTemplate\" : \"./$value.UsrNegPA.bw\",\n"
//                        . "\t\t\"uniqueStoreName\" : {\n"
//                        . "\t\t\t\"type\" : \"JBrowse/View/Track/Wiggle/XYPlot\",\n"
//                        . "\t\t\t\"urlTemplate\" : \"./$value.UsrNegPA.bw\",\n"
//                        . "\t\t},\n"
//                        . "\t\t\"style\" : {\n"
//                        . "\t\t\t\"pos_color\" : \"blue\",\n"
//                        . "\t\t\t\"neg_color\" : \"red\"\n"
//                        . "\t\t},\n"
//                        . "\t\t\"key\" : \"$value negative polyA site\",\n"
//                        . "\t\t\"autoscale\" : \"local\",\n"
//                        . "\t\t\"variance_band\" : false,\n"
//                        . "\t\t\"label\" : \"$value negative polyA site\"\n"
//                        . "\t}]}\n");
//                fclose($configure_file);
            }
            foreach ($upload_name as $key => $value) {
                mysql_query("select chr,strand,UPA_start,UPA_end,$value from db_user.PAC_".$_SESSION['file']." where $value>0 into outfile '/var/www/front/tojbrowse/$value.pac'");
                rename("/var/www/front/tojbrowse/$value.pac", "/var/www/jbrowse/data/".$_SESSION['file']."/$value.pac");
                shell_exec("./src/c/txt2bed ../jbrowse/data/".$_SESSION['file']."/$value.pac ../jbrowse/data/".$_SESSION['file']."/$value.bed");
                shell_exec("../jbrowse/bin/flatfile-to-json.pl --bed ../jbrowse/data/".$_SESSION['file']."/$value.bed --trackLabel PAC_$value --out ../jbrowse/data/".$_SESSION['file']."/");
                shell_exec("./src/c/jq '(.tracks[-1]) |= . +{\"menuTemplate\" : [{\"url\" : \"../../front/converse.php?species=".$_SESSION['species']."&chr={seq_id}&coord={start}&strand={strand}\",\"iconClass\" : \"digitIconDatabase\",\"action\" : \"newwindow\",\"label\" : \"seqence detail for this position\",\"title\" : \"seqence detail for this position\"}]}' /var/www/jbrowse/data/".$_SESSION['file']."/trackList.json > /var/www/jbrowse/data/".$_SESSION['file']."/trackList1.json");
                unlink("../jbrowse/data/".$_SESSION['file']."/trackList.json");
                rename("../jbrowse/data/".$_SESSION['file']."/trackList1.json", "../jbrowse/data/".$_SESSION['file']."/trackList.json");
//                $txt=file("../jbrowse/data/".$_SESSION['file']."/trackList.json");
//                $configure_file=fopen("../jbrowse/data/".$_SESSION['file']."/trackList.json", "r+");
//                rewind($configure_file);
//                echo $txt[count($txt)-3];
//                if(strlen($txt[count($txt)-3])==6){
//                    echo "short";
//                    fseek($configure_file, -106, SEEK_END);
//                     fwrite($configure_file,",\n"
//                        . "\t\"onClick\" : {\n"
//                        . "\t\t\"url\" : \"../front/sequence.php?chr={seq_id}&gene={start}&strand={strand}\",\n"
//                        . "\t\t\"label\" : \"see polyA site\",\n"
//                        . "\t\t\"action\" : \"newwindow\"\n"
//                        . "\t}}],"
//                             . "   \"names\" : {\n"
//                             . "      \"type\" : \"Hash\",\n"
//                             . "      \"url\" : \"names/\"\n"
//                             . "   }\n"
//                             . "}\n");
//                }
//                elseif(strlen($txt[count($txt)-3])==8){
//                    echo "short";
//                    fseek($configure_file, -15, SEEK_END);
//                     fwrite($configure_file,",\n"
//                        . "\t\"onClick\" : {\n"
//                        . "\t\t\"url\" : \"../front/sequence.php?chr={seq_id}&gene={start}&strand={strand}\",\n"
//                        . "\t\t\"label\" : \"see polyA site\",\n"
//                        . "\t\t\"action\" : \"newwindow\"\n"
//                        . "\t}}],"
//                             . "   \"names\" : {\n"
//                             . "      \"type\" : \"Hash\",\n"
//                             . "      \"url\" : \"names/\"\n"
//                             . "   }\n"
//                             . "}\n");
//                }
//                elseif(strlen($txt[count($txt)-3])==23){
//                    echo "middle";
//                    fseek($configure_file,-82,SEEK_END);
//                     fwrite($configure_file,",\n"
//                        . "\t\"onClick\" : {\n"
//                        . "\t\t\"url\" : \"../front/sequence.php?chr={seq_id}&gene={start}&strand={strand}\",\n"
//                        . "\t\t\"label\" : \"see polyA site\",\n"
//                        . "\t\t\"action\" : \"newwindow\"\n"
//                        . "\t}}],"
//                             . "   \"names\" : {\n"
//                             . "      \"type\" : \"Hash\",\n"
//                             . "      \"url\" : \"names/\"\n"
//                             . "   }\n"
//                             . "}\n");
//                }
//                else{
//                    echo "Oh no\n";
//                    echo strlen($txt[count($txt)-2]);
//                }
//                fclose($configure_file);
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
//             echo"<pre>$test</pre>";
                  
//                 echo '<script>window.location.href="task_summary.php";</script>';
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