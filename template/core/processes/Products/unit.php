<?php
$options = $_GET['product'];
require_once '../../database/connect.php';
include '../../database/products.php';
?>

<div class="form-group">
    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <div class="input-group-text"><?php echo "/"; ?></div>
        </div>            
        <input type="text" name="product_unit" placeholder=""  value="<?php echo $product->unit; ?>" class="form-control" readonly/>               
    </div>
</div>