<script src="./src/jquery-1.6.4.min.js" type="text/javascript"></script>
<script src="./src/jquery.nicenav.js" type="text/javascript"></script>
<style type="text/css">
    #bg{background-color: rgb(102,132,228);padding:30px 30px 30px 30px;width:828px;}
    .miniLogo,.navbar{
        float: left;
    }
</style>
<div class="miniLogo" style="width: 100px; height: 92px;border:1px solid #0066B3;text-align: center;">Mini Logo</div>
<div id="bg" class="navbar">
    <div id="container">
        <ul id="nav">
            <li><a href="./index.php">首页<span>Home</span></a></li>
            <li><a href="./upload_option.php">运行<span>Run</span></a></li>
            <!--<li><a href="./task_summery.php">简略<span>Task summary</span></a></li>-->
            <li><a href="../jbrowse/index.html">浏览<span>Jbrowse</span></a></li>
            <li><a href="./download.php">下载<span>Download</span></a></li>
            <li><a href="./help.php">帮助<span>Help</span></a></li>
            <li><a href="./contractus">联系<span>Contact us</span></a></li>
        </ul>
        <div id="buoy"></div>
    </div>
    <script type="text/javascript">
        $.nicenav(300);
    </script>
</div>            
