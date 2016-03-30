<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Sequence Detail</title>
        <script src="./src/jquery-1.10.1.min.js"></script>
        <!-- Mobile viewport optimisation -->
        <link href="./css/flexible-grids.css" rel="stylesheet" type="text/css"/>
        <!--[if lte IE 7]>
        <link href="./css/iehacks.min.css" rel="stylesheet" type="text/css" />
        <![endif]-->

        <!--[if lt IE 9]>
        <script src="./js/html5shiv/html5shiv.js"></script>
        <![endif]-->

        <!--<link rel="stylesheet" href="./src/font-awesome.min.css">-->
        <!--<link rel="stylesheet" href="./src/example.css">-->
        <!-- sliders -->
        <script src="./src/slider/js/jquery-plus-ui.min.js"></script>
        <script src="./src/slider/js/jquery-ui-slider-pips.js"></script>
        <link rel="stylesheet" href="./src/slider/css/jqueryui.min.css" />
        <link rel="stylesheet" href="./src/slider/css/jquery-ui-slider-pips.min.css" />
        <!--<link rel="stylesheet" href="./src/slider/css/app.min.css" />-->
        <!-- dataTables -->
        <script src="./src/jquery.dataTables.min.js"type="text/javascript" ></script>
        <link href="./src/jquery.dataTables.css"type="text/css" rel="stylesheet"></link>
        <!-- pwstabs -->
        <link type="text/css" rel="stylesheet" href="./src/pws-tabs/jquery.pwstabs-1.2.1.css"></link>
        <script src="./src/pws-tabs/jquery.pwstabs-1.2.1.js"></script>
        <!-- sumoselect -->
         <script src="./src/optselect/jquery.sumoselect.js"></script>
        <link href="./src/optselect/sumoselect.css" rel="stylesheet" />
        <style>
            .wrap{margin:0px auto;width: 100%;}
            .tabs {width:100%;}
            .tabs a{display: block;float: left;width:33.3%;color: #5db95b;text-align: center;background: #eee;line-height: 40px;font-size:16px;text-decoration: none;}
            .tabs a.active {color: #fff;background: #5db95b;border-radius: 5px 5px 0px 0px;}
            .swiper-container {height:500px;border-radius: 0 0 5px 5px;width: 100%;border-top: 0;background-color:#fff}
            .swiper-slide {height:325px;width:100%;background: none;color: #fff;}
            /*.content-slide {padding: 40px;}*/
            /*.content-slide{overflow-x: scroll;overflow-y: scroll}*/
            .content-slide p{text-indent:2em;line-height:1.9;}
            .slidebar-content{color: black;}
            .swiper-slide{color:black;}
            @media(max-width: 1245px){
                #tables{overflow: scroll}
            }
            #text{
                font-size: 15px;
            }
            .step-title{
                margin: auto;
                margin-top: 15px;
                margin-bottom: 0px;
                height: 20px;
                background-color: #5db95b;
                padding: 7px 18px 7px;
                border: 0px solid #000;
                border-radius: 8px;
                cursor: pointer;
            }
            .left{
                text-align: right;
                font-weight: bold;
            }
        </style>
        <script src="./src/idangerous.swiper.min.js"></script> 
        <link rel="stylesheet" href="./src/idangerous.swiper.css">
    </head>
    <body>
        <?php
            include"navbar.php";
        ?>
        <?php
            $con=  mysql_connect("localhost","root","root");
            mysql_select_db("db_server",$con);
            session_start();
            
            $seq = $_GET['seq'];
            if(isset($_GET['species'])){
                $species = $_GET['species'];
            }
            else{
                $species = $_SESSION['species'];
            }
            
            //获得strand
            if($_GET['flag']=='intergenic'){
                 $cgs=  mysql_query("select * from db_server.t_".$species."_gff_all where gene='".$_GET['seq']."' and ftr='intergenic';");
                 $gene_start = $coord -300;
                 $gene_end = $coord + 200;
            }
            else{
                $cgs=  mysql_query("select * from db_server.t_".$species."_gff_all where gene='".$_GET['seq']."' and ftr='gene';");
                $a="SELECT * from db_server.t_".$species."_gff_all where gene='$seq' and ftr='gene';";
                $result=mysql_query($a);
                while($row=mysql_fetch_row($result))
                {
                    $gene_start=$row[3];
                    $gene_end=$row[4];
                }
            }
            while($cgs_row=  mysql_fetch_row($cgs)){
                $chr=$cgs_row[0];
                $gene=($cgs_row[3]+$cgs_row[4])/2;
    //            echo $gene;
                if($cgs_row[1]=='+'){
                    $strand=1;
                }
                else{
                    $strand=-1;
                }
            }
        ?>
        <div  id="page" style="width:1200px;margin:auto">
            <table  cellspacing="0" cellpadding="0" border="0" style="margin: 20px auto;border-collapse:collapse;" >
            <tbody>
                <tr>
                    <td colspan=2 style="padding-left:0px;padding-right:0px;border-top: 0px">
                        <div class="tabs" style="padding:0px;border:solid #5db95b">
                            <div id="jbrowse_frame" data-pws-tab="jbrowse" data-pws-tab-name="Jbrowse">
                                <iframe id="jbrowse" src="../jbrowse/?data=data/<?php echo $_GET['species'];?>&loc=<?php echo $chr;?>:<?php echo $gene_start;?>..<?php echo $gene_end;?>&tracks=DNA,Gene annotation,PlantAPA stored PAC" width=1200px height="800px">
                                </iframe>
                            </div>
                            <div id="genepic_frame" data-pws-tab="genepic" data-pws-tab-name="PAT distribution">
                                <?php
                                    if($_GET['flag']=='intergenic'){
                                        if($_GET['analysis']==1)
                                            echo "<iframe id='genepic' src=\"./genepic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&intergenic=1&coord=$coord&analysis=1\" width=1190px></iframe>";
                                        else
                                            echo "<iframe id='genepic' src=\"./genepic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&intergenic=1&coord=$coord\" width=1190px></iframe>";
                                    }
                                    else{
                                        if($_GET['analysis']==1)
                                            echo "<iframe id='genepic' src=\"./genepic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&analysis=1\" width=1190px></iframe>";
                                        else
                                            echo "<iframe id='genepic' src=\"./genepic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand\" width=1190px></iframe>";
                                    }
                                    ?>
                            </div>
                            <div id="pacpic_frame" data-pws-tab="pacpic" data-pws-tab-name="PAC usage">
                                <?php
                                    if($_GET['flag']=='intergenic'){
                                        if($_GET['analysis']==1)
                                            echo "<iframe id='pacpic' src=\"./pacpic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&intergenic=1&coord=$coord&analysis=1\" width=1190px></iframe>";
                                        else
                                            echo "<iframe id='pacpic' src=\"./pacpic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&intergenic=1&coord=$coord\" width=1190px></iframe>";
                                    }
                                    else{
                                        if($_GET['analysis']==1)
                                            echo "<iframe id='pacpic' src=\"./pacpic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&coord=$coord&analysis=1\" width=1190px></iframe>";
                                        else
                                            echo "<iframe id='pacpic' src=\"./pacpic.php?species=$species&seq=".$_GET['seq']."&chr=$chr&strand=$strand&coord=$coord\" width=1190px></iframe>";
                                    }
                                ?>
                            </div>
                         </div>
                        <script>
                            jQuery(document).ready(function($){
                                $('.tabs').pwstabs({
                                effect: 'slideleft',
                                defaultTab: 1,
                                containerWidth: '1200px'
                             });
                             });
                             function setIframeHeight(id){
                                var iframe = document.getElementById(id);
                                var frame = document.getElementById(id+"_frame");
                                var pws = $('.pws_tabs_list');
                                doc = iframe.contentWindow.document;
                                html = doc.documentElement;
                                body = doc.body;

                                // 获取高度 
                                var height = Math.max( body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight ) + 30; 
                                frame.setAttribute('height', height);
                                iframe.setAttribute('height', height);
                                pws.height(height);
                                
                            }
                        </script>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
        <?php
            include"footer.php";
            ?>
    </body>
</html>
