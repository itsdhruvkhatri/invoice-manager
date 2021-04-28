<?php
include 'template/core/init.php';
include 'template/overall_header.php';


//Getting ?page=
if(isset($_GET['page'])) {
	$_GET['page'] = htmlspecialchars($_GET['page'], ENT_QUOTES);
	$pages = glob("template/pages/" . "*.php");
	$pages = preg_replace("(template/pages/|.php)", "", $pages);

	if(in_array($_GET['page'], $pages)) {
		include 'template/pages/'.$_GET['page'].'.php';
	} else {
		include 'template/pages/notfound.php';
	}
} else {
	include 'template/pages/home.php';
}

include 'template/overall_footer.php';