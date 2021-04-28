<?php User::check_permission(1); ?>
<div class="card">
    <div class="card-body">
        <table class="table">
            
            <thead class="thead-light">
            <tr>               
                <th scope="col" style="text-align: center;"><?php echo $language['misc']['product_id']; ?></th>
                <th scope="col"><?php echo $language['forms']['product_name']; ?></th>
                <th scope="col"><?php echo $language['forms']['product_description']; ?></th>
                <th scope="col"><?php echo $language['forms']['product_price']; ?></th>
                <th scope="col"><?php echo $language['forms']['product_unit']; ?></th>               
                <th scope="col"></th>
            </tr>
            </thead>            
            <tbody>
                <?php $products = $database->query("SELECT * FROM `products` $pagination->limit");
                while($product_data = $products->fetch_object()){ ?>                
                    <tr>
                        <td scope="row" class="table-text-heading"><?php echo $product_pfx . $product_data->product_id; ?></td>
                        <td style="width:200px;"><?php echo $product_data->product_name; ?></td>
                        <td style="width:400px;"><?php echo $product_data->description; ?></td>
                        <td style="width:100px;"><?php echo $currency_pfx . $product_data->price; ?></td>
                        <td style="width:70px;"><?php echo '/ ' . $product_data->unit; ?></td>
                        <td><?php echo '<a href="products/edit/' . $product_data->product_id . '"><button class="btn btn-sm btn-success"><span class="fa fa-pen"></span></button></a>' . 
                                        '&nbsp;<a onclick="return confirm(\'' . $language['misc']['remove_product'] .'\')" href="products/r/'. $product_data->product_id . '"><button type="button" class="btn btn-sm btn-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fas fa-trash"></i></button></a>'; ?></td>                    
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php echo $pagination->display_pagination('products'); ?>
    </div>
</div>
