<?php

require_once '../database/connect.php';
    $number = count($_POST["product_name_edit"]);
    if($number >= 1){
        for($i=0; $i<$number; $i++){
            if(trim($_POST["product_name_edit"][$i] != '')){

                $product_id = $_POST['product_name_edit'][$i];
                $invoice_id = $_POST['invoices_id'];
                $qty = $_POST['product_qty_edit'][$i];
                
                $sql = $database->prepare("INSERT INTO `product_invoice` (`product_id`, `invoice_id`, `qty`) VALUES(?, ?, ?)");
                $sql->bind_param('sss', $product_id, $invoice_id, $qty);
                $sql->execute();
                $sql->close();
            }
        }	
    }


