<?php

/**
 * Description of Notifications
 *
 * @author Kodly
 */

class Notifications {
    public $user_id,
            $notifications,
            $count;
    
    public function __construct($user_id) {
        global $database;
        $this->user_id = $user_id;
        
        $this->notifications = $database->query("SELECT * FROM `notifications` WHERE `user_id` = " . $this->user_id);
            
    }
    
    public function display(){
        global $language;
        global $invoice_pfx;
        global $database;
        
        while($notifications = $this->notifications->fetch_object()){
            
            if (self::count_notif() != "0"){
            if($notifications->type == "report"){
                if ($notifications->status == "unread"){
                    echo '<a class="dropdown-item" href="reports/view/'. $notifications->type_id .'/' . $notifications->notification_id . '"><strong>' . $language['notifications']['new_report'] . '<br><small>' . $notifications->date .  '</small></strong></a>';
                }
                if ($notifications->status  == "read"){
                    echo '<a class="dropdown-item" href="reports/view/'. $notifications->type_id .'/' . $notifications->notification_id . '">' . $language['notifications']['new_report'] . '<br><small>' . $notifications->date .  '</small></a>';
                }
            }
            if($notifications->type == "reply"){
                if ($notifications->status == "unread"){
                    echo '<a class="dropdown-item" href="reports/view/'. $notifications->type_id .'/' . $notifications->notification_id . '"><strong>' . $language['notifications']['new_reply'] . '<br><small>' . $notifications->date .  '</small></strong></a>';
                }
                if ($notifications->status == "read"){
                    echo '<a class="dropdown-item" href="reports/view/'. $notifications->type_id .'/' . $notifications->notification_id . '">' . $language['notifications']['new_reply'] . '<br><small>' . $notifications->date .  '</small></a>';
                }
            }
            if($notifications->type == "invoice"){
                if ($notifications->status == "unread"){
                    echo '<a class="dropdown-item" href="invoices/view/'. $notifications->type_id .'/' . $notifications->notification_id . '"><strong>' . $language['notifications']['new_invoice'] . '<br><small>' . $notifications->date .  '</small></strong></a>';
                }
                if ($notifications->status == "read"){
                    echo '<a class="dropdown-item" href="invoices/view/'. $notifications->type_id .'/' . $notifications->notification_id . '">' . $language['notifications']['new_invoice'] . '<br><small>' . $notifications->date .  '</small></a>';
                }
            }
            if($notifications->type == "payment"){
                $payments = $database->query("SELECT * FROM `payments` WHERE `payment_id` = " . $notifications->type_id);
                while ($payment = $payments->fetch_object()){
                    $users = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $payment->user_id);
                    $user = $users->fetch_object();

                    if ($notifications->status == "unread"){
                        echo '<a class="dropdown-item" href="payments/' . $notifications->notification_id . '"><strong>' . sprintf($language['notifications']['payment'], $user->name, ($invoice_pfx . $payment->invoice_id)) . '<br><small>' . $notifications->date .  '</small></strong></a>';
                    }
                    if ($notifications->status == "read"){
                        echo '<a class="dropdown-item" href="payments/' . $notifications->notification_id . '">' . sprintf($language['notifications']['payment'], $user->name, ($invoice_pfx . $payment->invoice_id)) . '<br><small>' . $notifications->date .  '</small></a>';
                    }
                }
            }
            }else{
                echo '<a class="dropdown-item"><p class="text-secondary">' . $language['notifications']['none'] . '</p></a>';
            }
            
        }
    }
    
    public function count_notif(){
        global $database;
        $result = $database->query("SELECT COUNT(*) AS `notif_counts` FROM `notifications` WHERE `user_id` = " . $this->user_id);
        $this->count = $result->fetch_row();
        
        return $this->count[0];
    }
    
    public function check_status(){       
        while($notifications = $this->notifications->fetch_object()){
            return $notifications->status;
        }
    }
    
}


