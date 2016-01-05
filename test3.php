<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <script src="./src/jquery-1.10.1.min.js"></script>
    <script src="./src/slider/js/jquery-plus-ui.min.js"></script>
    <script src="./src/slider/js/jquery-ui-slider-pips.js"></script>
    <link rel="stylesheet" href="./src/slider/css/jqueryui.min.css" />
    <link rel="stylesheet" href="./src/slider/css/jquery-ui-slider-pips.min.css" />
    <link rel="stylesheet" href="./src/slider/css/app.min.css" />
    <style>
        #steps-fivepercent-slider .ui-slider-tip {
            visibility: visible;
            opacity: 1;
            top: -30px;
        }
        .slider{
            margin: 40px;
        }
    </style>
</head>
<body>
    <div class="slider"></div>
     <script>
                
                             $(".slider")
                        
                                        .slider({ 
                                            min: 0, 
                                            max: 1000, 
                                            range: true, 
                                            values: [200, 800] 
                                        })
                        
                                        .slider("pips", {
                                            rest: "label"
                                        })
                        
                                        .slider("float");
            </script>
</body>
</html>