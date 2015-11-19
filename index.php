<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>polyA browser</title>
        <link href="./src/index.css" rel="stylesheet" type="text/css" />
        <script src="./src/jquery-2.0.0.min.js"></script>
        <script src="./src/jquery.slides.min.js"></script>
        <!-- Mobile viewport optimisation -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" type="text/css" href="js/wowslider/style.css" />
        <script type="text/javascript" src="js/wowslider/jquery.js"></script>

        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <!--[if lte IE 7]>
        <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <!--[if lt IE 9]>
        <script src="./js/html5shiv/html5shiv.js"></script>
        <![endif]-->

        <!--<link rel="stylesheet" href="./src/font-awesome.min.css">-->
        <!--<link rel="stylesheet" href="./src/example.css">-->
       
    </head>
    <body>

        111111111111111111111111111
        <?php include './navbar.php'; ?>
        <div class="ym-wrapper">
          <div class="ym-wbox">
            <div class="box info">
              <div class="ym-grid linearize-level-1">
                <div class="ym-g50 ym-gl">
                  <h1>PolyA Browser</h1>
                </div>
                <div class="ym-g50 ym-gr">
                  <div id="wowslider-container1">
                    <div class="ws_images"><ul>
                      <li><img src="pic/sliderimg/images/slide1.jpg" alt="slide1" title="slide1" id="wows1_0"/></li>
                      <li><img src="pic/sliderimg/images/slide2.jpg" alt="Desert" title="slide2" id="wows1_1"/></li>
                    </ul></div>
                    <div class="ws_bullets">
                      <div>
                      <a href="#" title="326911"><span><img src="data1/tooltips/326911.jpg" alt="326911"/>1</span></a>
                      <a href="#" title="Desert"><span><img src="data1/tooltips/desert.jpg" alt="Desert"/>2</span></a>
                      </div>
                    </div>
                    <div class="ws_shadow"></div>
                  </div>
                </div>
              </div>
            </div>

            <div class="box info" id="introduction">
              <h2>Introduction</h2>
            </div>
          </div>
        </div>
        <?php
            include"./footer.php";
        ?>
        <script type="text/javascript" src="js/wowslider/wowslider.js"></script>
        <script type="text/javascript" src="js/wowslider/script.js"></script>
    </body>
</html>
