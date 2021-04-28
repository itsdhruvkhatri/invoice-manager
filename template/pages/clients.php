<?php User::check_permission(3); ?>
<div class="card">
    <div class="card-body">
        <table class="table">
            
            <thead class="thead-light">
            <tr>
                <th scope="col"></th>
                <th scope="col" style="text-align: center;"><?php echo $language['misc']['user_id']; ?></th>
                <th scope="col"><?php echo $language['forms']['first_name']; ?></th>
                <th scope="col"><?php echo $language['forms']['last_name']; ?></th>
                <th scope="col"><?php echo $language['misc']['user_type']; ?></th>
                <th scope="col"><?php echo $language['misc']['register_date']; ?></th>
                <th scope="col"></th>
            </tr>
            </thead>            
            <tbody>
                <?php $users = $database->query("SELECT * FROM `users` $pagination->limit");
                while($user_data = $users->fetch_object()){ ?>                
                    <tr>
                        <td style="text-align: right;"><span data-toggle="tooltip" title="<?php echo country_check(2,$user_data->location); ?>" class="tooltipz"><img src="template/images/locations/<?php echo $user_data->location; ?>.png" alt="<?php echo $user_data->location; ?>"/></span></td>
                        <td scope="row" class="table-text-heading"><?php echo $user_pfx . $user_data->user_id; ?></td>
                        <td><?php echo $user_data->name; ?></td>
                        <td><?php echo $user_data->surname; ?></td>
                        <td><p class="badge k-badge-secondary"><?php User::get_level($user_data->user_id); ?></p></td>
                        <td><?php echo $user_data->date; ?></td>
                        <td><?php echo '<a href="client/' . $user_data->user_id . '"><button class="btn btn-sm btn-secondary"><span class="fa fa-user"></span></button></a>' . 
                                        '&nbsp;<a onclick="return confirm(\'' . $language['misc']['remove_user'] .'\')" href="clients/r/'. $user_data->user_id . '"><button type="button" class="btn btn-sm btn-danger js-sweetalert" title="Delete" data-type="confirm"><i class="fas fa-trash"></i></button></a>'; ?></td>                    
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php echo $pagination->display_pagination('clients'); ?>
    </div>
</div>
