<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
         <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
            if(isset($_SESSION['sample'])&&$_GET['analysis']==1){
                while ($sample_num=  mysql_fetch_row($sample)){
                    if(in_array($sample_num[0], $sample_selected)){
                        $num++; 
                        array_push($samples,$sample_num[0]);
                    }
                }
            }
            else{
                while ($sample_num=  mysql_fetch_row($sample)){
                    $num++;
                    array_push($samples,$sample_num[0]);
                }
            }
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
            $pa_table= mysql_query("select distinct PA_table from t_sample_desc where species='".$_GET['species']."';");
            while ($pa_table_row=  mysql_fetch_row($pa_table)){
                $j++;
                $pa_result=  mysql_query("select * from $pa_table_row[0] where chr='$chr' and coord>=$gene_start and coord<=$gene_end;");
                while ($pa_row=  mysql_fetch_row($pa_result)){
                    if($j==1){
                        for($i=1;$i<=count($pa_row)-4;$i++){
                            $pa_loc="pa_loc".$i;
                            $pa_tagnum="pa_tagnum".$i;
                            $r=$i+3;
                            array_push($$pa_loc, $pa_row[2]);
                            array_push($$pa_tagnum, $pa_row[$r]);
//                            var_dump($i);
                        }
                        $key=$i;
                    }
                    else if($j==2){
                        for($i=$key;$i<=$num;$i++){
                            $pa_loc="pa_loc".$i;
                            $pa_tagnum="pa_tagnum".$i;
                            $r=$i-5;
                            array_push($$pa_loc, $pa_row[2]);
                            array_push($$pa_tagnum, $pa_row[$r]);
//                            var_dump($i);
                        }
                    }
                }
            }
//            for($i=1;$i<=$num;$i++){
//                        $pa_loc="pa_loc".$i;
//                        $pa_tagnum="pa_tagnum".$i;
//                        if($i==1){
//                            var_dump($$pa_loc);
//                            var_dump($$pa_tagnum);
//                        }
//            }
            $pac_result=  mysql_query("select * from t_".$_GET['species']."_pac where gene='$seq';");
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
                info("#ff0000",100,"3UTR","gene");
                info("#BA55D3",200,"5UTR","gene");
                info("#9AFF9A",300,"CDS","gene");
                info("#080808",400,"INTRON","gene");
                info("#eeee00",500,"EXON","gene");
                info("#97ffff",600,"AMB","gene");
                info("#1c86ee",700,"3UTR EXTEND","gene");
                line("gene");
                line("no_extend");
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
                    if($_GET['intergenic']==1)
                        echo "intergenic($strand,\"gene\");"
                ?>
                title("#000000","<?php echo $seq;?>","gene");
                xscale("gene");
                grid("gene","title");
                title("#000000","3UTR Shorten","no_extend");
//                xscale("3utr_extend");
                grid("no_extend","1");
            }
            function title(color,text,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle=color;
                context.font="20px Arial";
                context.fillText(text,500,145);
            }
            function info(color,loc,text,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle=color;
                context.fillRect(loc+70,20,20,20);
                context.fillText(text,loc+95,35);
            }
            function line(id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.strokeStyle="#000000";
                context.fillStyle="#000000";//line为黑色
                context.moveTo(0,100);
                context.lineTo(1000,100);
                context.stroke();
            }
            function xscale(id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
//                context.strokeStyle="#000000";
//                context.fillStyle="#000000";//x刻度尺为黑色
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
                context.font="10px Arial";
                start=<?php echo $gene_start;?>;
                end=<?php echo $gene_end;?>;
                context.fillText(start,10,50);
                context.fillText(end,970,50);
                for(i=1;i<10;i++){
                    x=<?php echo round(($gene_end-$gene_start)/10); ?>;
                    context.fillText(start+x*i,100*i-10,55);
                }
                context.stroke();
            }
            function grid(id,type){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.beginPath();
                context.strokeStyle="#00ff00";
                context.fillStyle="#00ff00";//x
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
                context.strokeStyle="#000000";
                context.fillStyle="#000000";//y刻度尺为黑色
                context.moveTo(0,100);
                context.lineTo(0,0);
                for(i=4;i>0;i--){
                    context.moveTo(0,20*i);
                    context.lineTo(5,20*i);
                    context.font="10px Arial";
                    context.fillText(50-10*i,5,21*i);
                }
                context.stroke();
            }
            function sutr(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#ff0000";//3utr为红色
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
                context.fillStyle="#1c86ee";//3utr_extend为蓝色
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
                context.fillStyle="#BA55D3";//5utr为zise
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
                context.fillStyle="#9AFF9A";//cds为绿色
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
                context.fillStyle="#080808";//intron为黑色
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
                context.fillStyle="#eeee00";//exon为黄色
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
                context.fillStyle="#97ffff";//amb为兰色
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
                context.fillStyle="#7a8b8b";//intergenic为灰色
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
                context.strokeStyle="#878787";//pa为黑色
                context.moveTo(loc,100);
                if(tagnum>50){
                    context.lineTo(loc,0);
                }
                else{
                    context.lineTo(loc,100-2*tagnum);
                }
                context.stroke();
            }
            function pac(loc,tagnum,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#ff0000";//pac为红色
                if(tagnum>50){
                    context.fillRect(loc,100,3,-100);
                    context.fillText("PAT:"+tagnum,loc-20,110);
                }
                else if(tagnum==0){
                    
                }
                else{
                    context.fillRect(loc,100,3,-2*tagnum);
                    context.fillText("PAT:"+tagnum,loc-20,110);
                }
            }
        </script>
    </head>
    <body style="zoom: 95%">
        <canvas id="gene" width="1000px;" height="150px;"></canvas>
        <canvas id='no_extend' width="1000px" height="150px;"></canvas>
        <?php
            for($i=1;$i<=$num;$i++){
                if($i%2==0)
                    echo "<canvas id=\"sample$i\" width=\"1000px; \" height=\"150px;\"></canvas>";
                else
                    echo "<canvas id=\"sample$i\" width=\"1000px; \" height=\"150px;\" style=\"background-color:#f9f9f9\"></canvas>";
            }
        ?>
        <div id="pactagnum" style="height:400px;width:1000px;border:1px solid #ccc;padding:10px;"></div>
        <script src="src/dist/echarts.js"></script>  

            <script type="text/javascript">  
            // Step:3 为模块加载器配置echarts的路径，从当前页面链接到echarts.js，定义所需图表路径  
            require.config({  
                paths: {  
                    echarts: 'src/dist/'  
                }  
            });  

            // Step:4 require echarts and use it in the callback.  
            // Step:4 动态加载echarts然后在回调函数中开始使用，注意保持按需加载结构定义图表路径  
            require(  
                [  
                    //这里的'echarts'相当于'./js'  
                    'echarts',  
                    'echarts/chart/bar',  
                    'echarts/chart/line',  
                ],  
                //创建ECharts图表方法  
                function (ec) {   
                        //基于准备好的dom,初始化echart图表  

                    //为echarts对象加载数据            
                    var myChart1 = ec.init(document.getElementById('pactagnum'));  

                    var option1 = {
                        title : {
                            text: 'PAC in the sequence',
//                            subtext: '纯属虚构'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
                            data:['蒸发量','降水量']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: true},
                                dataView : {show: true, readOnly: false},
                                magicType : {show: true, type: ['line', 'bar']},
                                restore : {show: true},
                                saveAsImage : {show: true}
                            }
                        },
                        calculable : true,
                        xAxis : [
                            {
                                    type : 'category',
                                    axisLabel:{
                                            interval:0
                                    },
//                                data : ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月']
                                data:[<?php
                                            for($i = 0;$i<10000;$i++){
                                                echo $i;
                                                echo ",";
                                            }
                                        ?>]
                            }
                        ],
                        yAxis : [
                            {
//                                max: 100,
//                                min: -10,
                                scale: 0,
                                type : 'value'
                            }
                        ],
                        grid: { // 控制图的大小，调整下面这些值就可以，
                            x: 30,
                            x2: 10,
                            y2: 50,// y2可以控制 X轴跟Zoom控件之间的间隔，避免以为倾斜后造成 label重叠到zoom上
                        },
                        color:["#ff8247","#9acd32","#b23aee","#4169e1","#00fa9a","#cd96cd","#9acd32","#cdcd00","#cd00cd","#3b3b3b"],
                        series : [
                            
                            <?php
//                                    $i=0;
//                                    foreach ($pac_loc as $key => $value) {
//                                        $tmp_data=array();
//                                        for($j=0;$j<  count($samples);$j++){
//                                            array_push($tmp_data, $pac_tagnum[$i*count($samples)+$j]);
//                                        }
//                                        $i++;
//                                        $data=  implode(",", $tmp_data);
//                                        unset($tmp_data);
//                                        echo "{"
//                                                    . "name:'PAC pos:$value',"
////                                                    . "barMinHeight: 10,"
//                                                    . "type:'bar',"
//                                                    . "data:[$data]"
//                                                . "},";    
//                                    }
                            ?>
                            {
                                name:'降水量',
                                type:'bar',
                                data:[<?php
                                            for($i = 0;$i<10000;$i++){
                                                echo $i;
                                                echo ",";
                                            }
                                        ?>]
                            }
//                            {
//                                name:'蒸发量',
//                                type:'bar',
//                                data:[2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3],
//                                markPoint : {
//                                    data : [
//                                        {type : 'max', name: '最大值'},
//                                        {type : 'min', name: '最小值'}
//                                    ]
//                                },
//                                markLine : {
//                                    data : [
//                                        {type : 'average', name: '平均值'}
//                                    ]
//                                }
//                            },
//                            {
//                                name:'降水量',
//                                type:'bar',
//                                data:[2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3],
//                                markPoint : {
//                                    data : [
//                                        {name : '年最高', value : 182.2, xAxis: 7, yAxis: 183, symbolSize:18},
//                                        {name : '年最低', value : 2.3, xAxis: 11, yAxis: 3}
//                                    ]
//                                },
//                                markLine : {
//                                    data : [
//                                        {type : 'average', name : '平均值'}
//                                    ]
//                                }
//                            }
                        ]
                    };

                    myChart1.setOption(option1);  
                }  
            );  
        </script>  
    </body>
</html>
