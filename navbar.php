<meta name="renderer" content="webkit">
<header style="background-image:url(./pic/navbgimg.jpg) ;background-position: 0px -200px;height: 100px;background-size: cover;background-repeat: no-repeat;min-width: 1080px">
    <div style="width: 400px;height: 150px;">
<!--    <div class="ym-wbox">
        <img src="./pic/biglogoshadow.jpg"/>
        <span style="float:right;font-size: 15px">a portal for visualization and analysis of alternative polyadenylation in plants</span>
    </div>-->
        <!--<img style="float:left;" src="./pic/logo.jpg"/>-->
        <img style="float:left;height: 80px;padding-left: 80px" src="./pic/biglogo.png"/>
        <div style="float:right;padding-right: 8px;font-weight: bold"><span style="color:#0168b8">A</span>lternative <span style="color:#5db95b">P</span>oly<span style="color:#d4245d">a</span>denylation</div>
<!--        <span style="float:left;font-size: 18px;padding: 40px 0px 0px 3%">A portal for visualization and analysis of alternative polyadenylation in plants</span>-->
        <div style="clear:both"></div>
  </div>
</header>
<nav id="nav" class="fix" role="navigation">
  <div class="ym-wrapper">
    <div class="ym-hlist">
      <ul>
              <li ><a href="./index.php">Home</a></li>
              <li><a href="./search.php">PAC search</a></li>
              <!--<li><a href="../jbrowse/?data=data/arabidopsis">PAC browse</a></li>-->
              <li><a href="./browse.php">PAC browse</a></li>
              <li><a href="./analysis.php">PAC analysis</a></li>
              <li><a href="./upload_option.php">PAC trap</a></li>
              <li><a href="./download.php">Download</a></li>
              <li><a href="./task.php">My task</a></li>
              <li><a href="./help.php">Help</a></li>
      </ul>
        <form class="ym-searchform" method="post" action="search_result.php?method=fuzzy" onsubmit="return check();" style="height: 30px;padding: 11px 7.5px">
          <select name="species" id="navspecies" style="height: 30px;border-radius: 0.2em" onchange="chgtitle()">
            <option value="japonica">Rice</option>
            <option value="arab" selected="true">Arabidopsis</option>
            <option value="mtr">Medicago</option>
            <option value="chlamy">Chlamy</option>
        </select>
          <input title="Example: AT1G01020" class="ym-searchfield" id='inputkey' name="key" type="search" placeholder="input a keyword" style="border-radius: 0.2em;padding: 4px;height: 22px"/>
          <button class="ym-searchbutton" type="submit" style="height: 30px">Search</button>
      </form>
    </div>
  </div>
</nav>
<script type="text/javascript">
    function check(){
        if(document.getElementById("inputkey").value == ""){
            return false;
        }
    }
    function chgtitle(){
        if(document.getElementById("navspecies").value == 'arab')
            document.getElementById("inputkey").setAttribute("title","Example: AT1G01020");
        else if(document.getElementById("navspecies").value == 'mtr')
            document.getElementById("inputkey").setAttribute("title","Example: Medtr0236s0040");
        else if(document.getElementById("navspecies").value == 'chlamy')
            document.getElementById("inputkey").setAttribute("title","Example: Cre04.g225950");
        else if(document.getElementById("navspecies").value == 'japonica')
            document.getElementById("inputkey").setAttribute("title","Example: LOC_Os01g01070");
    }
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?269157b058029d76de196bc8844d0ee9";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
function GetUrlRelativePath()
　{
　　　　var url = document.location.toString();
　　　　var arrUrl = url.split("/");

　　　　
　　　　var relUrl = arrUrl[4];

　　　　if(relUrl.indexOf("?") != -1){
　　　　　　relUrl = relUrl.split("?")[0];
　　　　}
　　　　return relUrl;
}

$(document).ready(function(){
Url = GetUrlRelativePath();

var obj=$("[href$="+"'"+Url+"'"+"]");
obj.unwrap();
obj.wrap("<li class='active'></li>");
});
</script>
