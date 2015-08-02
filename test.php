<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
    <script src="//code.jquery.com/jquery-1.11.1.min.js" type="text/javascript" ></script>
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"type="text/javascript" ></script>
    <link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css"type="text/css" rel="stylesheet"></link>
    </head>
    <body>
        <table id="example" class="display dataTable" cellspacing="0" width="100%" role="grid" aria-describedby="example_infox">
            <thead>
                <tr>
                    <?php
                        $a=file("./searched/SearchedPAC_arab20150710092808.APAgene.pac.userXsys.minpat5_minrep1_norm0");
                        $title_tmp=  explode("\t", $a[0]);
                        foreach ($title_tmp as $key => $value) {
                            echo "<th>$value</th>";
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                    <?php
                        foreach ($a as $key => $value) {
                                if($key!=0){
                                    echo "<tr>";
                                    $tmp=  explode("\t", $a[$key]);
                                    foreach ($tmp as $key1 => $value1) {
                                        if($key1==0)
                                            echo "<td><a href='./aftertreatment_result_test.php?chr=$tmp[2]&gene=$tmp[1]&strand=$tmp[3]1'>$value1</a></td>";
                                        else
                                            echo "<td>$value1</td>";
                                    }
                                    echo "</tr>";
                                }
                        }
                    ?>
            </tbody>
        </table>
        <script>
            $(document).ready(function(){
                $('#example').dataTable({
                    "lengthMenu":[[10,25,50,-1],[10,25,50,"all"]],
                    "pagingType":"full_numbers"
                });
            });
        </script>
    </body>
</html>
