<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <script src="./src/jquery-1.10.1.min.js"></script>
        <script src="./src/optselect/jquery.sumoselect.js"></script>
        <link href="./src/optselect/sumoselect.css" rel="stylesheet" />
        <?php
            $con=  mysql_connect("localhost","root","root");
            mysql_select_db("db_server",$con);
            session_start();
            $seq=$_GET['seq'];
            $chr=$_GET['chr'];
            $strand=$_GET['strand'];
            $species = $_GET['species'];
            $pac_num = array();
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
            //增加起始终止坐标余量
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
                        for($i=$continue;$i<=$num-count($_SESSION['file_real']);$i++){
                            $pa="pa".$i;
                            ${$pa}[$tmp_pa_row[2]] = $tmp_pa_row[$i-5];
                        }
                    }
                }
            }
            //读取pac数据并存入数组
            $upa_start = array();
            $upa_end = array();
            $tmp_pac = mysql_query("select * from t_".$species."_pac where gene = '$seq'");
            while($tmp_pac_row = mysql_fetch_row($tmp_pac)){
                for($i=1;$i<=$num-count($_SESSION['file_real']);$i++){
                    $pac="pac".$i;
                    ${$pac}[$tmp_pac_row[2]] = $tmp_pac_row[$i+13];
                }
                array_push($upa_start, $tmp_pac_row[10]);
                array_push($upa_end, $tmp_pac_row[11]);
            }
            
            //user trap数据
            if(isset($_SESSION['file'])){
                $sql_sample = implode($_SESSION['file_real'], ",");
                $user_pa = mysql_query("select coord,$sql_sample from db_user.PA_".$_SESSION['file']." where chr='$chr' and coord>=$gene_start and coord<=$gene_end;");
                $user_pac = mysql_query("select coord,$sql_sample from db_user.PAC_".$_SESSION['file']." where gene = '$seq';");
                while($usr_row_pa = mysql_fetch_row($user_pa)){
                    for($i=$num-count($_SESSION['file_real'])+1;$i<=$num;$i++){
                        $pa="pa".$i;
                        ${$pa}[$usr_row_pa[0]] = $usr_row_pa[$i-$num+count($_SESSION['file_real'])];
                    }
                }
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
                        unset(${"pa".$i});
                        unset(${"pac".$i});
                    }
                }
                //重排samples序号
               $samples = array_merge($samples);
                //重新排列pa和pac的序号
                $j = 1;
                for($i=1;$i<=$num;$i++){
                    if(!empty(${"pa".$i})){
                        ${"pa".$j} = ${"pa".$i};
                        ${"pac".$j} = ${"pac".$i};
                        $j++;
                    }
                }
                //去除多余
                for($i=count($_SESSION['sample'])+1;$i<=$num;$i++){
                    unset(${"pa".$i});
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
//            PA数据测试
//            for($i=1;$i<=$num;$i++){
////                $pa="pa".$i;
//                if($i==5){
//                    var_dump(${"pa".$i});
//                }
//            }
//            PAC数据测试
//            for($i=1;$i<=$num;$i++){
//                $pac="pac".$i;
//                if($i==2){
//                    var_dump($$pac);
//                }
//            }
            //group处理
            $statistics_samples = array();#存储statistics的title
            //声明每个group的总和，均值，中位数数组
            foreach (array_unique($group) as $key => $value) {
                array_push($statistics_samples, $value."_sum");
                array_push($statistics_samples, $value."_avg");
                array_push($statistics_samples, $value."_med");
                ${"pa_".$value."_sum"} = array();
                ${"pa_".$value."_avg"} = array();
                ${"pa_".$value."_med"} = array();
                ${"pac_".$value."_sum"} = array();
                ${"pac_".$value."_avg"} = array();
                ${"pac_".$value."_med"} = array();
                $pa_group_key=array();#用于存储本组pa所有的下标key值,也就是coord坐标
                $pac_group_key=array();#用于存储本组pac所有的下标key值,也就是coord坐标
                $group_member = array();#用于存储同组成员的编号$i
                for($i = 1;$i <= $num; $i++){
                    if($group[$i-1] == $value){#group是从0开始
                        array_push($group_member, $i);
                    }
                }
//                var_dump($group_member);
                //遍历同group所有成员来获得本组的所有coord值
                foreach ($group_member as $key1 => $value1) {
//                    echo $value1;
                        $pa_group_key = $pa_group_key + ${"pa".$value1};
                        $pac_group_key = $pac_group_key + ${"pac".$value1};
//                        var_dump($pac1);
                }
                //遍历coord值
                foreach ($pa_group_key as $key2 => $value2) {
                    $sum_patmp = 0;
                    $avg_patmp = 0;
                    $med_patmp = 0;
                    $patmp = array();#临时用于存储的数组
                    foreach ($group_member as $key3 => $value3) {
                        if(array_key_exists($key2, ${"pa".$value3})){
                            array_push($patmp, ${"pa".$value3}[$key2]);
                        }
                        else{
                            array_push($patmp,0);
                        }
                    }
                    sort($patmp);//从小到大排列$tmp
                    $num_patmp = count($patmp);#统计同group下sample的个数
                    foreach ($patmp as $key4 => $value4) {
                        $sum_patmp = $sum_patmp + $value4;
                    }
                    $avg_patmp = $sum_patmp / $num_patmp;
                    $avg_patmp = number_format($avg_patmp,1,".","");
                    if($num_patmp % 2 == 1){
                        $med_patmp = $patmp[round($num_patmp/2)-1];
                    }
                    else{
                        $med_patmp = ($patmp[$num_patmp/2]+$patmp[$num_patmp/2-1])/2;
                    }
                    $med_patmp = number_format($med_patmp,1,".","");
                    ${"pa_".$value."_sum"}[$key2] = $sum_patmp;
                    ${"pa_".$value."_avg"}[$key2] = $avg_patmp;
                    ${"pa_".$value."_med"}[$key2] = $med_patmp;
                }
                foreach ($pac_group_key as $key2 => $value2) {
                    $sum_pactmp = 0;
                    $avg_pactmp = 0;
                    $med_pactmp = 0;
                    $pactmp = array();#临时用于存储的数组
                    //统计所有的pac的coord
                    if(!in_array($key2, $pac_num)){
                        array_push($pac_num, $key2);
                    }
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
            if(!isset($_GET['pac'])){
                $pac_selected = $pac_num;
            }
            else{
                $tmp_selected = explode(",", $_GET['pac']);
                $pac_selected = array_intersect($pac_num, $tmp_selected);
            }
//            foreach ($pac_num as $key => $value) {
//                if(!in_array($value, $pac_selected)){
//                    $upa_start[array_keys($pac_num,$value)[0]]=0;
//                    $upa_end[array_keys($pac_num,$value)[0]]=0;
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
                    foreach ($sutr_start as $key => $value) {
                        $st_st=($sutr_start[$key]-$gene_start)*$rate;
                        $start=($sutr_start_org[$key]-$gene_start)*$rate;
                        $st_ed=($sutr_end[$key]-$gene_start )*$rate;
                        $end=($sutr_end_org[$key]-$gene_start)*$rate;
                        if($start>=0 && $start<=1000 && $end>=0 && $end<=1000)
                            echo "sutr_shorten($start,$end,$st,$en,$strand,'no_extend');\n";
                        if($strand==-1){
                            if($sutr_end_org[$key]!=null&&$sutr_start_org[$key]!=null)
                                echo "sutr_extend($st_st,$start,0,1000,-1,'gene');\n";
                            else{
                                $start = ($sutr_start_org[$key-1]-$gene_start)*$rate;
                                echo "sutr_extend($st_st,$start,0,1000,-1,'gene');\n";
                            }
                        }
                        else if($strand==1){
                            if($sutr_end_org[$key]!=null&&$sutr_start_org[$key]!=null)
                                echo "sutr_extend($end,$st_ed,0,1000,1,'gene');\n";
                            else{
                                $end = ($sutr_end_org[$key-1]-$gene_start)*$rate;
                                echo "sutr_extend($end,$st_ed,0,1000,1,'gene');\n";
                            }
                        }
                    }
                    foreach ($wutr_start_org as $key => $value) {
                        $start=($wutr_start_org[$key]-$gene_start)*$rate;
                        $end=($wutr_end_org[$key]-$gene_start)*$rate;
                        echo "wutr_shorten($start,$end,$st,$en,$strand,'no_extend');\n";
                    }
                    foreach ($intron_start_org as $key => $value) {
                        $start=($intron_start_org[$key]-$gene_start)*$rate;
                        $end=($intron_end_org[$key]-$gene_start)*$rate;
                        echo "intron_shorten($start,$end,$st,$en,$strand,'no_extend');\n";
                    }
                    foreach ($exon_org as $key => $value) {
                        $start=($exon_start_org[$key]-$gene_start)*$rate;
                        $end=($exon_end_org[$key]-$gene_start)*$rate;
                        echo "exon_shorten($start,$end,$st,$en,$strand,'no_extend');\n";
                    }
                    foreach ($cds_start_org as $key => $value) {
                        $start=($cds_start_org[$key]-$gene_start)*$rate;
                        $end=($cds_end_org[$key]-$gene_start)*$rate;
                        echo "cds_shorten($start,$end,$st,$en,$strand,'no_extend');\n";
                    }
                    foreach ($amb_start_org as $key => $value) {
                        $start=($amb_start_org[$key]-$gene_start)*$rate;
                        $end=($amb_end_org[$key]-$gene_start)*$rate;
                        echo "amb_shorten($start,$end,$st,$en,$strand,'no_extend');\n";
                    }
                    for($i=1;$i<=$num;$i++){
                        $pa="pa".$i;
                        $pac="pac".$i;
                        if(empty($$pa)||empty($$pac))
                            continue;
                        foreach ($$pa as $key1 => $value1) {
                            $j = 10;
                            foreach ($upa_start as $key3 => $value3) {
                                if($key1 > $value3 && $key1 < $upa_end[$key3])
                                    $j = $key3;
                            }
                            $loc=($key1-$gene_start)*$rate;
                            if($j!=10){
                                if(in_array($pac_num[$j], $pac_selected))
                                    echo "pa($loc,$value1,$j,'sample$i');\n";
                                else
                                    echo "pa($loc,$value1,'none','sample$i');\n";
                            }
                        }
                        foreach ($$pac as $key2 => $value2) {
                            $loc=($key2-$gene_start)*$rate;
                            if(in_array($key2, $pac_selected)){
                                $j = array_keys($pac_selected, $key2)[0];
                                echo "pac($loc,$value2,$j,'sample$i');\n";
                            }
                            else
                                echo "pac($loc,$value2,'none','sample$i');\n";
                        }
                    }
                    $i = 1;
                    foreach (array_unique($group) as $key => $value) {
                        foreach (${"pa_".$value."_sum"} as $key1 => $value1) {
                            $j = 10;
                            foreach ($upa_start as $key3 => $value3) {
                                if($key1 > $value3 && $key1 < $upa_end[$key3])
                                    $j = $key3;
                            }
                            $loc=($key1-$gene_start)*$rate;
                            if($j!=10){
                                if(in_array($pac_num[$j], $pac_selected))
                                    echo "pa($loc,$value1,$j,'statistics_sample$i');\n";
                                else
                                    echo "pa($loc,$value1,'none','statistics_sample$i');\n";
                            }
                        }
                        foreach (${"pac_".$value."_sum"} as $key1 => $value1) {
                            $loc=($key1-$gene_start)*$rate;
                            if(in_array($key1, $pac_selected)){
                                $j = array_keys($pac_selected, $key1)[0];
                                echo "pac($loc,$value1,$j,'statistics_sample$i');\n";
                            }
                            else
                                echo "pac($loc,$value1,'none','statistics_sample$i');\n";
                        }
                        $i++;
                        foreach (${"pa_".$value."_avg"} as $key2 => $value2) {
                            $j = 10;
                            foreach ($upa_start as $key3 => $value3) {
                                if($key2 > $value3 && $key2 < $upa_end[$key3])
                                    $j = $key3;
                            }
                            $loc=($key2-$gene_start)*$rate;
                            if($j!=10){
                                if(in_array($pac_num[$j], $pac_selected))
                                    echo "pa($loc,$value2,$j,'statistics_sample$i');\n";
                                else
                                    echo "pa($loc,$value2,'none','statistics_sample$i');\n";
                            }
                        }
                        foreach (${"pac_".$value."_avg"} as $key2 => $value2) {
                            $loc=($key2-$gene_start)*$rate;
                            if(in_array($key2, $pac_selected)){
                                $j = array_keys($pac_selected, $key2)[0];
                                echo "pac($loc,$value2,$j,'statistics_sample$i');\n";
                            }
                            else
                                echo "pac($loc,$value2,'none','statistics_sample$i');\n";
                        }
                        $i++;
                        foreach (${"pa_".$value."_med"} as $key3 => $value3) {
                            $j = 10;
                            foreach ($upa_start as $key4 => $value4) {
                                if($key3 > $value4 && $key3 < $upa_end[$key4])
                                    $j = $key4;
                            }
                            $loc=($key3-$gene_start)*$rate;
                            if($j!=10){
                                if(in_array($pac_num[$j], $pac_selected))
                                    echo "pa($loc,$value3,$j,'statistics_sample$i');\n";
                                else
                                    echo "pa($loc,$value3,'none','statistics_sample$i');\n";
                            }
                        }
                        foreach (${"pac_".$value."_med"} as $key3 => $value3) {
                            $loc=($key3-$gene_start)*$rate;
                            if(in_array($key3, $pac_selected)){
                                $j = array_keys($pac_selected, $key3)[0];
                                echo "pac($loc,$value3,$j,'statistics_sample$i');\n";
                            }
                            else
                                echo "pac($loc,$value3,'none','statistics_sample$i');\n";
                        }
                        $i++;
                    }
                    foreach ($pac_num as $key => $value) {
                        //画pac的pointer
                        $position = ($value-$gene_start)* $rate;
                        if(in_array($value, $pac_selected)){
                            echo "pointer($position,$key,\"gene\");";
                        }
                        else{
                            echo "pointer($position,'none',\"gene\");";
                        }
                    }
                ?>
                arrow("gene",10,<?php echo $_GET['strand'];?>);
                shorten_arrow("no_extend",<?php echo ($gene_start_org-$gene_start)*$rate;?>,<?php echo ($gene_end_org-$gene_start)*$rate;?>,<?php echo $_GET['strand'];?>);
                <?php
                    for($i=1;$i<=$num;$i++){
                        $r=$i-1;
                        echo "line(\"sample$i\");";
                        echo "title(\"#000000\",\"$samples[$r]\",\"sample$i\");";
                        echo "yscale(\"sample$i\");";
                        echo "grid(\"sample$i\",\"sample\");";
                    }
                    for($i=1;$i<=count(array_unique($group))*3;$i++){
                        $r=$i-1;
                        echo "line(\"statistics_sample$i\");";
                        echo "title(\"#000000\",\"$statistics_samples[$r]\",\"statistics_sample$i\");";
                        echo "yscale(\"statistics_sample$i\");";
                        echo "grid(\"statistics_sample$i\",\"statistics_sample\");";
                    }
                    if($_GET['intergenic']==1)
                        echo "intergenic($strand,\"gene\");"
                ?>
                seq_title("#000000","<?php echo $seq;?>","gene");
                xscale("gene");
//                xscale("3utr_extend");
            }
            function seq_title(color,text,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle=color;
                context.font="bold 15px Droid Serif";
                context.fillText(text,1015,125);
            }
            function title(color,text,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle=color;
                context.font="bold 15px Droid Serif";
                context.fillText(text,1015,85);
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
                context.moveTo(0,120);
                context.lineTo(1000,120);
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
                context.moveTo(500,120);
                context.lineTo(500,10);
                for(i=5;i>0;i--){
                    context.moveTo(500,20*i);
                    context.lineTo(505,20*i);
                    context.font="12px Droid Serif";
                    context.fillText(60-10*i,505,21*i);
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
            function sutr_shorten(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
//                context.fillStyle="#ff0000";//3utr为红色
                context.fillStyle="#42A881";
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,15,endpos-startpos-10,10);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,15,endpos-startpos,10);
                }
                else{
                    context.fillRect(startpos,15,endpos-startpos,10);
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
            function wutr_shorten(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#8C5E4D";//5utr为zise
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,15,endpos-startpos-10,10);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,15,endpos-startpos-10,10);
                }
                else{
                    context.fillRect(startpos,15,endpos-startpos,10);
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
            function cds_shorten(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#00ABD8";//cds为绿色
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,10,endpos-startpos-10,20);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,10,endpos-startpos-10,20);
                }
                else{
                    context.fillRect(startpos,10,endpos-startpos,20);
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
            function intron_shorten(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#D390B9";//intron为黑色
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,15,endpos-startpos-10,10);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,15,endpos-startpos-10,10);
                }
                else{
                    context.fillRect(startpos,15,endpos-startpos,10);
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
            function exon_shorten(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#65D97D";//exon为黄色
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,10,endpos-startpos-10,20);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,10,endpos-startpos-10,20);
                }
                else{
                    context.fillRect(startpos,10,endpos-startpos,20);
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
            function amb_shorten(startpos,endpos,start,end,strand,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.fillStyle="#F35A4A";//amb为兰色
                if(endpos==end&&strand==1){
                    context.fillRect(startpos,10,endpos-startpos-10,20);
                }
                else if(startpos==start&&strand==-1){
                    context.fillRect(startpos+10,10,endpos-startpos-10,20);
                }
                else{
                    context.fillRect(startpos,10,endpos-startpos,20);
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
            function arrow(id,distance,strand){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.beginPath();
                if(strand==1){
                    context.moveTo(1000-distance,105);
                    context.lineTo(1005-distance,105);
                    context.lineTo(1010-distance,100);
                    context.lineTo(1005-distance,95);
                    context.lineTo(1000-distance,95);
                    context.lineTo(1000-distance,105);
                }
                else if(strand==-1){
                    context.moveTo(distance-10,100);
                    context.lineTo(distance-5,105);
                    context.lineTo(distance,105);
                    context.lineTo(distance,95);
                    context.lineTo(distance-5,95);
                    context.lineTo(distance-10,100);
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
                    context.moveTo(end-10,25);
                    context.lineTo(end-5,25);
                    context.lineTo(end,20);
                    context.lineTo(end-5,15);
                    context.lineTo(end-10,15);
                    context.lineTo(end-10,25);
                }
                else if(strand==-1){
                    context.moveTo(start,20);
                    context.lineTo(start+5,25);
                    context.lineTo(start+10,25);
                    context.lineTo(start+10,15);
                    context.lineTo(start+5,15);
                    context.lineTo(start,20);
                }
                context.closePath();
                context.fillStyle="#878787";
                context.fill();
            } 
            function pa(loc,tagnum,key,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                if(key == 'none'){
                    context.strokeStyle="#808a87";
                    context.fillStyle="#808a87";
                }
                else if(key==0){
                    context.strokeStyle="#ffd39b";
                    context.fillStyle="#ffd39b";
                }
                else if(key==1){
                    context.strokeStyle="#b4eeb4";
                    context.fillStyle="#b4eeb4";
                }
                else if(key==2){
                    context.strokeStyle="#b23aee";
                    context.fillStyle="#b23aee";
                }
                else if(key==3){
                    context.strokeStyle="#9F79EE";
                    context.fillStyle="#9F79EE";
                }
                else if(key==4){
                    context.strokeStyle="#40E0D0";
                    context.fillStyle="#40E0D0";
                }
                else if(key==5){
                    context.strokeStyle="#CDB5CD";
                    context.fillStyle="#CDB5CD";
                }
                else if(key==6){
                    context.strokeStyle="#9BCD9B";
                    context.fillStyle="#9BCD9B";
                }
                else if(key==7){
                    context.strokeStyle="#EEE9BF";
                    context.fillStyle="#EEE9BF";
                }
                else if(key==8){
                    context.strokeStyle="#CD69C9";
                    context.fillStyle="#CD69C9";
                }
                else if(key==9){
                    context.strokeStyle="#666666";
                    context.fillStyle="#666666";
                }
                context.beginPath();
                context.moveTo(loc,120);
                if(tagnum>50){
                    context.lineTo(loc,10);
                }
                else{
                    context.lineTo(loc,120-2*tagnum);
                }
                context.stroke();
            }
            function pac(loc,tagnum,key,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
                context.beginPath();
                context.font="10px Droid Serif";
                if(key == 'none'){
                    context.strokeStyle="#808a87";
                    context.fillStyle="#808a87";
                }
                else if(key==0){
                    context.strokeStyle="#ff8247";
                    context.fillStyle="#ff8247";
                }
                else if(key==1){
                    context.strokeStyle="#9acd32";
                    context.fillStyle="#9acd32";
                }
                else if(key==2){
                    context.strokeStyle="#b23aee";
                    context.fillStyle="#b23aee";
                }
                else if(key==3){
                    context.strokeStyle="#4169e1";
                    context.fillStyle="#4169e1";
                }
                else if(key==4){
                    context.strokeStyle="#00fa9a";
                    context.fillStyle="#00fa9a";
                }
                else if(key==5){
                    context.strokeStyle="#cd96cd";
                    context.fillStyle="#cd96cd";
                }
                else if(key==6){
                    context.strokeStyle="#9acd32";
                    context.fillStyle="#9acd32";
                }
                else if(key==7){
                    context.strokeStyle="#cdcd00";
                    context.fillStyle="#cdcd00";
                }
                else if(key==8){
                    context.strokeStyle="#cd00cd";
                    context.fillStyle="#cd00cd";
                }
                else if(key==9){
                    context.strokeStyle="#3b3b3b";
                    context.fillStyle="#3b3b3b";
                }
                if(tagnum>50){
                    context.moveTo(loc-5,10);
                    context.lineTo(loc+7,10);
                    context.fillRect(loc,120,3,-110);
                    context.fillText(tagnum,loc-10,135);
                }
                else if(tagnum==0){
                }
                else{
                    context.fillRect(loc,120,3,-2*tagnum);
                    context.fillText(tagnum,loc-4,135);
                }
                context.stroke();
                context.closePath();
            }
            function pointer(pos,key,id){
                var canvas = document.getElementById(id);
                var context = canvas.getContext("2d");
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
                if(key == 'none'){
                    context.strokeStyle="#808a87";
                    context.fillStyle="#808a87";
                }
                else if(key==0)
                    context.fillStyle="#ff8247";
                else if(key==1)
                    context.fillStyle="#9acd32";
                else if(key==2)
                    context.fillStyle="#b23aee";
                else if(key==3)
                    context.fillStyle="#4169e1";
                else if(key==4)
                    context.fillStyle="#00fa9a";
                else if(key==5)
                    context.fillStyle="#cd96cd";
                else if(key==6)
                    context.fillStyle="#9acd32";
                else if(key==7)
                    context.fillStyle="#cdcd00";
                else if(key==8)
                    context.fillStyle="#cd00cd";
                else if(key==9)
                    context.fillStyle="#3b3b3b";
                context.fill();
            }
        </script>
    </head>
    <body style="width:1000px">
        <div id="button" style="width:1000px;">
            <input type="radio" name="display" value="origin" checked="checked" onchange="reset($(this).val())"/>individual
            <div class="origind" style="display: inline;">
                 <select id="origin"   multiple="multiple" placeholder="Select to display" onchange="getname($(this).children(':selected'))" class="okbutton">
                     <?php
                            foreach ($samples as $key => $value) {
                                echo "<option selected value=$value>$value</option>";
                            }
                     ?>
                   </select>
            </div>
            <input type="radio" name="display" value="statistics" onchange="reset($(this).val())"/>grouping
            <div class="statisticsd" style="display: inline;">
                <select id="statistics"   multiple="multiple" placeholder="Select to display" onchange="getname($(this).children(':selected'))" class="okbutton">
                     <option selected value="sum">sum</option>
                     <option selected value="avg">avg</option>
                     <option selected value="med">med</option>
                </select>
            </div>
            <div class="pacd" style="display: inline;float: right">Highlight PAC
                <select id="pac"   multiple="multiple" placeholder="Select to display" onchange="showpa($(this).children(':selected'))" class="okbutton">
                    <?php
//                        sort($pac_num);
                        foreach ($pac_num as $key => $value) {
                            if(in_array($value, $pac_selected))
                                echo "<option selected value='$value'>PAC@$value</option>";
                            else
                                echo "<option value='$value'>PAC@$value</option>";
                        }
                    ?>
                </select>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('#pac').SumoSelect({ csvDispCount: 2,okCancelInMulti:true,selectAll:true });
                $('#origin').SumoSelect({ csvDispCount: 2,okCancelInMulti:true,selectAll:true });
                $('#statistics').SumoSelect({ csvDispCount: 0,okCancelInMulti:true });
                $('.okbutton').SumoSelect({okCancelInMulti:true });
                $("#statistics").attr("disabled",true);
                <?php
                    foreach ($pac_num as $key => $value) {
                        if($key==0)
                            $bgcolor="#ff8247";
                        else if($key==1)
                            $bgcolor="#9acd32";
                        else if($key==2)
                            $bgcolor="#b23aee";
                        else if($key==3)
                            $bgcolor="#4169e1";
                        else if($key==4)
                            $bgcolor="#00fa9a";
                        else if($key==5)
                            $bgcolor="#cd96cd";
                        else if($key==6)
                            $bgcolor="#9acd32";
                        else if($key==7)
                            $bgcolor="#cdcd00";
                        else if($key==8)
                            $bgcolor="#cd00cd";
                        else if($key==9)
                            $bgcolor="#3b3b3b";
                        echo "$(\"#li$value\").css(\"background-color\",\"$bgcolor\");\n";
                    }
                ?>
            });
            function reset(a){
                $("#origin").attr("disabled",true);
                $("#statistics").attr("disabled",true);
                $("#"+a).attr("disabled",false);
                getname($("#"+a).children(':selected'));
            }
            function getname(a){
                $(".origin").hide();
                $('.sum').hide();
                $('.avg').hide();
                $('.med').hide();
                var select=[];
                for(var key in a){
                    select.push(a[key].value);
                }
                select = select.slice(0,a.length);
//                console.log(select);
                display(select);
            }
            function display(a){
                for(var i in a){
                    $("."+a[i]).show();
                }
            }
            function showpa(a){
                var select=[];
                for(var key in a){
                    select.push(a[key].value);
                }
                select = select.slice(0,a.length);
                url = parent.document.getElementById("genepic").contentWindow.location.href;
                loc = url.lastIndexOf("pac");
                if(loc==-1)
                    window.location.href=url+"&pac="+select.toString();
                else
                    window.location.href=url.substring(0,loc)+"pac="+select.toString();
            }
        </script>
        <canvas id="gene" width="1150px;" height="150px;"></canvas><br>
        <canvas id='no_extend' width="1150px" height="40px;"></canvas><br>
        <?php
            for($i=1;$i<=$num;$i++){
                if($i%2==0)
                    echo "<canvas id=\"sample$i\" class=\"origin\" width=\"1150px; \" height=\"150px;\"></canvas>";
                else
                    echo "<canvas id=\"sample$i\" class=\"origin\" width=\"1150px; \" height=\"150px;\" style=\"background-color:#f1f1f1\"></canvas>";
            }
            for($i=1;$i<=count(array_unique($group))*3;$i+=3){
                $j = $i + 1;
                $k = $i + 2;
                if($i%2==0){
                    echo "<canvas id=\"statistics_sample$i\" class=\"sum statistics\" width=\"1150px; \" height=\"150px;\" style=\"display:none;\"></canvas>";
                    echo "<canvas id=\"statistics_sample$j\" class=\"avg statistics\" width=\"1150px; \" height=\"150px;\" style=\"display:none;\"></canvas>";
                    echo "<canvas id=\"statistics_sample$k\" class=\"med statistics\" width=\"1150px; \" height=\"150px;\" style=\"display:none;\"></canvas>";
                }
                else{
                    echo "<canvas id=\"statistics_sample$i\" class=\"sum statistics\" width=\"1150px; \" height=\"150px;\" style=\"background-color:#f1f1f1;display:none;\"></canvas>";
                    echo "<canvas id=\"statistics_sample$j\" class=\"avg statistics\" width=\"1150px; \" height=\"150px;\" style=\"background-color:#f1f1f1;display:none;\"></canvas>";
                    echo "<canvas id=\"statistics_sample$k\" class=\"med statistics\" width=\"1150px; \" height=\"150px;\" style=\"background-color:#f1f1f1;display:none;\"></canvas>";
                }
            }
        ?>
            <script>
                <?php
                    for($i=1;$i<=$num;$i++){
                        $j = $i-1;
                        echo "$('#sample$i').addClass('$samples[$j]');";
                    }
                ?>
            </script>
    </body>
</html>
