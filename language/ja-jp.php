<?php
	global $setup_config;
	global $setup_title;
	$setup_title=array(
		"プログラム設定",
		"基本設定",
		"データベースの設定",
		"メールシステム設定",
		"ベッドの設定",
		"機能設定",
		"データ設定",
		"ページの設定",
		"ホームページの設定",
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
			array("setup-background","设置界面背景","input"),
			array("setup-title","设置界面标题","input"),
		),
		array(
			array("server","服务器地址","input"),
			array("domain","服务器域名","input"),
			array("protocol","网站协议(http/https)","input"),
			array("icon-addr","网站图标","input"),
			array("website-title","网站标题","input"),
			array("background","默认背景","input"),
			array("language","默认语言","choice","source"=>"language-source","value"=>"code","name"=>"name"),
		),
		array(
			array("mysql-address","MySQL数据库地址","input"),
			array("mysql-user","MySQL数据库用户名","input"),
			array("mysql-password","MySQL数据库密码","input"),
			array("mysql-database","MySQL数据库名","input"),
		),
		array(
			array("email-account","邮件用户名","input"),
			array("email-password","邮件用户密码","input"),
			array("email-server","邮件系统地址","input"),
			array("email-protocol","邮件系统地址协议","input"),
			array("email-port","邮件系统端口","input"),
			array("email-from","发送人署名","input"),
		),
		array(
			array("drawing-bed-platform","图床平台","input"),
			array("drawing-bed-user-name","图床用户名","input"),
			array("drawing-bed-repository","图床仓库名","input"),
			array("drawing-bed-email","图床邮箱","input"),
			array("drawing-bed-token","图床Token","input"),
			array("enable-all-manage","允许普通用户参观","checkbox"),
		),
		array(
			array("enable-article","开放文章投稿","check"),
			array("enable-notice","开放公告发布","check"),
			array("enable-wiki","开放百科存储","check"),
			array("enable-drawing-bed","开放图床","check"),
			array("enable-explorer","开放文件资源管理器","check"),
			array("enable-change-password","允许用户修改密码","check"),
		),
		array(
			array("article-data","文章数据路径","input"),
			array("notice-data","公告数据路径","input"),
			array("wiki-data","百科数据路径","input"),
			array("account-data","用户数据路径","input"),
			array("language-data","语言数据路径","input"),
			array("extension-data","扩展数据路径","input"),
			array("language-source","语言资源","array","var"=>array("name","code","path")),
		),
		array(
			array("index-path","Index.php路径","input"),
			array("setup-data","Setup.php路径","input"),
		),
		array(
			array("index-pictures","主页图片及跳转链接","array","var"=>array("image","link")),
			array("index-article-number","主页显示文章数","input"),
			array("index-column-number","主页显示栏目数","input"),
			array("index-wiki-number","主页显示百科数","input"),
			array("index-notice-number","主页显示公告数","input"),
		),
	);
?>