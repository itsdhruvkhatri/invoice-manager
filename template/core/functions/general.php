<?php


/* Function to return all the settings table */
function settings_data() {
	global $database;

	$result = $database->query("SELECT * FROM `settings` WHERE `id` = 1");
	$data   = $result->fetch_object();

	return $data;
}

/* Function to return all the user table */
function user_data(){
    global $database;
    
    if(!empty($_COOKIE['user_id'])){
        $_SESSION['user_id'] = $_COOKIE['user_id'];
    }
    if(!empty($_SESSION['user_id'])){
        $result = $database->query("SELECT * FROM `users` WHERE `user_id` = " . $_SESSION['user_id']);
        $details = $result->fetch_object();

        return $details;

    }
}

function calculate_price($price, $quantity){    
    $total = $price * $quantity;
        
    return $total;
}

//Displaying errors/success/info alerts
function disp_notifications() {
    global $language;

	$types = array("error", "success", "info", "warning");
	foreach($types as $type) {
		if(isset($_SESSION[$type]) && !empty($_SESSION[$type])) {
                    if($type == "error"){
                        $stat = "danger";
                    }
                    if($type == "success"){
                        $stat = "success";
                    }
                    if($type == "info"){
                        $stat = "info";
                    }
                    if($type == "warning"){
                        $stat = "warning";
                    }
			if(!is_array($_SESSION[$type])) $_SESSION[$type] = array($_SESSION[$type]);

			foreach($_SESSION[$type] as $message) {
                            
				echo '
					<div class="alert alert-' . $stat . '">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>' . $language['alerts'][$type] . '</strong> ' . $message . '
					</div>
				';
			}
			unset($_SESSION[$type]);
		}
	}

}

function total_price($discount, $subtotal){
    global $settings;
    
    $subtotal = array_sum($subtotal);
    
    $total_discount = $subtotal * ($discount/100);
    $total_vat = $subtotal * ($settings->tax/100);
    
    $total = ($subtotal + $total_vat) - $total_discount;
    
    
    return round($total,2);
}

function get_products(){
    global $database;
    global $product_pfx;
    
    $product = $database->query("SELECT * FROM `products`");
    $product_data = $product->fetch_all(MYSQLI_ASSOC);
    
    echo '<option value=""></option>';
    foreach($product_data as $products){
        echo '<option value="'.$products['product_id'].'">'.  $product_pfx. $products['product_id']. "&nbsp;&nbsp;" .$products['product_name'].'</option>';

    }
    
}

function user_select($type, $value = null){
    //TYPE 0: Verifying if the user exists
    //TYPE 1: Return the user list
    global $database;
    global $user_pfx;
    
    $user = $database->query("SELECT * FROM `users`");
    $user_data = $user->fetch_all(MYSQLI_ASSOC);
   
    
    foreach($user_data as $user_details){
        if($type == 1) {		
            if($value == $user_details['user_id']) {
                    $selected = ' selected="selected"';
            } else {
                    $selected = '';
            }
            echo '<option value="'.$user_details['user_id'].'"'.$selected.'>'.'<small>'. $user_pfx. $user_details['user_id']. '</small>'. " " .$user_details['name'] . " " . $user_details['surname'] .'</option>';
        }
        elseif($type == 0) {
            if($value != null){
                $check_user =$database->query("SELECT * FROM `users` WHERE `user_id` = " . $value);
                $check_users = $check_user->fetch_object();


                if(!empty($check_users->user_id)){
                    return $check_users->user_id;
                }
            }
        }
    }  
}

function country_check($type, $value) {
	// Type 0: Verify whether the country exists or not
	// Type 1: Return the country list
	// Type 2: Key to Value
	$list = array("AF" => "Afghanistan", "AL" => "Albania", "DZ" => "Algeria", "AS" => "American Samoa", "AD" => "Andorra", "AO" => "Angola", "AI" => "Anguilla", "AQ" => "Antarctica", "AG" => "Antigua and Barbuda", "AR" => "Argentina", "AM" => "Armenia", "AW" => "Aruba", "AU" => "Australia", "AT" => "Austria", "AZ" => "Azerbaijan", "AX" => "Åland Islands", "BS" => "Bahamas", "BH" => "Bahrain", "BD" => "Bangladesh", "BB" => "Barbados", "BY" => "Belarus", "BE" => "Belgium", "BZ" => "Belize", "BJ" => "Benin", "BM" => "Bermuda", "BT" => "Bhutan", "BO" => "Bolivia", "BA" => "Bosnia and Herzegovina", "BW" => "Botswana", "BV" => "Bouvet Island", "BR" => "Brazil", "BQ" => "British Antarctic Territory", "IO" => "British Indian Ocean Territory", "VG" => "British Virgin Islands", "BN" => "Brunei", "BG" => "Bulgaria", "BF" => "Burkina Faso", "BI" => "Burundi", "KH" => "Cambodia", "CM" => "Cameroon", "CA" => "Canada", "CV" => "Cape Verde", "KY" => "Cayman Islands", "CF" => "Central African Republic", "TD" => "Chad", "CL" => "Chile", "CN" => "China", "CX" => "Christmas Island", "CC" => "Cocos [Keeling] Islands", "CO" => "Colombia", "KM" => "Comoros", "CG" => "Congo - Brazzaville", "CD" => "Congo - Kinshasa", "CK" => "Cook Islands", "CR" => "Costa Rica", "HR" => "Croatia", "CU" => "Cuba", "CY" => "Cyprus", "CZ" => "Czech Republic", "CI" => "Côte d’Ivoire", "DK" => "Denmark", "DJ" => "Djibouti", "DM" => "Dominica", "DO" => "Dominican Republic", "EC" => "Ecuador", "EG" => "Egypt", "SV" => "El Salvador", "GQ" => "Equatorial Guinea", "ER" => "Eritrea", "EE" => "Estonia", "ET" => "Ethiopia", "FK" => "Falkland Islands", "FO" => "Faroe Islands", "FJ" => "Fiji", "FI" => "Finland", "FR" => "France", "GF" => "French Guiana", "PF" => "French Polynesia", "TF" => "French Southern Territories", "GA" => "Gabon", "GM" => "Gambia", "GE" => "Georgia", "DE" => "Germany", "GH" => "Ghana", "GI" => "Gibraltar", "GR" => "Greece", "GL" => "Greenland", "GD" => "Grenada", "GP" => "Guadeloupe", "GU" => "Guam", "GT" => "Guatemala", "GN" => "Guinea", "GW" => "Guinea-Bissau", "GY" => "Guyana", "HT" => "Haiti", "HM" => "Heard Island and McDonald Islands", "HN" => "Honduras", "HK" => "Hong Kong SAR China", "HU" => "Hungary", "IS" => "Iceland", "IN" => "India", "ID" => "Indonesia", "IR" => "Iran", "IQ" => "Iraq", "IE" => "Ireland", "IL" => "Israel", "IT" => "Italy", "JM" => "Jamaica", "JP" => "Japan", "JO" => "Jordan", "KZ" => "Kazakhstan", "KE" => "Kenya", "KI" => "Kiribati", "KW" => "Kuwait", "KG" => "Kyrgyzstan", "LA" => "Laos", "LV" => "Latvia", "LB" => "Lebanon", "LS" => "Lesotho", "LR" => "Liberia", "LY" => "Libya", "LI" => "Liechtenstein", "LT" => "Lithuania", "LU" => "Luxembourg", "MO" => "Macau SAR China", "MK" => "Macedonia", "MG" => "Madagascar", "MW" => "Malawi", "MY" => "Malaysia", "MV" => "Maldives", "ML" => "Mali", "MT" => "Malta", "MH" => "Marshall Islands", "MQ" => "Martinique", "MR" => "Mauritania", "MU" => "Mauritius", "YT" => "Mayotte", "MX" => "Mexico", "FM" => "Micronesia", "MD" => "Moldova", "MC" => "Monaco", "MN" => "Mongolia", "ME" => "Montenegro", "MS" => "Montserrat", "MA" => "Morocco", "MZ" => "Mozambique", "MM" => "Myanmar [Burma]", "NA" => "Namibia", "NR" => "Nauru", "NP" => "Nepal", "NL" => "Netherlands", "AN" => "Netherlands Antilles", "NC" => "New Caledonia", "NZ" => "New Zealand", "NI" => "Nicaragua", "NE" => "Niger", "NG" => "Nigeria", "NU" => "Niue", "NF" => "Norfolk Island", "KP" => "North Korea", "MP" => "Northern Mariana Islands", "NO" => "Norway", "OM" => "Oman", "PK" => "Pakistan", "PW" => "Palau", "PS" => "Palestinian Territories", "PA" => "Panama", "PG" => "Papua New Guinea", "PY" => "Paraguay", "PE" => "Peru", "PH" => "Philippines", "PN" => "Pitcairn Islands", "PL" => "Poland", "PT" => "Portugal", "PR" => "Puerto Rico", "QA" => "Qatar", "RO" => "Romania", "RU" => "Russia", "RW" => "Rwanda", "RE" => "R?ion", "SH" => "Saint Helena", "KN" => "Saint Kitts and Nevis", "LC" => "Saint Lucia", "PM" => "Saint Pierre and Miquelon", "VC" => "Saint Vincent and the Grenadines", "WS" => "Samoa", "SM" => "San Marino", "SA" => "Saudi Arabia", "SN" => "Senegal", "RS" => "Serbia", "CS" => "Serbia and Montenegro", "SC" => "Seychelles", "SL" => "Sierra Leone", "SG" => "Singapore", "SK" => "Slovakia", "SI" => "Slovenia", "SB" => "Solomon Islands", "SO" => "Somalia", "ZA" => "South Africa", "GS" => "South Georgia and the South Sandwich Islands", "KR" => "South Korea", "ES" => "Spain", "LK" => "Sri Lanka", "SD" => "Sudan", "SR" => "Suriname", "SJ" => "Svalbard and Jan Mayen", "SZ" => "Swaziland", "SE" => "Sweden", "CH" => "Switzerland", "SY" => "Syria", "ST" => "S?Tom?nd Pr?ipe", "TW" => "Taiwan", "TJ" => "Tajikistan", "TZ" => "Tanzania", "TH" => "Thailand", "TL" => "Timor-Leste", "TG" => "Togo", "TK" => "Tokelau", "TO" => "Tonga", "TT" => "Trinidad and Tobago", "TN" => "Tunisia", "TR" => "Turkey", "TM" => "Turkmenistan", "TC" => "Turks and Caicos Islands", "TV" => "Tuvalu", "UM" => "U.S. Minor Outlying Islands", "VI" => "U.S. Virgin Islands", "UG" => "Uganda", "UA" => "Ukraine", "SU" => "Union of Soviet Socialist Republics", "AE" => "United Arab Emirates", "GB" => "United Kingdom", "US" => "United States", "UY" => "Uruguay", "UZ" => "Uzbekistan", "VU" => "Vanuatu", "VA" => "Vatican City", "VE" => "Venezuela", "VN" => "Vietnam", "WF" => "Wallis and Futuna", "EH" => "Western Sahara", "YE" => "Yemen", "ZM" => "Zambia", "ZW" => "Zimbabwe");

	if($type == 1) {

		foreach($list as $code => $name) {
			if($code == $value) {
				$selected = ' selected="selected"';
			} else {
				$selected = '';
			}
			echo '<option value="'.$code.'"'.$selected.'>'.$name.'</option>';
		}

	} elseif($type == 0) {

		return (array_key_exists($value, $list));

	} else {

		return $list[$value];

	}
}


function resize($file_name, $path, $width, $height, $center = false) {
	/* Get original image x y*/
	list($w, $h) = getimagesize($file_name);

	/* calculate new image size with ratio */
	$ratio = max($width/$w, $height/$h);
	$h = ceil($height / $ratio);
	$x = ($w - $width / $ratio) / 2;
	$w = ceil($width / $ratio);
	$y = 0;
	if($center) $y = 250 + $h/1.5;

	/* read binary data from image file */
	$imgString = file_get_contents($file_name);

	/* create image from string */
	$image = imagecreatefromstring($imgString);
	$tmp = imagecreatetruecolor($width, $height);
	imagecopyresampled($tmp, $image,
	0, 0,
	$x, $y,
	$width, $height,
	$w, $h);

	/* Save image */
	imagejpeg($tmp, $path, 100);

	return $path;
	/* cleanup memory */
	imagedestroy($image);
	imagedestroy($tmp);
}


//Display the status of reports
function reports_status($reports){
    global $language;
    
    if($reports == "R"){
        $status = $language['status']['read'];
        echo '<p class="badge k-badge-secondary">' . $status . '</p>';
    }
    if($reports == "U"){
        $status = $language['status']['unread'];
        echo '<p class="badge k-badge-danger">' . $status . '</p>';
    }
    if($reports == "D"){
        $status = $language['status']['done'];
        echo '<p class="badge k-badge-success">' . $status . '</p>';
    }   
}

function invoices_status($invoice){
    global $language;
    
    if($invoice == "deposit"){
        $status = $language['status']['deposit'];
        echo '<p class="badge k-badge-warning">' . $status . '</p>';
    }
    if($invoice == "overdue"){
        $status = $language['status']['over_due'];
        echo '<p class="badge k-badge-info">' . $status . '</p>';
    }
    if($invoice == "cancelled"){
        $status = $language['status']['cancelled'];
        echo '<p class="badge k-badge-danger">' . $status . '</p>';
    }
    if($invoice == "paid"){
        $status = $language['status']['paid'];
        echo '<p class="badge k-badge-success">' . $status . '</p>';
    }
    if($invoice == "draft"){
        $status = $language['status']['draft'];
        echo '<p class="badge k-badge-secondary">' . $status . '</p>';
    }
    if($invoice == "unpaid"){
        $status = $language['status']['unpaid'];
        echo '<p class="badge k-badge-secondary">' . $status . '</p>';
    }
}

function payment_type($payment){
    global $language;
    
    if($payment == "deposit"){
        $status = $language['misc']['deposit'];
        echo '<p class="badge k-badge-danger">' . $status . '</p>';
    }
    if($payment == "full"){
        $status = $language['misc']['full_payment'];
        echo '<p class="badge k-badge-success">' . $status . '</p>';
    }  
}

function sendmail($to, $from, $title, $message) {

	$headers = "From: " . strip_tags($from) . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	mail($to, $title, $message, $headers);
}