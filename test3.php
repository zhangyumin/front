<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>sequence viewer</title>
        <script src="./src/jquery-1.10.1.min.js"></script>
        <style>
            fieldset{
                border-color: #5499c9 !important;
                border-style: solid !important;
                border-width: 2px !important;
                padding: 5px 10px !important;
            }
            .scale{
                color:#324a17;
                font-weight: bold;
            }
            .sutr{
                background-color: #6F00D2;
            }
            .wutr{
                background-color: #F75000;
            }
            .cds{
                background-color: #FF0000;
            }
            .intron{
                background-color: #5B5B5B;
            }
            .exon{
                background-color: #984B4B;
            }
            .amb{
                background-color: #ffd700;
            }
            .extend{
                background-color: #9aff9a;
            }
            .pa{
                color:red;
                text-decoration: underline;
                font-weight: bold;
                cursor: pointer;
            }
            .patt1{
                background-color: #FF83FA;
            }
            .patt2{
                background-color: #87CEFA;
            }
            .aat{
                background-color: #B3EE3A;
            }
            .tgt{
                background-color: #EEEE00;
            }
        </style>
    </head>
    <body>
        <?php 
            $con=  mysql_connect("localhost","root","root");
            mysql_select_db("db_server",$con);
            session_start();
            
            $seq = $_GET['seq'];
            $species = $_GET['species'];
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
            foreach ($pa_start as $key => $value)
            {
                echo "pa_start.push('$value');";
                echo "pa_tagnum.push('$pa_tagnum[$key]');";
            }
            $i = -1;
            while(list($f_key,$val)=each($ftr))
            {
                if(strcmp($val, '3UTR')==0)
                {
                    $i++;
                    if($strand == 1){
                        echo "sutr_start.push('$ext_start[$i]');";
                        echo "sutr_end.push('$ext_end[$i]');";
                        $ext_start_pos = $ext_end[$i]+1;
                        echo "ext_start.push('$ext_start_pos');";
                        echo "ext_end.push('$f_end[$f_key]');";
                    }
                    else if ($strand == -1){
                        $ext_start_pos = $ext_start[$i]+1;
                        echo "sutr_start.push('$ext_start_pos');";
                        echo "sutr_end.push('$f_end[$f_key]');";
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
        <script>
            $(document).ready(function (){
                $('#find_patt').click(function(){
                    clear();
                    load_pattern();
                });
                 $('#reset').click(function(){
                    clear();
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
                $("input[name=cbox2]:checked").each(function(){ 
                    ftr.push($(this).val());
                });
                find_pattern(patts1,patts2,ftr);
            }
            function find_pattern(patts1,patts2,ftr){
                //ftr部分
                if(ftr.indexOf("3UTR")!=-1){
                    if(sutr_start.length&&sutr_end.length!=0)
                    {
                        for(var sutrkey in sutr_start){
                            for(var i = sutr_start[sutrkey]; i<= sutr_end[sutrkey]; i++){
                                $('#pos'+i).addClass("sutr");
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
                            }
                        }
                    }
                }
                if(ftr.indexOf("5UTR")!=-1){
                    if(wutr_start.length&&wutr_end.length!=0)
                    {
                        for(var wutrkey in wutr_start){
                            for(var i = wutr_start[wutrkey]; i<= wutr_end[wutrkey]; i++){
                                $('#pos'+i).addClass("wutr");
                            }
                        }
                    }
                }
                if(ftr.indexOf("CDS")!=-1){
                    if(cds_start.length&&cds_end.length!=0)
                    {
                        for(var cdskey in cds_start){
                            for(var i = cds_start[cdskey]; i<= cds_end[cdskey]; i++){
                                $('#pos'+i).addClass("cds");
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
                            }
                        }
                    }
                }
                if(ftr.indexOf("EXON")!=-1){
                    if(exon_start.length&&exon_end.length!=0)
                    {
                        for(var exonkey in exon_start){
                            for(var i = exon_start[exonkey]; i<= exon_end[exonkey]; i++){
                                $('#pos'+i).addClass("exon");
                            }
                        }
                    }
                }
                if(ftr.indexOf("AMB")!=-1){
                    if(amb_start.length&&amb_end.length!=0)
                    {
                        for(var ambkey in amb_start){
                            for(var i = amb_start[ambkey]; i<= amb_end[ambkey]; i++){
                                $('#pos'+i).addClass("amb");
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
                            $('#pos'+pa_start[pakey]).attr("title","tot_tagnum:"+pa_tagnum[pakey]);
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
                                j++
                                pos1_end.push(gene_end - result.index);
                                pos1_start.push(gene_end  - patt.length - result.index+1);
                            }
                            else if(strand == 1){
                                j++
                                pos1_end.push(gene_start + result.index + patt.length - 1);
                                pos1_start.push(gene_start + result.index);
                            }
                            for(var i = pos1_start[j]; i<= pos1_end[j];i++){
                                $('#pos'+i).addClass("patt1");
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
                                    else if(patt.toUpperCase() == "TGTAAA")
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
                                    else if(patt.toUpperCase() == "TGTAAA")
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
                                    $('#pos'+i).addClass("patt2");
                                }
                                for(var i = aat_start[j]; i<= aat_end[j];i++){
                                    $('#pos'+i).addClass("aat");
                                }
                                for(var i = tgt_start[j]; i<= tgt_end[j];i++){
                                    $('#pos'+i).addClass("tgt");
                                }
                            }
                    }
                }
            }
        </script>
        <div  class="straight_matter">
            <fieldset style="margin-top: 20px;margin-left: 2%;margin-right: 2%;">
                <legend>
                    <span class="h3_italic" style="font-size:21px">
                        <font color="#224055"><b>Gene Viewer</b></font>
                    </span>
                </legend>
                <div class = "seq_viewer" id="seq_viewer">
                    <div id = "pattern">	
                        <legend><span class="h3_italic">Typical Pattern</span>&nbsp;<span class='patt2' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;(AATAAA&nbsp;<span class='aat' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;TGTAAA&nbsp;<span class='tgt' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;)</legend>
                            <table style="width:950px;margin-top:10px;margin-bottom:10px;font-family: Courier New;font-size: 15px;">
                                <?php
                                echo "<tr>";
                                echo '<td><input type="checkbox" checked = "true" name = "cbox" id = "checkall" value = "checkall" /><em>&nbsp;Change All</em></td>';
                                $i = 0;
                                foreach ($singnals as $key => $value) {
                                        if($i == 6||$i == 13)
                                        {
                                                echo "</tr><tr>";
                                        }
                                        echo '<td><input type="checkbox" name = "cbox" checked = "true" value = "'.$value.'"/>&nbsp;'.$value.'</td>';
                                        $i++;
                                }
                                echo "</tr>";
                                ?>
                            </table>
                            <legend><span class="h3_italic">User’s Pattern </span>&nbsp;&nbsp;<span class='patt1' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;(Ex. AATAAA, TGTAAA)</legend>
                            <input type = "text" id = "user_pattern" style="margin-top:10px;margin-bottom:10px;"/>
                            <br>
                            <legend id='text'><span class="h3_ltalic">others</span>&nbsp;&nbsp;
                                (
                                <input type="checkbox" name="cbox2" value="EXT"/>3'UTR Extend<span class='extend' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
                                <input type="checkbox" name="cbox2" value="3UTR"/>3'UTR <span class='sutr' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
                                <input type="checkbox" name="cbox2" value="5UTR"/>5'UTR&nbsp;<span class='wutr' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;
                                <input type="checkbox" name="cbox2" value="CDS"/>CDS&nbsp;<span class='cds' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;
                                <input type="checkbox" name="cbox2" value="INTRON"/>Intron&nbsp;<span class='intron' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;
                                <input type="checkbox" name="cbox2" value="EXON"/>exon&nbsp;<span class='exon' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;
                                <input type="checkbox" name="cbox2" value="AMB"/>amb&nbsp;<span class='amb' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
                                <input type="checkbox" name="cbox2" value="PA"/><span class="pa">Poly(A) site</span>
                                )
                            </legend>
                            <br>
                            <button id = "find_patt" style="width:100px;"  class = "button blue medium">Show</button>
                            <button id = "reset" style = "width:100px;"  class = "button blue medium">Clear</button>
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
    </body>
</html>