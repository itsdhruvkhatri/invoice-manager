<?php User::check_permission(0);?>
<div class="card">
    <div class="card-body">
        <table class="table">
            
            <thead class="thead-light">
            <tr>
                <th scope="col" style="text-align: center;"><?php echo $language['misc']['report_id']; ?></th>
                <th scope="col" style="text-align: center;"><?php echo $language['misc']['invoice_id']; ?></th>
                <?php if($user->type >= 3){  ?><th scope="col" style="text-align: center;"><?php echo $language['misc']['user_full_name']; ?></th><?php } ?>
                <th scope="col"><?php echo $language['misc']['status']; ?></th>
                <th scope="col"></th>
            </tr>
            </thead>            
            <tbody>                
            
                <?php if($user->type < 3){
                    $reports_details = $database->query("SELECT * FROM `reports` WHERE `user_id` = " . $_SESSION['user_id'] . " " . $pagination->limit);
                    while($reports = $reports_details->fetch_object()){ 
                        $client_details = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $reports->user_id);
                        $clients = $client_details->fetch_object();?>
                        <tr>
                            <td scope="row" class="table-text-heading"><?php echo $report_pfx .  $reports->report_id; ?></td>
                            <td class="table-text-heading"><?php echo $invoice_pfx .  $reports->invoice_id; ?></td>
                            <td><?php echo reports_status($reports->status); ?></td>
                            <td><?php echo '<a href="reports/view/' . $reports->report_id . '"><button class="btn btn-sm btn-secondary"><span class="fa fa-file-alt"></span></button></a>' . 
                                    '&nbsp;<a onclick="return confirm(\'' . $language['misc']['remove_report'] .'\')" href="reports/r/'. $reports->report_id . '"><button type="button" class="btn btn-sm btn-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fas fa-trash"></i></button></a>'; ?></td>
                    
                        </tr>
                                    <?php }
                }
                if($user->type >= 3){ 
                    $reports_details = $database->query("SELECT * FROM `reports` $pagination->limit");
                    while($reports = $reports_details->fetch_object()){ 
                        $client_details = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $reports->user_id);
                        $clients = $client_details->fetch_object();?>
                        <tr>
                            <td scope="row" class="table-text-heading"><?php echo $report_pfx .  $reports->report_id; ?></td>
                            <td class="table-text-heading"><?php echo $invoice_pfx .  $reports->invoice_id; ?></td>
                            <td><?php echo $clients->name . " " .  $clients->surname; ?></td>
                            <td><?php echo reports_status($reports->status); ?></td>
                            <td><?php echo '<a href="reports/view/' . $reports->report_id . '"><button class="btn btn-sm btn-secondary"><span class="fa fa-file-alt"></span></button></a>' . 
                                    '&nbsp;<a onclick="return confirm(\'' . $language['misc']['remove_report'] .'\')" href="reports/r/'. $reports->report_id . '"><button type="button" class="btn btn-sm btn-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fas fa-trash"></i></button></a>'; ?></td>
                        </tr>
              <?php }
                
                } ?>
            </tbody>
        </table>
        <?php echo $pagination->display_pagination('reports'); ?>
    </div>
</div>