<?php
User::check_permission(0);
$invoice = $database->query("SELECT * FROM `invoices` WHERE `invoice_id` = " . $_GET['invoice_id']);
$invoice_data = $invoice->fetch_object();

if (!empty($_POST)){
   
    $report	= filter_var($_POST['report'], FILTER_SANITIZE_STRING);
        
    if(strlen(trim($_POST['report'])) > 2056){
        $_SESSION['error'] = $language['errors']['description_limit'];
    }
        

    if(empty($_SESSION['error'])){
        
        $date = new DateTime();
        $date = $date->format('d-m-Y H:i:s');
        $status = "U";
        
        $stmt = $database->prepare("INSERT INTO `reports` (`user_id`, `status`, `invoice_id`, `message`, `date`) VALUES (?, ?, ?, ?, ?) ");
        $stmt->bind_param('sssss', $_SESSION['user_id'], $status, $invoice_data->invoice_id, $report, $date);
        $stmt->execute();
        $stmt->close();
        
        $_SESSION['success'] = $language['errors']['report_created'];
        redirect('invoices/view/'.$_GET['invoice_id']);

    }
    
}
?>
<div class="light-nav">
    <h5 class="text-secondary"><span class="fa fa-file"></span> &nbsp; Report <?php echo '<strong>'. $invoice_pfx . $_GET['invoice_id'] . '</strong>'; ?></h5>
</div>
<br>

<form method="POST" action="" role="form">
    <div class="form-group">
        <label><?php echo $language['misc']['report']; ?></label>
        <textarea name="report" class="form-control" placeholder="<?php echo $language['report']['type_report']; ?>"></textarea>
    </div>
    <button class="btn btn-secondary" type="submit" name="submit"><?php echo $language['forms']['submit']; ?></button>
</form>