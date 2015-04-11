<?php
$arr = array();
$arr['q'] = array();
$arr['b'] = array();
$arr['a'] = array();
$arr['q']['date'] = '01-02-03';
$arr['q']['price'] = 100;
$arr['b']['date'] = '02-04-02';
$arr['b']['price'] = 200;
$arr['a']['date'] = '09-08-03';
$arr['a']['price'] = 300;

ksort($arr);

echo '<pre>';
var_dump($arr);
echo '</pre>';
?>