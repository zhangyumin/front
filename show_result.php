<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>show results</title>
    <link href="./src/navbar.css" rel="stylesheet"/>
    <script src="./src/jquery-1.10.1.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
    <!--[if lte IE 7]>
    <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
    <![endif]-->

    <!--[if lt IE 9]>
    <script src="./js/html5shiv/html5shiv.js"></script>
    <![endif]-->
        
    <?php
                $con=  mysql_connect("localhost","root","root");
                 mysql_select_db("db_server",$con);
                 session_start();
    ?>
    <style>
        a:link {
        color: #5499c9;
        text-decoration: none;
        }
        a:visited {
        text-decoration: none;
        }
        a:hover {
        color: #FFFFFF;
        text-decoration: none;
        }
        a:active {
        text-decoration: none;
        }
        .jtable{
            margin: 0px auto;
        }
    </style>
</head>
<body>
    <?php
           session_start();
           if(!isset($_SESSION['file_real']))
           {
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
           }
           $atcg_name=glob("./result/".$_SESSION['file']."/*.".$_SESSION['file'].".*.cnt");
           $file_atcg="$atcg_name[0]";
           #echo $file_atcg;
           $atcg=  file_get_contents($file_atcg);
           $array_atcg= explode("\n", $atcg);
           #$tmp_s=  explode("\t", $array_atcg[1]);
           #echo $tmp_s[0];
           #将文件分割为四个数组
           foreach ($array_atcg as $key => $value) {
               $tmp_array1=explode("\t", $value);
               $array_no[$key]=$tmp_array1[0];
               $array_a[$key]=$tmp_array1[1];
               $array_t[$key]=$tmp_array1[2];
               $array_c[$key]=$tmp_array1[3];
               $array_g[$key]=$tmp_array1[4];
           }
           #去除数组中的标题行
               array_shift($array_no);
               array_shift($array_a);
               array_shift($array_t);
               array_shift($array_c);
               array_shift($array_g);
               
               #六联子文件的切割
               $k6_name=glob("./result//".$_SESSION['file']."/*.".$_SESSION['file'].".*once");
               $file_k6=$k6_name[0];
               #echo $file_k6;
                $k6=  file_get_contents($file_k6);
                $array_atcg= explode("\n", $k6);
                foreach ($array_atcg as $key => $value) {
                    $tmp_array1=explode("\t", $value);
                    $array_k6[$key]=$tmp_array1[0];
                    $array_sum[$key]=$tmp_array1[1];
                }
    ?>
    
    <!-- 为jtable准备位置-->
    <div style="width: 100%;margin:0 auto;" class="page">
        <?php
            include './navbar.php'
        ?>
    
    <div id="task_summery" align="center" style="clear: both;">
        <fieldset class="summary">
            <h4>
                <span class="h3_italic">
                    <font color="#224055"><b>Task Summary</b></font>
                </span>
            </h4>
                <?php
                    $file=  file_get_contents("./log/".$_SESSION['file'].".txt");
                    $array_file=  explode("\n", $file);
                    foreach ($array_file as $key => $value) {
                        $array_input=  strstr($value, 'Input');
                        if( $array_input!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_discard=  strstr($value, 'discarded');
                        if( $array_discard!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_internal=  strstr($value, 'Total');
                        if( $array_internal!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_tail=  strstr($value, ' reads; of these',true);
                        if( $array_tail!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_read=  strstr($value, 'aligned exactly 1 time',true);
                        if( $array_read!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_pat=  strstr($value,  "rows to db_user.PA_".$_SESSION['file']."",true);
                        if( $array_pat!=false)
                            break;
                    }
                    foreach ($array_file as $key => $value) {
                        $array_pac=  strstr($value, ' PAC',true);
                        if( $array_pac!=false)
                            break;
                    }
                    //var_dump($array_input);
                    $array_input=explode(" ",$array_input);
                    $array_discard=explode(" ",$array_discard);
                    $array_tail=explode(" ",$array_tail);
                    $array_read=explode(" ",$array_read);
                    $array_internal=explode(" ",$array_internal);
                    $array_pat=explode(" ",$array_pat);
                    $array_pac=explode(" ",$array_pac);

                    $input_reads=$array_input[1];
                    $low_quality_reads=$array_discard[1];
                    $reads_with_tail=$array_tail[0];
                    $aligned_reads=$array_read[4];
                    $array_read[5]=  substr($array_read[5], 1, strlen($array_read[5])-2);
                    $alignment_rate=$array_read[5];
                    $array_internal[1]=  substr($array_internal[1], 0,strlen($array_internal[1])/2);
                    $internal_priming_reads=$array_internal[1];
                    $pat=$array_pat[0];
                    $pac=$array_pac[0];
                    
                    echo "<span style=\"color:red\">Task id :".$_SESSION['file']."</span><br>";
                    echo "Input reads : $input_reads";
                    echo "<br>Low quality reads : $low_quality_reads";
                    echo "<br>Reads with tail : $reads_with_tail";
                    echo "<br>Aligned reads : $aligned_reads";
                    echo "<br>Alignment rate : $alignment_rate";
                    echo "<br>Internal priming reads : $internal_priming_reads";
                    echo "<br>PAT : $pat";
                    echo "<br>PAC : $pac";
                    echo "<a style='display:block;text-align:right;color:red;' href='./download.php?data=".$_SESSION['file']."'>Click to download results</a>";
                ?>
            </fieldset>
    </div><br>
    <div class="filter" id="filter">
            <button onclick="javascript:window.location.href='./download_data.php?type=4&name=<?php echo "PAC_".$_SESSION['file'].".txt"; ?>'">download trap result</button>
            <button onclick="locking()">export sequences</button>
            <form>
                <input type="text" name="search" id="search" />
                <button type="submit" id="search_button">search</button>
                <button type="reset" id="reset_button">reset</button>
            </form>
            <div style="clear:both;"></div>
    </div>
    <div id="jtable"></div>
    <div id="ly" style="position: absolute; top: 0px; opacity:0.4; background-color: #777;z-index: 2; left: 0px; display: none;">
    </div>
    <!--          浮层框架开始         -->
    <div id="Layer2" align="center" style="border: 1px solid;position: absolute; z-index: 3; left: 40%; top: 50%;background-color: #fff; display: none;" >
        <table width="540" height="300" border="0" cellpadding="0" cellspacing="0" style="border: 0    solid    #e7e3e7;border-collapse: collapse ;" >
            <tr>
                <td style="background-color: #73A2d6; color: #fff; padding-left: 4px; padding-top: 2px;font-weight: bold; font-size: 12px;" height="10" valign="middle">
                     <div align="right">
                         <a href=JavaScript:; class="STYLE1" onclick="Lock_CheckForm(this);">[Close]
                         </a> &nbsp;&nbsp;&nbsp;&nbsp;
                     </div>
                </td>
            </tr>
            <tr>
                <td height="130" align="center">
                    <form name="pac_export" method="post" action="export_seq.php?source=PAC&species=<?php echo $_SESSION['species']; ?>" target="_blank">
                        method<select id="method" name="method" onchange="ChgMtd()">
                            <option value="choose">Please choose</option>
                            <option value="pacs">export sequences of PACs</option>
                            <option value="pacs-region">export sequences of regions of  PACs</option>
                            <option value="seq">export gene sequences</option>
                        </select><br>
                        <div id="pacs" style="display:none">
                            upstream (nt) <input type="text" value="200" name='upstream'></input><br>
                            downstream (nt) <input type="text" value="200" name='downstream'></input><br>
                            PAC in region <select name='pac_region'>
                                <option value="all">all</option>
                                <option value="genomic-region">genomic region</option>
                                <option value="3TUR">3'UTR</option>
                                <option value="5UTR">5‘UTR</option>
                                <option value="CDS">CDS</option>
                                <option value="intron">intron</option>
                                <option value="intergenic.igt">intergenic</option>
                                <option value="intergenic.pm">promoter</option>
                            </select>
                        </div>
                        <div id="pacs-region" style="display:none">
                            region of PACs <select name='pacs_region'>
                                <option value="all">all</option>
                                <option value="genomic-region">genomic region</option>
                                <option value="3TUR">3'UTR</option>
                                <option value="5UTR">5‘UTR</option>
                                <option value="CDS">CDS</option>
                                <option value="intron">intron</option>
                                <option value="intergenic.igt">intergenic</option>
                                <option value="intergenic.pm">promoter</option>
                            </select>
                        </div>
                        <div id="seq" style="display:none">
                            annotation version <select name='anno_version'>
                                <option value="raw-annotation">raw annotation</option>
                                <option value="3utr-extended-annotation">3' UTR extended annotation</option>
                            </select><br>
                            export <select name='export'>
                                <option value="whole-gene">whole gene</option>
                                <option value="joined-cds">joined CDS</option>
                                <option value="3utr-only">3' UTR only</option>
                            </select>
                        </div>
                        <button id="sub" type="submit" disabled="true">Submit</button>
                        <button id="can" type="reset" disabled="true">Reset</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>
    <link href="src/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
    <link href="src/jtable.css" rel="stylesheet" type="text/css" />
    
    <script src="src/jquery-1.6.4.min.js" type="text/javascript" ></script>
    <script src="src/jquery-ui-1.8.16.custom.min.js" type="text/javascript" ></script>
    <script src="src/jquery.jtable.js" type="text/javascript" ></script>
    <script type="text/javascript">
        var species = '<?php echo $_SESSION['species']; ?>';
         function locking(){   
           document.all.ly.style.display="block";   
           document.all.ly.style.width=document.body.clientWidth;   
           document.all.ly.style.height=document.body.offsetHeight;   
           document.all.Layer2.style.display='block';  
           }   
        function Lock_CheckForm(theForm){   
            document.all.ly.style.display='none';document.all.Layer2.style.display='none';
            return   false;   
         }
         function ChgMtd(){
                document.getElementById("pacs").style.display='none';
                document.getElementById("pacs-region").style.display='none';
                document.getElementById("seq").style.display='none';
                if(document.getElementById("method").value=='choose'){
                    document.getElementById("sub").disabled=true;
                    document.getElementById("can").disabled=true;
                }
                else{
                    document.getElementById("sub").disabled=false;
                    document.getElementById("can").disabled=false;
                    document.getElementById(document.getElementById("method").value).style.display='block';
//                console.log(document.getElementById("method").value);
                }
            }
        $(document).ready(function (){
            $('#jtable').jtable({
                title:'PAC',
                paging:true,
                pageSize:5,
                sorting:true,
                defaultSorting:'gene ASC',
                actions:{
                    listAction:'PAClist.php'
                },
                fields:{
                    gene:{
                        key:true,
                        edit:false,
                        width:'10%',
                        create:false,
                        columnResizable:false,
                        title:'gene',
                        display: function (data) {
                            if(data.record.ftr=='intergenic.igt' || data.record.ftr=='intergenic.pm'){
                                if(data.record.strand=='-'){
                                    return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"&strand=-1&flag=intergenic&coord="+data.record.coord+"\">"+data.record.gene+"</a>";
                                }
                                else
                                    return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"&strand=1&flag=intergenic&coord="+data.record.coord+"\">"+data.record.gene+"</a>";
                            }
                            else{
                                return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"\">"+data.record.gene+"</a>";
                            }
                        }
                    },
                    chr:{
                        title:'chromosome',
                        edit:false,
                        width:'10%'
                    },
                    strand:{
                        title:'strand',
                        edit:false,
                        width:'5%'
                    },
                    coord:{
                        title:'coordinate',
                        edit:false,
                        width:'10%'
                    },
                    ftr:{
                        title:'ftr',
                        edit:false,
                        width:'10%'
                    },
                     <?php
                     foreach ($_SESSION['file_real'] as $key => $value) {
                            echo $value.":{
                                title:'$value',
                                edit:false
                                },";
                     }
                     ?>
                    detail:{
                        title:'view',
                        display: function (data) {
                            if(data.record.ftr=='intergenic.igt' || data.record.ftr=='intergenic.pm'){
                                if(data.record.strand=='-'){
                                    return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"&strand=-1&flag=intergenic&coord="+data.record.coord+"\"><img align='center' src='./pic/browser.png'/></a>";
                                }
                                else
                                    return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"&strand=1&flag=intergenic&coord="+data.record.coord+"\"><img align='center' src='./pic/browser.png'/></a>";
                            }
                            else{
                                return "<a title='click to view detail' target=\"_blank\" href=\"./sequence_detail.php?species="+species+"&seq="+data.record.gene+"\"><img align='center' src='./pic/browser.png'/></a>";
                            }
                        }
                    },
                    view:{
                        title:'jbrowse',
                        display: function (data) {
                            return "<a title='click to view detail in jbrowse' target=\"_blank\" href=\"../jbrowse/?data=data/"+species+"&amp;loc="+data.record.chr+":"+data.record.coord+"\">"+"<img src='./pic/detail.png'/></a>";
                        }
                    }
                }
            });

            $('#jtable').jtable('load');
            $('#filter').appendTo(".jtable-title").addClass('filter_class');
            $('#search_button').click(function (e){
                e.preventDefault();
                        $('#jtable').jtable('load',{
                            search: $('#search').val()
                        });
                    });
            $('#reset_button').click(function(e){
                e.preventDefault();
                        $('#jtable').jtable('load');
                    });
        });
    </script>
    <!--Step:1 为ECharts准备一个具备大小（宽高）的Dom-->  
    <div id="ATCG" style="height:500px;border:1px solid #ccc;padding:10px;"></div>
    <div id="k6" style="height:600px;border:1px solid #ccc;padding:10px;"></div>
    
    <!--Step:2 引入echarts.js-->  
    <script src="src/dist/echarts.js"></script>  
    
    <script type="text/javascript">         
 
    // Step:3 conifg ECharts's path, link to echarts.js from current page.  
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
            var myChart = ec.init(document.getElementById('ATCG'));  
            //定义图表option  
            var option = {  
                //标题，每个图表最多仅有一个标题控件，每个标题控件可设主副标题  
                title: {  
                    //主标题文本，'\n'指定换行  
                    text: 'Single Nucleotide Compositions', 
                    subtext: 'exon',
                    x: 'left',  
                    //垂直安放位置，默认为全图顶端，可选为：'top' | 'bottom' | 'center' | {number}（y坐标，单位px）  
                    y: 'top'  
                },  
                //提示框，鼠标悬浮交互时的信息提示  
                tooltip: {  
                    //触发类型，默认（'item'）数据触发，可选为：'item' | 'axis'  
                    trigger: 'axis'  
                },  
                //图例，每个图表最多仅有一个图例  
                legend: {  
                    //显示策略，可选为：true（显示） | false（隐藏），默认值为true  
                    show: true,  
                    //水平安放位置，默认为全图居中，可选为：'center' | 'left' | 'right' | {number}（x坐标，单位px）  
                    x: 'center',  
                    //垂直安放位置，默认为全图顶端，可选为：'top' | 'bottom' | 'center' | {number}（y坐标，单位px）  
                    y: 'top',  
                    //legend的data: 用于设置图例，data内的字符串数组需要与sereis数组内每一个series的name值对应  
                    data: ['A','T','C','G']  
                },  
                //工具箱，每个图表最多仅有一个工具箱  
                toolbox: {  
                    //显示策略，可选为：true（显示） | false（隐藏），默认值为false  
                    show: true,  
                    //启用功能，目前支持feature，工具箱自定义功能回调处理  
                    orient: 'vertical',
                    x:'right',
                    y:'center',
                    feature: {  
                        //dataZoom，框选区域缩放，自动与存在的dataZoom控件同步，分别是启用，缩放后退  
                        dataZoom: {  
                            show: true,  
                             title: {  
                                dataZoom: '区域缩放',  
                                dataZoomReset: '区域缩放后退'  
                            }  
                        },  
                        //数据视图，打开数据视图，可设置更多属性,readOnly 默认数据视图为只读(即值为true)，可指定readOnly为false打开编辑功能  
                        dataView: {show: true, readOnly: true},  
                        //magicType，动态类型切换，支持直角系下的折线图、柱状图、堆积、平铺转换  
                        magicType: {show: true, type: ['line', 'bar']},  
                        //restore，还原，复位原始图表  
                        restore: {show: true},  
                        //saveAsImage，保存图片（IE8-不支持）,图片类型默认为'png'  
                        saveAsImage: {show: true}  
                    }  
                },  
                //是否启用拖拽重计算特性，默认关闭(即值为false)  
                calculable: true,  
                //直角坐标系中横轴数组，数组中每一项代表一条横轴坐标轴，仅有一条时可省略数值  
                //横轴通常为类目型，但条形图时则横轴为数值型，散点图时则横纵均为数值型  
                xAxis: [  
                    {  
                        //显示策略，可选为：true（显示） | false（隐藏），默认值为true  
                        show: true,  
                        //坐标轴类型，横轴默认为类目型'category'  
                        type: 'category',  
                        //类目型坐标轴文本标签数组，指定label内容。 数组项通常为文本，'\n'指定换行
                        boundaryGap : false,
                        <?php
                            echo "data:[";
                            foreach ($array_no as $key => $value) {
                                if($key!=0)
                                    echo ",";
                                echo "$value";                                  
                            }
                           echo "]";
                         ?>
                    }
                ],  
                //直角坐标系中纵轴数组，数组中每一项代表一条纵轴坐标轴，仅有一条时可省略数值  
                //纵轴通常为数值型，但条形图时则纵轴为类目型  
                yAxis: [  
                    {  
                        //显示策略，可选为：true（显示） | false（隐藏），默认值为true  
                        show: true,  
                        //坐标轴类型，纵轴默认为数值型'value'  
                        type: 'value',  
                        //分隔区域，默认不显示  
                        splitArea: {show: true}  
                    }  
                ],  
                  
                //sereis的数据: 用于设置图表数据之用。series是一个对象嵌套的结构；对象内包含对象  
                series: [  
                    {  
                        //系列名称，如果启用legend，该值将被legend.data索引相关  
                        name: 'A',  
                        //图表类型，必要参数！如为空或不支持类型，则该系列数据不被显示。  
                        type: 'line',  
                        //系列中的数据内容数组，折线图以及柱状图时数组长度等于所使用类目轴文本标签数组axis.data的长度，并且他们间是一一对应的。数组项通常为数值  
                        symbol : 'none',
                        smooth : true,
                        <?php
                                    echo "data:[";
                                    foreach ($array_a as $key => $value) {
                                            if($key!=0)
                                                echo ",";
                                            echo "$value";                                  
                                    }
                                    echo"]";
                             ?>
                        //系列中的数据标注内容  
                        
                    },  
                    {  
                        //系列名称，如果启用legend，该值将被legend.data索引相关  
                        name: 'T',  
                        //图表类型，必要参数！如为空或不支持类型，则该系列数据不被显示。  
                        type: 'line',  
                        //系列中的数据内容数组，折线图以及柱状图时数组长度等于所使用类目轴文本标签数组axis.data的长度，并且他们间是一一对应的。数组项通常为数值  
                        //data: [2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3],  
                        symbol : 'none',
                        <?php
                                    echo "data:[";
                                    foreach ($array_t as $key => $value) {
                                            if($key!=0)
                                                echo ",";
                                            echo "$value";                                  
                                    }
                                    echo"]";
                             ?>
                        //系列中的数据标注内容  
                        
                         },
                         {  
                        //系列名称，如果启用legend，该值将被legend.data索引相关  
                        name: 'C',  
                        //图表类型，必要参数！如为空或不支持类型，则该系列数据不被显示。  
                        type: 'line',  
                        //系列中的数据内容数组，折线图以及柱状图时数组长度等于所使用类目轴文本标签数组axis.data的长度，并且他们间是一一对应的。数组项通常为数值  
                        //data: [2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3],  
                        symbol : 'none',
                        <?php
                                    echo "data:[";
                                    foreach ($array_c as $key => $value) {
                                            if($key!=0)
                                                echo ",";
                                            echo "$value";                                  
                                    }
                                    echo"]";
                             ?>
                        //系列中的数据标注内容  
                        
                         },
                         {  
                        //系列名称，如果启用legend，该值将被legend.data索引相关  
                        name: 'G',  
                        //图表类型，必要参数！如为空或不支持类型，则该系列数据不被显示。  
                        type: 'line',  
                        //系列中的数据内容数组，折线图以及柱状图时数组长度等于所使用类目轴文本标签数组axis.data的长度，并且他们间是一一对应的。数组项通常为数值  
                        //data: [2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3],  
                        symbol : 'none',
                        <?php
                                    echo "data:[";
                                    foreach ($array_g as $key => $value) {
                                            if($key!=0)
                                                echo ",";
                                            echo "$value";                                  
                                    }
                                    echo"]";
                             ?>
                        //系列中的数据标注内容  
                       
                    }  
                ]  
            };  
                  
            //为echarts对象加载数据              
            myChart.setOption(option); 
            
            var myChart1 = ec.init(document.getElementById('k6'));  
  
            var option1 = {  
                title: {  
                    text: 'Hexamers', 
                    subtext: 'exon',
                    x: 'left',  
                    y: 'top'  
                },  
                tooltip: {  
                    trigger: 'item'  
                }, 
                legend: {  
                    show: true,  
                    x: 'center',    
                    y: 'top', 
                    //data: ['A','T','C','G']
                    <?php
                            echo "data:[";
                            foreach ($array_k6 as $key => $value) {
                                if($key!=0)
                                    echo ",";
                                echo "$value";                                  
                            }
                           echo "]";
                         ?>
                },  
                toolbox: {  
                    show: true, 
                    orient: 'vertical',
                    x:'right',
                    y:'center',
                    feature: {  
                        dataZoom: {  
                            show: true,  
                             title: {  
                                dataZoom: '区域缩放',  
                                dataZoomReset: '区域缩放后退'  
                            }  
                        },  
                        dataView: {show: true, readOnly: true},  
                        magicType: {show: true, type: ['line', 'bar']},  
                        restore: {show: true},  
                        saveAsImage: {show: true}  
                    }  
                },  
                calculable: true,  
                xAxis: [  
                    {  
                        show: true,  
                        type: 'category',  
                        data : ['sum']
                    }
                ],  
                yAxis: [  
                    {  
                        show: true,  
                        type: 'value',
                        data : ['Occurrences'],
                        splitArea: {show: true}  
                    }  
                ],  
                  
                series: [  
                    <?php
                        foreach ($array_k6 as $key => $value) {
                            if($key != 0)
                            {
                                    echo ",";
                            }
                            if($key!= count($array_sum)-1)
                                echo "{name:$value,type:\"bar\",data:[$array_sum[$key]]}";
                        }
                    ?>
                ]  
            };  
                   
            myChart1.setOption(option1);  
        }  
    );  
    </script>  
</div>
    <?php
//        include './wheelmenu.php';
        include './footer.php';
    ?>
</body>
</html>