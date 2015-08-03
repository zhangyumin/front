<html>
    <head>
        <meta charset="UTF-8">
        <title>Contact Us</title>
        <script src='src/jquery-2.0.0.min.js'></script>
    </head>
    <body>
        <?php
            include 'navbar.php';
        ?>
        <?php
            $content="\n\r"."name:".$_POST['username']."\n\r"."Email:".$_POST['email']."\n\r"."Phone:".$_POST['phone']."\n\r"."comment:".$_POST['comment']."\n\r"."=================================================================================================================";
            file_put_contents("/var/www/html/front/log/haha.txt", $content,FILE_APPEND);
        ?>
        <div class="page" style="width:100%;float:left;background-color: #ddddff;">
            <div class="page_left" style="width:35%;float:left;padding-top: 100px;text-align: center;">
                Get in Touch<br>
                Address:hahaha<br>
                phone:13000000000<br>
                Email:###@##.com<br>
            </div>
            <div class="page_right" style="width:65%;float: left;padding-top: 100px;text-align: center;">
                <form action="#" method="post">
                    <input type="text" name="username" placeholder="Name:*" oninvalid="setCustomValidity('please input your Name');" oninput="setCustomValidity('');" required="required"><br><br>
                    <input type="text" name="email" placeholder="Email:*" oninvalid="setCustomValidity('please input your Email');" oninput="setCustomValidity('');" required="required"><br><br>
                    <input type="text" name="phone" placeholder="Phone:"><br><br>
                    <textarea name="comment" placeholder="Message:"></textarea><br>
                    <button type="submit" onclick="javascript:alert('successful');">submit</button>
                </form>
            </div>
        </div>
        <br style="clear:both;">
        <?php
            include 'footer.php';
        ?>
    </body>
</html>

