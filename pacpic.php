<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script src="./src/jquery-1.10.1.min.js"></script>
        <?php
            session_start();
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
          
            $seq=$_GET['seq'];
            $chr=$_GET['chr'];
            $strand=$_GET['strand'];
            $species=$_GET['species'];
            //各部分坐标推入数组
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
            $samples = $pacol;
            //patable去除重复并重新排列
            $patable = array_unique($patable);
            $patable = array_merge($patable);
//            var_dump($patable);
            $num = count($pacol)+count($_SESSION['file_real']);#sample的个数
            //声明存储各个sample的数组，包括PA和PAC
            for($i=1;$i<=$num;$i++){
                $pac="pac".$i;
                $$pac=array();
            }
            //读取pac数据并存入数组
            $tmp_pac = mysql_query("select * from t_".$species."_pac where gene = '$seq'");
            while($tmp_pac_row = mysql_fetch_row($tmp_pac)){
                for($i=1;$i<=$num-count($_SESSION['file_real']);$i++){
                            $pac="pac".$i;
                            ${$pac}[$tmp_pac_row[2]] = $tmp_pac_row[$i+13];
//                            echo $i;
                        }
            }
            
             //user trap数据
            if(isset($_SESSION['file'])){
                $sql_sample = implode($_SESSION['file_real'], ",");
                $user_pac = mysql_query("select coord,$sql_sample from db_user.PAC_".$_SESSION['file']." where gene = '$seq';");
                while($usr_row_pac = mysql_fetch_row($user_pac)){
                    for($i=$num-count($_SESSION['file_real'])+1;$i<=$num;$i++){
                        $pac="pac".$i;
                        ${$pac}[$usr_row_pac[0]] = $usr_row_pac[$i-$num+count($_SESSION['file_real'])];
                    }
                }
                foreach ($_SESSION['usr_group'] as $key => $value) {
                    array_push($group, $value);
                }
                foreach ($_SESSION['file_real'] as $key => $value) {
                    array_push($samples, $value);
                }
            }
            //如果是analysis的数据
            if($_GET['analysis']==1){
                unset($group);
                $group = array();
                for($i=1;$i<=$num;$i++){
                    //从samples中去除未选中的samples
                    if(!in_array($samples[$i-1], $_SESSION['sample'])){
                        unset($samples[$i-1]);
                        unset(${"pac".$i});
                    }
                }
                //重排samples序号
               $samples = array_merge($samples);
                //重新排列pa和pac的序号
                $j = 1;
                for($i=1;$i<=$num;$i++){
                    if(!empty(${"pac".$i})){
                        ${"pac".$j} = ${"pac".$i};
                        $j++;
                    }
                }
                //去除多余
                for($i=count($_SESSION['sample'])+1;$i<=$num;$i++){
                    unset(${"pac".$i});
                }
                //根据勾选重新分组
                foreach ($samples as $key => $value) {
                    if(in_array($value, $_SESSION['sample1']))
                        array_push ($group,'sample1');
                    else if(in_array($value, $_SESSION['sample2']))
                        array_push ($group, 'sample2');
                }
                $num = count($_SESSION['sample']);
//                $samples = $_SESSION['sample'];
            }
//            var_dump(array_unique($group));
            
            //group处理
            $statistics_samples = array();#存储statistics的title
            $pac_num = array();
            //声明每个group的总和，均值，中位数数组
            foreach (array_unique($group) as $key => $value) {
                array_push($statistics_samples, $value."_sum");
                array_push($statistics_samples, $value."_avg");
                array_push($statistics_samples, $value."_med");
                ${"pac_".$value."_sum"} = array();
                ${"pac_".$value."_avg"} = array();
                ${"pac_".$value."_med"} = array();
                $pac_group_key=array();#用于存储本组pac所有的下标key值,也就是coord坐标
                $group_member = array();#用于存储同组成员的编号$i
                for($i = 1;$i <= $num; $i++){
                    if($group[$i-1] == $value){#group是从0开始
                        array_push($group_member, $i);
                    }
                }
                //遍历同group所有成员来获得本组的所有coord值
                foreach ($group_member as $key1 => $value1) {
                        $pac_group_key = $pac_group_key + ${"pac".$value1};
                }
                foreach ($pac_group_key as $key2 => $value2) {
                    if(!in_array($key2, $pac_num)){
                        array_push($pac_num, $key2);
                    }
                    $sum_pactmp = 0;
                    $avg_pactmp = 0;
                    $med_pactmp = 0;
                    $pactmp = array();#临时用于存储的数组
                    foreach ($group_member as $key3 => $value3) {
                        if(array_key_exists($key2, ${"pac".$value3})){
                            array_push($pactmp, ${"pac".$value3}[$key2]);
                        }
                        else{
                            array_push($pactmp,0);
                        }
                    }
                    sort($pactmp);//从小到大排列$pactmp
                    $num_pactmp = count($pactmp);#统计同group下sample的个数
                    foreach ($pactmp as $key4 => $value4) {
                        $sum_pactmp = $sum_pactmp + $value4;
                    }
                    $avg_pactmp = $sum_pactmp / $num_pactmp;
                    $avg_pactmp = number_format($avg_pactmp,1,".","");
                    if($num_pactmp % 2 == 1){
                        $med_pactmp = $pactmp[round($num_pactmp/2)-1];
                    }
                    else{
                        $med_pactmp = ($pactmp[$num_pactmp/2]+$pactmp[$num_pactmp/2-1])/2;
                    }
                    $med_pactmp = number_format($med_pactmp,1,".","");
                    ${"pac_".$value."_sum"}[$key2] = $sum_pactmp;
                    ${"pac_".$value."_avg"}[$key2] = $avg_pactmp;
                    ${"pac_".$value."_med"}[$key2] = $med_pactmp;
                }
            }
//            var_dump($group_member);
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
                    $i = -1;
                    foreach ($pac_num as $key => $value) {
                        $i++;
                        $position = ($value-$gene_start) * $rate;
                        echo "pointer($position,$i,\"gene\");";
                    }
                    if($_GET['intergenic']==1)
                        echo "intergenic($strand,\"gene\");"
                ?>
                arrow("gene",<?php echo $_GET['strand'];?>);
//                title("#000000","<?php echo $seq;?>","gene");
                xscale("gene");
//                pointer(700,1,"gene");
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
                context.beginPath();
                context.strokeStyle="#000000";
                context.fillStyle="#000000";//x刻度尺为黑色
                context.moveTo(0,60);
                context.lineTo(1000,60);
                context.moveTo(0,60);
                context.lineTo(5,55);
                context.moveTo(0,60);
                context.lineTo(5,65);
                context.moveTo(1000,60);
                context.lineTo(995,55);
                context.moveTo(1000,60);
                context.lineTo(995,65);
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
            function sutr(startpos,endpos,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#42A881";//3utr为红色
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
                context.fillStyle="#8C5E4D";//5utr为zise
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
                context.fillStyle="#00ABD8";//cds为绿色
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
                context.fillStyle="#D390B9";//intron为黑色
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
                context.fillStyle="#65D97D";//exon为黄色
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
                context.fillStyle="#F35A4A";//amb为兰色
                if(endpos==1000&&strand==1){
                    context.fillRect(startpos,90,endpos-startpos-10,20);
//                    var_dump($pactmp);
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
            
            function pointer(pos,key,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                <?php echo "var row =".count($pac_num).";";?>
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
        <div id="button" style="width:1000px;">
            <input type="radio" name="display" value="origin" checked="checked" onchange="display()"/>origin
            <input type="radio" name="display" value="ratio" onchange="display()"/>ratio
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
                    $('#pactagnum').hide();
                    $('#pactagnum_ratio').hide();
                    $('#pactagnum_sum').show();
                    $('#pactagnum_avg').show();
                    $('#pactagnum_med').show();
                }
                else if(Slt=='origin'){
                    $('#method').attr('disabled',true);
                    $('#pactagnum').show();
                    $('#pactagnum_ratio').hide();
                    $('#pactagnum_sum').hide();
                    $('#pactagnum_avg').hide();
                    $('#pactagnum_med').hide();
                }
                else if(Slt == 'ratio'){
                    $('#method').attr('disabled',true);
                    $('#pactagnum').hide();
                    $('#pactagnum_sum').hide();
                    $('#pactagnum_avg').hide();
                    $('#pactagnum_med').hide();
                    $('#pactagnum_ratio').show();
                }
            }
            function show(){
                var sta = $("#method  option:selected").val();
                if(sta != 'choose'){
                    $('#pactagnum').hide();
                    $('#pactagnum_sum').hide();
                    $('#pactagnum_avg').hide();
                    $('#pactagnum_med').hide();
                    $('#pactagnum_'+sta).show();
                }
                else{
                    $('#pactagnum').hide();
                    $('#pactagnum_sum').show();
                    $('#pactagnum_avg').show();
                    $('#pactagnum_med').show();
                }
//                console.log(sta);
            }
        </script>
        <canvas id="gene" width="1000px;" height="150px;"></canvas><br>
       
            <!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->  
            <div id="pactagnum" style="height:400px;width:1000px;border:1px solid #ccc;padding:10px;"></div>
            <div id="pactagnum_sum" style="height:400px;width:1000px;border:1px solid #ccc;padding:10px;display: none"></div>
            <div id="pactagnum_avg" style="height:400px;width:1000px;border:1px solid #ccc;padding:10px;display: none"></div>
            <div id="pactagnum_med" style="height:400px;width:1000px;border:1px solid #ccc;padding:10px;display: none"></div>
            <div id="pactagnum_ratio" style="height:400px;width:1000px;border:1px solid #ccc;padding:10px;display: none"></div>

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
                    var myChart2 = ec.init(document.getElementById('pactagnum_sum'));
                    var myChart3 = ec.init(document.getElementById('pactagnum_avg'));
                    var myChart4 = ec.init(document.getElementById('pactagnum_med'));
                    var myChart5 = ec.init(document.getElementById('pactagnum_ratio'));

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
                                        foreach ($pac_num as $key => $value) {
                                            echo "PAC@$value','";
                                        }
                                    ?>']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: false},
                                dataView : {show: false, readOnly: false},
                                magicType : {show: false, type: ['line', 'bar']},
                                restore : {show: false},
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
                            x: 40,
                            x2: 10,
                            y2: 50,// y2可以控制 X轴跟Zoom控件之间的间隔，避免以为倾斜后造成 label重叠到zoom上
                        },
                        color:["#ff8247","#9acd32","#b23aee","#4169e1","#00fa9a","#cd96cd","#9acd32","#cdcd00","#cd00cd","#3b3b3b"],
                        series : [
                            
                            <?php
                                    foreach ($pac_num as $key => $value) {
                                        $tmp_array = array();
                                        for($i=1;$i<=$num;$i++){
                                            $pac="pac".$i;
                                            if(${$pac}[$value]!=NULL)
                                                array_push($tmp_array,${$pac}[$value]);
                                            else
                                                array_push($tmp_array,0);
                                        }
                                        $data = implode(",", $tmp_array);
                                        unset($tmp_array);
                                        echo "{"
                                                    . "name:'PAC@$value',"
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
                    var option2 = {
                        title : {
                            text: 'PAC Sum in the sequence',
//                            subtext: '纯属虚构'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
//                            data:['蒸发量','降水量']
                            data:['<?php
                                        foreach ($pac_num as $key => $value) {
                                            echo "PAC pos:$value','";
                                        }
                                    ?>']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: false},
                                dataView : {show: false, readOnly: false},
                                magicType : {show: false, type: ['line', 'bar']},
                                restore : {show: false},
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
                                                echo implode("','", array_unique($group));
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
                                    $statistics_key = array();
                                    foreach (array_unique($group) as $key => $value) {
//                                        $data = implode(",", ${"pac_".$value."_sum"});
                                        foreach (${"pac_".$value."_sum"} as $key1 => $value1) {
                                            if(!in_array($key1, $statistics_key))
                                                array_push($statistics_key, $key1);
                                        }
                                    }
                                    foreach ($statistics_key as $key => $value) {
                                        $tmp_sum = array();
                                        foreach (array_unique($group) as $key3 => $value3) {
                                            if(${"pac_".$value3."_sum"}[$value]!=NULL)
                                                array_push($tmp_sum, ${"pac_".$value3."_sum"}[$value]);
                                            else
                                                array_push($tmp_sum, 0);
                                        }
                                        $data = implode(",", $tmp_sum);
                                        unset($tmp_sum);
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
                    var option3 = {
                        title : {
                            text: 'PAC Average in the sequence',
//                            subtext: '纯属虚构'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
//                            data:['蒸发量','降水量']
                            data:['<?php
                                        foreach ($pac_num as $key => $value) {
                                            echo "PAC pos:$value','";
                                        }
                                    ?>']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: false},
                                dataView : {show: false, readOnly: false},
                                magicType : {show: false, type: ['line', 'bar']},
                                restore : {show: false},
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
                                                echo implode("','", array_unique($group));
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
                                    $statistics_key = array();
                                    foreach (array_unique($group) as $key => $value) {
//                                        $data = implode(",", ${"pac_".$value."_sum"});
                                        foreach (${"pac_".$value."_avg"} as $key1 => $value1) {
                                            if(!in_array($key1, $statistics_key))
                                                array_push($statistics_key, $key1);
                                        }
                                    }
                                    foreach ($statistics_key as $key => $value) {
                                        $tmp_sum = array();
                                        foreach (array_unique($group) as $key3 => $value3) {
                                            if(${"pac_".$value3."_sum"}[$value]!=NULL)
                                                array_push($tmp_sum, ${"pac_".$value3."_avg"}[$value]);
                                            else
                                                array_push($tmp_sum, 0);
                                        }
                                        $data = implode(",", $tmp_sum);
                                        unset($tmp_sum);
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
                    var option4 = {
                        title : {
                            text: 'PAC Median in the sequence',
//                            subtext: '纯属虚构'
                        },
                        tooltip : {
                            trigger: 'axis'
                        },
                        legend: {
//                            data:['蒸发量','降水量']
                            data:['<?php
                                        foreach ($pac_num as $key => $value) {
                                            echo "PAC pos:$value','";
                                        }
                                    ?>']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: false},
                                dataView : {show: false, readOnly: false},
                                magicType : {show: false, type: ['line', 'bar']},
                                restore : {show: false},
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
                                                echo implode("','", array_unique($group));
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
                                    $statistics_key = array();
                                    foreach (array_unique($group) as $key => $value) {
//                                        $data = implode(",", ${"pac_".$value."_sum"});
                                        foreach (${"pac_".$value."_med"} as $key1 => $value1) {
                                            if(!in_array($key1, $statistics_key))
                                                array_push($statistics_key, $key1);
                                        }
                                    }
                                    foreach ($statistics_key as $key => $value) {
                                        $tmp_sum = array();
                                        foreach (array_unique($group) as $key3 => $value3) {
                                             if(${"pac_".$value3."_sum"}[$value]!=NULL)
                                                array_push($tmp_sum, ${"pac_".$value3."_med"}[$value]);
                                            else
                                                array_push($tmp_sum, 0);
                                        }
                                        $data = implode(",", $tmp_sum);
                                        unset($tmp_sum);
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
                    var option5 = {
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
                                        foreach ($pac_num as $key => $value) {
                                            echo "PAC@$value','";
                                        }
                                    ?>']
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                mark : {show: false},
                                dataView : {show: false, readOnly: false},
                                magicType : {show: false, type: ['line', 'bar']},
                                restore : {show: false},
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
                                max: 1,
                                min: 0,
                                scale: 0,
                                type : 'value'
                            }
                        ],
                        grid: { // 控制图的大小，调整下面这些值就可以，
                            x: 40,
                            x2: 10,
                            y2: 50,// y2可以控制 X轴跟Zoom控件之间的间隔，避免以为倾斜后造成 label重叠到zoom上
                        },
                        color:["#ff8247","#9acd32","#b23aee","#4169e1","#00fa9a","#cd96cd","#9acd32","#cdcd00","#cd00cd","#3b3b3b"],
                        series : [
                            
                            <?php
                                    $group_total = array();
                                    foreach (array_unique($group) as $key => $value) {
                                        $num_array = array();
                                        foreach ($group as $key1 => $value1) {
                                            if($value1 == $value){
                                                array_push($num_array, $key1+1);//修正group的value与$i之间的偏移
                                            }
                                        }
                                        $total = 0;
                                        foreach ($pac_num as $key2 => $value2) {
                                            foreach ($num_array as $key3 => $value3) {
                                                $pac = "pac".$value3;
                                                if(${$pac}[$value2]!=NULL)
                                                    $total = $total + ${$pac}[$value2];
                                            }
                                        }
                                        foreach ($num_array as $key4 => $value4) {
                                            $group_total[$value4] = $total;
                                        }
                                    }
//                                    var_dump($group_total);
                                    foreach ($pac_num as $key => $value) {
                                        $tmp_array = array();
                                        for($i=1;$i<=$num;$i++){
                                            $pac="pac".$i;
                                            if(${$pac}[$value]!=NULL)
                                                array_push($tmp_array,number_format(${$pac}[$value]/$group_total[$i],5,".",""));
                                            else
                                                array_push($tmp_array,0);
                                        }
                                        $data = implode(",", $tmp_array);
                                        unset($tmp_array);
                                        echo "{"
                                                    . "name:'PAC@$value',"
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
                    myChart5.setOption(option5);  
                    myChart4.setOption(option4);
                    myChart3.setOption(option3);
                    myChart2.setOption(option2);
                    myChart1.setOption(option1);  
                }  
            );  
        </script>  
    </body>
</html>
