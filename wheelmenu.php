<style>
    .main{position: fixed;z-index: 1000;bottom: 20px;right:20px;}
</style>
<link rel="stylesheet" type="text/css" href="./src/jquery-wheelmenu/css/default.css" />
<!--<script type="text/javascript" src="./src/jquery-2.0.0.min.js"></script>-->
<script type='text/javascript' src='./src/jquery-wheelmenu/js/jquery.wheelmenu.js'></script>
<link rel='stylesheet' type='text/css' href='./src/jquery-wheelmenu/css/style.css'/>
<link href='./src/jquery-wheelmenu/css/elusive-webfont.css' rel='stylesheet' type='text/css'/>

<div class="main">
    <a href="#wheel" class="wheel-button nw">
        <i class="customicon-plus"></i>
    </a>
        <ul id="wheel" data-angle="NW" class="wheel">
            <li class="item"><a href="./task_summary.php"><i class="customicon-file-edit"></i></a></li>
            <li class="item"><a href="./show_result.php"><i class="customicon-picture"></i></a></li>
            <li class="item"><a href="./show_sequence_searched.php?chr=1&gene=31185&strand=-1"><i class="customicon-search"></i></a></li>
        </ul>
</div>
<script type="text/javascript">
    $(".wheel-button").wheelmenu({
//        trigger: "hover",
        animation: "fly",
        angle: "NW"
    });
</script>
