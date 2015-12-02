<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Sequence Detail</title>
        <script src="./src/jquery-1.10.1.min.js"></script>
        <script src="./src/jquery.dataTables.min.js"type="text/javascript" ></script>
        <link href="./src/jquery.dataTables.css"type="text/css" rel="stylesheet"></link>
        <!-- Mobile viewport optimisation -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <!--[if lte IE 7]>
        <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <!--[if lt IE 9]>
        <script src="./js/html5shiv/html5shiv.js"></script>
        <![endif]-->

        <!--<link rel="stylesheet" href="./src/font-awesome.min.css">-->
        <!--<link rel="stylesheet" href="./src/example.css">-->
       
        <style>
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
            span.pt10{
                color:black;
                background-color: #ffd700;
                /*cursor: pointer;*/
            }
            span.pt11{
                color:black;
                background-color: #9aff9a;
                /*cursor: pointer;*/
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
        ?>
        <?php
        if(isset($_GET['strand'])){
            $strand=$_GET['strand'];
        }
        if(isset($_GET['species'])){
            $species=$_GET['species'];
        }
        else {
            $species=$_SESSION['species'];
        }
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
         $singnals = array("AATAAA","TATAAA","CATAAA","GATAAA","ATTAAA","ACTAAA","AGTAAA","AAAAAA","AACAAA","AAGAAA","AATTAA","AATCAA","AATGAA","AATATA","AATACA","AATAGA","AATAAT","AATAAC","AATAAG");        
         if($_GET['flag']=='intergenic'){
            $a="SELECT * from db_server.t_".$species."_gff_all where ftr_start<=$gene and ftr_end>=$gene and chr='$chr' and ftr='intergenic';";
         }
         else{
            $a="SELECT * from db_server.t_".$species."_gff_all where ftr_start<=$gene and ftr_end>=$gene and chr='$chr' and ftr='gene';";
         }
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
//        $b="select substring(seq,$gene_start,$gene_end-$gene_start) from db_server.t_".$species."_fa where title='$chr';";
//        //echo $b;
//        $seq_result=  mysql_query($b);
//        while($rows=mysql_fetch_row($seq_result))
//         {
//             //echo "in it";
//             $seq=$rows[0];
//         }
         $seq=  file_get_contents("./seq/".$_GET['species']."/".strtoupper($_GET['seq']).".fa");
         if($_GET['flag']=='intergenic'){
             $coord=$_GET['coord'];
             $coordL=$coord-200;
             $coordH=$coord+200;
             $seq_result=mysql_query("select substring(seq,$coordL,401) from db_server.t_".$species."_fa where title='$chr';");
             while($rows=mysql_fetch_row($seq_result))
             {
                 //echo "in it";
                 $seq=$rows[0];
             }
             echo strlen($seq);
         }
         if(strcmp($strand,-1)==0)//反转互补
         {
             $seq= strrev($seq);
             $seq_arr=str_split($seq);
             array_shift($seq_arr);
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
         else{
             $seq=substr($seq,0,strlen($seq)-1); 
         }
         $c="select * from db_server.t_".$species."_gff where gene like '$gene_name' ;";
         //echo $c;
         $seq_feature=  mysql_query($c);
         while($row_f=  mysql_fetch_row($seq_feature))
         {
             //echo "in it";
             $ftr[]=$row_f[2];
             $f_start[]=$row_f[3];
             $f_end[]=$row_f[4];
         }
//         print_r($f_start);
         //polyA 位点信息
         $pa_start=array();
         $pa_result=mysql_query("select * from db_server.t_".$_GET['species']."_pa1 where chr='$chr' and coord>=$gene_start and coord<=$gene_end and tot_tagnum>0;");
         while ($pa_row=  mysql_fetch_row($pa_result))
         {
             array_push($pa_start, $pa_row[2]);
         }
//         var_dump($gene_start);
//         var_dump($pa_start);
         foreach($pa_start as $key => $value)
         {
             if(strcmp($strand,-1)==0)
             {
                 $pa_start[$key]=$gene_end-$pa_start[$key]+1;
             }
             else if(strcmp($strand,1)==0)
            {
                $pa_start[$key]=$pa_start[$key]-$gene_start+1;
             }
         }
         //3utr extend 位置信息
         $extend=  mysql_query("select * from t_".$species."_gff_org where gene='$gene_name';");
        while($ext_r=  mysql_fetch_row($extend)){
            if($ext_r[2]=='3UTR')
                $ext_start=$ext_r[3];
                $ext_end=$ext_r[4];
        }

         echo "<script type=\"text/javascript\">";
         //echo "var sequences = ['AAAATAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA','AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA','AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'];"; 
         //echo "var current_seq='AAAATAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';";
         echo "var sequences=[];";
         //echo "sequences.push('$row[0]');";
         echo "var current_seq = '$seq';";
         echo "sequences.push('$seq');";
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
         foreach ($pa_start as $key => $value)
         {
             echo "pa_start.push('$value');";
         }
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
                    $ext_sp=$ext_end-$gene_start;
                    $ext_ep=$gene_end-$gene_start;
                    echo "ext_start.push('$ext_sp');";
                    echo "ext_end.push('$ext_ep');";
                 }
                 else if(strcmp($strand,-1)==0)
                {
                    $s_start=$f_start[$f_key];
                    $se_p=$gene_end-$s_start+1;//3utr end point
                    echo "sutr_end.push('$se_p');";
                    $s_end=$f_end[$f_key];
                    $ss_p=$gene_end-$s_end+1;//3utr start point
                    echo "sutr_start.push('$ss_p');";
                    $ext_sp=$gene_end-$ext_start;
                    $ext_ep=$gene_end-$gene_start;
                    echo "ext_start.push('$ext_sp');";
                    echo "ext_end.push('$ext_ep');";
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
             if(strcmp($val, 'AMB')==0)
             {
                 if(strcmp($strand,1)==0)
                 {
                    $a_start=$f_start[$f_key];
                    $as_p=$a_start-$gene_start;//AMB start point
                    echo "amb_start.push('$as_p');";
                    $a_end=$f_end[$f_key];
                    $ae_p=$a_end-$gene_start;//amb end point
                    echo "amb_end.push('$ae_p');";
                 }
                 else if(strcmp($strand,-1)==0)
                {
                    $a_start=$f_start[$f_key];
                    $ae_p=$gene_end-$a_start+1;//3utr end point
                    echo "amb_end.push('$ae_p');";
                    $a_end=$f_end[$f_key];
                    $as_p=$gene_end-$a_end+1;//3utr start point
                    echo "amb_start.push('$as_p');";
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
                var ftr = [];
  	var user_patt = $("#user_pattern").val();
  	patts1 = user_patt.split(",");
  	$("input[name=cbox1]:checked").each(function(){ 
                            if($(this).val() != "checkall")
                            {
                                                        patts2.push($(this).val());
                            }
	});
                $("input[name=cbox2]:checked").each(function(){ 
                        ftr.push($(this).val());
                });
	//find_pattern(patts1,patts2,pas[cur_seq_id],rpas[cur_seq_id]);
                find_pattern(patts1,patts2,ftr);
        }

        //function find_pattern(patts1,patts2,pa,rpa)
        function find_pattern(patts1,patts2,ftr)
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
            if(ftr.indexOf("3UTR")!=-1){
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
            }
            if(ftr.indexOf("EXT")!=-1){
                if(ext_start.length&&ext_end.length!=0)
                {
                    for(var extkey in ext_start)
                    {
                        var pos=ext_start[extkey];
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
                                mid="癌";
                                break;
                            case "T":
                                mid="蹋";
                                break;
                            case "C":
                                mid="材";
                                break;
                            case "G":
                                mid="割";
                                break;
                          }
                          seq=sub1+mid+sub2;
                    }
                    for(var extkey1 in ext_end)
                    {
                        var pos=ext_end[extkey1];
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
                                mid="埃";
                                break;
                            case "T":
                                mid="铊";
                                break;
                            case "C":
                                mid="裁";
                                break;
                            case "G":
                                mid="嗝";
                                break;
                          }
                          seq=sub1+mid+sub2;
                    }
                }
            }
            if(ftr.indexOf("5UTR")!=-1){
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
            }
            if(ftr.indexOf("CDS")!=-1){
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
            }
            if(ftr.indexOf("INTRON")!=-1){
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
            }
            if(ftr.indexOf("EXON")!=-1){
                if(exon_start.length&&exon_end.length!=0)
                {
                    for(var exonkey in exon_start)
                    {
                        var pos=exon_start[exon_key];
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
            }
            if(ftr.indexOf("AMB")!=-1){
                if(amb_start.length&&amb_end.length!=0)
                {
                    for(var ambkey in amb_start)
                    {
                        var pos=amb_start[ambkey];
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
                                mid="哀";
                                break;
                            case "T":
                                mid="挞";
                                break;
                            case "C":
                                mid="财";
                                break;
                            case "G":
                                mid="阁";
                                break;
                          }
                          seq=sub1+mid+sub2;
                    }
                    for(var ambkey1 in amb_end)
                    {
                        var pos=amb_end[ambkey1];
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
                                mid="碍";
                                break;
                            case "T":
                                mid="獭";
                                break;
                            case "C":
                                mid="蔡";
                                break;
                            case "G":
                                mid="革";
                                break;
                          }
                          seq=sub1+mid+sub2;
                    }
                }
            }
            if(ftr.indexOf("PA")!=-1){
                if(pa_start.length != 0)
                {
                    for(var pakey in pa_start)
                    {
                            var pos = pa_start[pakey];
                            pos1 = pos - 1;
                            var sub1 = seq.substring(0,pos1);
                            var sub2 = seq.substring(pos);
                            var mid = "";
                            switch(seq.charAt(pos1))
                            {
                            case "A":
                                    mid = "W";
                                    break;
                            case "T":
                                    mid = "X";
                                    break;
                            case "C":
                                    mid = "Y";
                                    break;
                            case "G":
                                    mid = "Z";
                                    break;
                            }
                            seq = sub1+mid+sub2;
                    }
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
            newSeq = newSeq.replace(/哀/g,"<span class='pt10'>A");
            newSeq = newSeq.replace(/挞/g,"<span class='pt10'>T");
            newSeq = newSeq.replace(/财/g,"<span class='pt10'>C");
            newSeq = newSeq.replace(/阁/g,"<span class='pt10'>G");
            newSeq = newSeq.replace(/碍/g,"A</span class='pt10'>");
             newSeq = newSeq.replace(/獭/g,"T</span class='pt10'>");
            newSeq = newSeq.replace(/蔡/g,"C</span class='pt10'>");
            newSeq = newSeq.replace(/革/g,"G</span class='pt10'>");
            newSeq = newSeq.replace(/癌/g,"<span class='pt11'>A");
            newSeq = newSeq.replace(/蹋/g,"<span class='pt11'>T");
            newSeq = newSeq.replace(/材/g,"<span class='pt11'>C");
            newSeq = newSeq.replace(/割/g,"<span class='pt11'>G");
            newSeq = newSeq.replace(/埃/g,"A</span class='pt11'>");
             newSeq = newSeq.replace(/铊/g,"T</span class='pt11'>");
            newSeq = newSeq.replace(/裁/g,"C</span class='pt11'>");
            newSeq = newSeq.replace(/嗝/g,"G</span class='pt11'>");
            newSeq = newSeq.replace(/W/g,"<font color='red'><strong><u>A</u></strong></font>");
            newSeq = newSeq.replace(/X/g,"<font color='red'><strong><u>T</u></strong></font>");
            newSeq = newSeq.replace(/Y/g,"<font color='red'><strong><u>C</u></strong></font>");
            newSeq = newSeq.replace(/Z/g,"<font color='red'><strong><u>G</u></strong></font>");

            

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
            
        <div  id="page">
            <table width="99%" cellspacing="0" cellpadding="0" border="0" style="margin: 20px auto;">
            <tbody>
                <tr>
                    <td width="30%" valign="top">
                          <hr width="98%" size="1" align="left" style="border-top: 1px dotted #5499c9;">
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
                        <hr width="98%" size="1" align="left" style="border-top: 1px dotted #5499c9;">
                        <div id="polya" style="overflow-x: auto;background-color: #fff;margin:auto;">
                            <table id="polyatable"  class="display dataTable" cellspacing="0" role="grid" aria-describedby="example_infox" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <!--<th>PAC</th>-->
                                        <th>Coordinate</th>
                                        <th width="15%">PA#</th>
                                        <th width="15%">PAT#</th>
                                        <td>PAC range</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                            $pac_res=mysql_query("select * from t_".$species."_pac where gene='$gene_name';");
                                            while($pac_r=  mysql_fetch_row($pac_res)){
//                                                $i=1;
                                                echo "<tr>";
//                                                        . "<td>PAC$i</td>";
                                                if($strand==1&&$pac_r[2]>$ext_end)
                                                    echo "<td>$pac_r[2](3UTR Extend)</td>";
                                                else if($strand==-1&&$pac_r[2]<$ext_start)
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
                            <hr width="98%" size="1" align="left" style="border-top: 1px dotted #5499c9;">
                        <div id="go" style="overflow-x: auto;background-color: #fff;margin:auto;">
                            <table id="gotable" class="display dataTable" cellspacing="0" role="grid" aria-describedby="example_infox" style="text-align: center;">
                                <thead>
                                    <tr>
                                        <th style="width:20%">Go id</th>
                                        <th style="width:45%">Go term</th>
                                        <th style="width:35%">Go function</th>
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
                                    "pagingType":"full_numbers"
                                });
                                $('#polyatable').dataTable({
                                    "lengthMenu":[[5,10,15,-1],[5,10,15,"all"]],
                                    "pagingType":"full_numbers"
                                });
                            });
                        </script>
                    </td>
                    <td width="70%" height="400px" valign="top">
                        <div class="wrap">
                            <div class="tabs">
                                <a href="#" hidefocus="true" class="active">Jbrowse</a>
                                <a href="#" hidefocus="true">Gene Pic</a>
                                <a href="#" hidefocus="true">Pac Pic</a>
                            </div><br>    
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="content-slide">
                                        <div style="height:99%">
                                            <iframe src="../jbrowse/?data=data/arabidopsis&loc=<?php echo $chr;?>:<?php echo $gene_start;?>..<?php echo $gene_end;?>&tracks=Arabidopsis,sys_polya" width=100% height=100%>
                                            </iframe>
                                        </div>
                                  </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="content-slide">
                                        <div style="height:99%">
                                            <?php
                                                if($_GET['flag']=='intergenic')
                                                    echo "<iframe src=\"./genepic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&analysis=1&intergenic=1&coord=$coord\" width=100%  height=100%></iframe>";
                                                else
                                                    echo "<iframe src=\"./genepic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&analysis=1\" width=100%  height=100%></iframe>";
                                            ?>
                                        </div>
                                    </div>
                                  </div>
                                    <div class="swiper-slide">
                                    <div class="content-slide">
                                        <div style="height:99%">
                                            <?php
                                                if($_GET['flag']=='intergenic')
                                                    echo "<iframe src=\"./pacpic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&intergenic=1&coord=$coord\" width=100%  height=100%></iframe>";
                                                else
                                                    echo "<iframe src=\"./pacpic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand\" width=100%  height=100%></iframe>";
                                            ?>
                                        </div>
                                  </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="content-slide">
                                        <script>
                                            var tabsSwiper = new Swiper('.swiper-container',{
                                              speed:500,
                                              onSlideChangeStart: function(){
                                                $(".tabs .active").removeClass('active');
                                                $(".tabs a").eq(tabsSwiper.activeIndex).addClass('active');
                                              }
                                            });
                                            $(".tabs a").on('touchstart mousedown',function(e){
                                              e.preventDefault()
                                              $(".tabs .active").removeClass('active');
                                              $(this).addClass('active');
                                              tabsSwiper.swipeTo($(this).index());
                                            })
                                            $(".tabs a").click(function(e){
                                              e.preventDefault()
                                            })
                                        </script>
                                    </div>
                                  </div>
                              </div>
                           </div>
                        </div>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
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
        	<table style="width:950px;margin-top:10px;margin-bottom:10px;font-family: Courier New;font-size: 16px;">
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
                                <legend><span class="h3_ltalic">others</span>&nbsp;&nbsp;
                                    (
                                    <input type="checkbox" name="cbox2" value="EXT"/>3'UTR Extend<span class='pt11' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
                                    <input type="checkbox" name="cbox2" value="3UTR"/>3'UTR <span class='pt5' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
                                    <input type="checkbox" name="cbox2" value="5UTR"/>5'UTR&nbsp;<span class='pt6' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;
                                    <input type="checkbox" name="cbox2" value="CDS"/>CDS&nbsp;<span class='pt7' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;
                                    <input type="checkbox" name="cbox2" value="INTRON"/>Intron&nbsp;<span class='pt8' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;
                                    <input type="checkbox" name="cbox2" value="EXON"/>exon&nbsp;<span class='pt9' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;
                                    <input type="checkbox" name="cbox2" value="AMB"/>amb&nbsp;<span class='pt10' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
                                    <input type="checkbox" name="cbox2" value="PA"/><span style="text-align:center;color:red;"><strong><u>Poly(A) site</u></strong></span>
                                    )
                                </legend>
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
        <?php
            include"footer.php";
            ?>
    </body>
</html>