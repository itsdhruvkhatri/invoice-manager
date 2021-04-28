<?php
User::check_permission(3);

if(!empty($_POST['products_id'])){
    $products = $database->query("SELECT * `products` WHERE `product_id` = " . $_POST['products_id']);
    $product_details = $products->fetch_object();
}

$invoices = $database->query("SELECT * FROM `invoices` WHERE `invoice_id` = " . $_GET['invoice_id']);
$invoice_dats = $invoices->fetch_object();

$user_id = $invoice_dats->user_id;

$user = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $user_id);
$user_dets = $user->fetch_object();

$product_item = $database->query("SELECT * FROM `product_invoice` WHERE `invoice_id` = " . $_GET['invoice_id']);

$subtotal = array();
if (!empty($_POST)){
    

    if(empty($_SESSION['error'])){
       
        if(!empty($_POST['bank_type'])){
            if($_POST['bank_type'] == "paypal"){
                $bank_type = "paypal";
            }
            if($_POST['bank_type'] == "cash"){
                $bank_type = "cash";
            }
        }
        
        $due_date = date('d-m-Y', strtotime($_POST['due_date']));       
                      
        $stmt = $database->prepare("UPDATE `invoices` SET `discount` = ? , `due_date` = ? , `form` = ? WHERE `invoice_id` = " . $_GET['invoice_id']);
        $stmt->bind_param('sss', $_POST['discount'], $due_date, $bank_type);
        $stmt->execute();
        $stmt->close();
       
        $_SESSION['success'] = $language['errors']['edit_invoice'];

        //sendmail($_POST['email'], $settings->email, $title, $message);
        redirect('invoices/view/'. $_GET['invoice_id']);
    }
}           
?>
<div class="light-nav">
    <h5 class="text-secondary inline"><span class="far fa-file-alt"></span>&nbsp;<?php echo $language['invoice']['update_invoice'] . " <b>" . $invoice_pfx . $_GET['invoice_id']. "</b>"; ?></h5>
</div>
<br>
<form action="" method="POST" role="form" name="edit_product" id="edit_product">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="user_datails"><?php echo $language['forms']['client']; ?></label>
                <br>
                <input class="form-control" type="text" placeholder="<?php echo $user_dets->name . " " . $user_dets->surname; ?>" readonly/>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="bank_type"><?php echo $language['forms']['payment_type']; ?></label>
                <select name="bank_type" class="form-control">
                    <option value="paypal">Paypal</option>
                    <option value="cash">Cash</option>
                </select>
            </div>
        </div>
        <div class="col">
            <label for="due_date"><?php echo $language['forms']['due_date']; ?></label>
            <input type="date" name="due_date" class="form-control" value="<?php echo $invoice_dats->due_date; ?>"/>
        </div>
        <div class="col">
            <label for="discount"><?php echo $language['invoice']['discount']; ?></label>
            <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><?php echo "%"; ?></div>
                    </div>
            
                <input type="text" name="discount" id="discount" class="form-control" value="<?php echo $invoice_dats->discount; ?>"/>
            </div>
        </div>
    </div>
    <hr>
    <br>
    
<!--    <div class="gray-nav">
        <h6 class="text-danger"><small><?php echo "*". $language['invoice']['section_message']; ?></small></h6>
        <Br>
        <div class="row">
            <div class="col">
                <label for="first_name"><?php echo $language['forms']['first_name']; ?></label>
                <input type="text" name="first_name" class="form-control" />
            </div>
            <div class="col">
                <label for="last_name"><?php echo $language['forms']['last_name']; ?></label>
                <input type="text" name="last_name" class="form-control" />
            </div>

            <div class="col">
                <label for="company_name"><?php echo $language['forms']['company_name']; ?></label>
                <input type="text" name="company_name" class="form-control" />
            </div>
            <div class="col">
                <label for="address"><?php echo $language['forms']['company_address']; ?></label>
                <input type="text" name="address" class="form-control" />
            </div>
            <div class="col">
                <label for="contact_number"><?php echo $language['forms']['contact_number']; ?></label>
                <input type="text" name="contact_number" class="form-control" />
            </div>
        </div>
    </div>
    <br>-->
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
                $sum = array_sum($subtotal);                
                }                   
                
                    }?>
            </tbody>
        </table>
    <div class="form-group float-right">
        <button type="button" name="edit" id="edit" class="btn btn-success btn-lg rounded-circle">+</button>
        <br>
        <Br>
    </div>



    <table class="table table-borderless">

        <tbody id="dynamic_field">

        </tbody>
    </table>
    <input type="text" name="invoices_id" value="<?php echo $_GET['invoice_id']; ?>" hidden/>
    <br>
    <div class="form-group">
        <button class="btn btn-secondary" type="submit" id="submit" name="submit"><?php echo $language['invoice']['update_invoice'];?></button>
    </div>
</form>
<br>
<hr>
<div class="light-nav">
    <div class="row" id="calc_total">
        <div class="col">
            <h6 class="text-secondary"><b><?php echo $language['invoice']['sub_total']; ?></b></h6>
            <h5 class="text-right" id="subtotal_tab"><?php echo $currency_pfx . "0"; ?></h5>
            
        </div>
        <div class="col">
            <h6 class="text-secondary text-center">+</h6>
        </div>
        <div class="col">
            <h6 class="text-secondary"><b><?php echo $language['invoice']['tax']; ?></b></h6>
            <h5 class="text-right"><?php echo $settings->tax . " " . "%"; ?></h5>
        </div>
        <div class="col">
            <h6 class="text-secondary text-center">-</h6>
        </div>
        <div class="col">
            <h6 class="text-secondary"><b><?php echo $language['invoice']['discount']; ?></b></h6>
            <h5 class="text-right" id="discount_tab"><?php echo $invoice_dats->discount; ?>%</h5>
        </div>
        <div class="col">
            <h6 class="text-secondary text-center">=</h6>
        </div>
        <div class="col">
            <h6 class="text-secondary"><b><?php echo $language['invoice']['total']; ?></b></h6>
            <h5 class="text-right" id="total_tab"><?php echo $currency_pfx . "0"; ?></h5>
        </div>
        
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    var i=0;
    var sub_total= new Array();
    var discount = new Array();
    
    discount[0] = "<?php echo $invoice_dats->discount; ?>";
    var tax = "<?php echo $settings->tax; ?>";
    var old_sub = "<?php echo $sum; ?>";

    $('#edit').click(function(){
            i++;

            $('#dynamic_field').append('<tr id="row'+i+'"><td class="form-group"><select name="product_name_edit[]" class="form-control" id="products"><?php get_products(); ?></select></td><td class="form-group"><input type="text" name="product_qty_edit[]" id="get_qty" placeholder="" class="form-control" />               </td><td id="product_price'+i+'"><input type="text" value="" class="form-control" readonly/></td><td id="product_unit'+i+'"><input type="text" value="" class="form-control" readonly/></td><td id="product_total'+i+'"><input type="text" value="" class="form-control" readonly/></td><td class="form-group"><button type="button" name="remove" id="'+i+'"  class="btn btn-danger btn_remove">X</button></td></tr>');
    });

    $(document).on('change', '#products', function(){  
        $('#product_price'+i+'').load("prodprice/"+this.value).appendTo();
        $('#product_unit'+i+'').load("produnit/"+this.value).appendTo();          
    });

    $(document).on('blur', '#discount', function(){              
        $('#discount_tab').load("discount/"+this.value).appendTo();	
        discount[0] = this.value;
        var tot = sub_total.reduce(function(a, b){
            return a + b;
        }, 0);
        
        var tota = parseFloat(tot) + parseFloat(old_sub);
        var tax_work = tota * (tax / 100);
        var disc_work = tota * (discount[0] / 100);
        var total = (tota + tax_work) - disc_work;
        $('#total_tab').html("<?php echo $currency_pfx; ?>"+total);
    });

    $(document).on('blur', '#get_qty', function(){  
        var price = document.getElementsByName('product_price')[i-1].value;          
        $('#product_total'+i+'').load("prodtotal/"+this.value+"/"+price).appendTo();

        var sum = price * this.value;           
        sub_total[i]=(sum);

        var tot = sub_total.reduce(function(a, b){
            return a + b;
        }, 0);
        var tota = parseFloat(tot) + parseFloat(old_sub);

        $('#subtotal_tab').load("subtotal/"+tota).appendTo();

        
        var tax_work = tota * (tax / 100);
        var disc_work = tota * (discount[0] / 100);
        var total = (tota + tax_work) - disc_work;
        $('#total_tab').html("<?php echo $currency_pfx; ?>"+total);
    });


    $(document).on('click', '.btn_remove', function(){
            var button_id = $(this).attr("id"); 
            $('#row'+button_id+'').remove();
    });

    $('#submit').click(function(){		
            $.ajax({
                    url:"editproduct",
                    method:"POST",
                    data:$('#edit_product').serialize()			
            });
    });
	
});


</script>
