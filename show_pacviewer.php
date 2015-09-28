<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php
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
       
        //echo $gene;
        //echo $strand;
        ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <style type="text/css">
            body{
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
            fieldset{
                border-color: #5499c9 !important;
                border-style: solid !important;
                border-width: 2px !important;
                padding: 5px 10px !important;
            }
            div.filter_class{
                //float: left;
                //button:5;
                //display: inline;
                text-align: right;
                //margin-left: 150px;
            }
            .jtable-title-text{
                font-size:20px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <?php
            include'./navbar.php';
            ?>
        <?php
        $con=  mysql_connect("localhost","root","root");
        mysql_select_db("db_bio",$con);
        ?>
        <?php
         $singnals = array("AATAAA","TATAAA","CATAAA","GATAAA","ATTAAA","ACTAAA","AGTAAA","AAAAAA","AACAAA","AAGAAA","AATTAA","AATCAA","AATGAA","AATATA","AATACA","AATAGA","AATAAT","AATAAC","AATAAG");        
         if(strcmp($strand,1)==0){
             $a="SELECT substring(seq,$gene-200,300) from fa_arab10 WHERE title='$chr';";
             //echo $a;
             //echo "in 1";
         }
         if(strcmp($strand,-1)==0){
             $a="SELECT substring(seq,$gene-100,300) from fa_arab10 WHERE title='$chr';";
             //echo $a;
             //echo "in 2";
         }
         
         $result=mysql_query($a);
         while($row=mysql_fetch_row($result))
         {
             //echo "in it";
             $seq=$row[0];
         }
         if(strcmp($strand,-1)==0)
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
         echo "<script type=\"text/javascript\">";
         //echo "var sequences = ['AAAATAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA','AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA','AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'];"; 
         //echo "var current_seq='AAAATAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';";
         echo "var sequences=[];";
         //echo "sequences.push('$row[0]');";
         echo "var current_seq = '$seq';";
         echo "sequences.push('$seq');";
         echo "</script>";

         ?>
        <div class="filter" id="filter">
            <form>
                <input type="text" name="search" id="search" />
                <button type="submit" id="search_button">search</button>
                <button type="reset" id="reset_button">reset</button>
            </form>
        </div>
         <div id="jtable" style="width: 100%;"></div>
    
        <link href="src/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>
        <link href="src/jtable.css" rel="stylesheet" type="text/css" />

        <script src="src/jquery-1.6.4.min.js" type="text/javascript" ></script>
        <script src="src/jquery-ui-1.8.16.custom.min.js" type="text/javascript" ></script>
        <script src="src/jquery.jtable.js" type="text/javascript" ></script>
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
                                    else if(patt.toUpperCase() == "TGTAA")
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
                                                    else if(patt.toUpperCase() == "TGTAA")
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
                                                    else if(patt.toUpperCase() == "TGTAA")
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
                                            else if(patt.toUpperCase() == "TGTAA")
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
            //var tmp_str="AATAAAAAAA,TTTTTTTTTT,TAAAAAAAAA,AAAAAAAAAA,AAAAAAAAAA";
            for(var i=0;i<rows;i++)
            {
                    arr[i] = seq.substr(0+100*i,100);
            }

            newSeq += "<font color='#324A17'><strong>" + rulerLeadingSpaces + "&nbsp;" + spaces_9 + "10" + spaces_9 + "20" + spaces_9 + "30" + spaces_9 + "40" + spaces_9 + "50" + spaces_9 + "60" + spaces_9 + "70" + spaces_9 + "80" + spaces_9 + "90" + spaces_9 + "100" + "</strong></font><br>";
            for(var key3 in arr)
            {
                    var row = parseInt(key3)+1;
                    newSeq += "<font color='#324A17'><strong>" + row + "</strong></font>";
                    var tmp_arr = [];
                    for(var n = 0;n<10;n++)
                    {
                            tmp_arr[n] = arr[key3].substr(0+10*n,10);
                    }

                    var tmp = tmp_arr.join(",");
                    var range_min = key3*100;
                    var range_max = row*100-1 ;
                    var tmp_str = "";

                    var insert = [];
                    var slices = [];

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
                                    insert[parseInt(pos2)] = "</span>";
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
                                    insert[parseInt(pos2)] = "</span>";
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
                                    insert[parseInt(pos2)] = "</span>";
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
                                    insert[parseInt(pos2)] = "</span>";
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
                                    newSeq +=  "&nbsp;";
                            };
                    }
                    newSeq += "&nbsp;" + tmp_str + "<br>";
            }
            newSeq = newSeq.replace(/,/g,"&nbsp;");

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

                newSeq += "<font color='#324F17'><strong>" + rulerLeadingSpaces + "&nbsp;" + spaces_9 + "10" + spaces_9 + "20" + spaces_9 + "30" + spaces_9 + "40" + spaces_9 + "50" + spaces_9 + "60" + spaces_9 + "70" + spaces_9 + "80" + spaces_9 + "90" + spaces_9 + "100" + "</strong></font><br>";
                for(var key in arr)
                {
                        var row = parseInt(key)+1;
                        newSeq += "<font color='#324F17'><strong>" + row + "</strong></font>";
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
                                        newSeq +=  "&nbsp;";
                                };
                        }
                        newSeq += "&nbsp;" + tmp + "<br>";
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
                $('#jtable').jtable({
                    title:'System PAC list',
                    paging:true,
                    pageSize:5,
                    sorting:true,
                    defaultSorting:'gene ASC',
                    actions:{
                        listAction:'system_PAClist.php'
                    },
                    fields:{
                        gene:{
                            key:true,
                            edit:false,
                            width:'30%',
                            create:false,
                            columnResizable:false,
                            title:'Gene ID',
                            display: function (data) {
                                var short_name = data.record.gene;
                                if(data.record.gene.length > 30)
                                {
                                        short_name = data.record.gene.substr(0,30) + "...";
                                }
                                return short_name + "<a style='display:inline;' href='http://127.0.0.1/jbrowse/?loc="+data.record.chr+":"+data.record.coord+"' target='_blank'><img src = './pic/gmap.png' hight='10' width='100' title='go to PolyA browser' align='right'/></a>";
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
                            width:'10%'
                        },
                        coord:{
                            title:'coordinate',
                            edit:false,
                            width:'20%'
                        },
                        ftr:{
                            title:'ftr',
                            edit:false,
                            width:'20%'
                        },
                        tot_tagnum:{
                            title:'tot_tagnum',
                            edit:false,
                            width:'10%'
                        }
                    }
                });
                $('#filter').appendTo(".jtable-title").addClass('filter_class');
                $('#jtable').jtable('load');
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
                
                $('#find_patt').click(function(){
  	find_pattern_onload();
                });

                $('#reset').click(function(){
                  //reset($("#seq_switch").val());
                  reset(0);
                });

                $('#checkall').click(function(){
  	//reset($("#seq_switch").val());
  	var value = $('#checkall').val();
  	if(value == "checkall")
  	{
  		$('#checkall').val('uncheck');
  		$("input[type=checkbox]:checked").each(function(){ 
	  		this.checked = false;
		}); 
  	}
  	else if(value == "uncheck")
  	{
  		$('#checkall').val('checkall');
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
                            <font color="#224055" size="18px;"><b>Pattern Viewer</b></font>
                        </span>
                    </legend>
	<div class = "seq_viewer" id="seq_viewer">
                    <div id = "pattern">	
        	<legend><span class="h3_italic">Typical Pattern</span>&nbsp;<span class='pt2' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;(AATAAA&nbsp;<span class='pt3' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;TGTAA&nbsp;<span class='pt4' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;)</legend>
        	<table style="width:900px;margin-top:10px;margin-bottom:10px;font-family:Courier New;font-size:16px;">
                    <?php
                    echo "<tr>";
                    echo '<td><input type="checkbox" checked = "true" name = "cbox" id = "checkall2" value = "checkall" /><em>&nbsp;Change All</em></td>';
                    $i = 0;
                    foreach ($singnals as $key => $value) {
                            if($i == 6||$i == 13)
                            {
                                    echo "</tr><tr>";
                            }
                            echo '<td><input type="checkbox" name = "cbox2" checked = "true" value = "'.$value.'"/>&nbsp;'.$value.'</td>';
                            $i++;
                    }
                    echo "</tr>";
                    ?>
           	</table>

                    <legend><span class="h3_italic">User’s Pattern </span>&nbsp;&nbsp;<span class='pt1' style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;&nbsp;(Ex. AATAAA, TGTAAA)</legend>
                    <input type = "text" id = "user_pattern" style="margin-top:10px;margin-bottom:10px;"></input>
                        <legend>
                                    <span class="h3_italic">poly(A) site</span>
                                    &nbsp;&nbsp;
                                    <span style="text-align:center;color:red;"><strong><u>N</u></strong></span>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                        </legend>
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
                            </br>
                            <button id = "find_patt" style="width:100px;"  class = "button blue medium">Show</button>
                            <button id = "reset" style = "width:100px;"  class = "button blue medium">Clear</button>
                    </div>
                    <div id = "seq_content" style="max-height:400px;overflow:auto;margin-top:20px;font-family: Courier New;font-size:15px;">
                            <p class = "sequence" id = "sequence"style="word-break:break-all;"><?php echo "AATAAAAAA";  ?>
                            </p>	            	
                    </div>
	</div>
	</fieldset>
            </div>
            

            <?php
                                include 'footer.php';
            ?>
    </body>
</html>
