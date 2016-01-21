<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Sequence Detail</title>
        <script src="./src/jquery-1.10.1.min.js"></script>
        <!-- Mobile viewport optimisation -->
        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <!--[if lte IE 7]>
        <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <!--[if lt IE 9]>
        <script src="./js/html5shiv/html5shiv.js"></script>
        <![endif]-->

        <!--<link rel="stylesheet" href="./src/font-awesome.min.css">-->
        <!--<link rel="stylesheet" href="./src/example.css">-->
        <!-- sliders -->
        <script src="./src/slider/js/jquery-plus-ui.min.js"></script>
        <script src="./src/slider/js/jquery-ui-slider-pips.js"></script>
        <link rel="stylesheet" href="./src/slider/css/jqueryui.min.css" />
        <link rel="stylesheet" href="./src/slider/css/jquery-ui-slider-pips.min.css" />
        <!--<link rel="stylesheet" href="./src/slider/css/app.min.css" />-->
        <!-- dataTables -->
        <script src="./src/jquery.dataTables.min.js"type="text/javascript" ></script>
        <link href="./src/jquery.dataTables.css"type="text/css" rel="stylesheet"></link>
        <!-- pwstabs -->
        <link type="text/css" rel="stylesheet" href="./src/pws-tabs/jquery.pwstabs-1.2.1.css"></link>
        <script src="./src/pws-tabs/jquery.pwstabs-1.2.1.js"></script>
        <!-- sumoselect -->
         <script src="./src/optselect/jquery.sumoselect.js"></script>
        <link href="./src/optselect/sumoselect.css" rel="stylesheet" />
        <style>
            .patt_text{
                font-size: 15px;
            }
            .CaptionCont SlectBox{
                height: 14px;
                padding: 0px 8px;
            }
            .slider{
                width: 700px;
            }
            #text{
                padding-top: 20px;
            }
            .ui-widget-content .ui-slider-handle.ui-state-default{
                background: #434d5a;
                border-color: #434d5a;
            }
            .ui-widget-content .ui-slider-handle.ui-state-hover {
                background: #00c7d7;
                border-color: #00c7d7;
            }
            .ui-slider-pips .ui-slider-pip-selected-first{
                font-weight: 700;
                color: #FF7A00;
            }
            table{
                font-size: 12px;
                table-layout: fixed;
                word-wrap: break-word;
                word-break: break-all;
                overflow: hidden;
            }
            #gotable td,#genetable td,#polyatable td{
                padding: 4px 4px;
            }
            #straight_matter{
                font-family:Courier New;
            }
            .scale{
                color:#324a17;
                font-weight: bold;
            }
            .sutr{
                /*background-color: #6F00D2;*/
                font-weight: bold;
                color: gray;
            }
            .wutr{
                /*background-color: #F75000;*/
                font-weight: bold;
                color: gray;
            }
            .cds{
                background-color: #D1EEEE;
            }
            .intron{
                /*background-color: #5B5B5B;*/
                color: gray;
            }
            .exon{
                background-color: #D1EEEE;
            }
            .amb{
                background-color: #FFF68F;
            }
            .extend{
                /*background-color: #9aff9a;*/
                text-decoration: underline;
            }
            .pa{
                background-color: #FFAEB9
            }
            .pac{
                background-color: #EEC900;
                color:red;
            }
            .patt1{
                background-color: #4EEE94;
            }
            .patt2{
                background-color: #00EE00;
            }
            .aat{
                background-color: #008B00;
            }
            .tgt{
                background-color: #008B00;
            }
            fieldset{
                border-color: #5499c9 !important;
                border-style: solid !important;
                border-width: 2px !important;
                padding: 5px 10px !important;
            }
            .wrap{margin:0px auto;width: 100%;}
            .tabs {width:100%;}
            .tabs a{display: block;float: left;width:33.3%;color: #5499c9;text-align: center;background: #eee;line-height: 40px;font-size:16px;text-decoration: none;}
            .tabs a.active {color: #fff;background: #5499c9;border-radius: 5px 5px 0px 0px;}
            .swiper-container {height:500px;border-radius: 0 0 5px 5px;width: 100%;border-top: 0;background-color:#fff}
            .swiper-slide {height:325px;width:100%;background: none;color: #fff;}
            /*.content-slide {padding: 40px;}*/
            /*.content-slide{overflow-x: scroll;overflow-y: scroll}*/
            .content-slide p{text-indent:2em;line-height:1.9;}
            .slidebar-content{color: black;}
            .swiper-slide{color:black;}
            @media(max-width: 1245px){
                #tables{overflow: scroll}
            }
            .h3_italic{
                font-size: 15px;
                font-family: "Droid Serif", Georgia, "Times New Roman", Times, serif;
                font-weight: bold;
            }
            #text{
                font-size: 15px;
                font-family: "Droid Serif", Georgia, "Times New Roman", Times, serif;
            }
        </style>
        <script src="./src/idangerous.swiper.min.js"></script> 
        <link rel="stylesheet" href="./src/idangerous.swiper.css">
    </head>
    <body>
        <?php
            include"navbar.php";
        ?>
        <?php
            $con=  mysql_connect("localhost","root","root");
            mysql_select_db("db_server",$con);
            session_start();
            
            $seq = $_GET['seq'];
            if(isset($_GET['species'])){
                $species = $_GET['species'];
            }
            else{
                $species = $_SESSION['species'];
            }
            
            //获得strand
            if($_GET['flag']=='intergenic'){
                 $cgs=  mysql_query("select * from db_server.t_".$species."_gff_all where gene='".$_GET['seq']."' and ftr='intergenic';");
            }
            else{
                $cgs=  mysql_query("select * from db_server.t_".$species."_gff_all where gene='".$_GET['seq']."' and ftr='gene';");
            }
            while($cgs_row=  mysql_fetch_row($cgs)){
                $chr=$cgs_row[0];
                $gene=($cgs_row[3]+$cgs_row[4])/2;
    //            echo $gene;
                if($cgs_row[1]=='+'){
                    $strand=1;
                }
                else{
                    $strand=-1;
                }
            }
        ?>
        <?php
        $sequence = file_get_contents("./seq/$species/$seq.fa");
            //如果是intergenic
            if($_GET['flag']=='intergenic'){
                $coord=$_GET['coord'];
                $coordL=$coord-200;
                $coordH=$coord+200;
                $seq_result=mysql_query("select substring(seq,$coordL,401) from db_server.t_".$species."_fa where title='$chr';");
                while($rows=mysql_fetch_row($seq_result))
                {
                    $sequence=$rows[0];
                }
            }
            //反转互补
            if(strcmp($strand,-1)==0)
            {
                $sequence = strrev($sequence);
                $seq_arr=str_split($sequence);
                array_shift($seq_arr);
                foreach ($seq_arr as &$value) {
                    if($value=='A')
                        $value='T';
                    else if($value=='T'||$value=='U')
                        $value='A';
                    else if($value=='C')
                        $value='G';
                    else if($value=='G')
                        $value='C';
                    else
                        $value='N';
                }
                $sequence=  implode($seq_arr);
            }
            else{
                $sequence=substr($sequence,0,strlen($sequence)-1); 
            }
            $singnals = array("AATAAA","TGTAAA","CATAAA","GATAAA","ATTAAA","ACTAAA","AGTAAA","AAAAAA","AACAAA","AAGAAA","AATTAA","AATCAA","AATGAA","AATATA","AATACA","AATAGA","AATAAT","AATAAC","AATAAG");        
            //取sequence的起始和终点坐标
            if($_GET['flag']=='intergenic'){
                $a="SELECT * from db_server.t_".$species."_gff_all where gene='$seq' and ftr='intergenic';";
            }
            else{
                $a="SELECT * from db_server.t_".$species."_gff_all where gene='$seq' and ftr='gene';";
            }
            $result=mysql_query($a);
            while($row=mysql_fetch_row($result))
            {
                $gene_start=$row[3];
                $gene_end=$row[4];
            }
            //sequence处理，包括加空格、span坐标
            $sequence_pos = str_split($sequence);
            $seq_with_pos = "<span class='scale'>01&nbsp</span>";
            $row_number = 1;
            if($strand == 1){
                foreach ($sequence_pos as $key => $value) {
                    $coordinate = $gene_start+$key;
                    $seq_with_pos .="<span id='pos$coordinate'>$value</span>";
                    if($key%10==9)
                        $seq_with_pos.="&nbsp";
                    if($key%100 == 99){
                        $row_number++;
                        if($row_number<=9)
                            $seq_with_pos.="<br><span class='scale'>0$row_number&nbsp</span>";
                        else
                            $seq_with_pos.="<br><span class='scale'>$row_number&nbsp</span>";
                    }
                }
            }
            else if($strand == -1){
                foreach ($sequence_pos as $key => $value) {
                    $coordinate = $gene_end-$key;
                    $seq_with_pos .="<span id='pos$coordinate'>$value</span>";
                    if($key%10==9)
                        $seq_with_pos.="&nbsp";
                    if($key%100 == 99){
                        $row_number++;
                        if($row_number<=9)
                            $seq_with_pos.="<br><span class='scale'>0$row_number&nbsp</span>";
                        else
                            $seq_with_pos.="<br><span class='scale'>$row_number&nbsp</span>";
                    }
                }
            }
            //读取全序列信息
            $c="select * from db_server.t_".$species."_gff where gene like '$seq' ;";
            $seq_feature=  mysql_query($c);
            while($row_f=  mysql_fetch_row($seq_feature))
            {
                $ftr[]=$row_f[2];
                $f_start[]=$row_f[3];
                $f_end[]=$row_f[4];
            }
            //3utr extend 位置信息
            $ext_start = array();
            $ext_end = array();
            $extend=  mysql_query("select * from t_".$species."_gff_org where gene='$seq' and ftr='3UTR';");
            while($ext_r=  mysql_fetch_row($extend)){
                array_push($ext_start, $ext_r[3]);
                array_push($ext_end, $ext_r[4]);
            }
            //polyA 位点信息
            $pa_start=array();
            $pa_tagnum=array();
            $pa_result=mysql_query("select * from db_server.t_".$_GET['species']."_pa1 where chr='$chr' and coord>=$gene_start and coord<=$gene_end and tot_tagnum>0;");
            while ($pa_row=  mysql_fetch_row($pa_result))
            {
                array_push($pa_start, $pa_row[2]);
                array_push($pa_tagnum, $pa_row[3]);
            }
            //pac 信息
            $pac_start=array();
            $pac_tagnum=array();
            $pac_result=mysql_query("select * from db_server.t_".$_GET['species']."_pac where gene='$seq'");
            while ($pac_row=  mysql_fetch_row($pac_result))
            {
                array_push($pac_start, $pac_row[2]);
                array_push($pac_tagnum, $pac_row[3]);
            }
            echo "<script type=\"text/javascript\">";
            echo "var original_seq = '$sequence';";
            echo "var gene_start = $gene_start;";
            echo "var gene_end = $gene_end;";
            echo "var strand = $strand;";
            echo "var sutr_start=[];";
            echo "var sutr_end=[];";
            echo "var ext_start=[];";
            echo "var ext_end=[];";
            echo "var wutr_start=[];";
            echo "var wutr_end=[];";
            echo "var cds_start=[];";
            echo "var cds_end=[];";
            echo "var intron_start=[];";
            echo "var intron_end=[];";
            echo "var exon_start=[];";
            echo "var exon_end=[];";
            echo "var amb_start=[];";
            echo "var amb_end=[];";
            echo "var pa_start=[];";
            echo "var pa_tagnum=[];";
            echo "var pac_start=[];";
            echo "var pac_tagnum=[];";
            foreach ($pa_start as $key => $value)
            {
                echo "pa_start.push('$value');";
                echo "pa_tagnum.push('$pa_tagnum[$key]');";
            }
            foreach ($pac_start as $key => $value)
            {
                echo "pac_start.push('$value');";
                echo "pac_tagnum.push('$pac_tagnum[$key]');";
            }
            $i = -1;
            while(list($f_key,$val)=each($ftr))
            {
                if(strcmp($val, '3UTR')==0)
                {
                    $i++;
                    if($strand == 1){
                        if($ext_end[$i]!=null){
                            echo "sutr_start.push('$ext_start[$i]');";
                            echo "sutr_end.push('$ext_end[$i]');";
                            $ext_start_pos = $ext_end[$i]+1;
                        }
                        echo "ext_start.push('$ext_start_pos');";
                        echo "ext_end.push('$f_end[$f_key]');";
                    }
                    else if ($strand == -1){
                        if($ext_start[$i]!=null){
                            $ext_start_pos = $ext_start[$i]+1;
                            echo "sutr_start.push('$ext_start_pos');";
                            echo "sutr_end.push('$f_end[$f_key]');";
                        }
                        echo "ext_start.push('$f_start[$f_key]');";
                        echo "ext_end.push('$ext_start[$i]');";
                    }
                }
                if(strcmp($val, '5UTR')==0)
                {
                    echo "wutr_start.push('$f_start[$f_key]');";
                    echo "wutr_end.push('$f_end[$f_key]');";
                }
                if(strcmp($val, 'CDS')==0)
                {
                    echo "cds_start.push('$f_start[$f_key]');";
                    echo "cds_end.push('$f_end[$f_key]');";
                }
                if(strcmp($val, 'intron')==0)
                {
                    echo "intron_start.push('$f_start[$f_key]');";
                    echo "intron_end.push('$f_end[$f_key]');";
                }
               if(strcmp($val, 'exon')==0)
                {
                    echo "exon_start.push('$f_start[$f_key]');";
                    echo "exon_end.push('$f_end[$f_key]');";
                }
                if(strcmp($val, 'AMB')==0)
                {
                       echo "amb_start.push('$f_start[$f_key]');";
                       echo "amb_end.push('$f_end[$f_key]');";
                }
            }
            echo "</script>";
         ?>
        <script type="text/javascript">
            var pa_min = Math.min.apply(null,pa_start);
            var pa_max = Math.max.apply(null,pa_start);
            $(document).ready(function (){
                $('#other_patt').SumoSelect({ csvDispCount: 2,okCancelInMulti:true,selectAll:true });
                $('#find_patt').click(function(){
                    clear();
                    load_pattern();
                });
                 $('#reset').click(function(){
                    clear();
                });
                $("#annotation-select").click(function(){
                    var clk = $("#annotation-select").val();
                    if(clk =="checkall"){
                        $('#annotation-select').val('uncheck');
                        $("input[name=cbox2]:checked").each(function(){ 
                            this.checked = false;
                        }); 
                    } 
                    else if(clk == "uncheck")
                    {
                        $('#annotation-select').val('checkall');
                        $("input[name=cbox2]").each(function(){ 
                            this.checked = true;
                        }); 
                    }
                });
                $('#checkall').click(function(){
                    var value = $('#checkall').val();
                    if(value == "checkall")
                    {
                        $('#checkall').val('uncheck');
                        $("input[name=cbox]:checked").each(function(){ 
                            this.checked = false;
                        }); 
                    }
                    else if(value == "uncheck")
                    {
                        $('#checkall').val('checkall');
                        $("input[name=cbox]").each(function(){ 
                            this.checked = true;
                        }); 
                    }
                });
            });
            function clear(){
                for(var i = gene_start ; i<= gene_end ;i++){
                    $('#pos'+i).attr("class","");
                }
            }
            function load_pattern()
            {
                var patts1 = [];//存储user输入的pattern
                var patts2 = [];//存储勾选的pattern
                var ftr = [];//存储勾选的others
                var user_patt = $("#user_pattern").val();
                patts1 = user_patt.split(",");
                $("input[name=cbox]:checked").each(function(){ 
                    if($(this).val() != "checkall")
                    {
                        patts2.push($(this).val());
                    }
                });
                var a = $("#other_patt").children(":selected");
                for(var i in a){
                    if(a[i].value!=null)
                        patts2.push(a[i].value);
                }
                $("input[name=cbox2]:checked").each(function(){ 
                    ftr.push($(this).val());
                });
                find_pattern(patts1,patts2,ftr);
            }
            function find_pattern(patts1,patts2,ftr){
                //坐标过滤器
                var slider_min = $(".ui-slider-tip").html();
                var slider_all = $(".ui-slider-tip").text();
                var slider_max = slider_all.substr(slider_min.length);
//                alert("range:"+slider_min+":"+slider_max);
                var min = Number(pa_min) + Number(slider_min);
                var max = Number(pa_max) + Number(slider_max);
//                alert("range:"+min+":"+max);
                //ftr部分
                if(ftr.indexOf("UTR")!=-1){
                    if(sutr_start.length&&sutr_end.length!=0)
                    {
                        for(var sutrkey in sutr_start){
                            for(var i = sutr_start[sutrkey]; i<= sutr_end[sutrkey]; i++){
                                $('#pos'+i).addClass("sutr");
                                $('#pos'+i).attr("title","3' UTR:"+i);
                            }
                        }
                    }
                    if(wutr_start.length&&wutr_end.length!=0)
                    {
                        for(var wutrkey in wutr_start){
                            for(var i = wutr_start[wutrkey]; i<= wutr_end[wutrkey]; i++){
                                $('#pos'+i).addClass("wutr");
                                $('#pos'+i).attr("title","5' UTR:"+i);
                            }
                        }
                    }
                }
                if(ftr.indexOf("EXT")!=-1){
                    if(ext_start.length&&ext_end.length!=0)
                    {
                        for(var extkey in ext_start){
                            for(var i = ext_start[extkey]; i<= ext_end[extkey]; i++){
                                $('#pos'+i).addClass("extend");
                                $('#pos'+i).attr("title","Extended 3' UTR:"+i);
                            }
                        }
                    }
                }
//                if(ftr.indexOf("5UTR")!=-1){
//                    if(wutr_start.length&&wutr_end.length!=0)
//                    {
//                        for(var wutrkey in wutr_start){
//                            for(var i = wutr_start[wutrkey]; i<= wutr_end[wutrkey]; i++){
//                                $('#pos'+i).addClass("wutr");
//                            }
//                        }
//                    }
//                }
                if(ftr.indexOf("CDSEXON")!=-1){
                    if(cds_start.length&&cds_end.length!=0)
                    {
                        for(var cdskey in cds_start){
                            for(var i = cds_start[cdskey]; i<= cds_end[cdskey]; i++){
                                $('#pos'+i).addClass("cds");
                                $('#pos'+i).attr("title","CDS/exon:"+i);
                            }
                        }
                    }
                    if(exon_start.length&&exon_end.length!=0)
                    {
                        for(var exonkey in exon_start){
                            for(var i = exon_start[exonkey]; i<= exon_end[exonkey]; i++){
                                $('#pos'+i).addClass("exon");
                                $('#pos'+i).attr("title","CDS/exon:"+i);
                            }
                        }
                    }
                }
                if(ftr.indexOf("INTRON")!=-1){
                    if(intron_start.length&&intron_end.length!=0)
                    {
                        for(var intronkey in intron_start){
                            for(var i = intron_start[intronkey]; i<= intron_end[intronkey]; i++){
                                $('#pos'+i).addClass("intron");
                                $('#pos'+i).attr("title","intron:"+i);
                            }
                        }
                    }
                }
//                if(ftr.indexOf("EXON")!=-1){
//                    if(exon_start.length&&exon_end.length!=0)
//                    {
//                        for(var exonkey in exon_start){
//                            for(var i = exon_start[exonkey]; i<= exon_end[exonkey]; i++){
//                                $('#pos'+i).addClass("exon");
//                            }
//                        }
//                    }
//                }
                if(ftr.indexOf("AMB")!=-1){
                    if(amb_start.length&&amb_end.length!=0)
                    {
                        for(var ambkey in amb_start){
                            for(var i = amb_start[ambkey]; i<= amb_end[ambkey]; i++){
                                $('#pos'+i).addClass("amb");
                                $('#pos'+i).attr("title","AMBiguous region:"+i);
                            }
                        }
                    }
                }
                if(ftr.indexOf("PA")!=-1){
                    if(pa_start.length!=0)
                    {
                        for(var pakey in pa_start){
//                            console.log(pa_start[pakey]);
                            $('#pos'+pa_start[pakey]).addClass("pa");
                            $('#pos'+pa_start[pakey]).attr("title","cleavage site"+pa_start[pakey]);
                        }
                    }
                }
                if(ftr.indexOf("PAC")!=-1){
                    if(pac_start.length!=0)
                    {
                        for(var packey in pac_start){
//                            console.log(pa_start[pakey]);
                            $('#pos'+pac_start[packey]).addClass("pac");
                            $('#pos'+pac_start[packey]).attr("title","PAC:"+pac_start[packey]);
                        }
                    }
                }
                //pattern部分
                //user pattern
                var pos1_start = [];
                var pos1_end = [];
                for(var key1 in patts1)
                {
                    var patt = patts1[key1];
                    if(patt != "")
                    {
                        patt = patt.replace(/[ ]/g,""); 
                        if(patt.length == 1)
                        {
                            alert('The minimum length of pattern is 2!');
                            return;
                        }
                        var reg=new RegExp(patt,"gi");
                        var result;
                        var j = -1;
                        while((result = reg.exec(original_seq)) != null)
                        {
                            if(strand == -1){
                                j++;
                                pos1_end.push(gene_end - result.index);
                                pos1_start.push(gene_end  - patt.length - result.index+1);
                            }
                            else if(strand == 1){
                                j++;
                                pos1_end.push(gene_start + result.index + patt.length - 1);
                                pos1_start.push(gene_start + result.index);
                            }
                            for(var i = pos1_start[j]; i<= pos1_end[j];i++){
                                if(min <= i && i <= max){
                                    $('#pos'+i).addClass("patt1");
                                    $('#pos'+i).attr("title","Poly(A) signal:"+i);
                                }
                            }
                        }
                    }
                }
                //select pattern
                var pos2_start = [];
                var pos2_end = [];
                var aat_start = [];
                var aat_end = [];
                var tgt_start = [];
                var tgt_end = [];
                for(var key2 in patts2)
                {  
                    var patt = patts2[key2];
                    if(patt != "")
                    {
                            var reg=new RegExp(patt,"gi");
                            var result;
                            var j = -1;
                            while((result = reg.exec(original_seq)) != null)
                            {
                                if(strand == -1){
                                    j++;
                                    if(patt.toUpperCase() == "AATAAA")
                                    {
                                        aat_start.push(gene_end - result.index - patt.length + 1);
                                        aat_end.push(gene_end - result.index);
                                    }
                                    else if(patt.toUpperCase() == "TGTAA")
                                    {
                                        tgt_start.push(gene_end - result.index - patt.length + 1);
                                        tgt_end.push(gene_end - result.index);
                                    }
                                    else
                                    {
                                        pos2_end.push(gene_end - result.index);
                                        pos2_start.push(gene_end - result.index - patt.length + 1);
                                    }
                                }
                                else if(strand == 1){
                                    j++;
                                    if(patt.toUpperCase() == "AATAAA")
                                    {
                                        aat_start.push(gene_start + result.index);
                                        aat_end.push(gene_start + result.index + patt.length - 1);
                                    }
                                    else if(patt.toUpperCase() == "TGTAA")
                                    {
                                        tgt_start.push(gene_start + result.index);
                                        tgt_end.push(gene_start + result.index + patt.length - 1);
                                    }
                                    else
                                    {
                                        pos2_end.push(gene_start + result.index + patt.length - 1);
                                        pos2_start.push(gene_start + result.index);
                                    }
                                }
                                for(var i = pos2_start[j]; i<= pos2_end[j];i++){
                                    if(min <= i && i <= max){
                                        $('#pos'+i).addClass("patt2");
                                        $('#pos'+i).attr("title","Poly(A) signal:"+i);
                                    }
                                }
                                for(var i = aat_start[j]; i<= aat_end[j];i++){
                                    if(min <= i && i <= max){
                                        $('#pos'+i).addClass("aat");
                                        $('#pos'+i).attr("title","Poly(A) signal:"+i);
                                    }
                                }
                                for(var i = tgt_start[j]; i<= tgt_end[j];i++){
                                    if(min <= i && i <= max){
                                        $('#pos'+i).addClass("tgt");
                                        $('#pos'+i).attr("title","Poly(A) signal:"+i);
                                    }
                                }
                            }
                    }
                }
            }
        </script>
            
        <div  id="page" style="width:1200px;margin:auto">
            <table  cellspacing="0" cellpadding="0" border="0" style="margin: 20px auto;border-collapse:collapse;" >
            <tbody>
                <tr style="border: medium solid #5499c9;">
                    <td valign="top" id='tables' style="border-right: solid #5499c9;">
                    
                        <div id="gene">
                            <table id="genetable">
                                <tbody>
                                    <tr>
                                        <td>Gene name:</td>
                                        <td><?php echo $_GET['seq']?></td>
                                    </tr>
                                    <tr>
                                        <td>Chromosome:</td>
                                        <td><?php echo $chr;?></td>
                                    </tr>
                                    <tr>
                                        <td>Gene locus:</td>
                                        <td><?php echo $chr.":".$gene_start."-".$gene_end;?></td>
                                    </tr>
                                    <tr>
                                        <td>Gene Type:</td>
                                        <?php
                                                    if($_GET['flag']=='intergenic'){
                                                        $sql="select gene_type from t_".$species."_gff_all where gene=\"".$_GET['seq']."\" and ftr='intergenic';";
                                                    }
                                                    else
                                                        $sql="select gene_type from t_".$species."_gff_all where gene=\"".$_GET['seq']."\" and ftr='gene';";
                                                    $type=mysql_query($sql);
                                                    while($gene_type= mysql_fetch_row($type)){
                                                            echo "<td>$gene_type[0]</td>";
                                                    }
                                                ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr size="2" align="left" style="border-top: 2px solid #5499c9;">
                        <div id="polya" style="overflow-x: auto;background-color: #fff;">
                            <table id="polyatable"  class="display dataTable" cellspacing="0" role="grid" aria-describedby="example_infox" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <!--<th>PAC</th>-->
                                        <th>Coordinate</th>
                                        <th>PA#</th>
                                        <th>PAT#</th>
                                        <th>PAC range</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                            $pac_res=mysql_query("select * from t_".$species."_pac where gene='$seq';");
                                            while($pac_r=  mysql_fetch_row($pac_res)){
//                                                $i=1;
                                                echo "<tr>";
//                                                        . "<td>PAC$i</td>";
                                                if($ext_end[0]==NULL|| $ext_start[0] ==NULL){
                                                         echo "<td>$pac_r[2](3UTR)</td>";
                                                }
                                                else if($strand==1&&$pac_r[2]>$ext_end[0])
                                                    echo "<td>$pac_r[2](3UTR Extend)</td>";
                                                else if($strand==-1&&$pac_r[2]<$ext_start[0])
                                                    echo "<td>$pac_r[2](3UTR Extend)</td>";
                                                else
                                                    echo "<td>$pac_r[2](3UTR)</td>";
                                                echo "<td>$pac_r[12]</td>"
                                                        . "<td>$pac_r[3]</td>"
                                                        . "<td>$pac_r[10]~$pac_r[11]</td>";
//                                                                if($pac_r[2]<$ext_start)
//                                                                    echo "<td>3UTR extend</td>";
//                                                                else 
//                                                                    echo "<td>3UTR</td>"
//                                                $i++;

                                            }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </td>
                    <td >
                     
                        <div id="go" style="overflow-x: auto;background-color: #fff;margin:auto;">
                            <table id="gotable" class="display dataTable" cellspacing="0" role="grid" aria-describedby="example_infox" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th>Go id</th>
                                        <th>Go term</th>
                                        <th>Go function</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php
                                                    $go_sql="select * from t_".$species."_go where gene=\"".$_GET['seq']."\";";
                                                    $go_result=mysql_query($go_sql);
//                                                    echo $sql;
//                                                    $type=mysql_query("select * from db_bio.gff_arab10_all where gene=\"AT2G01008\";");
//                                                    echo "select gene_type from db_bio.gff_arab10_all where gene=\"".$_GET['seq']."\";";
//                                                        var_dump($type);
                                                    while($go_result_row= mysql_fetch_row($go_result)){
                                                            echo "<tr>";
                                                            echo "<td>$go_result_row[1]</td>";
                                                            echo "<td>$go_result_row[2]</td>";
                                                            echo "<td>$go_result_row[5]</td>";
                                                            echo "</tr>";
                                                    }
                                                ?>
                                </tbody>
                            </table>
                        </div>
                        <script>
                            $(document).ready(function(){
                                $('#gotable').dataTable({
                                    "lengthMenu":[[5,10,15,-1],[5,10,15,"all"]],
                                    "pagingType":"full_numbers",
                                    lengthChange:false,
                                    info:false,
                                    language: {
                                        paginate: {
                                            first:    '«',
                                            previous: '‹',
                                            next:     '›',
                                            last:     '»'
                                        },
                                        aria: {
                                            paginate: {
                                                first:    'First',
                                                previous: 'Previous',
                                                next:     'Next',
                                                last:     'Last'
                                            }
                                        }
                                    },
                                    searching:false
                                });
                                $('#polyatable').dataTable({
                                    "lengthMenu":[[5,10,15,-1],[5,10,15,"all"]],
                                    "pagingType":"full_numbers",
                                    lengthChange:false,
                                    info:false,
                                    language: {
                                        paginate: {
                                            first:    '«',
                                            previous: '‹',
                                            next:     '›',
                                            last:     '»'
                                        },
                                        aria: {
                                            paginate: {
                                                first:    'First',
                                                previous: 'Previous',
                                                next:     'Next',
                                                last:     'Last'
                                            }
                                        }
                                    },
                                    searching:false
                                });
                            });
                        </script>
                    </td>
                </tr>
                <tr>
                    <td colspan=2 style="padding-left:0px;padding-right:0px;">
                        <div class="tabs" style="padding:0px;border:solid #5499c9">
                            <div data-pws-tab="jbrowse" data-pws-tab-name="Jbrowse">
                                <iframe src="../jbrowse/?data=data/<?php echo $_GET['species'];?>&loc=<?php echo $chr;?>:<?php echo $gene_start;?>..<?php echo $gene_end;?>&tracks=Arabidopsis,sys_polya" width=1200px height=500px>
                                </iframe>
                            </div>
                            <div data-pws-tab="genepic" data-pws-tab-name="PAT distribution">
                                <?php
                                    if($_GET['flag']=='intergenic'){
                                        if($_GET['analysis']==1)
                                            echo "<iframe id='genepic' src=\"./genepic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&intergenic=1&coord=$coord&analysis=1\" width=1190px  height=500px></iframe>";
                                        else
                                            echo "<iframe id='genepic' src=\"./genepic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&intergenic=1&coord=$coord\" width=1190px  height=500px></iframe>";
                                    }
                                    else{
                                        if($_GET['analysis']==1)
                                            echo "<iframe id='genepic' src=\"./genepic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&analysis=1\" width=1190px  height=500px></iframe>";
                                        else
                                            echo "<iframe id='genepic' src=\"./genepic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand\" width=1190px  height=500px></iframe>";
                                    }
                                    ?>
                            </div>
                            <div data-pws-tab="pacpic" data-pws-tab-name="PAC usage">
                                <?php
                                    if($_GET['flag']=='intergenic'){
                                        if($_GET['analysis']==1)
                                            echo "<iframe src=\"./pacpic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&intergenic=1&coord=$coord&analysis=1\" width=1190px  height=500px></iframe>";
                                        else
                                            echo "<iframe src=\"./pacpic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&intergenic=1&coord=$coord\" width=1190px  height=500px></iframe>";
                                    }
                                    else{
                                        if($_GET['analysis']==1)
                                            echo "<iframe src=\"./pacpic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&coord=$coord&analysis=1\" width=1190px  height=500px></iframe>";
                                        else
                                            echo "<iframe src=\"./pacpic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&coord=$coord\" width=1190px  height=500px></iframe>";
                                    }
                                ?>
                            </div>
                         </div>
                        <script>
                            jQuery(document).ready(function($){
                                $('.tabs').pwstabs({
                                effect: 'slideleft',
                                defaultTab: 1,
                                containerWidth: '1196px'
                             });
                             });
                        </script>
                    </td>
                </tr>
            </tbody>
            </table>
            <div  class="straight_matter">
            <fieldset style="margin-top: 20px;">
                <legend>
                    <span class="h3_italic" style="font-size:21px">
                        <font color="#224055"><b>Sequence Viewer</b></font>
                    </span>
                </legend>
                <div class = "seq_viewer" id="seq_viewer">
                    <div id = "pattern">	
                        <span class="h3_italic">Typical Pattern</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--                            <table style="width:950px;margin-top:10px;margin-bottom:10px;font-family: Courier New;font-size: 15px;">
                                <?php
//                                echo "<tr>";
//                                echo '<td><input type="checkbox" checked = "true" name = "cbox" id = "checkall" value = "checkall" /><em>&nbsp;Change All</em></td>';
//                                $i = 0;
//                                foreach ($singnals as $key => $value) {
//                                        if($i == 6||$i == 13)
//                                        {
//                                                echo "</tr><tr>";
//                                        }
//                                        echo '<td><input type="checkbox" name = "cbox" checked = "true" value = "'.$value.'"/>&nbsp;'.$value.'</td>';
//                                        $i++;
//                                }
//                                echo "</tr>";
                                ?>
                            </table>-->
                            <input type="checkbox" name="cbox" value="AATAAA"/><font class="patt_text">AATAAA</font>&nbsp;<span class='aat' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="cbox" value="TGTAA"/><font class="patt_text">TGTAA</font>&nbsp;<span class='tgt' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <font class="patt_text">Others</font>&nbsp;<span class='patt2' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;<select id="other_patt"   multiple="multiple" placeholder="Select to display" class="okbutton" >
                                    <option selected value='CATAAA'>CATAAA</option>
                                    <option selected value='GATAAA'>GATAAA</option>
                                    <option selected value='ATTAAA'>ATTAAA</option>
                                    <option selected value='ACTAAA'>ACTAAA</option>
                                    <option selected value='AGTAAA'>AGTAAA</option>
                                    <option selected value='AAAAAA'>AAAAAA</option>
                                    <option selected value='AACAAA'>AACAAA</option>
                                    <option selected value='AAGAAA'>AAGAAA</option>
                                    <option selected value='AATTAA'>AATTAA</option>
                                    <option selected value='AATCAA'>AATCAA</option>
                                    <option selected value='AATGAA'>AATGAA</option>
                                    <option selected value='AATATA'>AATATA</option>
                                    <option selected value='AATACA'>AATACA</option>
                                    <option selected value='AATAGA'>AATAGA</option>
                                    <option selected value='AATAAC'>AATAAC</option>
                                    <option selected value='AATAAG'>AATAAG</option>
                                </select>
                            <br><br>
                                    <span class="h3_italic">User’s Pattern</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='patt1' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            <input type = "text" id = "user_pattern" style="margin-top:10px;margin-bottom:10px;width:217px;height: 25px"/>(Ex. AATAAA, TGTAAA)
                            <br>
                            <div style="float: left;margin-right: 20px;">Search</div><div class="slider" style="float:left"></div><div style="float: left;margin-left: 20px;">around PAC</div>
                            <legend id='text' style="clear:both"><span class="h3_italic">Annotation</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id='annotation-select' value="uncheck"/>all<br>
                                <div style="padding:10px 0px;font-family: 'Helvetica Neue', Arial, Helvetica, Geneva, sans-serif">
                                    <input type="checkbox" name="cbox2" value="EXT"/><span class='extend' style="text-align:center;">Extended 3'UTR</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" name="cbox2" value="UTR"/><span class='sutr' style="text-align:center;">UTR</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <!--<input type="checkbox" name="cbox2" value="5UTR"/>5'UTR&nbsp;<span class='wutr' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;-->
                                    <input type="checkbox" name="cbox2" value="CDSEXON"/><span class='cds' style="text-align:center;">CDS/exon</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" name="cbox2" value="INTRON"/><span class='intron' style="text-align:center;">intron</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <!--<input type="checkbox" name="cbox2" value="EXON"/>exon&nbsp;<span class='exon' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;-->
                                    <input type="checkbox" name="cbox2" value="AMB"/><span class='amb' style="text-align:center;">AMB</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" name="cbox2" value="PA"/><span class="pa">Cleavage site</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="checkbox" name="cbox2" value="PAC"/><span class="pac">PAC</span>
                                </div>
                            </legend>
                            <br>
                            <button id = "find_patt" style="width:100px;"  class = "button blue medium">Show</button>
                            <button id = "reset" style = "width:100px;"  class = "button blue medium">Clear</button>
                            <hr></hr>
                    </div>
                    <div id = "seq_content" style="max-height:400px;overflow:auto;margin-top:20px;font-family: Courier New;font-size: 15px;">
                        <p class="scale" style="margin:0px;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;10
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;20
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;30
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;40
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;50
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;60
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;70
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;80
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;90
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;100
                        </p>
                        <p class = "sequence" id = "sequence" style="word-break:break-all;margin:0px"><?php echo $seq_with_pos;  ?></p>	            	
                    </div>
                </div>
            </fieldset>
        </div>
            <script>
                
                             $(".slider")
                        
                                        .slider({ 
                                            min: -300, 
                                            max: 200, 
                                            range: true, 
                                            values: [-300, 200] 
                                        })
                        
                                        .slider("pips", {
                                            rest: "label"
                                        })
                        
                                        .slider("float");
            </script>
        </div>
        <?php
            include"footer.php";
            ?>
    </body>
</html>
