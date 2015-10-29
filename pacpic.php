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
            $samples=array();
            $pac_loc=array();
            $pac_tagnum=array();
            $num=0;
            //读取sample个数和名称
            $sample=  mysql_query("select label from t_sample_desc where species='".$_GET['species']."';");
            while ($sample_num=  mysql_fetch_row($sample)){
                $num++;
                array_push($samples,$sample_num[0]);
            }
            //声明存储各个sample的loc,talbe,col和tagnum数组
          
            $seq=$_GET['seq'];
            $chr=$_GET['chr'];
            $strand=$_GET['strand'];
            //各部分坐标推入数组
            $result= mysql_query("select * from t_".$_GET['species']."_gff_org where gene='$seq' order by ftr_end;");
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
            $genelength=$gene_end-$gene_start;
            $rate=1000/$genelength;
            
            $pac_result=  mysql_query("select * from t_".$_GET['species']."_pac where gene='$seq';");
            while($pac_row=  mysql_fetch_row($pac_result)){
                for($i=1;$i<=$num;$i++){
                    $r=$i+13;
                    array_push($pac_tagnum, $pac_row[$r]);
                }
                array_push($pac_loc, $pac_row[2]);
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
                xline("gene",100);
                yline("pac",1);
                xline("pac",181);
                <?php
                    foreach ($sutr_start as $key => $value) {
                        $start=($sutr_start[$key]-$gene_start)*$rate;
                        $end=($sutr_end[$key]-$gene_start)*$rate;
                        echo "sutr($start,$end,$strand,'gene');\n";
                    }
                    foreach ($wutr_start as $key => $value) {
                        $start=($wutr_start[$key]-$gene_start)*$rate;
                        $end=($wutr_end[$key]-$gene_start)*$rate;
                        echo "wutr($start,$end,$strand,'gene');\n";
                    }
                    foreach ($intron_start as $key => $value) {
                        $start=($intron_start[$key]-$gene_start)*$rate;
                        $end=($intron_end[$key]-$gene_start)*$rate;
                        echo "intron($start,$end,$strand,'gene');\n";
                    }
                    foreach ($exon as $key => $value) {
                        $start=($exon_start[$key]-$gene_start)*$rate;
                        $end=($exon_end[$key]-$gene_start)*$rate;
                        echo "exon($start,$end,$strand,'gene');\n";
                    }
                    foreach ($cds_start as $key => $value) {
                        $start=($cds_start[$key]-$gene_start)*$rate;
                        $end=($cds_end[$key]-$gene_start)*$rate;
                        echo "cds($start,$end,$strand,'gene');\n";
                    }
                    foreach ($amb_start as $key => $value) {
                        $start=($amb_start[$key]-$gene_start)*$rate;
                        $end=($amb_end[$key]-$gene_start)*$rate;
                        echo "amb($start,$end,$strand,'gene');\n";
                    }
                    foreach ($pac_tagnum as $key => $value){
                        echo "pac($value,$key,'pac');\n";
                    }
                    foreach($samples as $key => $value){
                        $pos=20+20*(($key+1)*count($pac_loc));
                        echo "sampleinfo(\"pac\",$pos,\"$value\");\n";
                    }
                    foreach ($pac_loc as $key => $value) {
                        $position = ($value-$gene_start) * $rate;
                        echo "pointer($position,$key,\"gene\");";
                    }
                    if($_GET['intergenic']==1)
                        echo "intergenic($strand,\"gene\");"
                ?>
                arrow("gene",<?php echo $_GET['strand'];?>);
                title("#000000","<?php echo $seq;?>","gene");
                xscale("gene");
//                pointer(700,1,"gene");
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
            function xline(id,ypos){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.strokeStyle="#000000";
                context.fillStyle="#000000";//line为黑色
                context.moveTo(0,ypos);
                context.lineTo(1000,ypos);
                context.stroke();
            }
            function yline(id,xpos){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.strokeStyle="#000000";
                context.fillStyle="#000000";//line为黑色
                context.moveTo(xpos,0);
                context.lineTo(xpos,200);
                context.stroke();
            }
            function sampleinfo(id,pos,name){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.beginPath();
                context.fillStyle="#000000";//line为黑色
                context.moveTo(pos,180);
                context.lineTo(pos,200);
                context.stroke();
                context.closePath();
                context.beginPath();
                context.font="8px Arial";
                context.fillText(name,pos-58,195);
                context.closePath();
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
            function sutr(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#ff0000";//3utr为红色
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,90,endpos-startpos-10,20);
                }
                else if(startpos==0&&strand==-1){
                    context.fillRect(startpos+10,90,endpos-startpos,20);
                }
                else{
                    context.fillRect(startpos,90,endpos-startpos,20);
                }
            }
            function wutr(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#BA55D3";//5utr为zise
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,90,endpos-startpos-10,20);
                }
                else if(startpos==0&&strand==-1){
                    context.fillRect(startpos+10,90,endpos-startpos,20);
                }
                else{
                    context.fillRect(startpos,90,endpos-startpos,20);
                }
            }
            function cds(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#9AFF9A";//cds为绿色
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,80,endpos-startpos-10,40);
                }
                else if(startpos==0&&strand==-1){
                    context.fillRect(startpos+10,80,endpos-startpos,40);
                }
                else{
                    context.fillRect(startpos,80,endpos-startpos,40);
                }
            }
            function intron(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#080808";//intron为黑色
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,90,endpos-startpos-10,20);
                }
                else if(startpos==0&&strand==-1){
                    context.fillRect(startpos+10,90,endpos-startpos,20);
                }
                else{
                    context.fillRect(startpos,90,endpos-startpos,20);
                }
            }
            function exon(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#eeee00";//exon为黄色
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,80,endpos-startpos-10,40);
                }
                else if(startpos==0&&strand==-1){
                    context.fillRect(startpos+10,80,endpos-startpos,40);
                }
                else{
                    context.fillRect(startpos,80,endpos-startpos,40);
                }
            }
            function amb(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#97ffff";//amb为兰色
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,80,endpos-startpos-10,40);
                }
                else if(startpos==0&&strand==-1){
                    context.fillRect(startpos+10,80,endpos-startpos,40);
                }
                else{
                    context.fillRect(startpos,80,endpos-startpos,40);
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
                    context.moveTo(990,110);
                    context.lineTo(995,110);
                    context.lineTo(1000,100);
                    context.lineTo(995,90);
                    context.lineTo(990,90);
                    context.lineTo(990,110);
                }
                else if(strand==-1){
                    context.moveTo(0,100);
                    context.lineTo(5,110);
                    context.lineTo(10,110);
                    context.lineTo(10,90);
                    context.lineTo(5,90);
                    context.lineTo(0,100);
                }
                context.closePath();
                context.fillStyle="#878787";
                context.fill();
            }
            function pac(tagnum,key,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                <?php echo "var row =".count($pac_loc).";";?>
                context.beginPath();
                if(key%row==0)
                    context.fillStyle="#ff8247";
                else if(key%row==1)
                    context.fillStyle="#9acd32";
                else if(key%row==2)
                    context.fillStyle="#b23aee";
                else if(key%row==3)
                    context.fillStyle="#4169e1";
                else if(key%row==4)
                    context.fillStyle="#00fa9a";
                else if(key%row==5)
                    context.fillStyle="#cd96cd";
                else if(key%row==6)
                    context.fillStyle="#9acd32";
                else if(key%row==7)
                    context.fillStyle="#cdcd00";
                else if(key%row==8)
                    context.fillStyle="#cd00cd";
                else if(key%row==9)
                    context.fillStyle="#3b3b3b";
                context.fillRect(20+key*20,180,18,-0.1*(tagnum+10));
                context.closePath();
                context.beginPath();
                context.fillStyle="#000000";
                context.fillText(tagnum,20+key*20,180-0.1*(tagnum+10));
                context.closePath();
            }
            function pointer(pos,key,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                <?php echo "var row =".count($pac_loc).";";?>
                context.beginPath();
                context.moveTo(pos,120);
                context.lineTo(pos-5,125);
                context.lineTo(pos-1,125);
                context.lineTo(pos-1,150);
                context.lineTo(pos+1,150);
                context.lineTo(pos+1,125);
                context.lineTo(pos+5,125);
                context.lineTo(pos,120);
                context.closePath();
                if(key%row==0)
                    context.fillStyle="#ff8247";
                else if(key%row==1)
                    context.fillStyle="#9acd32";
                else if(key%row==2)
                    context.fillStyle="#b23aee";
                else if(key%row==3)
                    context.fillStyle="#4169e1";
                else if(key%row==4)
                    context.fillStyle="#00fa9a";
                else if(key%row==5)
                    context.fillStyle="#cd96cd";
                else if(key%row==6)
                    context.fillStyle="#9acd32";
                else if(key%row==7)
                    context.fillStyle="#cdcd00";
                else if(key%row==8)
                    context.fillStyle="#cd00cd";
                else if(key%row==9)
                    context.fillStyle="#3b3b3b";
                context.fill();
            }
        </script>
    </head>
    <body>
        <canvas id="gene" width="1000px;" height="150px;"></canvas>
        <?php
            $width=40+20*$num*  count($pac_loc);
            echo "<br>";
            echo "<canvas id=\"pac\" width=\"".$width."px\" height=\"200px\"></canvas>";
            ?>
    </body>
</html>
