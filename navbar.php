<link rel='stylesheet' href='./src/bootstrap.min.css' type='text/css' media='all' />
<!--<script type='text/javascript' src='./src/jquery-2.0.0.min.js'></script>-->
<script type='text/javascript' src='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
<style>
            .container {height:150px;padding-left: 0px;padding-right: 0px;}
            nav.navbar.navbar-default.equinav{height: 150px;background-color: #5499C9;}
            div.collapse.nabar-collapse{float:left;font-family: 'Open Sans', 'sans-serif';}
            .navbar-nav li {display: inline-block;line-height: 110px;width:auto;font-family: 'Open Sans','sans-serif';text-align: center;}
            .navbar-nav li a{line-height: 110px;font-size: 25px;padding: 0 21px 0 21px !important;font-family: 'Lato', sans-serif;}
            h1 {font-size:40px;line-height:1.5;padding:0;margin:30px 0;}
            .navbar {width:100%;margin-left: auto;margin-right: auto;font-weight: 400;}
            .navbar-nav > li > a:hover {background:#366fa5 !important;color:#fff !important;}
            .navbar-nav > li:last-of-type > a {border:0;}
            @media ( min-width: 768px) {
                    .navbar-nav > li > a {text-align:center;border-right:1px solid rgba(0,0,0,0.1)}
            }
            @media (max-width:1270px){
                    .navbar-logo{display: none}
                    .navbar-theme{display: none}
                    ul.nav.navbar-nav li a{padding: 0px 1px !important}
                    .iconbar{display: none;}
                    .navbar.navbar-default.equinav{height:auto !important;}
                    .container{width:auto !important;}
            }
            .icon img{opacity:0.4;padding-top: 3px;cursor:pointer;width: 32px;height: 32px;}
            .icon img:hover{opacity: 1;padding-top: 3px;cursor:pointer;width: 32px;height: 32px;}
    </style>
<div class="container">
    <nav class="navbar navbar-default equinav" role="navigation">
      <div class="iconbar" style="height: 40px;padding: 0;background-color:#ddddff;">
          <div class="slogan" style="float:left;font-size: 18px;color:#366fa5;padding: 6px 8px 8px 8px;">Browser for visualization and analysis of alternative polyadenylation</div>
          <div class="lab" style="float:left;font-size: 24px;color:#366fa5;padding-left: 20%;">BMI Lab</div>
          <div class="icon" style="float:left;padding-left: 20%;">
              <img src="./pic/icon/mail.png" onclick="parent.location='mailto:xhuister@xmu.edu.cn'" />
              <img src="./pic/icon/github.png" onclick="location.href='http://github.com/zhangyumin/'"/>
              <img src="./pic/icon/home.png" onclick="location.href='http://bmi.xmu.edu.cn:8080/drupal7'" style="height:36px;width:36px;"/>
          </div>
      </div>  
      <div class='navbar-logo'>
          <img style="float: left;width:15%;height: 110px;padding-left: 15px;" src='./pic/logo.jpg'>
          <div class="navbar-theme" style="float:left;font-size: 45px;font-style: normal;color:#fff;height:110px;text-align: center;padding: 21px 61px 21px 61px;">
              Browser
          </div>
      </div>
      <div class="collapse navbar-collapse">    
        <ul class="nav navbar-nav">
            <li><a href="./index.php">Home</a></li>
            <li><a href="./upload_option.php">Run</a></li>
            <li><a href="../jbrowse/index.html">Jbrowse</a></li>
            <li><a href="./download.php">Download</a></li>
            <li><a href="./help.php">Help</a></li>
            <li><a href="./contactus.php">Contact us</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </nav>

  </div>
