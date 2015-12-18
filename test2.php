<?php
    $a = array();
    $b = array('0'=>'5','1'=>'4','2'=>'33','4'=>'22');
    function array_add($a,$b){
        //根据键名获取两个数组的交集
        $arr=array_intersect_key($a, $b);
        //遍历第二个数组，如果键名不存在与第一个数组，将数组元素增加到第一个数组
        foreach($b as $key=>$value){
            if(!array_key_exists($key, $a)){
                $a[$key]=$value;
            }
        }
            //计算键名相同的数组元素的和，并且替换原数组中相同键名所对应的元素值
        foreach($arr as $key=>$value){
            $a[$key]=$a[$key]+$b[$key];
        }
        //返回相加后的数组
        return $a;
    }
    var_dump(array_add($a, $b));
?>