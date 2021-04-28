<?php

$products = $database->query("SELECT * FROM `products` WHERE `product_id` = " . $options);
$product = $products->fetch_object();