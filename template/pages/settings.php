<?php
User::check_permission(4);

if(!empty($_POST)){
    $_POST['page_title'] = filter_var($_POST['page_title'], FILTER_SANITIZE_STRING);
    $_POST['meta_description'] = filter_var($_POST['meta_description'], FILTER_SANITIZE_STRING);
    $_POST['analytics_code'] = filter_var($_POST['analytics_code'], FILTER_SANITIZE_STRING);
    $_POST['invoice_disclaimer'] = filter_var($_POST['invoice_disclaimer'], FILTER_SANITIZE_STRING);
    $_POST['pagination'] = filter_var($_POST['pagination'], FILTER_SANITIZE_NUMBER_INT);
    $_POST['contact_email'] = filter_var($_POST['contact_email'], FILTER_SANITIZE_EMAIL);
    $_POST['paypal_email'] = filter_var($_POST['paypal_email'], FILTER_SANITIZE_EMAIL);
    $_POST['payment_currency'] = filter_var($_POST['payment_currency'], FILTER_SANITIZE_STRING);
    $_POST['currency_pfx'] = filter_var($_POST['currency_pfx'], FILTER_SANITIZE_STRING);
    $_POST['tax'] = filter_var($_POST['tax'], FILTER_SANITIZE_NUMBER_INT);
    $_POST['contact_number'] = filter_var($_POST['contact_number'], FILTER_SANITIZE_STRING);
    $_POST['address'] = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $_POST['report_pfx'] = filter_var($_POST['report_pfx'], FILTER_SANITIZE_STRING);
    $_POST['invoice_pfx'] = filter_var($_POST['invoice_pfx'], FILTER_SANITIZE_STRING);
    $_POST['payment_pfx'] = filter_var($_POST['payment_pfx'], FILTER_SANITIZE_STRING);
    $_POST['client_pfx'] = filter_var($_POST['client_pfx'], FILTER_SANITIZE_STRING);
    $_POST['product_pfx'] = filter_var($_POST['product_pfx'], FILTER_SANITIZE_STRING);
    $logo = (empty($_FILES['logo']['name']) == false) ? true : false;
    $favicon = (empty($_FILES['favicon']['name']) == false) ? true : false;

    if(empty($_POST['page_title'])){
        $_SESSION['error'] = $language['errors']['empty_field_title'];
    }
    if(filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL) == false) {
		$_SESSION['error'] = $language['errors']['invalid_email'];
	}
    if(strlen(trim($_POST['meta_description'])) > 1024){
        $_SESSION['error'] = $language['errors']['meta_limit'];
    }
    if(strlen(trim($_POST['invoice_disclaimer'])) > 2560){
        $_SESSION['error'] = $language['errors']['disclaimer_limit'];
    }
    
    if($favicon == true) {
        $favicon_file_name		= $_FILES['favicon']['name'];
        $favicon_file_extension	= explode('.', $favicon_file_name);
        $favicon_file_extension	= strtolower(end($favicon_file_extension));
        $favicon_file_temp		= $_FILES['favicon']['tmp_name'];
        $favicon_file_size		= $_FILES['favicon']['size'];
        list($favicon_width, $favicon_height)	= getimagesize($favicon_file_temp);            
    }
    
    if($logo == true) {
        $logo_file_name		= $_FILES['logo']['name'];
        $logo_file_extension	= explode('.', $logo_file_name);
        $logofile_extension	= strtolower(end($logo_file_extension));
        $logo_file_temp		= $_FILES['logo']['tmp_name'];
        $logo_file_size		= $_FILES['logo']['size'];
        list($logo_width, $logo_height)	= getimagesize($logo_file_temp);
              
    }
        
        
    
    if(empty($_SESSION['error'])){
        
        //Update logos
        if($logo == true) {

            /* Delete current image & thumbnail */
            @unlink('template/images/'.$settings->logo);

            /* Generate new name for image */
            $logo_new_name = md5(time().rand()) . '.' . $logo_file_extension[1];

            /* Resize & upload the image */
            if($logo_width != '50' || $logo_height != '50') {
                    resize($logo_file_temp, 'template/images/' . $logo_new_name, '50', '50');
            } else {
                    move_uploaded_file($logo_file_temp, 'template/images/' . $logo_new_name);	
            }

            /* Execute query */
            $database->query("UPDATE `settings` SET `logo` = '{$logo_new_name}' WHERE `id` = 1");
        }

        if($favicon == true) {

            /* Delete current image & thumbnail */
            @unlink('template/images/'.$settings->favicon);

            /* Generate new name for image */
            $favicon_new_name = md5(time().rand()) . '.' . $favicon_file_extension[1];

            /* Resize & upload the image */
            if($favicon_width != '16' || $favicon_height != '16') {
                    resize($favicon_file_temp, 'template/images/' . $favicon_new_name, '16', '16');
            } else {
                    move_uploaded_file($favicon_file_temp, 'template/images/' . $favicon_new_name);	
            }

            /* Execute query */
            $database->query("UPDATE `settings` SET `favicon` = '{$favicon_new_name}' WHERE `id` = 1");
        }
        
        $stmt = $database->prepare("UPDATE `settings` SET `page_title` = ?, `contact_email` = ?, `meta_description` = ?, `invoice_disclaimer` = ?, `per_page_pagination` = ?, `analytics_code` = ?, `paypal_email` = ?, `payment_currency` = ?, `report_pfx` = ? , `invoice_pfx` = ?, `payment_pfx` = ?, `currency_pfx` = ?, `client_pfx` = ?, `product_pfx` = ?, `tax` = ?, `address` = ?, `contact_number` = ? WHERE `id` = 1");
        $stmt->bind_param('sssssssssssssssss', $_POST['page_title'], $_POST['contact_email'], $_POST['meta_description'], $_POST['invoice_disclaimer'], $_POST['pagination'], $_POST['analytics_code'], $_POST['paypal_email'], $_POST['payment_currency'], $_POST['report_pfx'], $_POST['invoice_pfx'], $_POST['payment_pfx'], $_POST['currency_pfx'], $_POST['client_pfx'], $_POST['product_pfx'], $_POST['tax'], $_POST['address'], $_POST['contact_number']);
        $stmt->execute();
        $stmt->close();
        
        $_SESSION['success'] = $language['errors']['settings_updated'];
        redirect('settings');
    }
}
?>
<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" href="#main" data-toggle="tab" role="tab" aria-controls="main" aria-selected="true"><?php echo $language['forms']['main_settings']; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="#invoice" data-toggle="tab" role="tab" aria-controls="invoice" aria-selected="false"><?php echo $language['forms']['invoice_settings']; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="#payment" data-toggle="tab" role="tab" aria-controls="payment" aria-selected="false"><?php echo $language['forms']['payment_settings']; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="#prefix" data-toggle="tab" role="tab" aria-controls="prefix" aria-selected="false"><?php echo $language['forms']['prefix_settings']; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="#logo" data-toggle="tab" role="tab" aria-controls="logo" aria-selected="false"><?php echo $language['forms']['logos']; ?></a></li>
            <li class="nav-item"><a class="nav-link" href="#details" data-toggle="tab" role="tab" aria-controls="details" aria-selected="false"><?php echo $language['menu']['details']; ?></a></li>
        </ul>

        <form action="" method="post" role="form" enctype="multipart/form-data">
                <div class="tab-content">
                    <br>
                    <div class="tab-pane fade show active" id="main">
                        <div class="form-group">
                            <label><?php echo $language['forms']['page_title']; ?></label>
                            <input type="text" name="page_title" class="form-control" placeholder="<?php echo $language['forms']['page_title']; ?>" value="<?php echo $settings->page_title; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $language['forms']['contact_email']; ?></label>
                            <input type="text" name="contact_email" class="form-control" placeholder="<?php echo $language['forms']['contact_email']; ?>" value="<?php echo $settings->contact_email; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $language['forms']['meta_description']; ?></label>
                            <input type="text" name="meta_description" class="form-control" placeholder="<?php echo $language['forms']['meta_description']; ?>" value="<?php echo $settings->meta_description; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $language['forms']['pagination']; ?></label>
                            <input type="text" name="pagination" class="form-control" placeholder="<?php echo $language['forms']['pagination']; ?>" value="<?php echo $settings->per_page_pagination; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $language['forms']['analytics_code']; ?></label>
                            <input type="text" name="analytics_code" class="form-control" placeholder="<?php echo $language['forms']['analytics_code']; ?>" value="<?php echo $settings->analytics_code; ?>" />
                        </div>
                        <br>
                        <div class="form-group">
                            <button class="btn btn-secondary" type="submit" name="sumbit"><?php echo $language['forms']['save_settings'];?></button>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="invoice">

                        <div class="form-group">
                            <label><?php echo $language['invoice']['tax']; ?></label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><?php echo "%"; ?></div>
                                </div>

                                <input type="text" name="tax" class="form-control" placeholder="<?php echo $language['invoice']['tax']; ?>" value="<?php echo $settings->tax; ?>" />
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $language['forms']['invoice_disclaimer']; ?></label>
                            <textarea name="invoice_disclaimer" class="form-control" placeholder="<?php echo $language['forms']['invoice_disclaimer']; ?>" value=""><?php echo $settings->invoice_disclaimer; ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $language['forms']['address']; ?></label>
                            <input type="text" name="address" class="form-control" placeholder="<?php echo $language['forms']['address']; ?>" value="<?php echo $settings->address; ?>"/>
                        </div>
                        
                        <div class="form-group">
                            <label><?php echo $language['forms']['contact_number']; ?></label>
                            <input type="text" name="contact_number" class="form-control" placeholder="<?php echo $language['forms']['contact_number']; ?>" value="<?php echo $settings->contact_number; ?>"/>
                        </div>
                        <br>
                        <div class="form-group">
                            <button class="btn btn-secondary" type="submit" name="sumbit"><?php echo $language['forms']['save_settings'];?></button>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="payment">
                        <div class="form-group">
                            <label><?php echo $language['forms']['paypal_email']; ?></label>
                            <input type="text" name="paypal_email" class="form-control" placeholder="<?php echo $language['forms']['paypal_email']; ?>" value="<?php echo $settings->paypal_email; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $language['forms']['payment_currency']; ?></label>
                            <input type="text" name="payment_currency" class="form-control" placeholder="<?php echo $language['forms']['payment_currency']; ?>" value="<?php echo $settings->payment_currency; ?>" />
                        </div>
                        <br>
                        <div class="form-group">
                            <button class="btn btn-secondary" type="submit" name="sumbit"><?php echo $language['forms']['save_settings'];?></button>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="prefix">
                        <div class="form-group">
                            <label><?php echo $language['forms']['report_prefix']; ?></label>
                            <input type="text" name="report_pfx" class="form-control" placeholder="<?php echo $language['forms']['report_prefix']; ?>" value="<?php echo $settings->report_pfx; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $language['forms']['product_prefix']; ?></label>
                            <input type="text" name="product_pfx" class="form-control" placeholder="<?php echo $language['forms']['product_prefix']; ?>" value="<?php echo $settings->product_pfx; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $language['forms']['user_prefix']; ?></label>
                            <input type="text" name="client_pfx" class="form-control" placeholder="<?php echo $language['forms']['user_prefix']; ?>" value="<?php echo $settings->client_pfx; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $language['forms']['invoice_prefix']; ?></label>
                            <input type="text" name="invoice_pfx" class="form-control" placeholder="<?php echo $language['forms']['invoice_prefix']; ?>" value="<?php echo $settings->invoice_pfx; ?>" />
                        </div>
                        <div class="form-group">
                            <label><?php echo $language['forms']['payment_prefix']; ?></label>
                            <input type="text" name="payment_pfx" class="form-control" placeholder="<?php echo $language['forms']['payment_prefix']; ?>" value="<?php echo $settings->payment_pfx; ?>" />
                        </div>
                  
                        <div class="form-group">
                            <label><?php echo $language['forms']['currency_prefix']; ?></label>
                            <input type="text" name="currency_pfx" class="form-control" placeholder="<?php echo $language['forms']['currency_prefix']; ?>" value="<?php echo $settings->currency_pfx; ?>" />
                        </div>
                        <br>
                        <div class="form-group">
                            <button class="btn btn-secondary" type="submit" name="sumbit"><?php echo $language['forms']['save_settings'];?></button>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="logo">              
                        <div class="form-group">
                            <label><?php echo $language['forms']['logo']; ?></label>
                            <img src="template/images/<?php echo $settings->logo; ?>" style="width: 50px; height: 50px;" alt="Logo"/>
                            <br>
                            <br>
                            <input type="file" name="logo" class="form-control" accept=".png, .jpeg, .jpg, .gif" />
                        </div>
                        <br>
                        <div class="form-group">
                            <label><?php echo $language['forms']['favicon']; ?></label>
                            <img src="template/images/<?php echo $settings->favicon; ?>" style="width: 30px; height: 30px;" alt="Favicon"/>
                            <br>
                            <br>
                            <input type="file" name="favicon" class="form-control" accept=".png, .jpeg, .gif, .jpg" />
                        </div>
                        <br>
                        <div class="form-group">
                            <button class="btn btn-secondary" type="submit" name="sumbit"><?php echo $language['forms']['save_settings'];?></button>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="details">              
                        <div class="form-group">
                            <p class="inline"><?php echo $language['forms']['version']; ?></p>
                            <h6 class="inline">&nbsp;&nbsp;<?php echo "V" . $version; ?></h6>
                        </div>
                        <br>
                        <div class="form-group">
                            <p class="inline"><?php echo $language['forms']['url'] . ":"; ?></p>
                            <h6 class="inline">&nbsp;&nbsp;<?php echo $settings->url; ?></h6>
                        </div>
                    </div>

                </div>
            
            <br>
            
        </form>
    </div>
</div>