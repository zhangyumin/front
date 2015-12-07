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
            if($_GET['intergenic']==1){
                $gene_start=$_GET['coord']-200;
                $gene_end=$_GET['coord']+200;
            }
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
//                    foreach($samples as $key => $value){
//                        $pos=20+20*(($key+1)*count($pac_loc));
//                        echo "sampleinfo(\"pac\",$pos,\"$value\");\n";
//                    }
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
                    context.fillRect(startpos,95,endpos-startpos-10,10);
                }
                else if(startpos==0&&strand==-1){
                    context.fillRect(startpos+10,95,endpos-startpos-10,10);
                }
                else{
                    context.fillRect(startpos,95,endpos-startpos,10);
                }
            }
            function wutr(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#BA55D3";//5utr为zise
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,95,endpos-startpos-10,10);
                }
                else if(startpos==0&&strand==-1){
                    context.fillRect(startpos+10,95,endpos-startpos-10,10);
                }
                else{
                    context.fillRect(startpos,95,endpos-startpos,10);
                }
            }
            function cds(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#9AFF9A";//cds为绿色
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,90,endpos-startpos-10,20);
                }
                else if(startpos==0&&strand==-1){
                    context.fillRect(startpos+10,90,endpos-startpos-10,20);
                }
                else{
                    context.fillRect(startpos,90,endpos-startpos,20);
                }
            }
            function intron(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#080808";//intron为黑色
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,95,endpos-startpos-10,10);
                }
                else if(startpos==0&&strand==-1){
                    context.fillRect(startpos+10,95,endpos-startpos-10,10);
                }
                else{
                    context.fillRect(startpos,95,endpos-startpos,10);
                }
            }
            function exon(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#eeee00";//exon为黄色
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,90,endpos-startpos-10,20);
                }
                else if(startpos==0&&strand==-1){
                    context.fillRect(startpos+10,90,endpos-startpos-10,20);
                }
                else{
                    context.fillRect(startpos,90,endpos-startpos,20);
                }
            }
            function amb(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#97ffff";//amb为兰色
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,90,endpos-startpos-10,20);
                }
                else if(startpos==0&&strand==-1){
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
        <canvas id="gene" width="1000px;" height="150px;"></canvas><br>
       
            <!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->  
            <div id="pactagnum" style="height:400px;width:1000px;border:1px solid #ccc;padding:10px;"></div>

            <!--Step:2 引入echarts.js-->  
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
//                            data:['蒸发量','降水量']
                            data:['<?php
                                        foreach ($pac_loc as $key => $value) {
                                            echo "PAC pos:$value','";
                                        }
                                    ?>']
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
                                   data : ['<?php
                                                echo implode("','", $samples)
//                                                    echo "1','2','3','4','5','6','7','8";
                                            ?>']
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
                                    $i=0;
                                    foreach ($pac_loc as $key => $value) {
                                        $tmp_data=array();
                                        for($j=0;$j<  count($samples);$j++){
                                            array_push($tmp_data, $pac_tagnum[$i*count($samples)+$j]);
                                        }
                                        $i++;
                                        $data=  implode(",", $tmp_data);
                                        unset($tmp_data);
                                        echo "{"
                                                    . "name:'PAC pos:$value',"
//                                                    . "barMinHeight: 10,"
                                                    . "type:'bar',"
                                                    . "data:[$data]"
                                                . "},";    
                                    }
                            ?>
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
