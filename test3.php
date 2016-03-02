<html>
    <head>
        <title>抓AD专用页面</title>
    </head>
    <body onload="GetAd()">
        <div id="a"></div>
        <script>
            function GetAd(){
                var omain = document.getElementById("oMain");
                var a = document.getElementById("a");
                if(omain){
                    alert('find advertisement')
                }
                else{
                    for(i = 0 ;i < 10000000000 ; i++){
                        
                    }
                    location.reload();
                }
            }
        </script>
    </body>
</html>