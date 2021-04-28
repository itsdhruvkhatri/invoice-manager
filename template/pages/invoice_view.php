<?php
//Fix error, make pdf version, make report work
User::check_permission(0);
$invoice_items = $database->query("SELECT * FROM `invoices` WHERE `invoice_id` = " . $_GET['invoice_id']);
$invoice_data = $invoice_items->fetch_object();

$user_dat = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $invoice_data->user_id);
$user_data = $user_dat->fetch_object();

$product_item = $database->query("SELECT * FROM `product_invoice` WHERE `invoice_id` = " . $_GET['invoice_id']);

$subtotal = array();

if (($user->user_id == $invoice_data->user_id && $invoice_data->status != "cancelled") && ($user->user_id == $invoice_data->user_id && $invoice_data->status != "draft") || $user->type > 1){
    
    
    if($user->user_id == $invoice_data->user_id && $invoice_data->form == "paypal"){
?>
<div class="float-right">
    <ul>
        <!--<li class="inline">
            <button class="send"><i class="fab fa-paypal"></i>&nbsp; <?php echo $language['invoice']['deposit']; ?></button>
        </li> -->
        <li class="inline">
            
            <form name="_xclick" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="<?php echo $settings->paypal_email; ?>">
                <input type="hidden" name="currency_code" value="<?php echo $settings->payment_currency; ?>">
                <input type="hidden" name="item_name" value="<?php echo $settings->page_title . " - " . $invoice_pfx . $invoice_data->invoice_id; ?>">
                <input type="hidden" name="amount" value="<?php echo $invoice_data->total_price; ?>">
                <input type="hidden" name="custom" value="<?php echo $invoice_data->invoice_id; ?>">
                <input type="hidden" name="notify_url" value="<?php echo $settings->url . 'template/core/processes/ipn.php'; ?>">
                <button class="send" onclick="this.form.submit()"><i class="fab fa-paypal"></i>&nbsp; <?php echo $language['invoice']['pay_now']; ?></button>
            </form>
        </li>
    </ul>
</div>
<br>
<br>
    <?php } ?>
<div class="light-nav">
    <h5 class="text-secondary inline"><span class="far fa-file-alt"></span>&nbsp;<?php echo $invoice_pfx . $invoice_data->invoice_id; ?></h5>
    <?php if ($invoice_data->status != "cancelled"){ ?>
    <ul class="inline float-right">
        <li class="inline">
            <span data-toggle="tooltip" title="<?php echo $language['misc']['report_invoice']; ?>" class="tooltipz float-right"><a href="invoices/report/<?php echo $invoice_data->invoice_id; ?>"><button class="btn" data-><span class="fa fa-bullhorn fa-lg"></span></button></a></span>
        </li>
        <li class="inline">
            <span data-toggle="tooltip" title="<?php echo $language['misc']['invoice_download']; ?>" class="tooltipz float-right"><a href="invoices/view/pdf/<?php echo $invoice_data->invoice_id; ?>"><button class="btn" data-><span class="far fa-file-pdf fa-lg"></span></button></a></span>
        </li>
        
        <?php if ($user->type > 1){ ?>
        <li class="dropdown inline">     
            <a href="#" data-toggle="dropdown">
                <span data-toggle="tooltip" title="<?php echo $language['misc']['invoice_options']; ?>" class="tooltipz float-right">
                    <button class="btn"><span class="fa fa-ellipsis-v fa-lg"></span></button>                    
                </span>
            </a>
            
            <div class="dropdown-menu">    
                
                <a class="dropdown-item" href="invoices/view/<?php echo $_GET['invoice_id']; ?>&invoice_status=draft">
                    <i class="fa fa-edit"></i> &nbsp; <?php echo $language['menu']['save_draft']; ?>
                </a>
                
                <a class="dropdown-item" href="invoices/view/<?php echo $_GET['invoice_id']; ?>&invoice_status=cancelled">
                    <i class="fa fa-ban"></i> &nbsp; <?php echo $language['menu']['mark_cancelled']; ?>
                </a>

                <a class="dropdown-item" href="invoices/edit/<?php echo $_GET['invoice_id']; ?>">
                    <i class="fa fa-pen"></i>&nbsp; <?php echo $language['menu']['invoice_update']; ?>
                </a>
                </div> 
        </li>
        <?php } ?>
    </ul>
    <?php } ?>
</div>
<div class="gray-nav">
    <div class="row">
        <div class="col">
            <h6><?php echo $language['invoice']['from']; ?></h6>
            <small><b><?php echo $settings->page_title; ?></b></small>
            <br>
            <small><i class="fa fa-building"></i>&nbsp;&nbsp;<?php echo $settings->address; ?></small>
            <br>
            <small><i class="fa fa-phone-alt"></i>&nbsp;&nbsp;<?php echo $settings->contact_number; ?></small>
        </div>
        <div class="col">
            <h6><?php echo $language['invoice']['to']; ?></h6>
            <small><i class="fa fa-user"></i>&nbsp;&nbsp;<?php echo $user_data->name . " " . $user_data->surname ?></small>
            <br>
            <small><b><?php echo (!empty($invoice_data->user_id)) ? $user_data->company_name : $invoice_data->company_name; ?></b></small>
            <br>
            <small><i class="fa fa-building"></i>&nbsp;&nbsp;<?php echo (!empty($invoice_data->user_id)) ? $user_data->address : $invoice_data->address; ?></small>
            <br>
            <small><i class="fa fa-phone-alt"></i>&nbsp;&nbsp;<?php echo (!empty($invoice_data->user_id)) ? $user_data->contact_number : $invoice_data->contact_number; ?></small>
        </div>
    </div>
</div>
<br>
<div class="container-fluid">
    <table class="table">
            
            <thead class="thead-light">
            <tr>               
                <th scope="col"><?php echo $language['invoice']['product']; ?></th>
                <th scope="col"><?php echo $language['invoice']['quantity']; ?></th>
                <th scope="col"><?php echo $language['forms']['product_price']; ?></th>
                <th scope="col"><?php echo $language['forms']['product_unit']; ?></th>               
                <th scope="col"><?php echo $language['invoice']['total']; ?></th>               
            </tr>
            </thead>            
            <tbody>
                <?php while($product_items = $product_item->fetch_object()){
                    $products = $database->query("SELECT * FROM `products` WHERE `product_id` = " . $product_items->product_id);
                    while($product_data = $products->fetch_object()){                         ?>                
                    <tr>
                        <td style="width:300px;"><?php echo $product_data->product_name; ?><br> <small><?php echo $product_data->description; ?></small></td>
                        <td><?php echo $product_items->qty; ?></td>
                        <td style="width: 150px;"><?php echo $currency_pfx . $product_data->price; ?></td>
                        <td><?php echo '/ ' . $product_data->unit; ?></td>
                        <td><?php echo $currency_pfx . calculate_price($product_data->price, $product_items->qty); ?></td>
                    </tr>
                                      
                <?php $subtotal[] = calculate_price($product_data->price, $product_items->qty);
                
                }                   
                
                    }?>
            </tbody>
        </table>
</div>
<hr>
<div class="light-nav">
    <div class="row">
        <div class="col">
            <h6 class="text-secondary"><b><?php echo $language['invoice']['sub_total']; ?></b></h6>
            <h5 class="text-right"><?php echo $currency_pfx . array_sum($subtotal); ?></h5>
            
        </div>
        <div class="col">
            <h6 class="text-secondary text-center">+</h6>
        </div>
        <div class="col">
            <h6 class="text-secondary"><b><?php echo $language['invoice']['tax']; ?></b></h6>
            <h5 class="text-right"><?php echo $settings->tax . " " .$invoice_data->unit; ?></h5>
        </div>
        <div class="col">
            <h6 class="text-secondary text-center">-</h6>
        </div>
        <div class="col">
            <h6 class="text-secondary"><b><?php echo $language['invoice']['discount']; ?></b></h6>
            <h5 class="text-right"><?php echo $invoice_data->discount . " " . $invoice_data->unit; ?></h5>
        </div>
        <div class="col">
            <h6 class="text-secondary text-center">=</h6>
        </div>
        <div class="col">
            <h6 class="text-secondary"><b><?php echo $language['invoice']['total']; ?></b></h6>
            <h5 class="text-right"><?php echo $currency_pfx . total_price($invoice_data->discount, $subtotal); $sums = total_price($invoice_data->discount, $subtotal); ?></h5>
        </div>
        
    </div>
</div>
<br>
<div class="light-nav">
    <pre><?php echo $settings->invoice_disclaimer; ?></pre>
</div>
<?php 
$database->query("UPDATE `invoices` SET `total_price` = ".total_price($invoice_data->discount, $subtotal)." WHERE `invoice_id` = ". $_GET['invoice_id']);


} else{
    $_SESSION['error'] = $language['errors']['command_denied'];
    redirect("invoices");
} ?>
