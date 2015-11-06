<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<title>Jquery实例</title>  
</head>  
    <script language="javascript" src="./src/jquery-1.10.1.min.js"></script>  
<script language="javascript">  
    $(document).ready(function(){  
        $('#send_ajax').click(function (){  
            var username = $('#input1').val();  
            var age = $('#input2').val();  
            if (username == '') {  
                $('#result').html('<font color="red">帐号为空</font>');  
                $('#send_ajax').attr('disabled','');//不好用  
                return false;  
            }  
            if (age == '') {  
                $('#result').html('<font color="red">年龄为空</font>');  
                $('#send_ajax').attr('disabled','');  
                return false;  
            }  
            var params = $('input').serialize(); //序列化表单的值
            alert(params);
            $.ajax({  
                url:'test2.php', //后台处理程序  
                type:'post',       //数据传送方式  
                dataType:'json',   //接受数据格式  
                data:params,       //要传送的数据  
                success:update_page//回传函数(这里是函数名字)  
            });  
        });  
        //post()方式  
        $('#test_post').click(function (){   
            $.post(  
                'test2.php',  
                {  
                    username:$("#input1").val(),  
                    age:$("#input2").val(),  
                    sex:$("#input3").val(),  
                    job:$("#input4").val()  
                },  
                function (data) { //回调函数  
                    var myjson='';  
                    eval('myjson=' + data + ';');  
                    $('#result').html("姓名:" + myjson.username + "<br/>工作:" + myjson['job']);  
                }  
            );  
        });  
        //get()方式  
        $('#test_get').click(function (){   
            $.get(  
                'test2.php',  
                {   
                    username:$("#input1").val(),  
                    age:$("#input2").val(),  
                    sex:$("#input3").val(),  
                    job:$("#input4").val()  
                },  
                function (data) { //回调函数  
                    var myjson='';  
                    eval('myjson=' + data + ';');  
                    $('#result').html("姓名:" + myjson.username + "<br/>工作:" + myjson['job']);  
//                    alert(data);
                }  
            );  
        });  
    });  
function update_page(json) { //回传函数实体，参数为XMLhttpRequest.responseText  
    var str="姓名:"+json.username+"<br />";  
        str+="年龄:"+json.age+"<br />";  
        str+="性别:"+json.sex+"<br />";  
        str+="工作:"+json.job+"<br />";  
        str+="追加测试:"+json.append;  
    $("#result").html(str);  
}  
</script>  
<body>  
<div id="result" style="background:orange;border:1px solid red;width:300px;height:200px;"></div>  
<form id="formtest" action="" method="post">  
    <p><span>输入姓名: </span><input type="text" name="username" id="input1" /></p>  
    <p><span>输入年龄: </span><input type="text" name="age"      id="input2" /></p>  
    <p><span>输入性别: </span><input type="text" name="sex"      id="input3" /></p>  
    <p><span>输入工作: </span><input type="text" name="job"      id="input4" /></p>  
</form>  
<input type="submit" name="send_ajax" id="send_ajax" value="提交" />  
<button id="test_post">POST提交</button>  
<button id="test_get">GET提交</button>  
</body>  
</html>  