<?php User::check_permission(2);?>
<div class="card">
    <div class="card-body">
        <table class="table">
            
            <thead class="thead-light">
            <tr>
                <th scope="col" style="text-align: center;"><?php echo $language['misc']['payment_id']; ?></th>
                <th scope="col" style="text-align: center;"><?php echo $language['misc']['invoice_id']; ?></th>
                <th scope="col"><?php echo $language['misc']['user_full_name']; ?></th>
                <th scope="col"><?php echo $language['misc']['user_type']; ?></th>
                <th scope="col"><?php echo $language['payments']['revenue']; ?></th>
                <th scope="col"><?php echo $language['misc']['date']; ?></th>
                <th scope="col"><?php echo $language['payments']['form']; ?></th>
                
            </tr>
            </thead>            
            <tbody>                       
                <?php $payment_details = $database->query("SELECT * FROM `payments` $pagination->limit");
                while($payments = $payment_details->fetch_object()){ 
                    $client_details = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $payments->user_id);
                    $clients = $client_details->fetch_object(); ?>
                    <tr>
                        <td scope="row" class="table-text-heading"><?php echo $payment_pfx .  $payments->payment_id; ?></td>
                        <td class="table-text-heading"><?php echo $invoice_pfx .  $payments->invoice_id; ?></td>
                        <td><?php echo $clients->name . " " . $clients->surname; ?></td>
                        <td><?php echo payment_type($payments->type); ?></td>
                        <td><?php echo $currency_pfx . " " . $payments->revenue; ?></td>
                        <td><?php echo $payments->date; ?></td>
                        <td><?php echo $payments->form; ?></td>


                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php echo $pagination->display_pagination('payments'); ?>      
    </div>
</div>