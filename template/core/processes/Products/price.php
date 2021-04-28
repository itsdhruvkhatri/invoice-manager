<?php
$options = $_GET['product'];
require_once '../../database/connect.php';
include '../../database/products.php';
require_once '../../functions/general.php';
$settings = settings_data();
require_once '../../functions/prefixes.php';

?>

<div class="form-group">
    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <div class="input-group-text"><?php echo $currency_pfx; ?></div>
        </div>            
        <input type="text" name="product_price" id="product_prices" placeholder="" value="<?php echo $product->price; ?>" class="form-control" readonly/>               
    </div>
</div>

