<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Search</title>
        <script src="./src/jquery-2.0.0.min.js"></script>
        <style>
            fieldset{
                border-color: #5499c9 !important;
                border-style: solid !important;
                border-width: 2px !important;
                padding: 5px 10px !important;
            }   
        </style>
    </head>
    <body>
        <?php
            include"navbar.php";
        ?>
       <br>
        <fieldset style="margin: 0 auto;width: 70%;text-align: center;">
                    <legend style="text-align:left;">
                        <span class="h3_italic">
                            <font color="#224055"><b>Search task</b>:Get your results back</font>
                        </span>
                    </legend>
                    <form method="post" id="getback" action="#">
                        Task ID:&nbsp;&nbsp;
                        <input name="getback" style="width: 40%;"/>
                        <button type="submit">submit</button>
                    </form>
        </fieldset>
       <br>
       <fieldset style="margin: 0 auto;width: 70%;">
                    <legend>
                        <span class="h3_italic" >
                            <font color="#224055" ><b>Search</b>:Search and view the system samples</font>
                        </span>
                    </legend>
                    <form method="post" id="getback" action="#">
                        Task ID:&nbsp;&nbsp;
                        <input name="getback" style="width: 40%;"/>
                        <button type="submit">submit</button>
                    </form>
        </fieldset>
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