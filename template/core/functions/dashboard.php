<?php

//Get the sums for revenue
function total_revenue(){
    global $database;
    
    $payment = $database->query("SELECT SUM(`revenue`) FROM `payments`");
    $sum = $payment->fetch_row();
    
    return $sum[0];
}

function monthly_revenue(){
    global $database;
    
    $payment = $database->query("SELECT SUM(`revenue`) AS monthly_revenue FROM `payments` WHERE YEAR(`date`) = YEAR(CURDATE()) AND MONTH(`date`) = MONTH(CURDATE())");
    $sum = $payment->fetch_object();
    
    return $sum->monthly_revenue;
}

function total_invoices(){
    global $database;
    
    $inv_count = $database->query("SELECT COUNT(*) AS count_invoices FROM `invoices`");
    
    if(!empty($inv_count)){
        $count = $inv_count->fetch_object();
    
        return $count->count_invoices;  
    }
}

function total_reports(){
    global $database;
    
    $rep_count = $database->query("SELECT COUNT(*) AS count_rep FROM `reports`");
    if(!empty($rep_count)){
        $count = $rep_count->fetch_object();

        return $count->count_rep;
    }
}

function overdue_perc(){
    global $database;
    
    $invoice_count = $database->query("SELECT COUNT(*) AS count_overdue FROM `invoices` WHERE `status` = 'overdue'");
    if(!empty($invoice_count)){
        $overdue = $invoice_count->fetch_object();

        $total = round(($overdue->count_overdue / total_invoices()) * 100);
        
        return $total;
    }
}

function paid_perc(){
    global $database;
    
    $invoice_count = $database->query("SELECT COUNT(*) AS count_paid FROM `invoices` WHERE `status` = 'paid'");
    if(!empty($invoice_count)){
        $paid = $invoice_count->fetch_object();
    
        $total = round(($paid->count_paid / total_invoices()) * 100);
    
        return $total;
    }
}

function cancelled_perc(){
    global $database;
    
    $invoice_count = $database->query("SELECT COUNT(*) AS count_cancelled FROM `invoices` WHERE `status` = 'cancelled'");
    if(!empty($invoice_count)){
        $cancelled = $invoice_count->fetch_object();
    
        $total = round(($cancelled->count_cancelled / total_invoices()) * 100);
    
        return $total;
    }
}

function unpaid_perc(){
    global $database;
    
    $invoice_count = $database->query("SELECT COUNT(*) AS count_draft FROM `invoices` WHERE `status` = 'draft'");
    
    
    
    $unpaid_count = $database->query("SELECT COUNT(*) AS count_unpaid FROM `invoices` WHERE `status` = 'unpaid'");
    if(!empty($invoice_count) || !empty($unpaid_count)){
        $unpaid = $unpaid_count->fetch_object();
        $draft = $invoice_count->fetch_object();

        $sum = $unpaid->count_unpaid + $draft->count_draft;
        $total = round(($sum / total_invoices()) * 100);

        return $total;
    }
}

function total_clients(){
    global $database;
    
    $clients_count = $database->query("SELECT COUNT(*) AS total_clients FROM `users`");
    if(!empty($clients_count)){
    $clients = $clients_count->fetch_object();
    
    return $clients->total_clients;
    }
}

function invoice_show(){
    global $database;
    global $invoice_pfx;
    global $language;
    
    $invoice_data = $database->query("SELECT * FROM `invoices` WHERE `user_id` = " . $_SESSION['user_id'] . " ORDER BY `invoice_id` DESC LIMIT 3");
    
    
    
    echo "<table class=\"table\">
                <thead class=\"thead-dark\">
                    <tr>
                        <th scope=\"col\" class=\"text-center\">{$language['misc']['invoice_id']}</th>
                        <th scope=\"col\">{$language['misc']['date']}</th>
                        <th scope=\"col\">{$language['forms']['due_date']}</th>
                        <th scope=\"col\">{$language['misc']['status']}</th>
                        <th scope=\"col\">{$language['misc']['view']}</th>
                    </tr>
                </thead><tbody>";
     if(!empty($invoice_data)){                   
    while($invoice = $invoice_data->fetch_object()){
        echo "
                
                    <tr>
                        <td class=\"table-text-heading\"><b>{$invoice_pfx}{$invoice->invoice_id}</b></td>
                        <td>" . date("D d M Y", strtotime($invoice->date)) . "</td>
                        <td>" . date("D d M Y", strtotime($invoice->due_date)) . "</td>
                        <td>"; 
                        
                        invoices_status($invoice->status);
                        
                        echo "</td>
                        <td><a href=\"invoices/view/$invoice->invoice_id\"><button class=\"btn btn-sm btn-secondary\"><span class=\"fa fa-file-alt\"></span></button></a></td>
                    </tr>
                

";
    }
     }
    echo "</tbody>
              </table>";
    
}

function payments_show(){
    global $database;
    global $currency_pfx;
    global $invoice_pfx;
    global $payment_pfx;
    global $language;
    
    $payment_data = $database->query("SELECT * FROM `payments` WHERE `user_id` = " . $_SESSION['user_id'] . " ORDER BY `date` DESC LIMIT 3");
    
    
    
    echo "<table class=\"table\">
                <thead class=\"thead-dark\">
                    <tr>
                        <th scope=\"col\" class=\"text-center\">{$language['misc']['invoice_id']}</th>
                        <th scope=\"col\" class=\"text-center\">{$language['misc']['payment_id']}</th>
                        <th scope=\"col\">{$language['payments']['revenue']}</th>
                        <th scope=\"col\">{$language['misc']['date']}</th>
                    </tr>
                </thead><tbody>";
     if(!empty($payment_data)){                   
    while($payment = $payment_data->fetch_object()){
        echo "
                
                    <tr>
                        <td class=\"table-text-heading\"><b>{$invoice_pfx}{$payment->invoice_id}</b></td>
                        <td class=\"table-text-heading\"><b>{$payment_pfx}{$payment->payment_id}</b></td>
                        <td>{$currency_pfx}{$payment->revenue}</td>
                        <td>" . date("D d M Y", strtotime($payment->date)) . "</td>
                    </tr>
                

";
    }
     }
    echo "</tbody>
              </table>";
    
}