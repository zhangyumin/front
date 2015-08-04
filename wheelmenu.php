<style>
    .main{position: fixed;z-index: 1000;bottom: 30px;right:0;}
</style>
<link rel="stylesheet" type="text/css" href="./src/jquery-wheelmenu/css/default.css" />
<script type="text/javascript" src="./src/jquery-2.0.0.min.js"></script>
<script type='text/javascript' src='./src/jquery-wheelmenu/js/jquery.wheelmenu.js'></script>
<link rel='stylesheet' type='text/css' href='./src/jquery-wheelmenu/css/style.css'/>
<link href='./src/jquery-wheelmenu/css/elusive-webfont.css' rel='stylesheet' type='text/css'/>

<div class="main">
    <a href="#wheel" class="wheel-button">
        <i class="customicon-plus"></i>
    </a>
    <div class="pointer">Click me</div>
        <ul id="wheel">
            <li class="item"><a href="#home"><i class="customicon-home"></i></a></li>
            <li class="item"><a href="#home"><i class="customicon-music"></i></a></li>
            <li class="item"><a href="#home"><i class="customicon-video"></i></a></li>
            <li class="item"><a href="#home"><i class="customicon-heart"></i></a></li>
            <li class="item"><a href="#home"><i class="customicon-user"></i></a></li>
            <li class="item"><a href="#home"><i class="customicon-trash"></i></a></li>
            <li class="item"><a href="#home"><i class="customicon-envelope"></i></a></li>
            <li class="item"><a href="#home"><i class="customicon-camera"></i></a></li>
            <li class="item"><a href="#home"><i class="customicon-picture"></i></a></li>
            <li class="item"><a href="#home"><i class="customicon-eye-open"></i></a></li>
        </ul>
</div>
<script type="text/javascript">
    $(".wheel-button").wheelmenu();
</script>
