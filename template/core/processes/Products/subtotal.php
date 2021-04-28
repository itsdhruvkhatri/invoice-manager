<?php

require_once 'display.php';
require_once '../../database/connect.php';
require_once '../../functions/general.php';
$settings = settings_data();
require_once '../../functions/prefixes.php';

//$total_psum = array($_GET['total']);

$total_price = $_GET['total'];

if(empty($total_price)){
    echo $currency_pfx . "0";
}else{
    echo $currency_pfx . $total_price;
}

//$total_sum = total_price($discount ,$total);

?>

