<?php




$price = [
    ['n'=>232,'m'=>'12'],
    ['n'=>2222,'m'=>'32'],
    ['n'=>22,'m'=>'312']
];

foreach ($price as $key => $row) {
            $n[$key]  = $row['n'];
        }
        array_multisort($n, SORT_ASC, $price);
        
var_dump($price);


