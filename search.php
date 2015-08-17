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
        <div class="search">
            <div class="search_block">
                
            </div>
            <div class="jump_block">
                
            </div>
        </div>
        <br>
        <div class="getback">
            <form method="post" id="getback" action="#">
                <input name="getback"/>
                <button type="submit">submit</button>
            </form>
        </div>
        <?php
            session_start();
            if($_POST['getback']!=NULL){
                $_SESSION['file']=$_POST['getback'];
                 echo '<script>window.location.href="show_result.php";</script>';
            }
            ?>
        <?php
            include"wheelmenu.php";
            ?>
        <?php
            include"footer.php";
            ?>
    </body>
</html>