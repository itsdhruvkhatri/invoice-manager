<?php

$path = (file_exists("languages/English.php")) ? "languages/" : "../../languages/";    //languages directory
$defLanguage = "English";    					//default language
$lang 		 = $defLanguage; 					//current language variable
$languages   = array();        					//store the available languages

$files = glob($path . "*.php");
$languages = preg_replace("($path|.php)", "", $files);

//if cookie is set and its correct, then override the default language
if(isset($_COOKIE['language']) && in_array($_COOKIE['language'], $languages)){ $lang = $_COOKIE['language']; }

//check for get request to change the current language
if(isset($_POST['language'])) {
	$_POST['language'] = filter_var($_POST['language'], FILTER_SANITIZE_STRING);
	if(in_array($_POST['language'], $languages)) {
		setcookie("language", $_POST['language'], time()+60*60*24*3); //set cookie for 30 days with that specific language
		$lang = $_POST['language'];
	}
}

//include the language file
if(file_exists($path . $lang . ".php")){	include $path . $lang . ".php";} else {include "../../" . $path . $lang . ".php"; }
?>