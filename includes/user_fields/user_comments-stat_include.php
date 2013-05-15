<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_comments-stat_include.php
| Author: Digitanium
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
	//Nothing here
} elseif ($profile_method == "display") {
	echo "<tr>\n";
	echo "<td class='tbl1'>".$locale['uf_comments-stat']."</td>\n";
	echo "<td align='right' class='tbl1'>".number_format(dbcount("(comment_id)", DB_COMMENTS, "comment_name='".$user_data['user_id']."'"))."</td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "validate_insert") {
	//Nothing here
} elseif ($profile_method == "validate_update") {
	//Nothing here
}
?>