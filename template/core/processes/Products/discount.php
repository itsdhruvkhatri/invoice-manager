<?php
$discount = $_GET['discount'];

if(empty($discount) || $discount == "0"){
    echo "0%";
}else{
    echo $discount . "%";
}
?>