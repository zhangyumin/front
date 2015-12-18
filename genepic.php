<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <script src="./src/jquery-1.10.1.min.js"></script>
        <?php
            $con=  mysql_connect("localhost","root","root");
            mysql_select_db("db_server",$con);
            session_start();
            $seq=$_GET['seq'];
            $chr=$_GET['chr'];
            $strand=$_GET['strand'];
            $species = $_GET['species'];
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
            
            
            //读取数据库 存储物种的pa_table,pa_col和group数据
            $group = array();
            $patable = array();
            $pacol = array();
            $table = mysql_query("select lbl_group,PA_col,PA_table from t_sample_desc where species = '$species'");
            while($table_row = mysql_fetch_array($table)){
                array_push($group, $table_row['lbl_group']);
                array_push($pacol, $table_row['PA_col']);
                array_push($patable, $table_row['PA_table']);
            }
            //去除重复并重新排列
            $patable = array_unique($patable);
            $patable = array_merge($patable);
//            var_dump($patable);
            $num = count($pacol);#sample的个数
            //声明存储各个sample的数组，包括PA和PAC
            for($i=1;$i<=$num;$i++){
                $pa="pa".$i;
                $$pa=array();
                $pac="pac".$i;
                $$pac=array();
            }
            //循环读取$patable 查询数据库并存储pa数据
            foreach ($patable as $key => $value) {
                $tmp_pa = mysql_query("select * from $value where chr='$chr' and coord>=$gene_start and coord<=$gene_end;");
                while($tmp_pa_row = mysql_fetch_row($tmp_pa)){
                    if($key==0){
                        for($i=1;$i<=count($tmp_pa_row)-4;$i++){
                            $pa="pa".$i;
                            ${$pa}[$tmp_pa_row[2]] = $tmp_pa_row[$i+3];
                        }
                        $continue = $i;
                    }
                    else if($key==1){
                        for($i=$continue;$i<=$num;$i++){
                            $pa="pa".$i;
                            ${$pa}[$tmp_pa_row[2]] = $tmp_pa_row[$i-5];
                        }
                    }
                }
            }
//            PA数据测试
//            for($i=1;$i<=$num;$i++){
//                $pa="pa".$i;
//                if($i==13){
//                    var_dump($$pa);
//                }
//            }
            //读取pac数据并存入数组
            $tmp_pac = mysql_query("select * from t_".$species."_pac where gene = '$seq'");
            while($tmp_pac_row = mysql_fetch_row($tmp_pac)){
                for($i=1;$i<=$num;$i++){
                            $pac="pac".$i;
                            ${$pac}[$tmp_pac_row[2]] = $tmp_pac_row[$i+13];
                        }
            }
//            PAC数据测试
//            for($i=1;$i<=$num;$i++){
//                $pac="pac".$i;
//                if($i==14){
//                    var_dump($$pac);
//                }
//            }
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
                    for($i=1;$i<=$num;$i++){
                        $pa="pa".$i;
                        $pac="pac".$i;
                        foreach ($$pa as $key1 => $value1) {
                            $loc=($key1-$gene_start)*$rate;
                            echo "pa($loc,$value1,'sample$i');\n";
                        }
                        foreach ($$pac as $key2 => $value2) {
                            $loc=($key2-$gene_start)*$rate;
                            echo "pac($loc,$value2,'sample$i');\n";
                        }
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
    <body style="width:1000px">
        <div id="button" style="width:1000px;">
            <input type="radio" name="display" value="origin" checked="checked" onchange="display()"/>origin
            <input type="radio" name="display" value="statistics" onchange="display()"/>statistics
            <select id="method" disabled="true" onchange="show()">
                <option value="choose" selected="selected">Please choose</option>
                <option value="sum">Sum</option>
                <option value="avg">Average</option>
                <option value="med">Median</option>
            </select>
        </div>
        <script>
            function display(){
                var Slt = $('#button input[name="display"]:checked').val();
                $('#method').val("choose");
                if(Slt=='statistics'){
                    $('#method').attr('disabled',false);
                    $('.origin').hide();
                    $('.sum').show();
                    $('.avg').show();
                    $('.med').show();
                }
                else{
                    $('#method').attr('disabled',true);
                    $('.origin').show();
                    $('.sum').hide();
                    $('.avg').hide();
                    $('.med').hide();
                }
            }
            function show(){
                var sta = $("#method  option:selected").val();
                if(sta != 'choose'){
                    $('.origin').hide();
                    $('.sum').hide();
                    $('.avg').hide();
                    $('.med').hide();
                    $('.'+sta).show();
                }
                else{
                    $('.origin').hide();
                    $('.sum').show();
                    $('.avg').show();
                    $('.med').show();
                }
//                console.log(sta);
            }
        </script>
        <canvas id="gene" width="1000px;" height="150px;"></canvas><br>
        <canvas id='no_extend' width="1000px" height="150px;"></canvas><br>
        <?php
            for($i=1;$i<=$num;$i++){
                if($i%2==0)
                    echo "<canvas id=\"sample$i\" class=\"origin\" width=\"1000px; \" height=\"150px;\"></canvas>";
                else
                    echo "<canvas id=\"sample$i\" class=\"origin\" width=\"1000px; \" height=\"150px;\" style=\"background-color:#f1f1f1\"></canvas>";
            }
            for($i=1;$i<=$statistics_num;$i+=3){
                $j = $i + 1;
                $k = $i + 2;
                if($i%2==0){
                    echo "<canvas id=\"statistics_sample$i\" class=\"sum\" width=\"1000px; \" height=\"150px;\" style=\"display:none;\"></canvas>";
                    echo "<canvas id=\"statistics_sample$j\" class=\"avg\" width=\"1000px; \" height=\"150px;\" style=\"display:none;\"></canvas>";
                    echo "<canvas id=\"statistics_sample$k\" class=\"med\" width=\"1000px; \" height=\"150px;\" style=\"display:none;\"></canvas>";
                }
                else{
                    echo "<canvas id=\"statistics_sample$i\" class=\"sum\" width=\"1000px; \" height=\"150px;\" style=\"background-color:#f1f1f1;display:none;\"></canvas>";
                    echo "<canvas id=\"statistics_sample$j\" class=\"avg\" width=\"1000px; \" height=\"150px;\" style=\"background-color:#f1f1f1;display:none;\"></canvas>";
                    echo "<canvas id=\"statistics_sample$k\" class=\"med\" width=\"1000px; \" height=\"150px;\" style=\"background-color:#f1f1f1;display:none;\"></canvas>";
                }
            }
        ?>
    </body>
</html>
