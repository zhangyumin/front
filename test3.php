<html>
    <head>
        <script src="./src/jquery-1.10.1.min.js"></script>
        <script src="./src/jquery.webui-popover.js"></script>
        <link href="./src/jquery.webui-popover.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <a>hahaha</a>
        <script>
            $('a').webuiPopover({
                placement:'right',//值: auto,top,right,bottom,left,top-right,top-left,bottom-right,bottom-left
                title:'Title',
                content:'Content<a href="www.baidu.com">click</a>',
                trigger:'hover',
                type:'html'
            });
        </script>
    </body>
</html>