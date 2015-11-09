<header>
  <div class="ym-wrapper">
    <div class="ym-wbox">
      <h1>PolyA Browser</h1>
    </div>
  </div>
</header>
<nav id="nav" class="fix" role="navigation">
  <div class="ym-wrapper">
    <div class="ym-hlist">
      <ul>
        <li class="active"><a href="./index.php">Home</a></li>
              <li><a href="./search.php">PAC search</a></li>
              <!--<li><a href="../jbrowse/?data=data/arabidopsis">PAC browse</a></li>-->
              <li><a href="./browse.php">PAC browse</a></li>
              <li><a href="./analysis.php">PAC analysis</a></li>
              <li><a href="./upload_option.php">PAC trap</a></li>
              <li><a href="./download.php">Download</a></li>
              <li><a href="./task.php">My task</a></li>
              <li><a href="./help.php">Help</a></li>
      </ul>
      <form class="ym-searchform" method="post" action="search_result.php?method=fuzzy">
        <input class="ym-searchfield" name="key" type="search" placeholder="Input your keyword here..." />
        <button class="ym-searchbutton" type="submit" >submit</button>
      </form>
    </div>
  </div>
</nav>