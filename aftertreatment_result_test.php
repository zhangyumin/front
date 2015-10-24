<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <title>Aftertreatment Result</title>
    <link href="./src/navbar.css" rel="stylesheet"/>
    <?php
        session_start();
        $file=$_SESSION['file'];
         if($_POST['chr'])
        {
            $chr=$_POST['chr'];
        }
        else if($_GET['chr'])
        {
            $chr=$_GET['chr'];
        }
        else
        {
            echo"<script language=javascript>alert('Error file format , please try again');window.opener=null;window.close();</script>";
        }
        if($_POST['gene'])
        {
            $gene=$_POST['gene'];
        }
        else if($_GET['gene'])
        {
            $gene=$_GET['gene'];
        }
        else
        {
            echo"<script language=javascript>alert('Please type gene ID');window.opener=null;window.close();</script>";
        }
        if($_POST['strand'])
        {
            $strand=$_POST['strand'];
        }
        else if($_GET['strand'])
        {
            $strand=$_GET['strand'];
        }
        else
        {
            echo"<script language=javascript>alert('Error file format , please try again');window.opener=null;window.close();</script>";
        }
        if($_GET['result']=='degene'){
            $a=file(glob("./searched/SearchedPAC_$file.gene.column1Xcolumn2.minpat*")[0]);
            $b=file("./searched/SearchedPAC_$file.gene.stat");
            $c = substr(glob("./searched/SearchedPAC_$file.gene.column1Xcolumn2.minpat*")[0], 11);
        }
        if($_GET['result']=='depac'){
            $a=file(glob("./searched/SearchedPAC_$file.APAgene.pac.column1Xcolumn2.minpat*")[0]);
            $c = substr(glob("./searched/SearchedPAC_$file.APAgene.pac.column1Xcolumn2.minpat*")[0], 11);
        }
        if($_GET['result']=='switchinggene_o'){
            $a=file(glob("./searched/SearchedPAC_$file.xx.avgPAT?.column1Xcolumn2.FU")[0]);
            $b=file(glob("./searched/SearchedPAC_$file.xx.avgPAT?.FU.stat")[0]);
            $c = substr(glob("./searched/SearchedPAC_$file.xx.avgPAT?.column1Xcolumn2.FU")[0], 11);
        }
        if($_GET['result']=='switchinggene_n'){
            $a=file("./searched/SearchedPAC_$file.xx.column1Xcolumn2.switch");
            $b=file("./searched/SearchedPAC_$file.xx.switch.stat");
            $c = substr("./searched/SearchedPAC_$file.xx.column1Xcolumn2.switch", 11);
        }
        ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <style type="text/css">
            body{
                font-family:Courier New;
                font-size: 14px;
            }
             span.pt1{
                color:black;
                background-color: #FF83FA;
            }
            span.pt2{
                color:black;
                background-color: #87CEFA;
            }
            span.pt3{
                color:black;
                background-color: #B3EE3A;
            }
             span.pt4{
                color:black;
                background-color: #EEEE00;
            }
             span.pt5{
                color:black;
                background-color: #6F00D2;
            }
             span.pt6{
                color:black;
                background-color: #F75000;
            }
             span.pt7{
                color:black;
                background-color: #FF0000;
            }
             span.pt8{
                color:black;
                background-color: #5B5B5B;
            }
             span.pt9{
                color:black;
                background-color: #984B4B;
            }
            fieldset{
                border-color: #5499c9 !important;
                border-style: solid !important;
                border-width: 2px !important;
                padding: 5px 10px !important;
            }
        </style>
</head>
<body>
     <?php
        $con=  mysql_connect("localhost","root","root");
        mysql_select_db("db_server",$con);
        ?>
    <div style="width: 100%;margin:0 auto;background-color: #ddddff;" class="page">
        <?php
            include './navbar.php'
        ?>
        <script src="./src/jquery-1.10.1.min.js" type="text/javascript"></script>
        <script src="./src/jquery.dataTables.min.js"type="text/javascript" ></script>
        <link href="./src/jquery.dataTables.css"type="text/css" rel="stylesheet"></link>
        <div id="task_summery" align="center" style="clear: both;color:#224055;font-size: 24px;margin: auto;">summary<br>
            <table style="border-top-style:dashed;border-right-style: dashed;border-left-style: dashed;border-bottom-style: dashed;font-size: 18px;width: 80%;" borderColor="#4a4a84" align=center>
                <?php
                        foreach ($b as $key => $value) {
                            echo "<tr>";
                            $summery_tmp=  explode("\t", $value);
                            foreach ($summery_tmp as $key => $value) {
                                echo "<th>$value</th>";
                            }
                            echo "</tr>";
                        }
                ?>
            </table>
        </div><br><br>
        <div id="table"  style="width: 90%;overflow-x: auto;background-color: #fff;margin:auto;">
            <table id="example" class="display dataTable" cellspacing="0" role="grid" aria-describedby="example_infox">
                <thead>
                    <tr>
                        <?php
                            $title_tmp=  explode("\t", $a[0]);
                            foreach ($title_tmp as $key => $value) {
                                echo "<th>$value</th>";
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                        <?php
                            foreach ($a as $key => $value) {
                                    if($key!=0){
                                        echo "<tr>";
                                        $tmp=  explode("\t", $a[$key]);
                                        foreach ($tmp as $key1 => $value1) {
                                            if($_GET['result']=='degene'){
                                                if($key1==1){
                                                    $pos_sql=mysql_query("select ftr_start from t_".$_SESSION['species']."_gff where gene='$tmp[1]';");
                                                    $pos=  mysql_fetch_row($pos_sql)[0];
                                                    if($tmp[4]=='+')
                                                        echo "<td><a href='./aftertreatment_result_test.php?result=".$_GET['result']."&chr=$tmp[3]&gene=$pos&strand=1'>$value1</a></td>";
                                                    else
                                                        echo "<td><a href='./aftertreatment_result_test.php?result=".$_GET['result']."&chr=$tmp[3]&gene=$pos&strand=$tmp[4]1'>$value1</a></td>";
                                                }
                                                else
                                                    echo "<td>$value1</td>";
                                            }
                                            else if($_GET['result']=='depac'){
                                                if($key1==0){
                                                    if($tmp[3]=='+')
                                                    echo "<td><a href='./aftertreatment_result_test.php?result=".$_GET['result']."&chr=$tmp[2]&gene=$tmp[1]&strand=1'>$value1</a></td>";
                                                else
                                                    echo "<td><a href='./aftertreatment_result_test.php?result=".$_GET['result']."&chr=$tmp[2]&gene=$tmp[1]&strand=$tmp[3]1'>$value1</a></td>";
                                                }
                                                else
                                                    echo "<td>$value1</td>";
                                            }
                                            else if($_GET['result']=='switchinggene_o'){
                                                if($key1==0){
                                                    $pos_sql=mysql_query("select chr,strand,ftr_start from t_".$_SESSION['species']."_gff where gene='$tmp[0]';");
                                                    while($o_row=  mysql_fetch_row($pos_sql)){
                                                        $o_chr=$o_row[0];
                                                        $pos=$o_row[2];
                                                        $o_strand=$o_row[1];
                                                    }
                                                    if($o_strand=='+')
                                                        echo "<td><a href='./aftertreatment_result_test.php?result=".$_GET['result']."&chr=$o_chr&gene=$pos&strand=1'>$value1</a></td>";
                                                    else
                                                        echo "<td><a href='./aftertreatment_result_test.php?result=".$_GET['result']."&chr=$o_chr&gene=$pos&strand=-1'>$value1</a></td>";
                                                }
                                                else
                                                    echo "<td>$value1</td>";
                                            }
                                            else if($_GET['result']=='switchinggene_n'){
                                                if($key1==7){
                                                    if($tmp[1]=='+')
                                                        echo "<td><a href='./aftertreatment_result_test.php?result=".$_GET['result']."&chr=$tmp[0]&gene=$tmp[2]&strand=1'>$value1</a></td>";
                                                    else
                                                        echo "<td><a href='./aftertreatment_result_test.php?result=".$_GET['result']."&chr=$tmp[0]&gene=$tmp[2]&strand=$tmp[1]1'>$value1</a></td>";
                                                }
                                                else
                                                    echo "<td>$value1</td>";
                                            }
                                        }
                                        echo "</tr >";
                                    }
                            }
                        ?>
                </tbody>
            </table>
        </div><br><br>
        <script>
            $(document).ready(function(){
                $('#example').dataTable({
                    "lengthMenu":[[10,25,50,-1],[10,25,50,"all"]],
                    "pagingType":"full_numbers"
                });
            });
        </script>
        <div id="download"style="border: #ff6600 2px dotted;border-collapse: collapse;text-align: center">
            CLick to download the list data&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button onclick="javascript:window.location.href='./download_data.php?type=4&name=<?php echo $c; ?>'">download</button>
        </div>
   <?php
         $singnals = array("AATAAA","TATAAA","CATAAA","GATAAA","ATTAAA","ACTAAA","AGTAAA","AAAAAA","AACAAA","AAGAAA","AATTAA","AATCAA","AATGAA","AATATA","AATACA","AATAGA","AATAAT","AATAAC","AATAAG");        
//pattern viewer数据支持
         $pa_file=$_SESSION['file'];
         $pa_high100=$gene+100;
         $pa_high200=$gene+200;
         $pa_low100=$gene-100;
         $pa_low200=$gene-200;
         if(strcmp($strand,1)==0){
             $a_area="SELECT substring(seq,$gene-200,300) from db_server.t_".$_SESSION['species']."_fa WHERE title='$chr';";
             $pa_query="select * from db_server.t_".$_SESSION['species']."_pa1 where chr='$chr' and coord>=$pa_low200 and coord<=$pa_high100 and tot_tagnum>0;";
            //echo $a;
             //echo "in 1";
         }
         if(strcmp($strand,-1)==0){
             $a_area="SELECT substring(seq,$gene-100,300) from db_server.t_".$_SESSION['species']."_fa WHERE title='$chr';";
             $pa_query="select * from db_server.t_".$_SESSION['species']."_pa1 where chr='$chr' and coord>=$pa_low100 and coord<=$pa_high200 and tot_tagnum>0;";
              //echo $a;
             //echo "in 2";
         }
         
         $result_area=mysql_query($a_area);
         while($row_area=mysql_fetch_row($result_area))
         {
             //echo "in it";
             $seq_area=$row_area[0];
         }
         if(strcmp($strand,-1)==0)
         {
             $seq_area= strrev($seq_area);
             $seq_arr_area=str_split($seq_area);
             //$seq_arr=['G','A','T','C','G','T','A'];
             foreach ($seq_arr_area as &$value) {
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
             $seq_area=  implode($seq_arr_area);
         }
         $pa_result=  mysql_query($pa_query);
         while ($pa_row=  mysql_fetch_row($pa_result))
         {
             $pa_start[]=$pa_row[2];
         }
         foreach($pa_start as $key => $value)
         {
             if(strcmp($strand,-1)==0)
             {
                 $pa_start[$key]=$pa_start[$key]-$pa_low100;
             }
             else if(strcmp($strand,1)==0)
            {
                $pa_start[$key]=$pa_start[$key]-$pa_low200;
             }
         }
//         var_dump($seq_area);
//gene viewer数据支持        
         $a="SELECT * from db_server.t_".$_SESSION['species']."_gff_all where ftr_start<=$gene and ftr_end>=$gene and chr='$chr' and ftr='gene';";
         $result=mysql_query($a);
         //var_dump($result);
         while($row=mysql_fetch_row($result))
         {
             //echo "in it";
             $gene_name=$row[6];
             $gene_start=$row[3];
             $gene_end=$row[4];
         }
         //print_r($gene_name);
        $b="select substring(seq,$gene_start,$gene_end-$gene_start) from db_server.t_".$_SESSION['species']."_fa where title='$chr';";
        //echo $b;
        $seq_result=  mysql_query($b);
        while($rows=mysql_fetch_row($seq_result))
         {
             //echo "in it";
             $seq=$rows[0];
         }
         if(strcmp($strand,-1)==0)//反转互补
         {
             $seq= strrev($seq);
             $seq_arr=str_split($seq);
             //$seq_arr=['G','A','T','C','G','T','A'];
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
             $seq=  implode($seq_arr);
         }
         $c="select * from db_server.t_".$_SESSION['species']."_gff where gene like '$gene_name' ;";
         //echo $c;
         $seq_feature=  mysql_query($c);
         while($row_f=  mysql_fetch_row($seq_feature))
         {
             //echo "in it";
             $ftr[]=$row_f[2];
             $f_start[]=$row_f[3];
             $f_end[]=$row_f[4];
         }
         //print_r($ftr);

         echo "<script type=\"text/javascript\">";
         //echo "var sequences = ['AAAATAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA','AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA','AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'];"; 
         //echo "var current_seq='AAAATAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';";
         echo "var sequences=[];";
         //echo "sequences.push('$row[0]');";
         echo "var current_seq = '$seq';";
         echo "sequences.push('$seq');";
         echo "var sutr_start=[];";
         echo "var sutr_end=[];";
         echo "var wutr_start=[];";
         echo "var wutr_end=[];";
         echo "var cds_start=[];";
         echo "var cds_end=[];";
         echo "var intron_start=[];";
         echo "var intron_end=[];";
         echo "var exon_start=[];";
         echo "var exon_end=[];";
         while(list($f_key,$val)=each($ftr))
         {
             if(strcmp($val, '3UTR')==0)
             {
                 if(strcmp($strand,1)==0)
                 {
                    $s_start=$f_start[$f_key];
                    $ss_p=$s_start-$gene_start;//3utr start point
                    echo "sutr_start.push('$ss_p');";
                    $s_end=$f_end[$f_key];
                    $se_p=$s_end-$gene_start;//3utr end point
                    echo "sutr_end.push('$se_p');";
                 }
                 else if(strcmp($strand,-1)==0)
                {
                    $s_start=$f_start[$f_key];
                    $se_p=$gene_end-$s_start+1;//3utr end point
                    echo "sutr_end.push('$se_p');";
                    $s_end=$f_end[$f_key];
                    $ss_p=$gene_end-$s_end+1;//3utr start point
                    echo "sutr_start.push('$ss_p');";
                 }
             }
              if(strcmp($val, '5UTR')==0)
             {
                 if(strcmp($strand,1)==0)
                 {
                    $w_start=$f_start[$f_key];
                    $ws_p=$w_start-$gene_start+1;//5utr start point
                    echo "wutr_start.push('$ws_p');";
                    $w_end=$f_end[$f_key];
                    $we_p=$w_end-$gene_start+1;//5utr end point
                    echo "wutr_end.push('$we_p');";
                 }
                 else if(strcmp($strand,-1)==0)
                {
                    $w_start=$f_start[$f_key];
                    $we_p=$gene_end-$w_start+1;//5utr end point
                    echo "wutr_end.push('$we_p');";
                    $w_end=$f_end[$f_key];
                    $ws_p=$gene_end-$w_end+1;//5utr start point
                    echo "wutr_start.push('$ws_p');";
                 }
             }
             if(strcmp($val, 'CDS')==0)
             {
                 if(strcmp($strand,1)==0)
                 {
                    $c_start=$f_start[$f_key];
                    $cs_p=$c_start-$gene_start+1;//cds start point
                    echo "cds_start.push('$cs_p');";
                    $c_end=$f_end[$f_key];
                    $ce_p=$c_end-$gene_start+1;//cds end point
                    echo "cds_end.push('$ce_p');";
                 }
                 else if(strcmp($strand,-1)==0)
                {
                    $c_start=$f_start[$f_key];
                    $ce_p=$gene_end-$c_start+1;//cds end point
                    echo "cds_end.push('$ce_p');";
                    $c_end=$f_end[$f_key];
                    $cs_p=$gene_end-$c_end+1;//cds start point
                    echo "cds_start.push('$cs_p');";
                 }
             }
             if(strcmp($val, 'intron')==0)
             {
                 if(strcmp($strand,1)==0)
                 {
                    $i_start=$f_start[$f_key];
                    $is_p=$i_start-$gene_start+1;//intron start point
                    echo "intron_start.push('$is_p');";
                    $i_end=$f_end[$f_key];
                    $ie_p=$i_end-$gene_start+1;//intron end point
                    echo "intron_end.push('$ie_p');";
                 }
                 else if(strcmp($strand,-1)==0)
                {
                    $i_start=$f_start[$f_key];
                    $ie_p=$gene_end-$i_start+1;//intron end point
                    echo "intron_end.push('$ie_p');";
                    $i_end=$f_end[$f_key];
                    $is_p=$gene_end-$i_end+1;//intron start point
                    echo "intron_start.push('$is_p');";
                 }
             }
            if(strcmp($val, 'exon')==0)
             {
                 if(strcmp($strand,1)==0)
                 {
                    $e_start=$f_start[$f_key];
                    $es_p=$e_start-$gene_start+1;//exon start point
                    echo "exon_start.push('$es_p');";
                    $e_end=$f_end[$f_key];
                    $ee_p=$e_end-$gene_start+1;//exon end point
                    echo "exon_end.push('$ee_p');";
                 }
                 else if(strcmp($strand,-1)==0)
                {
                    $e_start=$f_start[$f_key];
                    $ee_p=$gene_end-$e_start+1;//exon end point
                    echo "exon_end.push('$ee_p');";
                    $e_end=$f_end[$f_key];
                    $es_p=$gene_end-$e_end+1;//exon start point
                    echo "exon_start.push('$es_p');";
                 }
             }
         }
         //echo "var ftr[]='$ftr';";
         //echo "var f_start[]='$f_start';";
         //echo "var f_end[]='$f_end';";
         echo "</script>";

         ?>
        <script type="text/javascript">
        function find_pattern_onload()
        {
  	//reset($("#seq_switch").val());
                    reset(0);
  	//var cur_seq_id = $("#seq_switch").val();
                    var cur_seq_id=0;
  	var patts1 = [];
  	var patts2 = [];
  	var user_patt = $("#user_pattern").val();
  	patts1 = user_patt.split(",");
  	$("input[type=checkbox]:checked").each(function(){ 
                            if($(this).val() != "checkall")
                            {
                                                        patts2.push($(this).val());
                            }
	}); 
	//find_pattern(patts1,patts2,pas[cur_seq_id],rpas[cur_seq_id]);
                      find_pattern(patts1,patts2);
        }

        //function find_pattern(patts1,patts2,pa,rpa)
        function find_pattern(patts1,patts2)
        {
            var original_seq = current_seq;
            var seq = current_seq;
            var len = original_seq.length;
            var pos1_start = [];
            var pos1_end = [];
            var pos2_start = [];
            var pos2_end = [];
            var aat_start = [];
            var aat_end = [];
            var tgt_start = [];
            var tgt_end = [];
            
 //           var sutr1_start['3','15'];
  //          var sutr1_end['8','21'];

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
                            while((result = reg.exec(original_seq)) != null)
                            {
                                    pos1_start.push(result.index);
                                    var end = patt.length + result.index -1;
                                    if(end > 99)
                                    {
                                            var str1 = result.index.toString();
                                            var str2 = end.toString();
                                            if(str1.substr(str1.length-3,1) == str2.substr(str2.length-3,1))
                                            {
                                                    pos1_end[result.index] = end;
                                            }
                                            else
                                            {
                                                    var end1;
                                                    for(var i = result.index;i<=end;i++)
                                                    {
                                                            if(i.toString().substr(i.toString().length-2,2) == "00")
                                                            {
                                                                    end1 = parseInt(i)-1;
                                                            }
                                                    }
                                                    pos1_end[result.index] = end1;
                                                    pos1_start.push(end1+1);
                                                    pos1_end[end1+1] = end;
                                            }
                                    }
                                    else
                                    {
                                            pos1_end[result.index] = end;
                                    }
                            }

                            var fake = "pppppppppppp";
                            fake = fake.substr(0,patt.length);
                            original_seq = original_seq.replace(reg, fake);
                    }
            }
            for(var key2 in patts2)
            {  
                    var patt = patts2[key2];
                    if(patt != "")
                    {
                            var reg=new RegExp(patt,"gi");
                            var result;
                            while((result = reg.exec(original_seq)) != null)
                            {
                                    if(patt.toUpperCase() == "AATAAA")
                                    {
                                            aat_start.push(result.index);
                                    }
                                    else if(patt.toUpperCase() == "TGTAAA")
                                    {
                                            tgt_start.push(result.index);
                                    }
                                    else
                                    {
                                            pos2_start.push(result.index);
                                    }
                                    var end = patt.length + result.index -1;
                                    if(end > 99)
                                    {
                                            var str1 = result.index.toString();
                                            var str2 = end.toString();
                                            if(str1.substr(str1.length-3,1) == str2.substr(str2.length-3,1))
                                            {
                                                    if(patt.toUpperCase() == "AATAAA")
                                                    {
                                                            aat_end[result.index] = end;
                                                    }
                                                    else if(patt.toUpperCase() == "TGTAAA")
                                                    {
                                                            tgt_end[result.index] = end;
                                                    }
                                                    else
                                                    {
                                                            pos2_end[result.index] = end;
                                                    }
                                            }
                                            else
                                            {
                                                    var end1;
                                                    for(var i = result.index;i<=end;i++)
                                                    {
                                                            if(i.toString().substr(i.toString().length-2,2) == "00")
                                                            {
                                                                    end1 = parseInt(i)-1;
                                                            }
                                                    }
                                                    if(patt.toUpperCase() == "AATAAA")
                                                    {
                                                            aat_end[result.index] = end1;
                                                            aat_start.push(end1+1);
                                                            aat_end[end1+1] = end;
                                                    }
                                                    else if(patt.toUpperCase() == "TGTAAA")
                                                    {
                                                            tgt_end[result.index] = end1;
                                                            tgt_start.push(end1+1);
                                                            tgt_end[end1+1] = end;
                                                    }
                                                    else
                                                    {
                                                            pos2_end[result.index] = end1;
                                                            pos2_start.push(end1+1);
                                                            pos2_end[end1+1] = end;
                                                    }
                                            }
                                    }
                                    else
                                    {
                                            if(patt.toUpperCase() == "AATAAA")
                                            {
                                                    aat_end[result.index] = end;
                                            }
                                            else if(patt.toUpperCase() == "TGTAAA")
                                            {
                                                    tgt_end[result.index] = end;
                                            }
                                            else
                                            {
                                                    pos2_end[result.index] = end;
                                            }
                                    }
                            }
                            var fake = "pppppppppppp";
                            fake = fake.substr(0,patt.length);
                            original_seq = original_seq.replace(reg, fake);
                    }
            }
            pos1_start.sort(function(a,b){return a>b?1:-1});
            pos2_start.sort(function(a,b){return a>b?1:-1});
            aat_start.sort(function(a,b){return a>b?1:-1});
            tgt_start.sort(function(a,b){return a>b?1:-1});
            
               
               
            //var sutr_start=['1','115'];
            //var sutr_end=['114','121'];
//            var wutr=[];
//            var cds=[]
//            var intron=[];
            if(sutr_start.length&&sutr_end.length!=0)
            {
                for(var sutrkey in sutr_start)
                {
                    var pos=sutr_start[sutrkey];
                    pos2=pos+1;
                    for(var a in pos1_start)
                    {
                        var i=pos1_start[a];
                        if(pos>i&&pos<=pos1_end[i])
                            pos=pos1_end[i]+2;
                    }
                    for(var b in pos2_start)
                    {
                        var i=pos2_start[b];
                        if(pos>i&&pos<=pos2_end[i])
                            pos=pos2_end[i]+2;
                    }
                    for(var c in aat_start)
                    {
                        var i=aat_start[c];
                        if(pos>i&&pos<=aat_end[i])
                            pos=aat_end[i]+2;
                    }
                    for(var d in tgt_start)
                    {
                        var i=tgt_start[d];
                        if(pos>i&&pos<=tgt_end[i])
                        pos=tgt_end[i]+2;
                    }
                    pos1=pos-1;
                    var sub1=seq.substring(0,pos1);
                    var sub2=seq.substring(pos);
                    var mid="";
                    switch(seq.charAt(pos1))
                    {
                        case "A":
                            mid="啊";
                            break;
                        case "T":
                            mid="他";
                            break;
                        case "C":
                            mid="擦";
                            break;
                        case "G":
                            mid="个";
                            break;
                      }
                      seq=sub1+mid+sub2;
                }
                for(var sutrkey1 in sutr_end)
                {
                    var pos=sutr_end[sutrkey1];
                    for(var e in pos1_start)
                    {
                        var i=pos1_start[e]
                        if(pos>i&&pos<pos1_end[i]+1)
                            pos=i;
                    }
                    for(var f in pos2_start)
                    {
                        var i=pos2_start[f]
                        if(pos>i&&pos<pos2_end[i]+1)
                            pos=i;
                    }
                    for(var g in aat_start)
                    {
                        var i=aat_start[g]
                        if(pos>i&&pos<aat_end[i]+1)
                            pos=i;
                    }
                    for(var h in tgt_start)
                    {
                        var i=tgt_start[h]
                        if(pos>i&&pos<tgt_end[i]+1)
                        pos=i;
                    }
                    pos1=pos-1;
                    var sub1=seq.substring(0,pos1);
                    var sub2=seq.substring(pos);
                    var mid="";
                    switch(seq.charAt(pos1))
                    {
                        case "A":
                            mid="阿";
                            break;
                        case "T":
                            mid="它";
                            break;
                        case "C":
                            mid="嚓";
                            break;
                        case "G":
                            mid="噶";
                            break;
                      }
                      seq=sub1+mid+sub2;
                }
            }
            if(wutr_start.length&&wutr_end.length!=0)
            {
                for(var wutrkey in wutr_start)
                {
                    var pos=wutr_start[wutrkey];
                    pos2=pos+1;
                    for(var a in pos1_start)
                    {
                        var i=pos1_start[a];
                        if(pos>i&&pos<=pos1_end[i])
                            pos=pos1_end[i]+2;
                    }
                    for(var b in pos2_start)
                    {
                        var i=pos2_start[b];
                        if(pos>i&&pos<=pos2_end[i])
                            pos=pos2_end[i]+2;
                    }
                    for(var c in aat_start)
                    {
                        var i=aat_start[c];
                        if(pos>i&&pos<=aat_end[i])
                            pos=aat_end[i]+2;
                    }
                    for(var d in tgt_start)
                    {
                        var i=tgt_start[d];
                        if(pos>i&&pos<=tgt_end[i])
                        pos=tgt_end[i]+2;
                    }
                    pos1=pos-1;
                    var sub1=seq.substring(0,pos1);
                    var sub2=seq.substring(pos);
                    var mid="";
                    switch(seq.charAt(pos1))
                    {
                        case "A":
                            mid="吖";
                            break;
                        case "T":
                            mid="她";
                            break;
                        case "C":
                            mid="拆";
                            break;
                        case "G":
                            mid="哥";
                            break;
                      }
                      seq=sub1+mid+sub2;
                }
                for(var wutrkey1 in wutr_end)
                {
                    var pos=wutr_end[wutrkey1];
                    for(var e in pos1_start)
                    {
                        var i=pos1_start[e]
                        if(pos>i&&pos<pos1_end[i]+1)
                            pos=i;
                    }
                    for(var f in pos2_start)
                    {
                        var i=pos2_start[f]
                        if(pos>i&&pos<pos2_end[i]+1)
                            pos=i;
                    }
                    for(var g in aat_start)
                    {
                        var i=aat_start[g]
                        if(pos>i&&pos<aat_end[i]+1)
                            pos=i;
                    }
                    for(var h in tgt_start)
                    {
                        var i=tgt_start[h]
                        if(pos>i&&pos<tgt_end[i]+1)
                        pos=i;
                    }
                    pos1=pos-1;
                    var sub1=seq.substring(0,pos1);
                    var sub2=seq.substring(pos);
                    var mid="";
                    switch(seq.charAt(pos1))
                    {
                        case "A":
                            mid="嗄";
                            break;
                        case "T":
                            mid="塔";
                            break;
                        case "C":
                            mid="攃";
                            break;
                        case "G":
                            mid="改";
                            break;
                      }
                      seq=sub1+mid+sub2;
                }
            }
            if(cds_start.length&&cds_end.length!=0)
            {
                for(var cdskey in cds_start)
                {
                    var pos=cds_start[cdskey];
                    pos2=pos+1;
                    for(var a in pos1_start)
                    {
                        var i=pos1_start[a];
                        if(pos>i&&pos<=pos1_end[i])
                            pos=pos1_end[i]+2;
                    }
                    for(var b in pos2_start)
                    {
                        var i=pos2_start[b];
                        if(pos>i&&pos<=pos2_end[i])
                            pos=pos2_end[i]+2;
                    }
                    for(var c in aat_start)
                    {
                        var i=aat_start[c];
                        if(pos>i&&pos<=aat_end[i])
                            pos=aat_end[i]+2;
                    }
                    for(var d in tgt_start)
                    {
                        var i=tgt_start[d];
                        if(pos>i&&pos<=tgt_end[i])
                        pos=tgt_end[i]+2;
                    }
                    pos1=pos-1;
                    var sub1=seq.substring(0,pos1);
                    var sub2=seq.substring(pos);
                    var mid="";
                    switch(seq.charAt(pos1))
                    {
                        case "A":
                            mid="挨";
                            break;
                        case "T":
                            mid="沓";
                            break;
                        case "C":
                            mid="踩";
                            break;
                        case "G":
                            mid="搞";
                            break;
                      }
                      seq=sub1+mid+sub2;
                }
                for(var cdskey1 in cds_end)
                {
                    var pos=cds_end[cdskey1];
                    for(var e in pos1_start)
                    {
                        var i=pos1_start[e]
                        if(pos>i&&pos<pos1_end[i]+1)
                            pos=i;
                    }
                    for(var f in pos2_start)
                    {
                        var i=pos2_start[f]
                        if(pos>i&&pos<pos2_end[i]+1)
                            pos=i;
                    }
                    for(var g in aat_start)
                    {
                        var i=aat_start[g]
                        if(pos>i&&pos<aat_end[i]+1)
                            pos=i;
                    }
                    for(var h in tgt_start)
                    {
                        var i=tgt_start[h]
                        if(pos>i&&pos<tgt_end[i]+1)
                        pos=i;
                    }
                    pos1=pos-1;
                    var sub1=seq.substring(0,pos1);
                    var sub2=seq.substring(pos);
                    var mid="";
                    switch(seq.charAt(pos1))
                    {
                        case "A":
                            mid="艾";
                            break;
                        case "T":
                            mid="牠";
                            break;
                        case "C":
                            mid="彩";
                            break;
                        case "G":
                            mid="工";
                            break;
                      }
                      seq=sub1+mid+sub2;
                }
            }
             if(intron_start.length&&intron_end.length!=0)
            {
                for(var intronkey in intron_start)
                {
                    var pos=intron_start[intronkey];
                    pos2=pos+1;
                    for(var a in pos1_start)
                    {
                        var i=pos1_start[a];
                        if(pos>i&&pos<=pos1_end[i])
                            pos=pos1_end[i]+2;
                    }
                    for(var b in pos2_start)
                    {
                        var i=pos2_start[b];
                        if(pos>i&&pos<=pos2_end[i])
                            pos=pos2_end[i]+2;
                    }
                    for(var c in aat_start)
                    {
                        var i=aat_start[c];
                        if(pos>i&&pos<=aat_end[i])
                            pos=aat_end[i]+2;
                    }
                    for(var d in tgt_start)
                    {
                        var i=tgt_start[d];
                        if(pos>i&&pos<=tgt_end[i])
                        pos=tgt_end[i]+2;
                    }
                    pos1=pos-1;
                    var sub1=seq.substring(0,pos1);
                    var sub2=seq.substring(pos);
                    var mid="";
                    switch(seq.charAt(pos1))
                    {
                        case "A":
                            mid="哎";
                            break;
                        case "T":
                            mid="踏";
                            break;
                        case "C":
                            mid="礤";
                            break;
                        case "G":
                            mid="跟";
                            break;
                      }
                      seq=sub1+mid+sub2;
                }
                for(var intronkey1 in intron_end)
                {
                    var pos=intron_end[intronkey1];
                    for(var e in pos1_start)
                    {
                        var i=pos1_start[e]
                        if(pos>i&&pos<pos1_end[i]+1)
                            pos=i;
                    }
                    for(var f in pos2_start)
                    {
                        var i=pos2_start[f]
                        if(pos>i&&pos<pos2_end[i]+1)
                            pos=i;
                    }
                    for(var g in aat_start)
                    {
                        var i=aat_start[g]
                        if(pos>i&&pos<aat_end[i]+1)
                            pos=i;
                    }
                    for(var h in tgt_start)
                    {
                        var i=tgt_start[h]
                        if(pos>i&&pos<tgt_end[i]+1)
                        pos=i;
                    }
                    pos1=pos-1;
                    var sub1=seq.substring(0,pos1);
                    var sub2=seq.substring(pos);
                    var mid="";
                    switch(seq.charAt(pos1))
                    {
                        case "A":
                            mid="爱";
                            break;
                        case "T":
                            mid="塌";
                            break;
                        case "C":
                            mid="才";
                            break;
                        case "G":
                            mid="挂";
                            break;
                      }
                      seq=sub1+mid+sub2;
                }
            } 
            if(exon_start.length&&exon_end.length!=0)
            {
                for(var exonkey in exon_start)
                {
                    var pos=exon_start[exonskey];
                    pos2=pos+1;
                    for(var a in pos1_start)
                    {
                        var i=pos1_start[a];
                        if(pos>i&&pos<=pos1_end[i])
                            pos=pos1_end[i]+2;
                    }
                    for(var b in pos2_start)
                    {
                        var i=pos2_start[b];
                        if(pos>i&&pos<=pos2_end[i])
                            pos=pos2_end[i]+2;
                    }
                    for(var c in aat_start)
                    {
                        var i=aat_start[c];
                        if(pos>i&&pos<=aat_end[i])
                            pos=aat_end[i]+2;
                    }
                    for(var d in tgt_start)
                    {
                        var i=tgt_start[d];
                        if(pos>i&&pos<=tgt_end[i])
                        pos=tgt_end[i]+2;
                    }
                    pos1=pos-1;
                    var sub1=seq.substring(0,pos1);
                    var sub2=seq.substring(pos);
                    var mid="";
                    switch(seq.charAt(pos1))
                    {
                        case "A":
                            mid="唉";
                            break;
                        case "T":
                            mid="榻";
                            break;
                        case "C":
                            mid="菜";
                            break;
                        case "G":
                            mid="过";
                            break;
                      }
                      seq=sub1+mid+sub2;
                }
                for(var exonkey1 in exon_end)
                {
                    var pos=exon_end[exonkey1];
                    for(var e in pos1_start)
                    {
                        var i=pos1_start[e]
                        if(pos>i&&pos<pos1_end[i]+1)
                            pos=i;
                    }
                    for(var f in pos2_start)
                    {
                        var i=pos2_start[f]
                        if(pos>i&&pos<pos2_end[i]+1)
                            pos=i;
                    }
                    for(var g in aat_start)
                    {
                        var i=aat_start[g]
                        if(pos>i&&pos<aat_end[i]+1)
                            pos=i;
                    }
                    for(var h in tgt_start)
                    {
                        var i=tgt_start[h]
                        if(pos>i&&pos<tgt_end[i]+1)
                        pos=i;
                    }
                    pos1=pos-1;
                    var sub1=seq.substring(0,pos1);
                    var sub2=seq.substring(pos);
                    var mid="";
                    switch(seq.charAt(pos1))
                    {
                        case "A":
                            mid="矮";
                            break;
                        case "T":
                            mid="祂";
                            break;
                        case "C":
                            mid="猜";
                            break;
                        case "G":
                            mid="高";
                            break;
                      }
                      seq=sub1+mid+sub2;
                }
            }
            

     
            var miss = [];
            var spaces_9 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            var newSeq = "";
            var rulerLeadingSpaces = "&nbsp;";
            var rows = Div(seq.length,100) + 1;
            if (rows.toString().length - 1 > 0) 
            {
                    for (var n = 0; n < rows.toString().length - 1; n++) 
                    {
                            rulerLeadingSpaces += "&nbsp;";
                  }
            }
            var arr = [];
            var tmp_str = seq;
            for(var i=0;i<rows;i++)
            {
                    arr[i] = seq.substr(0+100*i,100);
            }

            newSeq += "<font color='#324A17'><strong>"  + spaces_9 + "10" + spaces_9 + "20" + spaces_9 + "30" + spaces_9 + "40" + spaces_9 + "50" + spaces_9 + "60" + spaces_9 + "70" + spaces_9 + "80" + spaces_9 + "90" + spaces_9 + "100" + "</strong></font><br>";
            
//            //1到19之间改变颜色
//            var sutr_start=['146','12'];
//            var sutr_end=['189','22'];
//            var sutr_key1=Div(sutr_start[0],100)+1;//判断第几行
//            var sutr_key2=Div(sutr_end[0],100)+1;
//            var sutr_key3=Div(sutr_start[0],10)-10*(sutr_key1-1);//判断第几个块
//            var sutr_key4=Div(sutr_end[0],10)-10*(sutr_key2-1);
//            var sutr_key5=sutr_start[0]%10-1;//判断所属块的第几个
//            var sutr_key6=sutr_end[0]%10;
//            
            for(var key3 in arr)
            {
                    var row = parseInt(key3)+1;
                    //newSeq += "<font color='#324A17'><strong>" + row + "</strong></font>";
                    var tmp_arr = [];

                    for(var n = 0;n<10;n++)
                    {
                            tmp_arr[n] = arr[key3].substr(0+10*n,10);
                    }
//                    if(sutr_key1==row)
//                    {
//                        tmp1=tmp_arr[sutr_key3].substring(0,sutr_key5);
//                        tmp2="<span class='pt8'>";
//                        tmp3=tmp_arr[sutr_key3].substring(sutr_key5);
//                        tmp_arr[sutr_key3]=tmp1+tmp2+tmp3;
//                    }
//                    if(sutr_key2==row)
//                    {
//                        tmp1=tmp_arr[sutr_key4].substring(0,sutr_key6);
//                        tmp2="</span class='pt8'>";
//                        tmp3=tmp_arr[sutr_key4].substring(sutr_key6);
//                        tmp_arr[sutr_key4]=tmp1+tmp2+tmp3;
//                    }
                    var tmp = tmp_arr.join(",");
                    var range_min = key3*100;
                    var range_max = row*100-1 ;
                    var tmp_str = "";

                    var insert = [];
                    var slices = [];
//                    insert[5]="<span class='pt8'>";
//                   insert[20]="</span class='pt8'>";
//                   slices.push(5);
//                   slices.push(20);
//                            

                    while(pos1_start[0] <= range_max)
                    {
                            var pos1 = pos1_start[0];
                            var pos2 = pos1_end[pos1];
                            pos1 -=  range_min;
                            if(pos1 > 9)
                            {
                                    var tmp_num = parseInt(pos1.toString().substr(0,1)) ;
                                    //document.write(pos1);
                                    pos1 +=  tmp_num;
                                    //document.write("+"+tmp_num+"=");
                                    //document.write(pos1);
                            }
                            pos2 -= range_min;
                            if(pos2 > 9)
                            {
                                    var tmp_num = parseInt(pos2.toString().substr(0,1)) ;
                                    //document.write(pos2);
                                    if(tmp_num == 0)
                                    {
                                            pos2 += 9;
                                    }
                                    else
                                    {
                                            pos2 +=  tmp_num;
                                    }

                                    //document.write("+"+tmp_num+"=");
                                    //document.write(pos2);
                            }
                            insert[pos1] = "<span class='pt1'>";
                            if(pos1 != pos2)
                            {
                                    insert[parseInt(pos2)] = "</span class='pt1'>";
                                    slices.push(pos1);
                                    slices.push(pos2);
                            }
                            else
                            {
                                    miss.push(pos1);
                                    slices.push(pos2);
                            }

                            pos1_start.shift();
                    }
                    while(pos2_start[0] <= range_max)
                    {
                            var pos1 = pos2_start[0];
                            var pos2 = pos2_end[pos1];
                            pos1 -= range_min;
                            if(pos1 > 9)
                            {
                                    var tmp_num = parseInt(pos1.toString().substr(0,1)) ;
                                    //document.write(pos1);
                                    pos1 +=  tmp_num;
                                    //document.write("+"+tmp_num+"=");
                                    //document.write(pos1);
                            }
                            pos2 = pos2 - range_min;
                            if(pos2 > 9)
                            {
                                    var tmp_num = parseInt(pos2.toString().substr(0,1)) ;
                                    //document.write(pos2);
                                    if(tmp_num == 0)
                                    {
                                            pos2 += 9;
                                    }
                                    else
                                    {
                                            pos2 +=  tmp_num;
                                    }
                                    //document.write("+"+tmp_num+"=");
                                    //document.write(pos2);
                            }
                            insert[parseInt(pos1)] = "<span class='pt2'>";
                            if(pos1 != pos2)
                            {
                                    insert[parseInt(pos2)] = "</span class='pt2'>";
                                    slices.push(pos1);
                                    slices.push(pos2);
                            }
                            else
                            {
                                    miss.push(pos1);
                                    slices.push(pos2);
                            }
                            //insert[parseInt(pos1)] = "qq";
                            //insert[parseInt(pos2)] = "pp";

                            pos2_start.shift();
                    }
                    while(aat_start[0] <= range_max)
                    {
                            var pos1 = aat_start[0];
                            var pos2 = aat_end[pos1];
                            pos1 = pos1 - range_min;
                            if(pos1 > 9)
                            {
                                    var tmp_num = parseInt(pos1.toString().substr(0,1)) ;
                                    //document.write(pos1);
                                    pos1 +=  tmp_num;
                                    //document.write("+"+tmp_num+"=");
                                    //document.write(pos1);
                            }
                            pos2 = pos2 - range_min;
                            if(pos2 > 9)
                            {
                                    var tmp_num = parseInt(pos2.toString().substr(0,1)) ;
                                    //document.write(pos2);
                                    if(tmp_num == 0)
                                    {
                                            pos2 += 9;
                                    }
                                    else
                                    {
                                            pos2 +=  tmp_num;
                                    }
                                    //document.write("+"+tmp_num+"=");
                                    //document.write(pos2);
                            }
                            insert[parseInt(pos1)] = "<span class='pt3'>";
                            if(pos1 != pos2)
                            {
                                    insert[parseInt(pos2)] = "</span class='pt3'>";
                                    slices.push(pos1);
                                    slices.push(pos2);
                            }
                            else
                            {
                                    miss.push(pos1);
                                    slices.push(pos2);
                            }
                            //insert[parseInt(pos1)] = "qq";
                            //insert[parseInt(pos2)] = "pp";

                            aat_start.shift();
                    }
                    while(tgt_start[0] <= range_max)
                    {
                            var pos1 = tgt_start[0];
                            var pos2 = tgt_end[pos1];
                            pos1 = pos1 - range_min;
                            if(pos1 > 9)
                            {
                                    var tmp_num = parseInt(pos1.toString().substr(0,1)) ;
                                    //document.write(pos1);
                                    pos1 +=  tmp_num;
                                    //document.write("+"+tmp_num+"=");
                                    //document.write(pos1);
                            }
                            pos2 = pos2 - range_min;
                            if(pos2 > 9)
                            {
                                    var tmp_num = parseInt(pos2.toString().substr(0,1)) ;
                                    //document.write(pos2);
                                    if(tmp_num == 0)
                                    {
                                            pos2 += 9;
                                    }
                                    else
                                    {
                                            pos2 +=  tmp_num;
                                    }
                                    //document.write("+"+tmp_num+"=");
                                    //document.write(pos2);
                            }
                            insert[parseInt(pos1)] = "<span class='pt4'>";
                            if(pos1 != pos2)
                            {
                                    insert[parseInt(pos2)] = "</span class='pt4'>";
                                    slices.push(pos1);
                                    slices.push(pos2);
                            }
                            else
                            {
                                    miss.push(pos1);
                                    slices.push(pos2);
                            }
                            //insert[parseInt(pos1)] = "qq";
                            //insert[parseInt(pos2)] = "pp";


                            tgt_start.shift();
                    }
                    slices.sort(function(a,b){return a>b?1:-1});
                    /*
                    document.write(key3+"============="+range_min+":"+range_max);
                    document.write("<br>");
                    for (var test in slices)
                    {
                            document.write(slices[test]);
                            document.write("<br>");
                    }
                    */
                    var sign = 0;
                    for(var subkey5 in slices)
                    {
                            if(subkey5 == 0)
                            {
                                    /*
                                    if(insert[slices[subkey5]].substr(1,1) == "s")
                                    {
                                            tmp_str = tmp.substring(0,slices[subkey5]);
                                            tmp_str += insert[slices[subkey5]];
                                    }
                                    else
                                    {
                                            tmp_str = tmp.substring(0,slices[subkey5]+1);
                                            tmp_str += insert[slices[subkey5]+1];
                                    }
                                    */
                                    if(slices[0] == 0)
                                    {
                                            //tmp_str = tmp.substring(0,slices[subkey5]);
                                            tmp_str = insert[slices[subkey5]];
                                    }
                                    else
                                    {
                                            tmp_str = tmp.substring(0,slices[subkey5]);
                                            tmp_str += insert[slices[subkey5]];
                                    }		
                            }
                            else
                            {
                                    if(insert[slices[subkey5]].substr(1,1) == "s")
                                    {
                                            if(sign == 1)
                                            {
                                                    //tmp_str += tmp.substring(slices[subkey5-1]+1,slices[subkey5]);
                                                    tmp_str += tmp.substr(slices[subkey5-1],1);
                                                    tmp_str += "</span>";
                                                    tmp_str += tmp.substring(slices[subkey5-1]+1,slices[subkey5]);
                                                    sign = 0;
                                            }
                                            else
                                            {
                                                    tmp_str += tmp.substring(slices[subkey5-1]+1,slices[subkey5]);
                                            }
                                            tmp_str += insert[slices[subkey5]];
                                    }
                                    else
                                    {
                                            tmp_str += tmp.substring(slices[subkey5-1],slices[subkey5]+1);
                                            tmp_str += insert[slices[subkey5]];
                                    }
                            }
                            if(in_array(miss,slices[subkey5]))
                            {
                                    sign = 1;
                            }
                    }
                    if(insert.length%2 != 0)
                    //if(insert[insert.length-1].substr(1,1) == "s")
                    {
                            if(insert[insert.length-1].substr(1,1) == "s")
                            {
                                    tmp_str += tmp.substring(slices[slices.length -1]);
                                    tmp_str += "</span>";
                            }
                            else
                            {	
                                    tmp_str += tmp.substring(slices[slices.length -1]+1);
                            }	
                    }
                    else
                    {
                            tmp_str += tmp.substring(slices[slices.length -1]+1);
                    }
                    if(row.toString().length < rows.toString().length)
                    {
                            var q = rows.toString().length - key3.toString().length;
                            for (var p = 0; p < q; p++) {
                                    //newSeq +=  "&nbsp;";
                            };
                    }
                    newSeq += tmp_str + "<br>";
            }
            newSeq = newSeq.replace(/,/g,"&nbsp;");
            newSeq = newSeq.replace(/啊/g,"<span class='pt5'>A");
            newSeq = newSeq.replace(/他/g,"<span class='pt5'>T");
            newSeq = newSeq.replace(/擦/g,"<span class='pt5'>C");
            newSeq = newSeq.replace(/个/g,"<span class='pt5'>G");
            newSeq = newSeq.replace(/阿/g,"A</span class='pt5'>");
             newSeq = newSeq.replace(/它/g,"T</span class='pt5'>");
            newSeq = newSeq.replace(/嚓/g,"C</span class='pt5'>");
            newSeq = newSeq.replace(/噶/g,"G</span class='pt5'>");
            newSeq = newSeq.replace(/吖/g,"<span class='pt6'>A");
            newSeq = newSeq.replace(/她/g,"<span class='pt6'>T");
            newSeq = newSeq.replace(/拆/g,"<span class='pt6'>C");
            newSeq = newSeq.replace(/哥/g,"<span class='pt6'>G");
            newSeq = newSeq.replace(/嗄/g,"A</span class='pt6'>");
             newSeq = newSeq.replace(/塔/g,"T</span class='pt6'>");
            newSeq = newSeq.replace(/攃/g,"C</span class='pt6'>");
            newSeq = newSeq.replace(/改/g,"G</span class='pt6'>");
            newSeq = newSeq.replace(/挨/g,"<span class='pt7'>A");
            newSeq = newSeq.replace(/沓/g,"<span class='pt7'>T");
            newSeq = newSeq.replace(/踩/g,"<span class='pt7'>C");
            newSeq = newSeq.replace(/搞/g,"<span class='pt7'>G");
            newSeq = newSeq.replace(/艾/g,"A</span class='pt7'>");
             newSeq = newSeq.replace(/牠/g,"T</span class='pt7'>");
            newSeq = newSeq.replace(/彩/g,"C</span class='pt7'>");
            newSeq = newSeq.replace(/工/g,"G</span class='pt7'>");
            newSeq = newSeq.replace(/哎/g,"<span class='pt8'>A");
            newSeq = newSeq.replace(/踏/g,"<span class='pt8'>T");
            newSeq = newSeq.replace(/礤/g,"<span class='pt8'>C");
            newSeq = newSeq.replace(/跟/g,"<span class='pt8'>G");
            newSeq = newSeq.replace(/爱/g,"A</span class='pt8'>");
             newSeq = newSeq.replace(/塌/g,"T</span class='pt8'>");
            newSeq = newSeq.replace(/才/g,"C</span class='pt8'>");
            newSeq = newSeq.replace(/挂/g,"G</span class='pt8'>");
            newSeq = newSeq.replace(/唉/g,"<span class='pt9'>A");
            newSeq = newSeq.replace(/榻/g,"<span class='pt9'>T");
            newSeq = newSeq.replace(/菜/g,"<span class='pt9'>C");
            newSeq = newSeq.replace(/过/g,"<span class='pt9'>G");
            newSeq = newSeq.replace(/矮/g,"A</span class='pt9'>");
             newSeq = newSeq.replace(/祂/g,"T</span class='pt9'>");
            newSeq = newSeq.replace(/猜/g,"C</span class='pt9'>");
            newSeq = newSeq.replace(/高/g,"G</span class='pt9'>");

            

            load_current_seq(newSeq);
        }

        function in_array(array,value)
        {
                for(i=0;i<array.length;i++)
                {
                if(array[i] == value)
                return true;
                }
                return false;
        }

        function reset(value)
        {
                var seq = sequences[value];
                var makeseq  = make(seq);
                load_current_seq(makeseq);
        }

        function Div(exp1, exp2)  
        {  
            var n1 = Math.round(exp1); //四舍五入  
            var n2 = Math.round(exp2); //四舍五入  

            var rslt = n1/n2; //除  

            if (rslt >= 0)  
            {  
                rslt = Math.floor(rslt); //返回小于等于原rslt的最大整数。  
            }  
            else  
            {  
                rslt = Math.ceil(rslt); //返回大于等于原rslt的最小整数。  
            }  
      
            return rslt;  
        }  

        function make(seq)
        {
                var spaces_9 = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                var newSeq = "";
                var rulerLeadingSpaces = "&nbsp;";
                var rows = Div(seq.length,100) + 1;
                if (rows.toString().length - 1 > 0) 
                {
                        for (var n = 0; n < rows.toString().length - 1; n++) 
                        {
                                rulerLeadingSpaces += "&nbsp;";
                        }
                }
                var arr = [];
                for(var i=0;i<rows;i++)
                {
                        arr[i] = seq.substr(0+100*i,100);
                }

                newSeq += "<font color='#324F17'><strong>"  + spaces_9 + "10" + spaces_9 + "20" + spaces_9 + "30" + spaces_9 + "40" + spaces_9 + "50" + spaces_9 + "60" + spaces_9 + "70" + spaces_9 + "80" + spaces_9 + "90" + spaces_9 + "100" + "</strong></font><br>";
                for(var key in arr)
                {
                        var row = parseInt(key)+1;
                        //newSeq += "<font color='#324F17'><strong>" + row + "</strong></font>";
                        var tmp_arr = [];
                        for(var n = 0;n<10;n++)
                        {
                                tmp_arr[n] = arr[key].substr(0+10*n,10);
                        }
                        var tmp = tmp_arr.join("&nbsp;");
                        if(row.toString().length < rows.toString().length)
                        {
                                var q = rows.toString().length - key.toString().length;
                                for (var p = 0; p < q; p++) {
                                        //newSeq +=  "&nbsp;";
                                };
                        }
                        newSeq +=  tmp + "<br>";
                }
                return newSeq;
        }

        function load_current_seq(current_seq)
        {
                $('#seq_content').html(current_seq);
        }

        </script>
        <script type="text/javascript">
            $(document).ready(function (){

                $('#find_patt').click(function(){
  	find_pattern_onload();
                });

                $('#reset').click(function(){
                  //reset($("#seq_switch").val());
                  reset(0);
                });

                $('#checkall1').click(function(){
  	//reset($("#seq_switch").val());
  	var value = $('#checkall1').val();
  	if(value == "checkall")
  	{
  		$('#checkall1').val('uncheck');
  		$("input[type=checkbox]:checked").each(function(){ 
	  		this.checked = false;
		}); 
  	}
  	else if(value == "uncheck")
  	{
  		$('#checkall1').val('checkall');
		$("input[type=checkbox]").each(function(){ 
	  		this.checked = true;
		}); 
  	}
                });
                var makeseq  = make(current_seq);
                load_current_seq(makeseq);
                find_pattern_onload();
            });
            </script>
            
<!--            sequence viewer-->
 <div  class="straight_matter">
	<fieldset style="margin-top: 20px;margin-left: 2%;margin-right: 2%;">
                    <legend>
                        <span class="h3_italic">
                            <font color="#224055" size="18px;"><b>Gene Viewer</b></font>
                        </span>
                    </legend>
	<div class = "seq_viewer" id="seq_viewer">
                    <div id = "pattern">	
        	<legend><span class="h3_italic">Typical Pattern</span>&nbsp;<span class='pt2' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;(AATAAA&nbsp;<span class='pt3' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;TGTAAA&nbsp;<span class='pt4' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;)</legend>
        	<table style="width:900px;margin-top:10px;margin-bottom:10px;font-family: Courier New;font-size: 16px;">
                    <?php
                    echo "<tr>";
                    echo '<td><input type="checkbox" checked = "true" name = "cbox" id = "checkall1" value = "checkall" /><em>&nbsp;Change All</em></td>';
                    $i = 0;
                    foreach ($singnals as $key => $value) {
                            if($i == 6||$i == 13)
                            {
                                    echo "</tr><tr>";
                            }
                            echo '<td><input type="checkbox" name = "cbox1" checked = "true" value = "'.$value.'"/>&nbsp;'.$value.'</td>';
                            $i++;
                    }
                    echo "</tr>";
                    ?>
           	</table>

                    <legend><span class="h3_italic">User’s Pattern </span>&nbsp;&nbsp;<span class='pt1' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;(Ex. AATAAA, TGTAAA)</legend>
                    <input type = "text" id = "user_pattern" style="margin-top:10px;margin-bottom:10px;"></input>
                                    
<!--                            <legend>
                                    <span class="h3_italic">Predicted poly(A) site</span>
                                    &nbsp;&nbsp;
                                    <span style="text-align:center;"><font size="2" color='red'><u>N</u></font></span>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <span class="h3_italic">Real poly(A) site</span>
                                    &nbsp;&nbsp;
                                    <span style="text-align:center;"><font size="2" color='blue'><u>N</u></font></span>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <span class="h3_italic">Predicted&Real poly(A) site</span>
                                    &nbsp;&nbsp;
                                    <span style="text-align:center;"><font size="2" color='green'><u>N</u></font></span>
                            </legend>-->
                            <br>
                                <legend><span class="h3_ltalic">others</span>&nbsp;&nbsp;(3'UTR <span class='pt5' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;5'UTR&nbsp;<span class='pt6' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;CDS&nbsp;<span class='pt7' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;Intron&nbsp;<span class='pt8' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;exon&nbsp;<span class='pt9' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;)</legend>
                            <br>
                            <button id = "find_patt" style="width:100px;"  class = "button blue medium">Show</button>
                            <button id = "reset" style = "width:100px;"  class = "button blue medium">Clear</button>
                    </div>
                    <div id = "seq_content" style="max-height:400px;overflow:auto;margin-top:20px;font-family: Courier New;font-size: 15px;">
                            <p class = "sequence" id = "sequence"style="word-break:break-all;"><?php echo "AATAAAAAA";  ?>
                            </p>	            	
                    </div>
	</div>
	</fieldset>
            </div>
        </div>
    <?php
        include './wheelmenu.php';
        ?>
    <?php
        include './footer.php';
    ?>
</body>
</html>