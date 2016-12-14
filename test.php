<?php
$url = "http://m.qqty.com/Schedule/index.html?date=20161214";
$ch = curl_init($url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);
echo $ouput = curl_exec($ch);
