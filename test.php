<?php
$arr = array(
    'val' => null,
    'changed' => false,
);

echo '<pre>';
var_dump($arr);
echo PHP_EOL;
var_dump(array_key_exists('val', $arr));