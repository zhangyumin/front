<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>JS+CSS实现带关闭按钮的DIV弹出窗口</title>
<script>   
  function    locking(){   
//   document.all.ly.style.display="block";   
   document.all.ly.style.width=document.body.clientWidth;   
   document.all.ly.style.height=document.body.clientHeight;   
   document.all.Layer2.style.display='block';  
    
   }   
  function    Lock_CheckForm(theForm){   
   document.all.ly.style.display='none';document.all.Layer2.style.display='none';
  return   false;   
   }   
    </script>
    <style type="text/css">
        .STYLE1 {font-size: 12px}
        a:link {
        color: #FFFFFF;
        text-decoration: none;
        }
        a:visited {
        text-decoration: none;
        }
        a:hover {
        text-decoration: none;
        }
        a:active {
        text-decoration: none;
        }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
    <p align="center">
        <input type="button" value="弹出DIV" onClick="locking()" />
    </p>
    <div id="ly" style="position: absolute; top: 0px; filter: alpha(opacity=60); background-color: #777;z-index: 2; left: 0px; display: none;">
    </div>
    <!--          浮层框架开始         -->
    <div id="Layer2" align="center" style="border: 1px solid;position: absolute; z-index: 3; left: expression((document.body.offsetWidth-540)/2); top: expression((document.body.offsetHeight-170)/10);background-color: #fff; display: none;" >
        <table width="540" height="300" border="0" cellpadding="0" cellspacing="0" style="border: 0    solid    #e7e3e7;border-collapse: collapse ;" >
            <tr>
                <td style="background-color: #73A2d6; color: #fff; padding-left: 4px; padding-top: 2px;font-weight: bold; font-size: 12px;" height="10" valign="middle">
                     <div align="right">
                         <a href=JavaScript:; class="STYLE1" onclick="Lock_CheckForm(this);">[关闭]
                         </a> &nbsp;&nbsp;&nbsp;&nbsp;
                     </div>
                </td>
            </tr>
            <tr>
                <td height="130" align="center">
                    <form name="pac_export" method="post" action="test1.php" target="_blank">
                        upstream (nt) <input type="text" value="200" name='upstream'></input><br>
                        downstream (nt) <input type="text" value="200" name='downstream'></input><br>
                        PAC in region <select name='pac_region'>
                            <option>all</option>
                            <option>genomic region</option>
                            <option>3'UTR</option>
                            <option>5‘UTR</option>
                            <option>CDS</option>
                            <option>intron</option>
                            <option>intergenic</option>
                            <option>promoter</option>
                        </select><br>
                        <button type="submit">Submit</button>
                        <button type="reset">Reset</button>
                    </form>
                </td>
            </tr>
        </table>
    </div>
    <!--  浮层框架结束-->
</body>
</html>
