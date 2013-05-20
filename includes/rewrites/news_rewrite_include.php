<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: news_rewrite_include.php
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
if (!defined("IN_FUSION")) { die("Access Denied"); }

$regex = array(
	"%news_id%" => "([0-9]+)",
	"%news_title%" => "([a-zA-Z0-9-]+)"
);
$pattern = array(
	"news" => "news.php",
	"news/%news_id%/%news_title%" => "news.php?readmore=%news_id%",
	"news/%news_id%/%news_title%#comments" => "news.php?readmore=%news_id%#comments"
);
$alias_pattern = array(
	"news/%alias%" => "%alias_target%",
	"news/%alias%#comments" => "%alias_target%#comments"
);
$dir_path = ROOT;
$dbname = DB_NEWS;
$dbid = array("%news_id%" => "news_id");
$dbinfo = array(
	"%news_title%" => "news_subject",
	"%news_start%" => "news_start"
);

?>