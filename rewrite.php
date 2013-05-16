<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: rewrite.php
| Author: Ankur Thakur
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
define("IN_PERMALINK", TRUE);

require_once dirname(__FILE__)."/maincore.php";
require_once CLASSES."Rewrite.class.php";

// Starting Rewrite Object
$seo_rewrite = new Rewrite();

// Call the main function
$seo_rewrite->rewritePage();

// Get the php file path of corresponding request
$filepath = $seo_rewrite->getFilePath();

if ($filepath != "") {
	// Set FUSION_SELF to File path
	if (preg_match("/\.php/", basename($filepath))) {
		// If it is a file
		define("FUSION_SELF", basename($filepath));
	}
	else {
		// If it is a directory that actually exists(like /forum/)
		define("FUSION_SELF", "index.php");
	}

	// Define FUSION_QUERY
	define("FUSION_QUERY", isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : "");

	// Define START_PAGE for Panels
	$current_page = str_replace($settings['site_path'], "", $_SERVER['PHP_SELF']);
	define("TRUE_PHP_SELF", $current_page);
	define("START_PAGE", TRUE_PHP_SELF.($_SERVER['QUERY_STRING'] ? "?".$_SERVER['QUERY_STRING'] : ""));

	// Include the corresponding PHP File
	include_once $filepath;
}
else {
	echo "<h1>404 - Page Not Found!</h1>";
	echo "Or you can call Custom functions to display your own Error Messages.<br />";
	echo "We had been working on Custom Error Page I guess? We can call something like <strong>&#36;customErrors-&gt;Error()</strong>";
}
if (!defined("FUSION_SELF")) {
	define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
}

?>