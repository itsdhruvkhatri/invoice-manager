<?php
User::check_permission(0);

$report_details = $database->query("SELECT * FROM `reports` WHERE `report_id` = " . $_GET['report_id']);
$report_dets = $report_details->fetch_object();
if(($report_dets->user_id == $user->user_id) || $user->type >= 3){

$comments = $database->query("SELECT * FROM `comments` WHERE `report_id` = " . $_GET['report_id']);


?>
<div style="text-align: right;">
    <?php echo reports_status($report_dets->status); ?>
</div>
<div class="card">
    <div class="card-body">
        <?php
        $report_details = $database->query("SELECT * FROM `reports` WHERE `report_id` = " . $_GET['report_id']);
        while($report = $report_details->fetch_object()){
            
            $user_report = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $report->user_id);
            
            while($users = $user_report->fetch_object()){
                
                echo '<h5 class="text-center text-secondary">'. $language['misc']['report'] . ' ' . '<b>' .$report_pfx . $report->report_id . '</b>' .' - ' . '<b>' . $invoice_pfx . $report->invoice_id .'</b></h5>';
                echo '<br><br>';
                ?>
                <img class="rounded-circle" src="<?php echo ($users->pic_url != null) ? 'template/images/profile/' . $users->pic_url : 'template/images/profile/user.png'; ?>" style="height: 50px; width:50px;"/>
                <?php
                echo '<small>'. $users->name . ' ' . $users->surname . '</small>';
                echo '<br>';
                echo '<br>';
                echo '<small>' . $report->date .  '</small>';
                echo '<br>';
                echo '<br>';
                echo '<div class="card">';
                echo '<div class="card-body">';
                echo '<pre>' . $report->message . '</pre>';
                echo '</div>';
                echo '</div>';
                echo '<br>';
                echo '<hr>';
                
            }
        }
        
        
        echo '<h5 class="text-center text-secondary">' . $language['misc']['replies'] . '</h5>';
        while($replies = $comments->fetch_object()){
            
            $comment_user = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $replies->user_id);
                
            while($reply_user = $comment_user->fetch_object()){ 
                echo '<br><br>';
                echo '<div class="card">';
                echo '<div class="card-body">';
                ?>
                <img class="rounded-circle" src="<?php echo ($reply_user->pic_url != null) ? 'template/images/profile/' . $reply_user->pic_url : 'template/images/profile/user.png'; ?>" style="height: 50px; width:50px;"/>
                <?php
                echo '<small>'. $reply_user->name . ' ' . $reply_user->surname . '</small>';
                echo '<br>';
                echo '<br>';
                echo '<small>' . $replies->date .  '</small>';
                echo '<br>';
                echo '<br>';
                echo '<pre>' . $replies->comment . '</pre>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>
</div>
<?php
} else{
    $_SESSION['error'] = $language['errors']['command_denied'];
    redirect();
}
?>

