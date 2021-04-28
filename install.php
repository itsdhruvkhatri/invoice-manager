<?php
error_reporting(0);
$errors = array();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Installation</title>
		    <meta charset="UTF-8">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

                        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
                        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	</head>
	<body>
		<div class="container">
			<h2>Welcome !</h2>

			<div class="panel panel-default">
				<div class="panel-body">
					<?php
					if(!empty($_POST)) {
						/* Define some variables */
						$database_server 	= $_POST['database_server'];
						$database_user	 	= $_POST['database_user'];
						$database_password  = $_POST['database_password'];
						$database_name		= $_POST['database_name'];

						$database = new mysqli($database_server, $database_user, $database_password, $database_name);
						$connect_file = "template/core/database/connect.php";

						/* Check for any errors */
						if($database->connect_error) {
							$errors[] = 'We couldn\'t connect to the database !';
						}
						if(!is_readable($connect_file) || !is_writable($connect_file)) {
							$errors[] = '<u><strong>temaplate/core/database/connect.php</strong></u> doesn\'t have CHMOD 777';
						}
						if(filter_var($_POST['settings_url'], FILTER_VALIDATE_URL) == false) {
							$errors[] = 'Your website url is not valid !';
						}

						if(empty($errors)) {
							/* add "/" if the user didnt added it */
							if(substr($_POST['settings_url'], -1) !== "/") {
								$_POST['settings_url'] .= "/";
							}

							/* Define the connect.php content */
							$connect_content = <<<PHP
<?php
// Connection parameters
\$DatabaseServer = "$database_server";
\$DatabaseUser   = "$database_user";
\$DatabasePass   = "$database_password";
\$DatabaseName   = "$database_name";

// Connecting to the database
\$database = new mysqli(\$DatabaseServer, \$DatabaseUser, \$DatabasePass, \$DatabaseName);

?>
PHP;
							/* open, write and close */
							$command = fopen($connect_file, w);
							fwrite($command, $connect_content);
							fclose($command);

							/* Add the tables to the database */
                                                        
							$database->query("
								CREATE TABLE IF NOT EXISTS `reports` (
								  `report_id` int(11) NOT NULL AUTO_INCREMENT,
								  `user_id` int(11) NOT NULL,
								  `status` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
								  `invoice_id` int(11) NOT NULL,
								  `message` varchar(2056) COLLATE utf8_unicode_ci NOT NULL,
								  `date` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`report_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
							");
                                                        
                                                        $database->query("
								CREATE TABLE IF NOT EXISTS `comments` (
								  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
								  `report_id` int(11) NOT NULL,
								  `comment` varchar(2560) COLLATE utf8_unicode_ci NOT NULL,
								  `user_id` int(11) NOT NULL,                                                    
								  `date` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`comment_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
							");
                                                        
							$database->query("
								CREATE TABLE IF NOT EXISTS `settings` (
								  `id` int(11) NOT NULL,
								  `page_title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `url` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `meta_description` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
								  `analytics_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,                                                                 
                                                                  `invoice_disclaimer` varchar(2560) COLLATE utf8_unicode_ci NOT NULL,                                                                  
								  `per_page_pagination` int(11) NOT NULL DEFAULT '10',
								  `contact_email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
								  `paypal_email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
								  `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
								  `contact_number` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
                                                                  `tax` int(5) NOT NULL DEFAULT '0',
                                                                  `deposit_percentage` int(11) NOT NULL DEFAULT '20',
								  `payment_currency` varchar(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'USD',
                                                                  `currency_pfx` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '$',
                                                                  `payment_pfx` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PYM_',
                                                                  `invoice_pfx` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'INV_',
                                                                  `product_pfx` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'PRD_',
                                                                  `report_pfx` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'RPT_',
                                                                  `client_pfx` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'CLT_',
								  `logo` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                                                                  `favicon` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`id`)
								) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
							");
                                                        
							$database->query("
								CREATE TABLE IF NOT EXISTS `users` (
								  `user_id` int(11) NOT NULL AUTO_INCREMENT,
								  `username` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
								  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
								  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `surname` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `lost_password_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `comments` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
								  `company_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
								  `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
								  `location` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
                                                                  `total_invoices` int(5) NOT NULL,
                                                                  `contact_number` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
								  `pic_url` varchar(38) COLLATE utf8_unicode_ci NOT NULL,
								  `type` int(1) NOT NULL DEFAULT '0',
								  `ip` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `date` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `last_activity` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`user_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
							");
                                                        
							$database->query("
								CREATE TABLE IF NOT EXISTS `payments` (
								  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
								  `user_id` int(11) NOT NULL,
								  `invoice_id` int(11) NOT NULL,
								  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,                                                    
								  `date` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
								  `revenue` decimal(11,2) NOT NULL,
                                                                  `form` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'paypal',
								  PRIMARY KEY (`payment_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
							");
                                                        
                                                        $database->query("
								CREATE TABLE IF NOT EXISTS `notifications` (
								  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
								  `user_id` int(11) NOT NULL,
								  `status` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unread',
								  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
                                                                  `type_id` int(11) NOT NULL,
								  `date` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`notification_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
							");
                                                        
                                                        $database->query("
								CREATE TABLE IF NOT EXISTS `products` (
								  `product_id` int(11) NOT NULL AUTO_INCREMENT,
								  `product_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `description` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
								  `price` varchar(10) COLLATE utf8_unicode_ci NOT NULL,                                                    
								  `unit` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`product_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
							");
                                                        
                                                        $database->query("
								CREATE TABLE IF NOT EXISTS `invoices` (
								  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
								  `user_id` int(11) NOT NULL,
                                                                  `discount` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
                                                                  `total_price` decimal(11,2) NOT NULL,
                                                                  `unit` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '%',
								  `status` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
                                                                  `date` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
                                                                  `due_date` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
								  `deposit_status` int(2) NOT NULL DEFAULT 0,                                                    
								  `deposit_total` decimal(11,2) NOT NULL,
								  `form` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
								  PRIMARY KEY (`invoice_id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
							");
                                                        
                                                        $database->query("
								CREATE TABLE IF NOT EXISTS `product_invoice` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `product_id` int(11) NOT NULL,
								  `invoice_id` int(11) NOT NULL,
								  `qty` int(11) NOT NULL,
								  PRIMARY KEY (`id`)
								) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
							");
                                                        
							$database->query("
								INSERT INTO `users` (`user_id`, `name`, `surname` ,`username`, `password`, `lost_password_code`, `comments`, `location`, `total_invoices`, `contact_number`, `pic_url`, `type`, `ip`, `date`, `last_activity`) VALUES
								(1, 'Admin', 'Admin' , 'admin@admin.com', 'ce4a140c729f412deb4dbcbc023397e6f2ac06bfc45b679a41c17e975d7f1fb9bfc3bc0d37480140fa22d35db9facf3f55a0dcd83f98fbe0360e1f0a90a434e1', '0', '', '', '', '', '', 4, '-hax-', '-hax-', '')
							");
                                                        
							$database->query("
								INSERT INTO `settings` (`id`, `page_title`, `contact_email`, `url`, `meta_description`, `analytics_code`, `per_page_pagination`, `invoice_disclaimer`, `logo`, `favicon`) VALUES
								(1, '" . $_POST['settings_title'] . "', 'no-reply@domain.com', '" . $_POST['settings_url'] . "', '', '', 15, '', 'loading.gif', 'loading_1.gif');
							");


							/* Display a success message */
							echo '<div class="alert alert-success"><strong>Congratulations !</strong> Now delete the install.php file and you are good to go !</div>';
						} else {

							/* Display all the errors if needed */
							foreach($errors as $nr => $error) {
								echo '<div class="alert alert-warning">' . $error . '</div>';
							}

							echo '<a href="install.php"><button class="btn btn-primary">Go back !</button></a>';
						}
					} else {
					?>
					<div class="alert alert-info">Make sure the <u><strong>core/database/connect.php</strong></u> file has CHMOD 777 before installing !</div>

					<form action="" method="post" role="form">
						<div class="form-group">
							<label>Database Server</label>
							<input type="text" class="form-control" name="database_server" value="localhost" />
						</div>
						<div class="form-group">
							<label>Database User</label>
							<input type="text" class="form-control" name="database_user" />
						</div>
						<div class="form-group">
							<label>Database Password</label>
							<input type="text" class="form-control" name="database_password" />
						</div>
						<div class="form-group">
							<label>Database Name</label>
							<input type="text" class="form-control" name="database_name" />
						</div>

						<div class="form-group">
							<label>URL</label>
							<p class="help-block">e.g: http://domain.com/directory/</p>
							<input type="text" class="form-control" name="settings_url" />
						</div>
						<div class="form-group">
							<label>Site Title</label>
							<input type="text" class="form-control" name="settings_title" />
						</div>

						<div class="form-group">
							<button type="submit" name="submit" class="btn btn-primary col-lg-4">Install</button>
						</div>
					</form>
					<?php } ?>
				</div>

				<div class="panel-footer">
					<span>Created by <a href="http://kodlyweb.com">Kodly</a></span>
				</div>

			</div>

		</div>
	</body>
</html>