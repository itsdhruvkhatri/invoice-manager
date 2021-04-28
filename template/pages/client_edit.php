<?php
User::check_permission(0);

if (!empty($_POST)){
   
    $_POST['fName']	= filter_var($_POST['fName'], FILTER_SANITIZE_STRING);
    $_POST['lName']	= filter_var($_POST['lName'], FILTER_SANITIZE_STRING);
    $_POST['company_name']	= filter_var($_POST['company_name'], FILTER_SANITIZE_STRING);
    $_POST['address']	= filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $_POST['mob']       = filter_var($_POST['mob'], FILTER_SANITIZE_STRING);
    $new_password = filter_var($_POST['new_pwd'], FILTER_SANITIZE_STRING);
    $confirm_password = filter_var($_POST['confirm_pwd'], FILTER_SANITIZE_STRING);
    $image = (empty($_FILES['image']['name']) == false) ? true : false;
    
        
        if(strlen(trim($_POST['mob'])) > 14){
            $_SESSION['error'] = $language['errors']['mobile_digit_greater'];
            redirect('clients/edit');
        }
        
    if($image == true) {
        $image_file_name		= $_FILES['image']['name'];
        $image_file_extension	= explode('.', $image_file_name);
        $imagefile_extension	= strtolower(end($image_file_extension));
        $image_file_temp		= $_FILES['image']['tmp_name'];
        $image_file_size		= $_FILES['image']['size'];
        list($image_width, $image_height)	= getimagesize($image_file_temp);
              
    }
    
    if(strlen(trim($new_password)) > 0 && strlen(trim($new_password)) < 6){
        $_SESSION['error'] = $language['errors']['password_too_short'];
        redirect('clients/edit');
    }
    else if($new_password != $confirm_password){
        $_SESSION['error'] = $language['errors']['passwords_doesnt_match'];
        redirect('clients/edit');
    }
    
    

    if(empty($_SESSION['error'])){
        $location = (country_check(0, $_POST['location'])) ? $_POST['location'] : 'MT';
        
        if($image == true) {

            /* Delete current image & thumbnail */
            @unlink('template/images/profile/'.$user->pic_url);

            /* Generate new name for image */
            
            $image_new_name = md5(time().rand()) . '.' . $image_file_extension[1];

            /* Resize & upload the image */
            if($image_width != '50' || $image_height != '50') {
                    resize($image_file_temp, 'template/images/profile/' . $image_new_name, '50', '50');
            } else {
                    move_uploaded_file($image_file_temp, 'template/images/profile/' . $image_new_name);	
            }

            /* Execute query */
            $database->query("UPDATE `users` SET `pic_url` = '{$image_new_name}' WHERE `user_id` = " . $user->user_id);
        }
        
        if($new_password == $confirm_password ){
            /* Hash new password */
            $password = User::encrypt_password($user->username, $new_password);
            
            /* Execute query */
            $database->query("UPDATE `users` SET `password` = '{$password}' WHERE `user_id` = " . $user->user_id);
            
            $title = $settings->page_title . " " . $language['emails']['changepass_mail_title'];    
            $message = "<body>Dear ". $getName . ",<br><br>";
            $message .= $language['emails']['changepass_mail_message'] . "<br>";
            $message .= "<a href=\"" . $settings->url . "\"><button class=\"btn btn-primary btn-lg\">" . $language['forms']['sign_in'] . "</button><br>";
            $message .= "<strong>" . $language['forms']['username'] . ":</strong> " . $user->username . "<br>";
            $message .= "<strong>" . $language['forms']['password'] . ":</strong> " . $new_password . "<br>";
            $message .= "<br>";
            $message .= "Regards,<br>";
            $message .= $settings->page_title . "<br>";
        
            /* Send E-mail */
            sendmail($user->username, $settings->contact_email, $title, $message);
        }

        $stmt = $database->prepare("UPDATE `users` SET `name` = ?, `surname` = ?, `location` = ?, `contact_number` = ?, `company_name` = ?, `address` = ? WHERE `user_id` = " . $_SESSION['user_id']);
        $stmt->bind_param('ssssss', $_POST['fName'], $_POST['lName'], $location, $_POST['mob'], $_POST['company_name'], $_POST['address']);
        $stmt->execute();
        $stmt->close();
        
        $_SESSION['success'] = $language['errors']['profile_edited'];

        redirect('clients/edit');

    }
    
}
?>
<br>
<form action="" method="POST" role="form" enctype="multipart/form-data">
    <div class="form-group">
        <label for="lName"><?php echo $language['forms']['username']; ?></label>
        <input type="email" name="username" class="form-control" placeholder="<?php echo $language['forms']['username'];?>" value="<?php echo $user->username; ?>" disabled/>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="fName"><?php echo $language['forms']['first_name']; ?></label>
                <input type="text" name="fName" class="form-control" placeholder="<?php echo $language['forms']['first_name'];?>" value="<?php echo $user->name; ?>"/>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="lName"><?php echo $language['forms']['last_name']; ?></label>
                <input type="text" name="lName" class="form-control" placeholder="<?php echo $language['forms']['last_name'];?>" value="<?php echo $user->surname; ?>"/>
            </div>
        </div>    
    </div>
    
    <div class="form-group">
        <label for="location"><?php echo $language['forms']['location']; ?></label>
        <select name="location" class="form-control">
			<?php country_check(1, $user->location); ?>
        </select>
    </div>
    
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="company_name"><?php echo $language['forms']['company_name']; ?></label>
                <input type="text" name="company_name" class="form-control" placeholder="<?php echo $language['forms']['company_name'];?>" value="<?php echo $user->company_name; ?>"/>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="mob"><?php echo $language['forms']['contact_number']; ?></label>
                <input type="text" name="mob" class="form-control" placeholder="<?php echo $language['forms']['contact_number']; ?>" value="<?php echo $user->contact_number; ?>"/>
            </div>
        </div>    
    </div>
    
    <div class="form-group">
        <label for="address"><?php echo $language['forms']['address']; ?></label>
        <input type="text" name="address" class="form-control" placeholder="<?php echo $language['forms']['address']; ?>" value="<?php echo $user->address; ?>"/>
    </div>
    
    <hr>
    
    <div class="form-group">
        <label><?php echo $language['forms']['client_pic']; ?></label>
        <br>
        <img class="rounded-circle" src="template/images/profile/<?php if($user->pic_url != null) echo $user->pic_url; else echo 'user.png'; ?>" style="width: 50px; height: 50px;" alt="Profile"/>
        <br>
        <br>
        
        <input type="file" name="image" class="form-control" accept=".png, .jpeg, .jpg" />
    </div>
    
    <hr>
    
    <div class="form-group">
        <label for="password"><?php echo $language['forms']['change_password']; ?> :</label>
        <div class="row">
            <div class="col">
                <input type="password" name="new_pwd" class="form-control" placeholder="<?php echo $language['forms']['password']; ?>"/>
            </div>
            <div class="col">
                <input type="password" name="confirm_pwd" class="form-control" placeholder="<?php echo $language['forms']['confirm_password']; ?>"/>
            </div>
        </div>
        
    </div>
    
    <div class="form-group">
        <button class="btn btn-secondary" type="submit" name="sumbit"><?php echo $language['forms']['edit_profile'];?></button>
    </div>
</form>