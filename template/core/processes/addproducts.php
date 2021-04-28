<?php

require_once '../database/connect.php';
$number = count($_POST["product_name"]);
if($number >= 1){
    for($i=0; $i<$number; $i++){
        if(trim($_POST["product_name"][$i] != '')){

            $invoice_count = $database->query("SELECT COUNT(*) AS count_invoice FROM `invoices`");
            $invoices_count = $invoice_count->fetch_object();   
            $count_invoices = $invoices_count->count_invoice + 1;

            $product_id = $_POST['product_name'][$i];
            $invoice_id = $count_invoices;
            $qty = $_POST['product_qty'][$i];

            $sql = $database->prepare("INSERT INTO `product_invoice` (`product_id`, `invoice_id`, `qty`) VALUES(?, ?, ?)");
            $sql->bind_param('sss', $product_id, $invoice_id, $qty);
            $sql->execute();
            $sql->close();
        }
    }	
}

