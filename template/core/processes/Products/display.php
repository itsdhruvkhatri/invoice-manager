<?php
    
require_once '../../database/connect.php';
require_once '../../functions/general.php';
$settings = settings_data();
require_once '../../functions/prefixes.php';


if(isset($_GET['qty']) && isset($_GET['price'])){
    $quantity = $_GET['qty'];
    $price = $_GET['price'];
    
    ?>
    <div class="form-group">
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text"><?php echo $currency_pfx; ?></div>
            </div>            
            <input type="text" name="product_total" placeholder="" value="<?php echo calculate_price($price, $quantity); ?>" class="form-control" readonly/>               
        </div>
    </div>
<?php
}
?>