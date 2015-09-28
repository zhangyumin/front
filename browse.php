<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Search</title>
        <script src="./src/jquery-2.0.0.min.js"></script>
    </head>
    <body>
        <?php
            include"navbar.php";
        ?>
        <script type="text/javascript">
            function change(){
                var value = document.getElementById("species").value;
                if(value=='arab'){
                    document.getElementById("jbrowse").src="../jbrowse/?data=data/arabidopsis";
                }
                else if(value=='rice'){
                    document.getElementById("jbrowse").src="../jbrowse/?data=data/arabidopsis1";
                }
                else if(value=='mtr'){
                    document.getElementById("jbrowse").src="../jbrowse/?data=data/example";
                }
                else if(value=='chlamy'){
                    document.getElementById("jbrowse").src="../jbrowse/?data=data/arabidopsis";
                }    
            }
        </script>
        <label for="species">Select species here:</label>
        <select id="species" name="species" onchange="change()">
            <option value="arab" selected="selected">Arabidopsis thaliana</option>
            <option value="rice">Oryza sativa (Rice)</option>
            <option value="mtr">Medicago truncatula</option>
            <option value="chlamy">Chlamydomonas reinhardtii (Green alga)</option>
        </select>
        <iframe id="jbrowse" style="border: 1px solid black" src="../jbrowse/?data=data/arabidopsis" width=100% height=100%>
        </iframe>
       <div class="bottom">
        <?php
            include"footer.php";
            ?>
       </div>
    </body>
</html>