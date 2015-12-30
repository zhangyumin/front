<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="./src/optselect/normalize.css" />
    <link rel="stylesheet" type="text/css" href="./src/optselect/default.css">
    <script src="./src/jquery-1.10.1.min.js"></script>
    <script src="./src/optselect/jquery.sumoselect.js"></script>
    <link href="./src/optselect/sumoselect.css" rel="stylesheet" />

    <script type="text/javascript">
        $(document).ready(function () {
            window.asd = $('.sumoselect').SumoSelect({ csvDispCount: 0 });
            window.test = $('.okbutton').SumoSelect({okCancelInMulti:true });
        });
    </script>
</head>
<body>
    <div class="sumoselect ">
        <h3>Multiple</h3>
         <select   multiple="multiple" placeholder="Hello  im from placeholder" onchange="getname($(this).children(':selected'))" class="okbutton">
               <option selected value="volvo">Volvo</option>
               <option value="saab">Saab</option>
               <option disabled="disabled" value="mercedes">Mercedes</option>
               <option value="audi">Audi</option>
               <option value="bmw">BMW</option>
               <option value="porsche">Porche</option>
               <option value="ferrari">Ferrari</option>
                <option value="audi">Audi</option>
               <option value="bmw">BMW</option>
               <option value="porsche">Porche</option>
               <option value="ferrari">Ferrari</option>
                <option value="audi">Audi</option>
               <option value="bmw">BMW</option>
               <option value="porsche">Porche</option>
               <option value="ferrari">Ferrari</option>
               <option value="hyundai">Hyundai</option>
               <option value="mitsubishi">Mitsubishi</option>
           </select>
    </div>
    <script>
        function getname(a){
            var select=[];
            for(var key in a){
                select.push(a[key].value);
            }
            select = select.slice(0,a.length);
            console.log(select);
        }
    </script>
    <div style="height: 900px"></div>
</body>
</html>