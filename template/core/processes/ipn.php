<?php
ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn.log');

include '../init.php';
include '../classes/IpnListener.php';

$listener = new IpnListener();

$listener->use_sandbox = false;

try {
	$listener->requirePostMethod();
	$verified = $listener->processIpn();
} catch (Exception $e) {
	error_log($e->getMessage());
	exit(0);
}



if ($verified) {
	/* Process the custom variable */
        $invoice_id = $_POST['custom'];
	$date = new DateTime();
	$date = $date->format('Y-m-d H:i:s');
	$user_id = User::x_to_y('invoice_id', 'user_id', $invoice_id, 'invoices');
        $user_email = User::x_to_y('user_id', 'username', $user_id, 'users');
        $type = "full";
        $form = "paypal";

	/* Check for any errors in the small details of the payment */
	if($_POST['payment_status'] != 'Completed') {
		$errors[] = "Payment not completed";
	}

	if($_POST['receiver_email'] != $settings->paypal_email) {
		$errors[] = "Receiver email is not the same: " . $_POST['receiver_email'];
	}

	/* If there are errors, log them */
	if(!empty($errors)) {
		$error_log = var_dump($errors);
		error_log($error_log);
	}

	/* If there are no errors, make the server highlighted and add a log in the database */
	else {

		$database->query("UPDATE `invoice` SET `status` = 'paid' WHERE `invoice_id` = {$invoice_id}");
		$database->query("INSERT INTO `payments` (`user_id`, `invoice_id`, `type`, `date`, `revenue`, `form`) VALUES ({$user_id}, {$invoice_id}, {$type}, '{$date}', {$_POST['amount']}, '{$form}')");
                
                $title = "Payment for " . $invoice_pfx . $invoice_id . "";
                $message = "Your payment of {$_POST['amount']} has been sent successfully to {$settings->page_title}\n";
                $message .= "Thank you for choosing $settings->page_title , Hope to see you back in the future";
                
                sendmail($user_email, $settings->contact_email, $title, $message);

	}

	error_log($listener->getTextReport());

} else {

	error_log($listener->getTextReport());

}

?>
