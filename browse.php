<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Search</title>
        <script src="./src/jquery-1.10.1.min.js"></script>

        <link href="./src/index.css" rel="stylesheet" type="text/css" />

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
        <?php
            include"navbar.php";
        ?>
        <script type="text/javascript">
            function change(){
                var value = document.getElementById("species").value;
                if(value=='arab'){
                    document.getElementById("jbrowse").src="../jbrowse/?data=data/arab";
                }
                else if(value=='rice'){
                    document.getElementById("jbrowse").src="../jbrowse/?data=data/japonica";
                }
                else if(value=='mtr'){
                    document.getElementById("jbrowse").src="../jbrowse/?data=data/mtr";
                }
                else if(value=='chlamy'){
                    document.getElementById("jbrowse").src="../jbrowse/?data=data/chlamy";
                }    
            }
        </script>
        <label for="species">Select species here:</label>
        <select id="species" name="species" onchange="change()">
            <option value="rice" selected="selected">Oryza sativa (Rice)</option>
            <option value="arab">Arabidopsis thaliana</option>
            <option value="mtr">Medicago truncatula</option>
            <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
        </select>
        <iframe id="jbrowse" style="border: 1px solid black" src="../jbrowse/?data=data/japonica" width=99% height="800px">
        </iframe>
       <div class="bottom">
        <?php
            include"footer.php";
            ?>
       </div>
    </body>
</html>