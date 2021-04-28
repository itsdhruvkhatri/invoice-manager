<?php
User::check_permission(3);

if(!empty($_POST['products_id'])){
    $products = $database->query("SELECT * `products` WHERE `product_id` = " . $_POST['products_id']);
    $product_details = $products->fetch_object();
}

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
        
        $due_date = date('Y-m-d', strtotime($_POST['due_date']));
        $date = new DateTime();
        $date = $date->format('Y-m-d H:i:s');
        $not_date = date('d-m-Y');
        $user_details = (user_select(0, $_POST['user_details']));
        $status = "draft";
        $type = "invoice";
        
                      
        $stmt = $database->prepare("INSERT INTO `invoices` (`invoice_id`, `user_id`, `discount`, `status`, `date`, `due_date`, `form`) VALUES (?, ?, ?, ?, ?, ?, ?) ");
        $stmt->bind_param('sssssss', $count_invoices, $user_details, $_POST['discount'], $status, $date, $due_date, $bank_type);
        $stmt->execute();
        $stmt->close();
        
        $stmt1 = $database->prepare("INSERT INTO `notifications` (`user_id`, `type`, `type_id`, `date`) VALUES (?, ?, ?, ?) ");
        $stmt1->bind_param('ssss', $user_details, $type, $count_invoices, $not_date);
        $stmt1->execute();
        $stmt1->close();
       
        $_SESSION['success'] = $language['errors']['add_invoice'];

        //sendmail($_POST['email'], $settings->email, $title, $message);
        redirect('invoices/edit/'. $count_invoices);
    }
}           
?>
<div class="light-nav">
    <h5 class="text-secondary inline"><span class="far fa-file-alt"></span>&nbsp;<?php echo $language['invoice']['create_invoice']; ?></h5>
</div>
<br>
<form action="" method="POST" role="form" name="add_product" id="add_product">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="user_datails"><?php echo $language['forms']['client']; ?></label>
                <select name="user_details" class="form-control">
                    <option value=""></option>
                    <?php user_select(1); ?>
                </select>
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
            <input type="date" name="due_date" class="form-control" />
        </div>
        <div class="col">
            <label for="discount"><?php echo $language['invoice']['discount']; ?></label>
            <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><?php echo "%"; ?></div>
                    </div>
            
                <input type="text" name="discount" id="discount" class="form-control" value="0"/>
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
    <div class="form-group float-right">
        <button type="button" name="add" id="add" class="btn btn-success btn-lg rounded-circle">+</button>
        <br>
        <Br>
    </div>

    <table class="table table-borderless">
        <thead>
            <tr>
                <th scope="col"><?php echo $language['invoice']['product']; ?></th>
                <th scope="col"><?php echo $language['invoice']['quantity']; ?></th>
                <th scope="col"><?php echo $language['forms']['product_price']; ?></th>
                <th scope="col"><?php echo $language['forms']['product_unit']; ?></th>
                <th scope="col"><?php echo $language['invoice']['total']; ?></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="dynamic_field">
        
        </tbody>
    </table>

    <br>
    <div class="form-group">
        <button class="btn btn-secondary" type="submit" id="submit" name="submit"><?php echo $language['invoice']['create_invoice'];?></button>
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
            <h5 class="text-right" id="discount_tab">0%</h5>
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
    discount[0] = 0;
    var tax = "<?php echo $settings->tax; ?>";

    $('#add').click(function(){
            i++;

            $('#dynamic_field').append('<tr id="row'+i+'"><td class="form-group"><select name="product_name[]" class="form-control" id="products"><?php get_products(); ?></select></td><td class="form-group"><input type="text" name="product_qty[]" id="get_qty" placeholder="" class="form-control" />               </td><td id="product_price'+i+'"><input type="text" value="" class="form-control" readonly/></td><td id="product_unit'+i+'"><input type="text" value="" class="form-control" readonly/></td><td id="product_total'+i+'"><input type="text" value="" class="form-control" readonly/></td><td class="form-group"><button type="button" name="remove" id="'+i+'"  class="btn btn-danger btn_remove">X</button></td></tr>');
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
        var tax_work = tot * (tax / 100);
        var disc_work = tot * (discount[0] / 100);
        var total = (tot + tax_work) - disc_work;
        $('#total_tab').html("<?php echo $currency_pfx; ?>"+parseFloat(total).toFixed(2));
    });

    $(document).on('blur', '#get_qty', function(){  
        var price = document.getElementsByName('product_price')[i-1].value;          
        $('#product_total'+i+'').load("prodtotal/"+this.value+"/"+price).appendTo();

        var sum = price * this.value;           
        sub_total[i]=(sum);

        var tot = sub_total.reduce(function(a, b){
            return a + b;
        }, 0);

        $('#subtotal_tab').load("subtotal/"+tot).appendTo();

        var tax_work = tot * (tax / 100);
        var disc_work = tot * (discount[0] / 100);
        var total = (tot + tax_work) - disc_work;
        $('#total_tab').html("<?php echo $currency_pfx; ?>"+parseFloat(total).toFixed(2));
    });


    $(document).on('click', '.btn_remove', function(){
            var button_id = $(this).attr("id"); 
            $('#row'+button_id+'').remove();
    });

    $('#submit').click(function(){		
            $.ajax({
                    url:"submitproduct",
                    method:"POST",
                    data:$('#add_product').serialize()			
            });
    });
	
});


</script>
