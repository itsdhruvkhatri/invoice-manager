<?php

$invoice_items = $database->query("SELECT * FROM `invoices` WHERE `invoice_id` = " . $_GET['invoice_id']);
$invoice_data = $invoice_items->fetch_object();

$user_dat = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $invoice_data->user_id);
$user_data = $user_dat->fetch_object();

$product_item = $database->query("SELECT * FROM `product_invoice` WHERE `invoice_id` = " . $_GET['invoice_id']);

$subtotal = array();



$pdf = "<div class=\"container\">
    <div class=\"light-nav\">
    <img src=\"template/images/" . $settings->logo . "\" width=\"100\"/>
    <h5 class=\" inline text-right text-secondary\"><img src=\"template/images/pdf/file.png\" width=\"17\"/>&nbsp;" . $invoice_pfx . $invoice_data->invoice_id."</h5>
       
</div>
<br>
<div class=\"gray-nav\">
    <table class=\"table\">
        <thead>
        <tr>
            <th scope=\"col\" width=\"300\" class=\"text-left\">" . $language['invoice']['from'] . "</th>
            <th scope=\"col\" class=\"text-left\">" . $language['invoice']['to'] . "</th>
            
        </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td class=\"text-left\"><small><img src=\"template/images/pdf/user.png\" width=\"17\"/>&nbsp;&nbsp;" . $user_data->name . " " . $user_data->surname . "</small></td>
            </tr>
            <tr>
                <td><small><b>" . $settings->page_title . "</b></small></td>
                <td><small><b>" . $user_data->company_name . "</b></small></td>
            </tr>
            
            <tr>
                <td><img src=\"template/images/pdf/building.png\" width=\"17\"/><small>&nbsp;&nbsp;" .  $settings->address . "</small></td>
                <td><img src=\"template/images/pdf/building.png\" width=\"17\"/><small>&nbsp;&nbsp; " . $user_data->address . "</small></td>
            </tr>
            
            <tr>
                <td><img src=\"template/images/pdf/phone.png\" width=\"17\"/><small>&nbsp;&nbsp;" . $settings->contact_number . "</small></td>
                <td><img src=\"template/images/pdf/phone.png\" width=\"17\"/><small>&nbsp;&nbsp;" . $user_data->contact_number . "</small></td>
            </tr>
        </tbody>
        
            

            
    </table>
</div>
<br>

    <table class=\"table\">
            
            <thead>
            <tr class=\"gray-nav\">               
                <th scope=\"col\" class=\"text-left\">" . $language['invoice']['product'] . "</th>
                <th scope=\"col\">" . $language['invoice']['quantity'] . "</th>
                <th scope=\"col\">" . $language['forms']['product_price']. "</th>
                <th scope=\"col\">" . $language['forms']['product_unit'] . "</th>               
                <th scope=\"col\" class=\"text-center\">" . $language['invoice']['total'] . "</th>               
            </tr>
            
            </thead>      

            <tbody>";
                  while($product_items = $product_item->fetch_object()){
                    $products = $database->query("SELECT * FROM `products` WHERE `product_id` = " . $product_items->product_id);
                    while($product_data = $products->fetch_object()){  
                     
                    $pdf .= "<tr>
                        <td style=\"width:300px;\">" . $product_data->product_name . "<br> <small>" . $product_data->description . "</small></td>
                        <td class=\"text-center\">" . $product_items->qty . "</td>
                        <td style=\"width: 150px;\" class=\"text-center\">" . $currency_pfx . $product_data->price . "</td>
                        <td>" . '/ ' . $product_data->unit . "</td>
                        <td class=\"text-center\">" . $currency_pfx . calculate_price($product_data->price, $product_items->qty) . "</td>
                    </tr>
                    
                                      
                " . $subtotal[] = calculate_price($product_data->price, $product_items->qty);
                
                }                   
                
                    }
           $pdf .=  "</tbody>
        </table>
<hr>
<div class=\"light-nav\">

    <table>
        <thead>
            <tr>
                <th scope=\"col\" width=\"150px\" class=\"text-left\"><h3 class=\"text-secondary\"><b>" . $language['invoice']['sub_total'] . "</b></h3></th>
                <th scope=\"col\" width=\"80px\" class=\"text-left\">+</th>
                <th scope=\"col\" width=\"150px\" class=\"text-left\"><h3 class=\"text-secondary inline\"><b>" . $language['invoice']['tax'] . "</b></h3></th>
                <th scope=\"col\" width=\"80px\" class=\"text-left\">-</th>
                <th scope=\"col\" width=\"150px\" class=\"text-left\"><h3 class=\"text-secondary\"><b>" . $language['invoice']['discount'] . "</b></h3></th>
                <th scope=\"col\" width=\"80px\" class=\"text-left\">=</th>
                <th scope=\"col\" width=\"150px\" class=\"text-left\"><h3 class=\"text-secondary\"><b>" . $language['invoice']['total'] . "</b></h3></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>" . $currency_pfx . array_sum($subtotal) . "</td>
                <td></td>
                <td>" . $settings->tax . " " .$invoice_data->unit . "</td>
                <td></td>
                <td>" . $invoice_data->discount . " " . $invoice_data->unit . "</td>
                <td></td>
                <td>" . $currency_pfx . total_price($invoice_data->discount, $subtotal) . "</td>
            </tr>
        </tbody>
    </table>
</div>

<br>
<div class=\"light-nav\">
    <pre><small>" . $settings->invoice_disclaimer . "</small></pre>
</div></div>
";