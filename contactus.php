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
            if($_POST['username']!=NULL&&$_POST['email']!=NULL){
                $content="\n\r"."name:".$_POST['username']."\n\r"."Email:".$_POST['email']."\n\r"."Phone:".$_POST['phone']."\n\r"."comment:".$_POST['comment']."\n\r"."=================================================================================================================";
                file_put_contents("/var/www/html/front/message.txt", $content,FILE_APPEND);
                echo "<script>alert('successful');</script>";
            }
        ?>
        <div class="page" style="width:100%;float:left;background-color: #ddddff;">
            <div class="page_left" style="width:35%;float:left;padding-top: 100px;text-align: center;font-weight: 300;font-family: 'Lato',sans-serif;color:#4a89c4;font-size: 14px;">
                <div style="font-weight: 400;font-family: 'Lato',sans-serif;color: #366fa5;font-size: 55px;">Get in Touch</div><br>
                Address:Xiamen University, Xiamen 361005 China<br>
                Email:xhuister@xmu.edu.cn<br>
            </div>
            <div class="page_right" style="width:65%;float: left;padding-top: 100px;text-align: center;">
                <form action="#" method="post">
                    <input type="text" name="username" placeholder="Name:*" oninvalid="setCustomValidity('please input your Name');" oninput="setCustomValidity('');" required="required" style="border:1px solid #5499c9;background: transparent none repeat scroll 0% 0%;padding: 20px 10px 20px 30px;width:60%"><br><br>
                    <input type="text" name="email" placeholder="Email:*" oninvalid="setCustomValidity('please input your Email');" oninput="setCustomValidity('');" required="required"style="border:1px solid #5499c9;background: transparent none repeat scroll 0% 0%;padding: 20px 10px 20px 30px;width:60%"><br><br>
                    <input type="text" name="phone" placeholder="Phone:"style="border:1px solid #5499c9;background: transparent none repeat scroll 0% 0%;padding: 20px 10px 20px 30px;width:60%"><br><br>
                    <textarea name="comment" placeholder="Message:"style="border:1px solid #5499c9;background: transparent none repeat scroll 0% 0%;padding: 20px 10px 20px 30px;width:60%"></textarea><br><br>
                    <button type="submit" style="color:#fff;background-color: #5499c9;box-sizing: border-box;line-height: 2;font-size: 23px;margin: 0;padding: auto;border: none;">submit</button>
                </form>
            </div>
        </div>
        <br style="clear:both;">
        <?php
            include 'footer.php';
        ?>
    </body>
</html>

