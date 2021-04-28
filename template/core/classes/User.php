<?php

/**
 * Description of User
 *
 * @author bart - Kodly Web Design
 */

class User{
    public $userId;
    
    public function __construct($uId){
        global $database;
		$this->user_id = $uId;

		$stmt = $database->prepare("SELECT * FROM `users` WHERE `user_id` = ?");
		$stmt->bind_param('s', $this->user_id);
		$stmt->execute();

		$parameters = array();
		$meta = $stmt->result_metadata();
		while($field = $meta->fetch_field()) {
			$parameters[] = &$row[$field->name]; 
		}

		call_user_func_array(array($stmt, 'bind_result'), $parameters);

		while($stmt->fetch()) {
			foreach($row as $key => $val) {
				$this->{$key} = $val;
			}
		}

		$stmt->close();
	}

        
        	public static function encrypt_password($username, $password) {
		//using $username as salt
		$username = hash('sha512', $username);
		$hash	  = hash('sha512', $password . $username);
		
		//iterating the hash
		for($i = 1;$i <= 1000;$i++) {
			$hash = hash('sha512', $hash);
		}
		
		return $hash;
	}

	public static function login($username, $password) {
		global $database;

		$stmt = $database->prepare("SELECT `user_id` FROM `users` WHERE `username` = ? AND `password` = ?");
		$stmt->bind_param('ss', $username, $password);
		$stmt->execute();
		$stmt->bind_result($result);
		$stmt->fetch();
		$stmt->close();

		return (!is_null($result)) ? $result : false;
	}

	public static function logout() {
		session_destroy();
		setcookie('username', '', time()-30);
		setcookie('password', '', time()-30);
		setcookie('user_id', '', time()-30);
		redirect();
	}

	public static function user_active($username) {
		global $database;

		$stmt = $database->prepare("SELECT `active` FROM `users` WHERE `username` = ?");
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$stmt->bind_result($result);
		$stmt->fetch();
		$stmt->close();

                if($result == true){ return true;}
                else{ return false;}
	}

	public static function logged_in_redirect($page = 'dashboard') { 
	global $language;

		if(self::logged_in()) {
			$_SESSION['info'] = $language['errors']['already_logged_in'];
			redirect($page);
		}
	}

	public static function logged_in() { 
		if(isset($_COOKIE['username']) && isset($_COOKIE['password']) && User::login($_COOKIE['username'], $_COOKIE['password']) !== false && $_COOKIE['id'] == User::login($_COOKIE['username'], $_COOKIE['password'])) {
			return true;
		} elseif(isset($_SESSION['user_id'])) {
			return true;
                } else{ return false;}
	}

	public static function get_back($new_page = 'index') {
		if(isset($_SERVER['HTTP_REFERER'])){
                Header('Location: ' . $_SERVER['HTTP_REFERER']);}
		else{
                redirect($new_page);
		die();
                }
	}

	public static function get_type($id) {
		global $database;

		$stmt = $database->prepare("SELECT `type` FROM `users` WHERE `user_id` = ?");
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$stmt->bind_result($result);
		$stmt->fetch();
		$stmt->close();

		return $result;
	}
        
        public static function get_level($id){
            global $language;
            
            if(self::get_type($id) == 0){
                echo $language['misc']['role_client'];
            }
            if(self::get_type($id) == 1){
                echo $language['misc']['role_store_manager'];
            }
            if(self::get_type($id) == 2){
                echo $language['misc']['role_accountant'];
            }
            if(self::get_type($id) == 3){
                echo $language['misc']['role_manager'];
            }
            if(self::get_type($id) == 4){
                echo $language['misc']['role_founder'];
            }
        }

        public static function get_profile_link($id) {
		global $database;

		$stmt = $database->prepare("SELECT `username`, `name` FROM `users` WHERE `user_id` = ?");
		$stmt->bind_param('s', $id);
		$stmt->execute();
		$stmt->bind_result($username, $fName);
		$stmt->fetch();
		$stmt->close();

		return ($username == false) ? 'Anonymous' : '<a href="user/' . $username . '">' . $fName . '</a>';
	}


	public static function x_to_y($x, $y, $x_value, $from = 'users') {
		global $database;

		$stmt = $database->prepare("SELECT `{$y}` FROM `{$from}` WHERE `{$x}` = ?");
		$stmt->bind_param('s', $x_value);
		$stmt->execute();
		$stmt->bind_result($result);
		$stmt->fetch();
		$stmt->close();

		return $result;
	}

	public static function x_exists($x, $x_value, $from = 'users') {
		global $database;

		$stmt = $database->prepare("SELECT `{$x}` FROM `{$from}` WHERE `{$x}` = ?");
		$stmt->bind_param('s', $x_value);
		$stmt->execute();
		$stmt->bind_result($result);
		$stmt->fetch();
		$stmt->close();

                if(!is_null($result)){ return true;}
                else{ return false;}
	}


	public static function online_users($seconds) {
		global $database;

		$stmt = $database->prepare("SELECT COUNT(`user_id`) FROM `users` WHERE `last_activity` > UNIX_TIMESTAMP() - ?");
		$stmt->bind_param('i', $seconds);
		$stmt->execute();
		$stmt->bind_result($result);
		$stmt->fetch();
		$stmt->close();

		return $result;
	}	


	public static function check_permission($level = 1) {
            global $language;
            global $account_user_id;

            if(!self::logged_in() || self::get_type($account_user_id) < $level) {
                    $_SESSION['error'] = $language['errors']['command_denied'];

                    if(isset($_SERVER['HTTP_REFERER'])){ Header('Location: ' . $_SERVER['HTTP_REFERER']);} else{ redirect();
            die();}
            }
	}

	public static function is_admin($id) {
		return (self::get_type($id) > 0) ? true : false;
	}
}
