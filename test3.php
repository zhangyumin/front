<?php
    $a = array();
    $a = file("./line.txt");
    foreach ($a as $key => $value) {
        $c = explode("\n", $value);
        $b = $b.$c[0]."\\n";
    }
    file_put_contents("./haha.txt", $b);
?>