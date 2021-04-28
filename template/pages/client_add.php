<?php
User::check_permission(3);
if (!empty($_POST)){
   
    $_POST['fName']	= filter_var($_POST['fName'], FILTER_SANITIZE_STRING);
    $_POST['lName']	= filter_var($_POST['lName'], FILTER_SANITIZE_STRING);
    $_POST['company_name']	= filter_var($_POST['company_name'], FILTER_SANITIZE_STRING);
    $_POST['address']	= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $_POST['username']	= filter_var($_POST['username'], FILTER_SANITIZE_EMAIL);
    $_POST['mob']       = filter_var($_POST['mob'], FILTER_SANITIZE_STRING);

	if(User::x_exists('username', $_POST['username'])) {
		$_SESSION['error'] = sprintf($language['errors']['user_exists'], $_POST['username']);
	}
        
	if(filter_var($_POST['username'], FILTER_VALIDATE_EMAIL) == false) {
		$_SESSION['error'] = $language['errors']['invalid_email'];
	}
        if(strlen(trim($_POST['mob'])) > 14){
            $_SESSION['error'] = $language['errors']['mobile_digit_greater'];
        }
	if(strlen(trim($_POST['pwd'])) < 6) {
        $_SESSION['error'] = $language['errors']['password_too_short'] ;
    }
    if($_POST['pwd'] !== $_POST['repwd']) {
        $_SESSION['error'] = $language['errors']['passwords_doesnt_match'];
    }

    if(empty($_SESSION['error'])){
        $getName = $_POST['fName'];
        $password = User::encrypt_password($_POST['username'], $_POST['pwd']);
        $date = new DateTime();
        $date = $date->format('D d-M-Y H:i:s');
        $location = (country_check(0, $_POST['location'])) ? $_POST['location'] : 'MT';
        $title = $settings->page_title . " " . $language['misc']['registration_mail_title'];
               
        $message = "<body>Dear ". $getName . ",<br><br>";
        $message .= $language['misc']['registration_email_message'] . "<br>";
        $message .= "<a href=\"" . $settings->url . "\"><button class=\"btn btn-primary btn-lg\">" . $language['forms']['sign_in'] . "</button><br>";
        $message .= "<strong>" . $language['forms']['username'] . ":</strong> " . $_POST['username'] . "<br>";
        $message .= "<strong>" . $language['forms']['password'] . ":</strong> " . $_POST['pwd'] . "<br>";
        $message .= "<br>";
        $message .= "Regards,<br>";
        $message .= $settings->page_title . "<br>";

        $stmt = $database->prepare("INSERT INTO `users` (`username`, `password`, `name`, `surname`, `date`, `location`, `contact_number`, `company_name`, `address`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ");
        $stmt->bind_param('sssssssss', $_POST['username'], $password, $_POST['fName'], $_POST['lName'], $date, $location, $_POST['mob'], $_POST['company_name'], $_POST['address']);
        $stmt->execute();
        $stmt->close();
       
        $_SESSION['success'] = $language['errors']['user_added'];

        sendmail($_POST['username'], $settings->contact_email, $title, $message);
        redirect('clients/add');

    }
}
?>
<br>
<form action="" method="POST" role="form">
    <div class="form-group">
        <label for="username"><?php echo $language['forms']['username']; ?></label>
        <input type="email" name="username" class="form-control" placeholder="<?php echo $language['forms']['username'];?>"/>
    </div>
    
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="fName"><?php echo $language['forms']['first_name']; ?></label>
                <input type="text" name="fName" class="form-control" placeholder="<?php echo $language['forms']['first_name'];?>"/>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="lName"><?php echo $language['forms']['last_name']; ?></label>
                <input type="text" name="lName" class="form-control" placeholder="<?php echo $language['forms']['last_name'];?>"/>
            </div>
        </div>    
    </div>
    
    
    <div class="form-group">
        <label for="location"><?php echo $language['forms']['location']; ?></label>
        <select name="location" class="form-control">
			<?php country_check(1, null); ?>
        </select>
    </div>
    
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="company_name"><?php echo $language['forms']['company_name']; ?></label>
                <input type="text" name="company_name" class="form-control" placeholder="<?php echo $language['forms']['company_name'];?>"/>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="mob"><?php echo $language['forms']['contact_number']; ?></label>
                <input type="text" name="mob" class="form-control" placeholder="<?php echo $language['forms']['contact_number']; ?>"/>
            </div>
        </div>    
    </div>
    
    <div class="form-group">
        <label for="address"><?php echo $language['forms']['address']; ?></label>
        <input type="text" name="address" class="form-control" placeholder="<?php echo $language['forms']['address']; ?>"/>
    </div>
    
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="pwd"><?php echo $language['forms']['password']; ?></label>
                <input type="password" name="pwd" class="form-control" placeholder="<?php echo $language['forms']['password'];?>"/>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="repwd"><?php echo $language['forms']['password']; ?></label>
                <input type="password" name="repwd" class="form-control" placeholder="<?php echo $language['forms']['confirm_password'];?>"/>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <button class="btn btn-secondary" type="submit" name="sumbit"><?php echo $language['forms']['add_client'];?></button>
    </div>
</form>