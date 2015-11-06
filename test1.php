<script type='text/javascript' src='./src/jquery-1.10.1.min.js'></script>
<script>  
$(document).ready(function(){  
        $('#test1-submit').click(function (){
            $.post(
                'test2.php',
                {
                    'name[]':[$('#one').val(),$('#two').val()],//数组
                    length:$('#three').val(),
                    'select':$('#four').val()
                },
                function(data){
//                    location.href="test2.php";
                    alert(data);
                });
         });
         $('#test2-submit').click(function (){
                var params = $('input,select,textarea').serialize(); //序列化表单的值
                console.log(params);
//            alert(params);
            $.ajax({  
                url:'test2.php', //后台处理程序  
                type:'post',       //数据传送方式  
                dataType:'json',   //接受数据格式  
                data:params,       //要传送的数据  
                success:update_page//回传函数(这里是函数名字)  
            });  
         });
});
function update_page(json) { //回传函数实体，参数为XMLhttpRequest.responseText  
//    alert(json.one+json.two);
} 
</script>
<div id='all'>
    <input type='text' id='one' name="one" value="2"><br>
    <input type='text' id='two' name='two' value='4'><br>
    <input type='text' id='three' name="three" value="6"><br>
    <select id='four' name='four'><br>
        <option value='1'>1</option>
        <option value='2'>2</option>
        <option value='3'>3</option>
    </select>
    <textarea id='five' name='five'></textarea>
    <button id='test1-submit'>submit1</button>
    <button id='test2-submit'>submit2</button>
</div>

