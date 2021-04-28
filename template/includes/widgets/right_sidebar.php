<?php if (($_GET['page'] == "report_view" && $report_dets->status != "D") || $_GET['page'] == "client_view" || ($_GET['page'] == "invoice_view" && User::get_type($user->user_id) > 1)){ ?>
<div class="col col-lg-2 grey-nav">
    <br>
    
    <?php if ($_GET['page'] == "report_view"){
        /* Report view page */
        if(!empty($_POST['reply'])){
            $_POST['reply'] = filter_var($_POST['reply'], FILTER_SANITIZE_STRING);

            if(strlen(trim($_POST['reply'])) > 2560){
                $_SESSION['error'] = $language['errors']['reply_limit'];
            }

            if(empty($_SESSION['error'])){
                
                
                
                $invoice = $database->query("SELECT * FROM `invoices` WHERE `invoice_id` = " . $report_dets->invoice_id);
                $invoice_data = $invoice->fetch_object();
                
                $date = new DateTime();
                $date = $date->format('D d-M-Y H:i:s');
                $notification_status = "reply";

                $stmt = $database->prepare("INSERT INTO `comments` (`report_id`, `comment`, `user_id`, `date`) VALUES (?, ?, ?, ?) ");
                $stmt->bind_param('ssss', $_GET['report_id'], $_POST['reply'], $user->user_id, $date);
                $stmt->execute();
                $stmt->close();
                
                if ($user->type >=3){
                    $database->query("UPDATE `reports` SET `status` = 'R' WHERE `report_id` = " . $_GET['report_id']);
                }
                if ($user->user_id == $invoice_data->user_id){
                    $database->query("UPDATE `reports` SET `status` = 'U' WHERE `report_id` = " . $_GET['report_id']);
                }
                
                if($_SESSION['report_user'] != $_SESSION['user_id']){
                    $stmt2 = $database->prepare("INSERT INTO `notifications` (`user_id`, `type`, `type_id`, `date`) VALUES (?, ?, ?, ?)");
                    $stmt2->bind_param('ssss', $_SESSION['report_user'], $notification_status, $_GET['report_id'], $date);
                    $stmt2->execute();
                    $stmt2->close();
                }

                $_SESSION['success'] = $language['errors']['reply_success'];
                redirect('reports/view/' . $_GET['report_id']);
            }
        }

        if(!empty($_POST['status'])){
            if($_POST['status'] == "D"){
                $database->query("UPDATE `reports` SET `status` = 'D' WHERE `report_id` = " . $_GET['report_id']);
                $_SESSION['success'] = sprintf($language['errors']['update_success'], $language['status']['done']);
                redirect('reports/view/' . $_GET['report_id']);
            }
        }
            ?>
        <h5><?php echo $language['forms']['reply']; ?></h5>
        <br>
        <form action="" method="POST" role="form">
            <div class="form-group" style="padding: 5px;">
                <textarea name="reply" class="form-control" rows="4" placeholder="<?php echo $language['forms']['add_reply']; ?>"></textarea>         
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-secondary"><?php echo $language['forms']['reply']; ?></button>                           
            </div>
        </form>
        <br>
        <hr>
        <br>
        <?php if($user->type >= 3){ ?>
        <form action="" method="POST" role="form">
            <div class="form-group" style="padding: 5px;">
                <button type="submit" name="status" value="D" class="btn btn-secondary" onchange="this.form.submit()"><?php echo $language['forms']['set_status']; ?></button>                           
            </div>
        </form>
        <?php }
    }
    
    if ($_GET['page'] == "client_view"){
        /* Client view page */
        if(!empty($_POST['comment'])){
            $_POST['comment'] = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

            if(strlen(trim($_POST['comment'])) > 1024){
                $_SESSION['error'] = $language['errors']['comment_limit'];
            }

            if(empty($_SESSION['error'])){

                $stmt = $database->prepare("UPDATE `users` SET `comments` = ? WHERE `user_id` = " . $_GET['client_id']);
                $stmt->bind_param('s', $_POST['comment']);
                $stmt->execute();
                $stmt->close();                   

                $_SESSION['success'] = $language['errors']['comment_success'];
                redirect('client/' . $_GET['client_id']);
            }
        }
        
        if(!empty($_POST['role'])){
            $database->query("UPDATE `users` SET `type` = ". $_POST['role'] . " WHERE `user_id` = " . $user_data->user_id);
            redirect('client/' . $_GET['client_id']);
        }
        if($user->type >= 3){ ?>
            <h5 class="text-center"><?php echo $language['misc']['comments']; ?></h5>
            <br>
            <form action="" method="POST" role="form">
                <div class="form-group" style="padding: 5px;">
                    <textarea name="comment" class="form-control" rows="4" placeholder="<?php echo $language['forms']['add_comment']; ?>"></textarea>         
                </div>
                <div class="form-group text-center">
                    <button type="submit" name="submit" class="btn btn-secondary"><?php echo $language['forms']['comment']; ?></button>                           
                </div>
            </form>
        <?php }
        
        if ($user->type == 4){ ?>
            <br/>
            <form action="" method="post" role="form">
                <div class="form-group">
                    <label for="role"><?php echo $language['misc']['user_type']; ?></label>
                    <input type="text" name="role" value="<?php echo $user_data->type; ?>" />
                </div>
                <p><kbd>0</kbd> - Client</p>
                <p><kbd>1</kbd> - Store Manager</p>
                <p><kbd>2</kbd> - Accountant</p>
                <p><kbd>3</kbd> - Manager</p>
                <p><kbd>4</kbd> - Founder</p>
                <button type="submit" class="btn btn-secondary" name="submit"><?php echo $language['misc']['user_type_change']; ?></button>
            </form>
            
        <?php }

    }
    if ($_GET['page'] == "invoice_view"){ 
        /* Invoice view page */
        
        if(!empty($_POST)){  
            if(!empty($invoice_data->user_id)){
                if(isset($_POST['client'])){

                    $title = $language['emails']['invoice_title'];
                    $message = "Hi " . $user_data->name . ",<br>";
                    $message .= $language['emails']['invoice_message'] . "<br>";
                    $message .= sprintf($language['emails']['invoice_message_view'] . " ", "<a href=\"" .$settings->url . "invoices/view/" . $_GET['invoice_id']. "\">Invoice</a>") . "<br>";
                    $message .= "Thanks,<br>";
                    $message .= $settings->page_title;

                    sendmail($user_data->username, $settings->contact_email, $title, $message);
                }
                
                if(isset($_POST['payment'])){

                    $title = $language['emails']['payment_title'];
                    $message = "Hi " . $user_data->name . ",<br>";
                    $message .= $language['emails']['pay_not_message'] . "<br>";
                    $message .= sprintf($language['emails']['invoice_message_view'] . " ", "<a href=\"" .$settings->url . "invoices/view/" . $_GET['invoice_id']. "\">Invoice</a>") . "<br>";
                    $message .= "Thanks,<br>";
                    $message .= $settings->page_title;

                    sendmail($user_data->username, $settings->contact_email, $title, $message);
                }
            }
            
            if(!empty($_POST['email'])){
                $_POST['email']	= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                
                if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
                    $_SESSION['error'] = $language['errors']['invalid_email'];
                }
                if(empty($_SESSION['error'])){
                    
                    $title = $language['emails']['invoice_title'];
                    $message = "Hi,<br>";
                    $message .= $language['emails']['invoice_message'] . "<br>";
                    $message .= sprintf($language['emails']['invoice_message_view'] . " ", "<a href=\"" .$settings->url . "invoices/view/" . $_GET['invoice_id']. "\">".$invoice_pfx.$_GET['invoice_id']."</a>") . "<br>";
                    $message .= $language['emails']['invoice_status'] . $invoice_data->status . "<br><br>";
                    $message .= "Thanks,<br>";
                    $message .= $settings->page_title;

                    sendmail($_POST['email'], $settings->contact_email, $title, $message);                
                }
            }
        }
        ?>
            <h4><?php echo $invoice_data->status; ?></h4>
            <br>
            <div class="row">
                <?php if ($invoice_data->status == "unpaid"){ ?>
                <div class="col button-gray text-center">
                    <br>
                    <a href="invoices/view/<?php echo $_GET['invoice_id']; ?>&invoice_status=draft"><small><?php echo $language['menu']['save_draft']; ?></small></a>
                </div>
                <?php }
                if ($invoice_data->status == "draft"){?>
                <div class="col button-gray text-center">
                    <a href="invoices/view/<?php echo $_GET['invoice_id']; ?>&invoice_status=unpaid"><small><?php echo $language['menu']['remove_draft']; ?></small></a>
                </div>
                
                <?php } ?>
                
         <?php     if(!empty($invoice_data->user_id) && $invoice_data->status != "draft"){ ?>    
                <div class="col send">
                    <form action="" method="post" role="form">
                        <button type="submit" name="client" class="btn" style="padding: 10px;">
                            <span class="fa fa-envelope"></span>&nbsp; <small><?php echo sprintf($language['misc']['invoice_email_client'], $user_data->name ); ?></small>
                        </button>
                    </form>
                </div>
       
        <?php } ?>
            </div>
            <br>
            <hr>
            <br>
            
            <?php if(!empty($invoice_data->user_id) && $invoice_data->status != "paid"){ ?>
            <form action="" method="post" role="form">
                <div class="button-warning text-center">                   
                        <button type="submit" name="payment" class="btn button-warning" style="padding: 10px;">
                            <span class="fas fa-money-bill-wave"></span>&nbsp; <small><?php echo $language['menu']['payment_notification']; ?></small>
                        </button>
                </div>
                </form>
            <?php } ?>
            <br/>
            <hr/>
            <br/>
            
        <?php /* if ($invoice_data->status == "unpaid"){ ?>
            <form action="" method="post" role="form">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="<?php echo $language['forms']['username'];?>"/>
                </div>
                <button type="submit" name="email_client" class="btn btn-secondary">
                    <span class="fa fa-envelope"></span>&nbsp; <small><?php echo $language['misc']['invoice_email']; ?></small>
                </button>
            </form> 
            <br>
            <hr>
    <?php } */
            if(!empty($invoice_data->user_id)){ ?>
            <p><i class="fa fa-user"></i>&nbsp;&nbsp;&nbsp; <b><?php echo $user_data->name . " " . $user_data->surname; ?></b></p>
            <small>(<?php echo $user_data->username; ?>)</small>
            <hr>
          <?php  } ?>
            <p class="text-danger"><i class="fa fa-bell"></i>&nbsp;&nbsp;&nbsp; <?php due_date($invoice_data->due_date); ?></p>
            <hr>
            <p><i class="fa fa-cash-register"></i>&nbsp;&nbsp;&nbsp; <?php echo $invoice_data->form; if($invoice_data->form == "cash" && $invoice_data->status == "unpaid"){?><a href="invoices/view/<?php echo $_GET['invoice_id']; ?>&invoice_status=paid" class="float-right"><button class="btn btn-sm btn-success"><?php echo $language['misc']['mark_paid']; ?></button></a><?php } ?></p>

            <hr>
       <?php }?>
</div>
<?php } ?>