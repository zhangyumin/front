<?php
      #两个长度皆为n的数组，两个数组已排序，求两个数组所有数字中的中位数
      function get_mid($a, $b) {
          print_r($a);
          echo "<br>";
          print_r($b);
            echo "<br>";
           if (count($a) == 1) {
               $result_mid = min($a[0], $b[0]); #如果数组只剩一个元素，返回这个元素即为中位数
           } else {
              $mid = floor((count($a) - 1) / 2); #中位数坐标取下中位数
              echo "mid : {$mid}<br>";
  
              #如果a[mid] == b[mid],则中位数就是mida,返回即可
              #如果a[mid] < b[mid],选择数组a的右部，数组b的左部进入递归，直到最后递归部分数组长度为1
              #如果a[mid] > b[mid],选择数组a的左部，数组b的右部进入递归，直到最后递归部分数组长度为1
  
              #此处需要特别注意,当数组长度为奇数时，切分AB数组都要把中位数加上
              #但数组长度为偶数时，只需要把较大的数组的左部加上中位数，较小的数组则舍弃中位数只要右部进入递归
  
              if (count($a) % 2 == 0) {
                  $small_arr_start = $mid + 1;
              } else {
                  $small_arr_start = $mid;
              }
  
              if ($a[$mid] == $b[$mid]) { #两数组中位数相等，返回中位数
                 $result_mid = $a[$mid];
              } else if ($a[$mid] < $b[$mid]) { #此处需要特别注意
                   $atemp = array_slice($a, $small_arr_start); #切分数组，较小的数组根据长度是否是奇偶舍弃中位数
                  $btemp = array_slice($b, 0, $mid + 1);
                  $result_mid = get_mid($atemp, $btemp);
              } else {
                  $atemp = array_slice($a, 0, $mid + 1);
                  $btemp = array_slice($b, $small_arr_start);
                  $result_mid = get_mid($atemp, $btemp);
              }
          }
  
          return $result_mid;
      }
  
      $a = array(1, 3, 5, 5, 7, 8, 9);
      $b = array(2, 2, 7, 8, 9, 10, 14);
  
      echo "midnum : " . get_mid($a, $b) . "<br>";
  
      array_splice($a, count($a) - 1, 0, $b);
      sort($a);
  
      print_r($a);
  ?>