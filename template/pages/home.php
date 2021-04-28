<?php
User::logged_in_redirect();

if(!empty($_POST)){
    $_POST['username'] = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = User::encrypt_password($_POST['username'], $_POST['pwd']);

    /* Check for any errors */
    if(empty($_POST['username']) || empty($_POST['pwd'])) {
        $_SESSION['error'] = $language['errors']['empty_fields'];
    }
    if(User::x_exists('username', $_POST['username']) == false) {
        $_SESSION['error'] = sprintf($language['errors']['email_doesnt_exist'], $_POST['username']);
    }
    if(User::login($_POST['username'], $password) == false) {
        $_SESSION['error'] = $language['errors']['password_user_doesnt_match'];
    }

    if(!empty($_POST) && empty($_SESSION['error'])){

        //Encrypting password
        //Setting cookies for 30 days or just logging in by a session only
        if(isset($_POST['rememberme'])) {
            setcookie("username", $_POST['username'], time()+60*60*24*30);
            setcookie("password", $_POST['pwd'], time()+60*60*24*30);
            setcookie("user_id", User::login($_POST['username'], $password), time()+60*60*24*30);
            
        }else{
            $_SESSION['user_id'] = User::login($_POST['username'], $password);
            
        }
        
        if(isset($_SESSION['user_id']) == User::login($_POST['username'], $password)){
        $_SESSION['info'] = $language['errors']['logged_in'];
        redirect();
        }
        

    }
    
    
    
    disp_notifications();
}

?>
<br>

<div class="container-fluid">
    
    <div class="row">
    <div class="col-md-4 col-lg-4 col-sm-1">
        
    </div>
        
        
    <div class="col-md-6 col-lg-5 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <h4><img src="template/images/<?php echo $settings->logo; ?>" alt="Logo" width="100"/>&nbsp;<?php echo $settings->page_title; ?></h4>
                    </div>
                    <br>
                    <br>
                    <br>
                    <form action="" method="POST" role="form">
                        <div class="form-group" style="padding: 5px;">
                            <input type="text" name="username" class="form-control" placeholder="<?php echo $language['forms']['username']; ?>"/>
                        </div>
                        <div class="form-group" style="padding: 5px;">
                            <input type="password" name="pwd" class="form-control" placeholder="<?php echo $language['forms']['password']; ?>"/>
                        </div>
                        
                        <br>
                        
                        <div class="row">
                            <div class="col-lg-9 col-md-6 col-sm-3">
                                <div class="checkbox">
                                    <label>
                                        <?php echo $language['forms']['remember_me']; ?>
                                        <input type="checkbox" name="rememberme">
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-12">
                                <small><a href="cngpass" style="color: gray;"><?php echo $language['forms']['lost_password']; ?></a></small>
                            </div>
                        </div>

                        <br>
                        <div class="form-group">
                            <select name="language" class="form-control">
                                <?php foreach($languages as $language_name) {
                                    echo ' <option>' . $language_name . '</option>';
                                }?>
                            </select>
                        </div>
                        <br>
                        <br>
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-secondary col-12"><?php echo $language['forms']['sign_in']; ?></button>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<br>
