<?php
User::check_permission(3);
$user_items = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $_GET['client_id']);
$user_data = $user_items->fetch_object();
?>

<div class="card">
    <div class="card-body">
        <img src="
                <?php if($user_data->pic_url == null){ 
                    echo "template/images/profile/user.png";
                }else{
                    echo "template/images/profile/" . $user_data->pic_url;
                }
                ?>" class="rounded-circle" style="height: 80px; width: 80px;" alt="Profile Pic"/>
        <br>
        <br>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><?php echo $language['misc']['user_id']; ?></td>
                    <td class="table-text-heading" style="text-align:left;"><?php echo $user_pfx .  $user_data->user_id; ?></td>
                </tr>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><?php echo $language['forms']['first_name']; ?></td>
                    <td><?php echo $user_data->name; ?></td>
                </tr>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><?php echo $language['forms']['last_name']; ?></td>
                    <td><?php echo $user_data->surname; ?></td>
                </tr>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><?php echo '<span class="fa fa-at"></span>&nbsp;' .$language['forms']['username']; ?></td>
                    <td><?php echo $user_data->username; ?></td>
                </tr>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><?php echo '<span class="fa fa-building"></span>&nbsp;' .$language['forms']['company_name']; ?></td>
                    <td><?php echo $user_data->company_name; ?></td>
                </tr>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><?php echo '<span class="fa fa-building"></span>&nbsp;' .$language['forms']['address']; ?></td>
                    <td><?php echo $user_data->address; ?></td>
                </tr>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><span class="fa fa-phone-alt"></span> &nbsp; <?php echo $language['forms']['contact_number']; ?></td>
                    <td><?php echo $user_data->contact_number; ?></td>
                </tr>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><?php echo $language['misc']['register_date']; ?></td>
                    <td><?php echo $user_data->date; ?></td>
                </tr>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><?php echo $language['forms']['location']; ?></td>
                    <td><span data-toggle="tooltip" title="<?php echo country_check(2,$user_data->location); ?>" class="tooltipz"><img src="template/images/locations/<?php echo $user_data->location; ?>.png" alt="<?php echo $user_data->location; ?>"/></span></td>
                </tr>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><?php echo $language['misc']['role']; ?></td>
                    <td><p class="badge k-badge-secondary"><?php User::get_level($user_data->user_id); ?></p></td>
                </tr>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><?php echo $language['misc']['total_invoices']; ?></td>
                    <td><?php echo $user_data->total_invoices; ?></td>
                </tr>
                <tr>
                    <td scope="row" class="table-text-heading" style="text-align: left;"><?php echo $language['misc']['comments']; ?></td>
                    <td><?php echo $user_data->comments; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>