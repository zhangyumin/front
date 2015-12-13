<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php
            $con=  mysql_connect("localhost","root","root");
            mysql_select_db("db_server",$con);
            session_start();
            $ftr_start=array();
            $ftr_end=array();
            $sutr_start=array();
            $sutr_end=array();
            $wutr_start=array();
            $wutr_end=array();
            $intron_start=array();
            $intron_end=array();
            $exon_start=array();
            $exon_end=array();
            $amb_start=array();
            $amb_end=array();
            $cds_start=array();
            $cds_end=array();
            $ftr_start_org=array();
            $ftr_end_org=array();
            $sutr_start_org=array();
            $sutr_end_org=array();
            $wutr_start_org=array();
            $wutr_end_org=array();
            $intron_start_org=array();
            $intron_end_org=array();
            $exon_start_org=array();
            $exon_end_org=array();
            $amb_start_org=array();
            $amb_end_org=array();
            $cds_start_org=array();
            $cds_end_org=array();
            $samples=array();
            $statistics_samples=array();
            $sample_selected=array();
            $num=0;
            //读取sample个数和名称
            foreach ($_SESSION['sample'] as $key => $value){
                $array_sample=  explode("_", $value);
                $sam=  implode(" ", $array_sample);
                array_push( $sample_selected,$sam);
            }
//            var_dump($sample_selected);
            $sample=  mysql_query("select label from t_sample_desc where species='".$_GET['species']."';");
            while ($sample_num=  mysql_fetch_row($sample)){
                $num++;
                array_push($samples,$sample_num[0]);
            }
            $statistics_sample=  mysql_query("select distinct lbl_group from t_sample_desc where species='".$_GET['species']."';");
            while ($statistics_sample_num=  mysql_fetch_row($statistics_sample)){
                array_push($statistics_samples,$statistics_sample_num[0]."_sum");
                array_push($statistics_samples,$statistics_sample_num[0]."_average");
                array_push($statistics_samples,$statistics_sample_num[0]."_median");
            }
            $statistics_num = count($statistics_samples);
            var_dump($statistics_samples);
            //声明存储各个sample的loc,talbe,col和tagnum数组
            for($i=1;$i<=$num;$i++){
                $pa_loc="pa_loc".$i;
                $$pa_loc=array();
                $pa_tagnum="pa_tagnum".$i;
                $$pa_tagnum=array();
                $pac_loc="pac_loc".$i;
                $$pac_loc=array();
                $pac_tagnum="pac_tagnum".$i;
                $$pac_tagnum=array();
            }
            //声明存储各个sample的loc,talbe,col和tagnum数组(statistics)
            for($i=1;$i<=$statistics_num;$i++){
                $statistics_pa_loc="statistics_pa_loc".$i;
                $$statistics_pa_loc=array();
                $statistics_pa_tagnum="statistics_pa_tagnum".$i;
                $$statistics_pa_tagnum=array();
                $statistics_pac_loc="statistics_pac_loc".$i;
                $$statistics_pac_loc=array();
                $statistics_pac_tagnum="statistics_pac_tagnum".$i;
                $$statistics_pac_tagnum=array();
            }
            $seq=$_GET['seq'];
            $chr=$_GET['chr'];
            $strand=$_GET['strand'];
            //各部分坐标推入数组
            //非延长
            $result_org= mysql_query("select * from t_".$_GET['species']."_gff_org where gene='$seq' order by ftr_end;");
            while($row_org=  mysql_fetch_row($result_org)){
                array_push($ftr_start_org, $row_org[3]);
                array_push($ftr_end_org,$row_org[4]);
                if($row_org[2]=='3UTR'){
                    array_push($sutr_start_org, $row_org[3]);
                    array_push($sutr_end_org, $row_org[4]);
                }
                elseif($row_org[2]=='5UTR'){
                    array_push($wutr_start_org, $row_org[3]);
                    array_push($wutr_end_org, $row_org[4]);
                }
                elseif($row_org[2]=='intron'){
                    array_push($intron_start_org, $row_org[3]);
                    array_push($intron_end_org, $row_org[4]);
                }
                elseif($row_org[2]=='exon'){
                    array_push($exon_start_org, $row_org[3]);
                    array_push($exon_end_org, $row_org[4]);
                }
                elseif($row_org[2]=='AMB'){
                    array_push($amb_start_org, $row_org[3]);
                    array_push($amb_end_org, $row_org[4]);
                }
                elseif($row_org[2]=='CDS'){
                    array_push($cds_start_org, $row_org[3]);
                    array_push($cds_end_org, $row_org[4]);
                }
            }
            $gene_start_org=  min($ftr_start_org);
            $gene_end_org= max($ftr_end_org);
            //延长3UTR部分
            $result= mysql_query("select * from t_".$_GET['species']."_gff where gene='$seq' order by ftr_end;");
            while($row=  mysql_fetch_row($result)){
                array_push($ftr_start, $row[3]);
                array_push($ftr_end,$row[4]);
                if($row[2]=='3UTR'){
                    array_push($sutr_start, $row[3]);
                    array_push($sutr_end, $row[4]);
                }
                elseif($row[2]=='5UTR'){
                    array_push($wutr_start, $row[3]);
                    array_push($wutr_end, $row[4]);
                }
                elseif($row[2]=='intron'){
                    array_push($intron_start, $row[3]);
                    array_push($intron_end, $row[4]);
                }
                elseif($row[2]=='exon'){
                    array_push($exon_start, $row[3]);
                    array_push($exon_end, $row[4]);
                }
                elseif($row[2]=='AMB'){
                    array_push($amb_start, $row[3]);
                    array_push($amb_end, $row[4]);
                }
                elseif($row[2]=='CDS'){
                    array_push($cds_start, $row[3]);
                    array_push($cds_end, $row[4]);
                }
            }
            $gene_start=  min($ftr_start);
            $gene_end= max($ftr_end);
            if($_GET['intergenic']==1){
                $gene_start=$_GET['coord']-200;
                $gene_end=$_GET['coord']+200;
            }
            $genelength=$gene_end-$gene_start;
            $rate=1000/$genelength;
            $i=1;
            $j=0;
            $sum_samples_name_array=array();
            $statistics_sum_samples_array=array();
            $pa_table= mysql_query("select distinct PA_table from t_sample_desc where species='".$_GET['species']."';");
            while ($pa_table_row=  mysql_fetch_row($pa_table)){
                $samples_name_array=array();
                $statistics_samples_name_array=array();
                $j++;
                //按表名搜出字段名
                if($j == 2)
                    $pa_colname_result=  mysql_query("select PA_col,lbl_group from t_sample_desc where PA_table = '$pa_table_row[0]' order by PA_col;");
                else
                    $pa_colname_result=  mysql_query("select PA_col,lbl_group from t_sample_desc where PA_table = '$pa_table_row[0]';");
                while($pa_colname_row = mysql_fetch_row($pa_colname_result) ){
                    array_push($samples_name_array, $pa_colname_row[0]);
                    array_push($sum_samples_name_array, $pa_colname_row[0]);
                    array_push($statistics_samples_name_array, $pa_colname_row[1]);
                }
                //普通PA位点数据
                $samples_name = implode(",", $samples_name_array);
                $pa_result=  mysql_query("select coord,$samples_name from $pa_table_row[0] where chr='$chr' and coord>=$gene_start and coord<=$gene_end;");
                while ($pa_row=  mysql_fetch_row($pa_result)){
                    if($j==1){
                        for($i=1;$i<=count($pa_row)-1;$i++){
                            $pa_loc="pa_loc".$i;
                            $pa_tagnum="pa_tagnum".$i;
                            array_push($$pa_loc, $pa_row[0]);
                            array_push($$pa_tagnum, $pa_row[$i]);
//                            var_dump($i);
                        }
                        $key=$i;
                    }
                    else if($j==2){
                        for($i=$key;$i<=$num;$i++){
                            $pa_loc="pa_loc".$i;
                            $pa_tagnum="pa_tagnum".$i;
                            $r=$i-8;
                            array_push($$pa_loc, $pa_row[0]);
                            array_push($$pa_tagnum, $pa_row[$r]);
//                            var_dump($i);
                        }
                    }
                }
                //统计后PA位点数据
                $statistics_samples_array=array();
                $statistics_samples_name_array = array_unique($statistics_samples_name_array);
                foreach ($statistics_samples_name_array as $key1 => $value1) {
                    array_push($statistics_samples_array, $value1."_sum");
                    array_push($statistics_samples_array, $value1."_avg");
                    array_push($statistics_samples_array, $value1."_med");
                    array_push($statistics_sum_samples_array, $value1."_sum");
                    array_push($statistics_sum_samples_array, $value1."_avg");
                    array_push($statistics_sum_samples_array, $value1."_med");
                }
//                var_dump($statistics_sum_samples_array);
                $statistics_samples_name = implode(",", $statistics_samples_array);
                $statistics_pa_result=  mysql_query("select coord,$statistics_samples_name from $pa_table_row[0] where chr='$chr' and coord>=$gene_start and coord<=$gene_end;");
                while ($statistics_pa_row=  mysql_fetch_row($statistics_pa_result)){
                    if($j==1){
                        for($i=1;$i<=count($statistics_pa_row)-1;$i++){
                            $statistics_pa_loc="statistics_pa_loc".$i;
                            $statistics_pa_tagnum="statistics_pa_tagnum".$i;
                            array_push($$statistics_pa_loc, $statistics_pa_row[0]);
                            array_push($$statistics_pa_tagnum, $statistics_pa_row[$i]);
//                            var_dump($i);
                        }
                    }
                    else if($j==2){
                        for($i=$key;$i<=$statistics_num;$i++){
                            $statistics_pa_loc="statistics_pa_loc".$i;
                            $statistics_pa_tagnum="statistics_pa_tagnum".$i;
                            array_push($$statistics_pa_loc, $statistics_pa_row[0]);
                            array_push($$statistics_pa_tagnum, $statistics_pa_row[$i-9]);
//                            var_dump($i);
                        }
                    }
                }
            }
//            var_dump($statistics_sum_samples_name_array);
            
//            for($i=1;$i<=$num;$i++){
//                        $pa_loc="pa_loc".$i;
//                        $pa_tagnum="pa_tagnum".$i;
//                        if($i==5){
//                            var_dump($$pa_loc);
//                            var_dump($$pa_tagnum);  
//                        }
//            }
//                for($i=1;$i<=count($statistics_sum_samples_array);$i++){
//                  $pa_loc="statistics_pa_loc".$i;
//                  $pa_tagnum="statistics_pa_tagnum".$i;
//                  if($i==15){
//                      var_dump($$statistics_pa_loc);
//                      var_dump($$statistics_pa_tagnum);  
//                  }
//                }
            $sum_samples_name = implode(",", $sum_samples_name_array);
            $pac_result=  mysql_query("select chr,strand,coord,tot_tagnum,ftr,ftr_start,ftr_end,transcript,gene,gene_type,UPA_start,UPA_end,tot_PAnum,ref_tagnum,$sum_samples_name from t_".$_GET['species']."_pac where gene='$seq' order by coord;");
//            var_dump($pac_result);
            while($pac_row=  mysql_fetch_row($pac_result)){
                for($i=1;$i<=$num;$i++){
                    $pac_loc="pac_loc".$i;
                    $pac_tagnum="pac_tagnum".$i;
                    $r=$i+13;
                    array_push($$pac_tagnum, $pac_row[$r]);
                    array_push($$pac_loc, $pac_row[2]);
                }
            }
        ?>
        <script type="text/javascript">
            <?php 
                echo "var genelength=$genelength;";
            ?>
            window.onload = function(){
                info("#42A881",100,"3'utr","gene");
                info("#8C5E4D",200,"5'utr","gene");
                info("#00ABD8",300,"cds","gene");
                info("#D390B9",400,"intron","gene");
                info("#65D97D",500,"exon","gene");
                info("#F35A4A",600,"amb","gene");
                info("#9FE0F6",700,"3'utr extend","gene");
//                line("gene");
//                line("no_extend");
                grid("no_extend","1");
                grid("gene","title");
                <?php
                //extend部分
                    foreach ($sutr_start as $key => $value) {
                        $start=($sutr_start[$key]-$gene_start)*$rate;
                        $end=($sutr_end[$key]-$gene_start)*$rate;
                        echo "sutr($start,$end,0,1000,$strand,'gene');\n";
                    }
                    foreach ($wutr_start as $key => $value) {
                        $start=($wutr_start[$key]-$gene_start)*$rate;
                        $end=($wutr_end[$key]-$gene_start)*$rate;
                        echo "wutr($start,$end,0,1000,$strand,'gene');\n";
                    }
                    foreach ($intron_start as $key => $value) {
                        $start=($intron_start[$key]-$gene_start)*$rate;
                        $end=($intron_end[$key]-$gene_start)*$rate;
                        echo "intron($start,$end,0,1000,$strand,'gene');\n";
                    }
                    foreach ($exon as $key => $value) {
                        $start=($exon_start[$key]-$gene_start)*$rate;
                        $end=($exon_end[$key]-$gene_start)*$rate;
                        echo "exon($start,$end,0,1000,$strand,'gene');\n";
                    }
                    foreach ($cds_start as $key => $value) {
                        $start=($cds_start[$key]-$gene_start)*$rate;
                        $end=($cds_end[$key]-$gene_start)*$rate;
                        echo "cds($start,$end,0,1000,$strand,'gene');\n";
                    }
                    foreach ($amb_start as $key => $value) {
                        $start=($amb_start[$key]-$gene_start)*$rate;
                        $end=($amb_end[$key]-$gene_start)*$rate;
                        echo "amb($start,$end,0,1000,$strand,'gene');\n";
                    }
                    for($i=1;$i<=$num;$i++){
                        $pa_loc="pa_loc".$i;
                        $pa_tagnum="pa_tagnum".$i;
                        $pac_loc="pac_loc".$i;
                        $pac_tagnum="pac_tagnum".$i;
                        foreach ($$pa_tagnum as $key => $value) {
                            $loc=(${$pa_loc}[$key]-$gene_start)*$rate;
                            echo "pa($loc,$value,'sample$i');\n";
                        }
                        foreach ($$pac_tagnum as $key => $value) {
                            $loc=(${$pac_loc}[$key]-$gene_start)*$rate;
                            echo "pac($loc,$value,'sample$i');\n";
                        }
                    }
                    for($i=1;$i<=$statistics_num;$i++){
                        $statistics_pa_loc="statistics_pa_loc".$i;
                        $statistics_pa_tagnum="statistics_pa_tagnum".$i;
//                        $pac_loc="statistics_pac_loc".$i;
//                        $pac_tagnum="statistics_pac_tagnum".$i;
                        foreach ($$statistics_pa_tagnum as $key => $value) {
                            $loc=(${$statistics_pa_loc}[$key]-$gene_start)*$rate;
                            echo "pa($loc,$value,'statistics_sample$i');\n";
                        }
                        foreach ($$statistics_pac_tagnum as $key => $value) {
                            $loc=(${$statistics_pac_loc}[$key]-$gene_start)*$rate;
                            echo "pac($loc,$value,'statistics_sample$i');\n";
                        }
                    }
                    //非extend部分
                    //shorten部分头与尾
                    $st=($gene_start_org-$gene_start)*$rate;
                    $en=($gene_end_org-$gene_start)*$rate;
                    foreach ($sutr_start_org as $key => $value) {
                        $st_st=($sutr_start[$key]-$gene_start)*$rate;
                        $start=($sutr_start_org[$key]-$gene_start)*$rate;
                        $st_ed=($sutr_end[$key]-$gene_start )*$rate;
                        $end=($sutr_end_org[$key]-$gene_start)*$rate;
                        echo "sutr($start,$end,$st,$en,$strand,'no_extend');\n";
                        if($strand==-1){
                            echo "sutr_extend($st_st,$start,0,1000,-1,'gene');\n";
                        }
                        else if($strand==1){
                            echo "sutr_extend($end,$st_ed,0,1000,1,'gene');\n";
                        }
                    }
                    foreach ($wutr_start_org as $key => $value) {
                        $start=($wutr_start_org[$key]-$gene_start)*$rate;
                        $end=($wutr_end_org[$key]-$gene_start)*$rate;
                        echo "wutr($start,$end,$st,$en,$strand,'no_extend');\n";
                    }
                    foreach ($intron_start_org as $key => $value) {
                        $start=($intron_start_org[$key]-$gene_start)*$rate;
                        $end=($intron_end_org[$key]-$gene_start)*$rate;
                        echo "intron($start,$end,$st,$en,$strand,'no_extend');\n";
                    }
                    foreach ($exon_org as $key => $value) {
                        $start=($exon_start_org[$key]-$gene_start)*$rate;
                        $end=($exon_end_org[$key]-$gene_start)*$rate;
                        echo "exon($start,$end,$st,$en,$strand,'no_extend');\n";
                    }
                    foreach ($cds_start_org as $key => $value) {
                        $start=($cds_start_org[$key]-$gene_start)*$rate;
                        $end=($cds_end_org[$key]-$gene_start)*$rate;
                        echo "cds($start,$end,$st,$en,$strand,'no_extend');\n";
                    }
                    foreach ($amb_start_org as $key => $value) {
                        $start=($amb_start_org[$key]-$gene_start)*$rate;
                        $end=($amb_end_org[$key]-$gene_start)*$rate;
                        echo "amb($start,$end,$st,$en,$strand,'no_extend');\n";
                    }
                ?>
                arrow("gene",<?php echo $_GET['strand'];?>);
                shorten_arrow("no_extend",<?php echo ($gene_start_org-$gene_start)*$rate;?>,<?php echo ($gene_end_org-$gene_start)*$rate;?>,<?php echo $_GET['strand'];?>);
                <?php
                    for($i=1;$i<=$num;$i++){
                        $r=$i-1;
                        echo "line(\"sample$i\");";
                        echo "title(\"#000000\",\"$samples[$r]\",\"sample$i\");";
                        echo "yscale(\"sample$i\");";
                        echo "grid(\"sample$i\",\"sample\");";
                    }
                    for($i=1;$i<=$statistics_num;$i++){
                        $r=$i-1;
                        echo "line(\"statistics_sample$i\");";
                        echo "title(\"#000000\",\"$statistics_samples[$r]\",\"statistics_sample$i\");";
                        echo "yscale(\"statistics_sample$i\");";
                        echo "grid(\"statistics_sample$i\",\"statistics_sample\");";
                    }
                    if($_GET['intergenic']==1)
                        echo "intergenic($strand,\"gene\");"
                ?>
                title("#000000","<?php echo $seq;?>","gene");
                xscale("gene");
                title("#000000","3UTR Shorten","no_extend");
//                xscale("3utr_extend");
            }
            function title(color,text,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle=color;
                context.font="bold 15px Droid Serif";
                context.fillText(text,510,145);
            }
            function info(color,loc,text,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle=color;
                context.font="bold 15px Droid Serif";
                context.fillRect(loc+70,20,20,20);
                context.fillText(text,loc+95,35);
            }
            function line(id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.strokeStyle="#000000";
                context.fillStyle="#000000";//line为黑色
                context.beginPath();
                context.moveTo(0,100);
                context.lineTo(1000,100);
                context.stroke();
            }
            function xscale(id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.beginPath();
                context.strokeStyle="#000000";
               context.fillStyle="#000000";//x刻度尺为黑色
                context.moveTo(0,60);
                context.lineTo(1000,60);
                context.moveTo(0,60);
                context.lineTo(10,50);
                context.moveTo(0,60);
                context.lineTo(10,70);
                context.moveTo(1000,60);
                context.lineTo(990,50);
                context.moveTo(1000,60);
                context.lineTo(990,70);
                context.font="12px Droid Serif";
                start=<?php echo $gene_start;?>;
                end=<?php echo $gene_end;?>;
                context.fillText("start:"+start,15,75);
                context.fillText(end+":end",915,75);
                for(i=1;i<10;i++){
                    x=<?php echo round(($gene_end-$gene_start)/10); ?>;
                    context.fillText(start+x*i,100*i-20,55);
                }
                context.stroke();
            }
            function grid(id,type){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.beginPath();
                context.strokeStyle="#BEAD92";
                context.fillStyle="#BEAD92";//x
                if(type=='title'){
                    for(i=1;i<10;i++){
                        context.moveTo(100*i,60);
                        context.lineTo(100*i,150);
                    }
                }
                else{
                    for(i=1;i<10;i++){
                        context.moveTo(100*i,0);
                        context.lineTo(100*i,150);
                    }
                }
                context.stroke();
            }
            function yscale(id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.beginPath();
                context.strokeStyle="#000000";
                context.fillStyle="#000000";//y刻度尺为黑色
                context.moveTo(500,100);
                context.lineTo(500,0);
                for(i=4;i>=0;i--){
                    context.moveTo(500,20*i);
                    context.lineTo(505,20*i);
                    context.font="12px Droid Serif";
                    context.fillText(50-10*i,505,21*i);
                }
                context.stroke();
            }
            function sutr(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
//                context.fillStyle="#ff0000";//3utr为红色
                context.fillStyle="#42A881";
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,95,endpos-startpos-10,10);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,95,endpos-startpos,10);
                }
                else{
                    context.fillRect(startpos,95,endpos-startpos,10);
                }
            }
            function sutr_extend(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
//                context.fillStyle="#1c86ee";//3utr_extend为蓝色
                context.fillStyle="#9FE0F6"
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,95,endpos-startpos-10,10);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,95,endpos-startpos-10,10);
                }
                else{
                    context.fillRect(startpos,95,endpos-startpos,10);
                }
            }
            function wutr(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#8C5E4D";//5utr为zise
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,95,endpos-startpos-10,10);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,95,endpos-startpos-10,10);
                }
                else{
                    context.fillRect(startpos,95,endpos-startpos,10);
                }
            }
            function cds(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#00ABD8";//cds为绿色
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,90,endpos-startpos-10,20);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,90,endpos-startpos-10,20);
                }
                else{
                    context.fillRect(startpos,90,endpos-startpos,20);
                }
            }
            function intron(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#D390B9";//intron为黑色
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,95,endpos-startpos-10,10);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,95,endpos-startpos-10,10);
                }
                else{
                    context.fillRect(startpos,95,endpos-startpos,10);
                }
            }
            function exon(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#65D97D";//exon为黄色
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,90,endpos-startpos-10,20);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,90,endpos-startpos-10,20);
                }
                else{
                    context.fillRect(startpos,90,endpos-startpos,20);
                }
            }
            function amb(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#F35A4A";//amb为兰色
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,90,endpos-startpos-10,20);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,90,endpos-startpos-10,20);
                }
                else{
                    context.fillRect(startpos,90,endpos-startpos,20);
                }
            }
            function intergenic(strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#7BA3A8";//intergenic为灰色
                if(strand==1){
                    context.fillRect(0,90,990,20);
                }
                else{
                    context.fillRect(10,90,1000,20);
                }
            }
            function arrow(id,strand){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.beginPath();
                if(strand==1){
                    context.moveTo(990,105);
                    context.lineTo(995,105);
                    context.lineTo(1000,100);
                    context.lineTo(995,95);
                    context.lineTo(990,95);
                    context.lineTo(990,105);
                }
                else if(strand==-1){
                    context.moveTo(0,100);
                    context.lineTo(5,105);
                    context.lineTo(10,105);
                    context.lineTo(10,95);
                    context.lineTo(5,95);
                    context.lineTo(0,100);
                }
                context.closePath();
                context.fillStyle="#878787";
                context.fill();
            }
            function shorten_arrow(id,start,end,strand){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.beginPath();
                if(strand==1){
                    context.moveTo(end-10,105);
                    context.lineTo(end-5,105);
                    context.lineTo(end,100);
                    context.lineTo(end-5,95);
                    context.lineTo(end-10,95);
                    context.lineTo(end-10,105);
                }
                else if(strand==-1){
                    context.moveTo(start,100);
                    context.lineTo(start+5,105);
                    context.lineTo(start+10,105);
                    context.lineTo(start+10,95);
                    context.lineTo(start+5,95);
                    context.lineTo(start,100);
                }
                context.closePath();
                context.fillStyle="#878787";
                context.fill();
            } 
            function pa(loc,tagnum,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.strokeStyle="#000";//pa为黑色
                context.beginPath();
                context.moveTo(loc,100);
                if(tagnum>50){
                    context.lineTo(loc,0);
                }
                else{
                    context.lineTo(loc,100-2*tagnum);
                }
                context.stroke();
            }
            var i = 0;
            function pac(loc,tagnum,id){
                i++;
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.beginPath();
                context.font="10px Droid Serif";
                context.fillStyle="#ff0000";//pac为红色
                if(tagnum>50){
                    context.fillRect(loc,100,3,-100);
                    if(i%2==1)
                        context.fillText("PAT:"+tagnum,loc-20,110);
                    else
                        context.fillText("PAT:"+tagnum,loc-20,125);
                }
                else if(tagnum==0){
                    i--;
                }
                else{
                    context.fillRect(loc,100,3,-2*tagnum);
                    if(i%2==1)
                        context.fillText("PAT:"+tagnum,loc-20,110);
                    else
                        context.fillText("PAT:"+tagnum,loc-20,125);
                }
            }
        </script>
    </head>
    <body>
        <canvas id="gene" width="1000px;" height="150px;"></canvas><br>
        <canvas id='no_extend' width="1000px" height="150px;"></canvas><br>
        <?php
            for($i=1;$i<=$num;$i++){
                if($i%2==0)
                    echo "<canvas id=\"sample$i\" width=\"1000px; \" height=\"150px;\"></canvas><br>";
                else
                    echo "<canvas id=\"sample$i\" width=\"1000px; \" height=\"150px;\" style=\"background-color:#f1f1f1\"></canvas><br>";
            }
            for($i=1;$i<=$statistics_num;$i++){
                 echo "<canvas id=\"statistics_sample$i\" width=\"1000px; \" height=\"150px;\"></canvas><br>";
            }
        ?>
    </body>
</html>
