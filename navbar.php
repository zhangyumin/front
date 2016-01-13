<meta name="renderer" content="webkit">
<header>
  <div class="ym-wrapper">
    <div class="ym-wbox">
      <h1>PlantAPA</h1>
    </div>
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
        <form class="ym-searchform" method="post" action="search_result.php?method=fuzzy" style="height: 30px;padding: 6px 7.5px">
          <select name="species" style="height: 29px;border-radius: 0.2em">
            <option value="arab">Arabidopsis</option>
            <option value="mtr">M.trunca</option>
            <option value="chlamy">Chlamy</option>
            <option value="japonica">Japonica</option>
        </select>
          <input class="ym-searchfield" name="key" type="search" placeholder="keyword" style="border-radius: 0.2em"/>
          <button class="ym-searchbutton" type="submit" style="height: 30px">Search</button>
      </form>
    </div>
  </div>
</nav>
<script type="text/javascript">
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
