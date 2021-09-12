<?php
	global $setup_config;
	global $setup_title;
	global $page;
	$setup_title=array(
		"Setup Settings",
		"Default Settings",
		"Database Settings",
		"Email System Settings",
		"Drawing Bed Settings",
		"Feature Settings",
		"Data Settings",
		"Page Settings",
		"Index Settings",
	);
	$setup_desc=array(
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
		"",
	);
	$setup_config=array(
		array(
			array("setup-background","Setup Interface Background","input"),
			array("setup-title","Setup System Title","input"),
		),
		array(
			array("server","Your Server Address","input"),
			array("domain","Your Server Domain","input"),
			array("protocol","Your Server Protocol(http/https)","input"),
			array("icon-addr","Website Icon File Address","input"),
			array("website-title","Website Title","input"),
			array("background","Default Background","input"),
			array("language","Language","choice","source"=>"language-source","value"=>"code","name"=>"name"),
		),
		array(
			array("mysql-address","MySQL Database Address","input"),
			array("mysql-user","MySQL Database User Name","input"),
			array("mysql-password","MySQL Database Password","input"),
			array("mysql-database","MySQL Database Name","input"),
		),
		array(
			array("email-account","Email Account","input"),
			array("email-password","Email Account's Password","input"),
			array("email-server","Email System Address","input"),
			array("email-protocol","Email System Protocol","input"),
			array("email-port","Email System Port","input"),
			array("email-from","Sender Name","input"),
		),
		array(
			array("drawing-bed-platform","Drawing Bed Platform","input"),
			array("drawing-bed-user-name","Drawing Bed User Name","input"),
			array("drawing-bed-repository","Drawing Bed Repository","input"),
			array("drawing-bed-email","Drawing Bed Email","input"),
			array("drawing-bed-token","Drawing Bed Token","input"),
			array("enable-all-manage","Allow Everyone to Manage","checkbox"),
		),
		array(
			array("enable-article","Enable Article Release","check"),
			array("enable-notice","Enable Notice Release","check"),
			array("enable-wiki","Enable Wiki Desipit","check"),
			array("enable-drawing-bed","Enable Drawing Bed","check"),
			array("enable-explorer","Enable Explorer","check"),
			array("enable-change-password","Enable User to Change the Password","check"),
		),
		array(
			array("article-data","Article Data Path","input"),
			array("notice-data","Notice Data Path","input"),
			array("wiki-data","Wiki Data Path","input"),
			array("account-data","Account Data Path","input"),
			array("language-data","Language Data Path","input"),
			array("extension-data","Extension Data Path","input"),
			array("language-source","Language Source","array","var"=>array("name","code","path")),
		),
		array(
			array("index-path","Index.php Path","input"),
			array("setup-data","Setup.php Path","input"),
		),
		array(
			array("index-pictures","Index Pixtures","array","var"=>array("image","link")),
			array("index-article-number","Index Article Number","input"),
			array("index-column-number","Index Column Number","input"),
			array("index-wiki-number","Index Wiki Number","input"),
			array("index-notice-number","Index Notice Number","input"),
		),
	);
?>