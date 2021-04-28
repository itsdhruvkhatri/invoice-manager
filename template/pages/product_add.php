<?php
User::check_permission(1);

if (!empty($_POST)){
   
    $product_name	= filter_var($_POST['product_name'], FILTER_SANITIZE_STRING);
    $product_price	= filter_var($_POST['product_price'], FILTER_SANITIZE_STRING);
    $product_unit      = filter_var($_POST['product_unit'], FILTER_SANITIZE_STRING);
    $product_description = filter_var($_POST['product_description'], FILTER_SANITIZE_STRING);
    $image = (empty($_FILES['image']['name']) == false) ? true : false;

        
    if(strlen(trim($_POST['product_description'])) > 1024){
        $_SESSION['error'] = $language['errors']['description_limit'];
    }
        

    if(empty($_SESSION['error'])){

        $stmt = $database->prepare("INSERT INTO `products` (`product_name`, `description`, `price`, `unit`) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $product_name, $product_description, $product_price, $product_unit);
        $stmt->execute();
        $stmt->close();
        
        $_SESSION['success'] = $language['errors']['product_added'];
        redirect('products');

    }
    
}

?>
<div class="card">
    <div class="card-body">
        <form action="" method="POST" role="form" enctype="multipart/form-data">

            <div class="form-group">
                <label><?php echo $language['forms']['product_name'];?></label>
                <input type="text" name="product_name" class="form-control" placeholder="<?php echo $language['forms']['product_name'];?>"/>
            </div>

            <div class="form-group">
                <label><?php echo $language['forms']['product_description'];?></label>
                <textarea name="product_description" class="form-control" rows="2" placeholder="<?php echo $language['forms']['product_description']; ?>"></textarea>
           </div>

            <div class="form-group">
                <label><?php echo $language['forms']['product_price'];?></label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><?php echo $currency_pfx; ?></div>
                    </div>
                    <input type="text" name="product_price" class="form-control" placeholder="<?php echo $language['forms']['product_price']; ?>"/>
                </div>
            </div>

            <div class="form-group">
                <label><?php echo $language['forms']['product_unit'];?></label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><?php echo '/'; ?></div>
                    </div>
                    <input type="text" name="product_unit" class="form-control" placeholder="<?php echo $language['forms']['product_unit']; ?>"/>
                </div>
            </div>
            <br>
            <div class="form-group">
                <button class="btn btn-secondary" type="submit" name="sumbit"><?php echo $language['forms']['product_button'];?></button>
            </div>
        </form>
    </div>
</div>
