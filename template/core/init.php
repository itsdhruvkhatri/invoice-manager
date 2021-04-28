<?php
ob_start();
session_start();
error_reporting(E_ALL);

require_once 'template/core/libs/vendor/autoload.php';

include 'template/core/database/connect.php';
include 'template/core/functions/general.php';
include 'template/core/functions/language.php';
include 'template/core/classes/User.php';
include 'template/core/classes/Pagination.php';
include 'template/core/classes/Notifications.php';


$system = "inv_mngr";
$version = "1.1";
$settings = settings_data();
$user = user_data();
$pagination = new Pagination($settings->per_page_pagination);

include 'template/core/functions/prefixes.php';
include 'template/core/functions/dashboard.php';


if(isset($_GET['page'])){
    if ($_GET['page'] == "settings"){
        include 'template/core/libs/check_version.php';
    }
    if($_GET['page'] == "invoice_pdf"){
        include 'template/core/functions/pdf.php';
    }
}

function due_date($invoice){
    global $language;
    
    if($invoice == null){
        echo $language['misc']['no_due_date'];
    }
    if($invoice != null){
        echo date("D d-M-Y", strtotime($invoice));
    }
}

//Redirect to index
function redirect($new_page = 'index') {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = (strlen(dirname($_SERVER['PHP_SELF'])) < 2 ) ? null : dirname($_SERVER['PHP_SELF']);
	header('Location: http://'. $host . $uri . '/' . $new_page);
	die();
}

include 'template/core/processes/cronJobs.php';