<?php

# 1.
$a = [];

for($i=0; $i<10; $i++){
    $a[] = rand(1,20);
}

# 2.
$f = array_filter($a, function($arr){
    return $arr<10;
});

print_r($a);
print_r($f);

# 3.
// ...

