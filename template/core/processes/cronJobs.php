<?php
$date = new DateTime();
$date = $date->format('d-m-Y');

if(User::logged_in()) {
    $account_user_id = (isset($_SESSION['user_id']) == true) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
    $account = new User($account_user_id);

    /* Update last activity */
    $database->query("UPDATE `users` SET `last_activity` = unix_timestamp() WHERE `user_id` = {$account_user_id}");


    /* Update total number of invoices */
    $invoice_count = $database->query("SELECT COUNT(`user_id`) AS count_invoice FROM `invoices` WHERE `user_id` = {$account_user_id}");

    $count = $invoice_count->fetch_object();
    $database->query("UPDATE `users` SET `total_invoices` = {$count->count_invoice} WHERE `user_id` = {$account_user_id}");

    

    /* Get notifications */
    $notifications = new Notifications($_SESSION['user_id']);
    

}

//NOTIFICATIONS
if(!empty($_GET['notif'])){
    /* Update notification status */
    $database->query("UPDATE `notifications` SET `status` = 'read' WHERE `notification_id` = " . $_GET['notif']);
}



//Removing Items
if(!empty($_GET['remove_client'])){
    /* Remove anything that had to do with the client */
    $database->query("DELETE FROM `users` WHERE `user_id` = " . $_GET['remove_client']);
    $database->query("DELETE FROM `reports` WHERE `user_id` = " . $_GET['remove_client']);
    $database->query("DELETE FROM `payments` WHERE `user_id` = " . $_GET['remove_client']);
    $database->query("DELETE FROM `invoices` WHERE `user_id` = " . $_GET['remove_client']);
}

if(!empty($_GET['remove_report'])){
    /* Remove anything that had to do with the report */
    $database->query("DELETE FROM `reports` WHERE `report_id` = " . $_GET['remove_report']);
    $database->query("DELETE FROM `comments` WHERE `report_id` = " . $_GET['remove_report']);
    $database->query("DELETE FROM `notifications` WHERE `type` = 'report' AND `type_id` = " . $_GET['remove_report']);
    $database->query("DELETE FROM `notifications` WHERE `type` = 'reply' AND `type_id` = " . $_GET['remove_report']);
}

if(!empty($_GET['remove_product'])){
    /* Remove anything that had to do with the product */
    $database->query("DELETE FROM `products` WHERE `product_id` = " . $_GET['remove_product']);
    $database->query("DELETE FROM `product_invoice` WHERE `product_id` = " . $_GET['remove_product']);
}

//Updating report status
if(!empty($_GET['report_id'])){
    if((isset($_SESSION['report_status']) && $_SESSION['report_status'] == "U") && $user->type >= 3){
        $database->query("UPDATE `reports` SET `status` = 'R' WHERE `report_id` = " . $_GET['report_id']);
    }
}


//Updating invoices
if(!empty($_GET['invoice_status']) && !empty($_GET['invoice_id'])){
    if($_GET['invoice_status'] == "draft"){
        $database->query("UPDATE `invoices` SET `status` = 'draft' WHERE `invoice_id` = " . $_GET['invoice_id']);
    }
    if($_GET['invoice_status'] == "cancelled"){
        $database->query("UPDATE `invoices` SET `status` = 'cancelled' WHERE `invoice_id` = " . $_GET['invoice_id']);
    }
    if($_GET['invoice_status'] == "unpaid"){
        $database->query("UPDATE `invoices` SET `status` = 'unpaid' WHERE `invoice_id` = " . $_GET['invoice_id']);
    }
    if($_GET['invoice_status'] == "paid"){
        
        $invoice_items = $database->query("SELECT * FROM `invoices` WHERE `invoice_id` = " . $_GET['invoice_id']);
        $invoice_data = $invoice_items->fetch_object();
        
        $type = "full";
        $date = date("Y-m-d");
        $form = "Manual";
        
        $stmt = $database->prepare("INSERT INTO `payments` (`user_id`, `invoice_id`, `type`, `date`, `revenue`, `form`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $invoice_data->user_id, $_GET['invoice_id'], $type, $date, $invoice_data->total_price, $form);
        $stmt->execute();
        $stmt->close();
        
        $database->query("UPDATE `invoices` SET `status` = 'paid' WHERE `invoice_id` = " . $_GET['invoice_id']);
    }
}


    $invoice_details = $database->query("SELECT * FROM `invoices`");
    if(!empty($invoice_details)){
if(isset($_GET['page']) == "invoice_create"){
    $invoice_count = $database->query("SELECT COUNT(*) AS count_invoice FROM `invoices`");
    $invoices_count = $invoice_count->fetch_object();
    $count_invoices = $invoices_count->count_invoice + 1;     
}

   

    while($invoice = $invoice_details->fetch_object()){ 

        if (($invoice->due_date <= $date && $invoice->status != "cancelled") && $invoice->status != "draft" && $invoice->status != "paid"){
            $database->query("UPDATE `invoices` SET `status` = 'overdue' WHERE `invoice_id` = " . $invoice->invoice_id);
            
            $user_detail = $database->query("SELECT * FROM `users` WHERE `user_id` = ". $invoice->user_id);
            $user_detail = $user_detail->fetch_object();
            $title = $language['emails']['payment_title'];
            $message = "Hi " . $user_detail->name . ",<br>";
            $message .= $language['emails']['pay_not_message'] . "<br>";
            $message .= sprintf($language['emails']['invoice_message_view'] . " ", "<a href=\"" .$settings->url . "invoices/view/" . $invoice->invoice_id. "\">Invoice</a>") . "<br>";
            $message .= "Thanks,<br>";
            $message .= $settings->page_title;
            
            //Send automatic overdue email if invoice is not paid by the overdue date and if are not saved as draft
            sendmail($user_detail->username, $settings->contact_email, $title, $message);
        }
        
        if (($invoice->due_date > $date && $invoice->status != "cancelled") && $invoice->status != "draft" && $invoice->status != "paid"){
            $database->query("UPDATE `invoices` SET `status` = 'unpaid' WHERE `invoice_id` = " . $invoice->invoice_id);
        }
    }
}

//payment status - to update invoice
$payment_details = $database->query("SELECT * FROM `payments`");
while($payments = $payment_details->fetch_object()){
    $invoice_details = $database->query("SELECT * FROM `invoices` WHERE `invoice_id` = " . $payments->invoice_id);
    $invoice = $invoice_details->fetch_object();
    
    if($invoice->total_price == $payments->revenue){
        $database->query("UPDATE `invoices` SET `status` = 'paid' WHERE `invoice_id` = ". $payments->invoice_id);
    }
}


//Remove read notifications after one week

?>