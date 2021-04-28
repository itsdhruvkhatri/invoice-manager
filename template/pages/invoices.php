<?php User::check_permission(0);?>
<div class="card">
    <div class="card-body">
        <table class="table">
            
            <thead class="thead-light">
            <tr>    
                <th scope="col" style="text-align: center;"><?php echo $language['misc']['invoice_id']; ?></th>
                <?php if($user->type >= 2){  ?><th scope="col"><?php echo $language['misc']['user_full_name']; ?></th><?php } ?>
                <th scope="col"><?php echo $language['misc']['status']; ?></th>
                <th scope="col"><?php echo $language['misc']['date']; ?></th>
                <th scope="col"><?php echo $language['forms']['due_date']; ?></th>
                <th scope="col"><?php echo $language['misc']['view']; ?></th>
            </tr>
            </thead>            
            <tbody>                
            
                <?php if($user->type >= 2){
                    $invoice_details = $database->query("SELECT * FROM `invoices` $pagination->limit");
                    if(!empty($invoice_details)){
                    while($invoice = $invoice_details->fetch_object()){
                        
                        $client_details = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $invoice->user_id);
                        if(!empty($client_details)){
                        $clients = $client_details->fetch_object();?>
                        <tr>
                            <td class="table-text-heading"><?php echo $invoice_pfx .  $invoice->invoice_id; ?></td>
                            <td><?php echo $clients->name . " " .  $clients->surname; ?></td>
                            <td><?php echo invoices_status($invoice->status); ?></td>
                            <td><?php echo $invoice->date; ?></td>
                            <td><?php echo $invoice->due_date; ?></td>
                            <td><?php echo '<a href="invoices/view/' . $invoice->invoice_id . '"><button class="btn btn-sm btn-secondary"><span class="fa fa-file-alt"></span></button></a>'; ?></td>
                        </tr>
                <?php }
                    }
                    }
                    } else{
                        $invoice_details = $database->query("SELECT * FROM `invoices` WHERE `user_id` = {$account_user_id} $pagination->limit");
                        if(!empty($invoice_details)){
                        while($invoice = $invoice_details->fetch_object()){

                            $client_details = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $invoice->user_id);
                            if(!empty($client_details)){
                            $clients = $client_details->fetch_object();?>
                            <tr>
                                <td class="table-text-heading"><?php echo $invoice_pfx .  $invoice->invoice_id; ?></td>
                                <td><?php echo invoices_status($invoice->status); ?></td>
                                <td><?php echo $invoice->date; ?></td>
                                <td><?php echo $invoice->due_date; ?></td>
                                <td><?php echo '<a href="invoices/view/' . $invoice->invoice_id . '"><button class="btn btn-sm btn-secondary"><span class="fa fa-file-alt"></span></button></a>'; ?></td>
                            </tr>
                  <?php }
                    }
                    }
                }?>
            </tbody>
        </table>
        <?php echo $pagination->display_pagination('invoices'); ?>
    </div>
</div>